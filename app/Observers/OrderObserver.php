<?php

namespace App\Observers;

use App\Jobs\ProcessOrderJob;
use App\Jobs\OrderBilling;
use App\Mail\RedplayLicenseMail;
use App\Classes\GeniusMailer;
use App\Models\Generalsetting;
use App\Models\License;
use App\Models\Order;
use App\Models\Pickup;
use App\Models\User;
use App\Models\WeddingProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class OrderObserver
{
    public function updating(Order $order)
    {
        if (config('features.wedding_list') && $order->payment_status == 'Completed') {
            $ids = [];
            foreach ($order->weddingProducts->toArray() as $item) {
                $ids[] = $item['pivot']['wedding_product_id'];
            }

            WeddingProduct::whereIn('id', $ids)->update([
                'buyer_id' => $order->user_id,
                'buyed_at' => now(),
            ]);
        }
    }

    public function createWeddingProducts(Order $order)
    {
        if (config('features.wedding_list') && session()->has('weddings')) {
            $ids = [];
            foreach ($order->cart['items'] as $item) {
                foreach (session('weddings') as $wedding) {
                    if ($wedding['product_id'] == $item['item']['id']) {
                        $ids[] = $wedding['id'];
                    }
                }
            }
            $order->weddingProducts()->attach($ids);
            Session::remove("weddings");
        }
    }

    /**
     * Handle the order "updated" event.
     *
     * @param Order $order
     * @return void
     */
    public function updated(Order $order)
    {
        if (config('features.redplay_digital_product')) {
            if ($order->payment_status === 'Completed') {
                $cart = $order->cart;
                foreach ($cart['items'] as $cartItem) {
                    $product = $cartItem['item'];
                    if ($product->licenses) {
                        $licenseToBeSentByEmail = License::where('product_id', $product->id)->where('available', true)->first();
                        if ($licenseToBeSentByEmail) {
                            Log::debug('License to be Sent: ', [$licenseToBeSentByEmail]);

                            Mail::to($order->customer_email)->queue(new RedplayLicenseMail($order, $licenseToBeSentByEmail));

                            $licenseToBeSentByEmail->available = false;
                            $licenseToBeSentByEmail->update();
                        }
                    }
                }
            }
        }

        if (env('ENABLE_ORDER') && $order->payment_status === 'Completed') {
            $parameters = [
                'cod' => env('ORDER_COD'),
                'pas' => env('ORDER_PASSWORD'),
                'ope' => 15,
                'ped' => $order->order_number,
                'pdc' => $order->number_cec,
            ];

            $url = 'https://saxpy.dyndns.org:444/EcommerceApi/production.php?' . http_build_query($parameters);
            OrderBilling::dispatch($url, $order);
        }
        
        if($order->payment_status === 'Completed') {
            $gs = Generalsetting::findOrFail(1);
            //Sending Email To Buyer
            if ($gs->is_smtp == 1) {
                if($order->shipping == 3) {
                    $data = [
                        'to' => $order->customer_email,
                        'type' => "new_order2",
                        'cname' => $order->customer_name,
                        'oamount' => "$order->pay_amount",
                        'aname' => "",
                        'aemail' => "",
                        'wtitle' => "",
                        'onumber' => $order->order_number,
                    ];
                } else {
                    $data = [
                        'to' => $order->customer_email,
                        'type' => "new_order",
                        'cname' => $order->customer_name,
                        'oamount' => "$order->pay_amount",
                        'aname' => "",
                        'aemail' => "",
                        'wtitle' => "",
                        'onumber' => $order->order_number,
                    ];
                }

                $mailer = new GeniusMailer();
                $mailer->sendAutoOrderMail($data, $order->id);
            } else {
                $to = $order->customer_email;
                $subject = __("Your Order Placed!!");
                $msg = $gs->title . "\n" .__("Hello") . " " . $order->customer_name . "!\n" . __("You have placed a new order.") . "\n" .
                    __("Your order number is") . " " . $order->order_number . "." . __("Please wait for your delivery.") . " \n"
                    . __("Thank you");
                $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
                mail($to, $subject, $msg, $headers);
            }
        }
    }

    public function created(Order $order)
    {
        $this->createWeddingProducts($order);
        if ($order->shipping == "pickup") {
            if ($order->store_id) {
                $data = $order->cart;
                foreach ($data['items'] as $item) {
                    $qtdProd[] = $item['qty'];
                    $productId[] = $item['item']['id'];
                }
                foreach ($qtdProd as $index => $qtd) {
                    DB::table('pickup_product')->where('product_id', $productId[$index])->where('pickup_id', $order->store_id)->decrement('stock', $qtd);
                }
            }
        }

        if (env('ENABLE_ORDER') && $order->method !== "Simplified") {
            $data = $order->cart;
            $skus = [];
            $price = [];

            if (isset($data['items'])) {
                foreach ($data['items'] as $item) {
                    if (isset($item['item']['sku'])) {
                        $skus[] = $item['item']['sku'];
                        $price[] = $item['item']['price'];
                    }
                }
            }

            $parameters = [
                'cod' => env('ORDER_COD'),
                'pas' => env('ORDER_PASSWORD'),
                'ope' => 12,
                'ped' => $order->order_number,
                'sku' => implode(',', $skus),
                'gra' => '',
                'qtd' => $order->totalQty,
                'pre' => implode(',', $price),
                'cli' => $order->user_id,
                'obs' => ($order->shipping === 'pickup') ? $order->shipping . ', ' . $order->pickup_location : $order->shipping,
                'pgt' => 1,
                'nom' => $order->customer_name,
                'eml' => $order->customer_email,
                'nas' => $order->method === 'Simplified' ? "" : $order->user->birth_date,
                'sex' => $order->method === 'Simplified' ? "" : $order->user->gender,
                'doc' => $order->customer_document,
                'fn1' => $order->customer_phone,
                'fn2' => '',
                'fn3' => '',
                'end' => $order->customer_address,
                'num' => $order->customer_address_number,
                'com' => $order->customer_complement,
                'bai' => $order->customer_district,
                'cid' => $order->customer_city,
                'uf' =>  $order->customer_state,
                'cep' => $order->customer_zip,
                'moe' => $order->currency_sign,
                'fre' => $order->shipping_cost,
                
            ];
            $url = 'https://saxpy.dyndns.org:444/EcommerceApi/production.php?' . http_build_query($parameters);
            ProcessOrderJob::dispatch($url, $order);
            
        }
    }
}
