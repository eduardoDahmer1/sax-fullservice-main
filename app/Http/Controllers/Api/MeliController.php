<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\MeliResource;
use App\Models\MercadoLivre;
use App\Models\Product;
use App\Models\Order;
use App\Models\Cart;
use App\Facades\MercadoLivre as FacadesMercadoLivre;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class MeliController extends Controller
{
    /**
     * Treatment for Meli callback. It can for example be used to save authorization code at database.
     *
     * @return \Illuminate\Http\Response
    */
    public function callback(Request $request)
    {
        if (!$request->code) {
            return redirect()->route('front.index');
        }
        if ($request->code) {
            $meli = MercadoLivre::first();
            $meli->authorization_code = $request->code;
            $meli->update();
            /* Just generates the Access Token and saves it at database. */
            Artisan::call('generate:token');
            return redirect()->route('admin-gs-integrations-mercadolivre-index');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function notifications(Request $request)
    {
        Log::notice("MELI Notification: ", [$request->all()]);

        $meli = MercadoLivre::first();
        $url = config('mercadolivre.api_base_url') . $request->resource;
        $headers = [
            "Authorization: Bearer ". $meli->access_token,
        ];
        $data = FacadesMercadoLivre::curlGet($url, $headers);

        $mercadolivre_id = null;
        if (array_key_exists('id', $data)) {
            $mercadolivre_id = $data['id'];
        }

        if ($request->topic == "items") {
            $new_stock = $data['available_quantity'];
            $new_price = $data['price'];
            Product::where('mercadolivre_id', $data['id'])->update([
                'mercadolivre_price' => $new_price,
                'stock' => $new_stock
            ]);
            Product::where('mercadolivre_id', $data['id'])->update(['mercadolivre_price' => $new_price]);
            Log::notice("Modificações no Produto via Mercado Livre detectadas. O produto com ID: ". $data['id'] . " foi atualizado.");
        }

        if ($request->topic == "items_prices") {
            $new_price = $data['prices'][0]->amount;
            Product::where('mercadolivre_id', $data['id'])->update(['price' => $new_price]);
            Log::notice("Preço do Produto ID: ". $data['id'] . " foi modificado e atualizado no e-Commerce.");
        }

        if ($request->topic == "orders_v2") {
            if (Order::where('order_number', $data['id'])->first()) {
                return;
            }
            $order = new Order;
            $order->customer_email = $data['buyer']->email;
            $order->customer_phone = $data['buyer']->phone->area_code . $data['buyer']->phone->number;
            $order->customer_name = $data['buyer']->first_name . " " .  $data['buyer']->last_name;
            $order->customer_country = 'Brazil';
            $order->currency_sign = "R$";
            $order->currency_value = 1;
            $order->method = 'Pagamento Externo via Mercado Livre';
            $order->shipping = 'pickup';
            $order->txnid = $data['id'];
            $order->created_at = now();
            $order->updated_at = now();
            $order->totalQty = count($data['order_items']);
            $order->pay_amount = $data['paid_amount'];

            $items = [];
            foreach ($data['order_items'] as $key => $item) {
                $product =  $this->convertMeliProductToStore($item->item);
                $items[$key+1]['item'] = $product;
                $items[$key+1]['qty'] = $item->quantity;
                $items[$key+1]['stock'] = $product->stock;
                $items[$key+1]['price'] = $product->price;
                $items[$key+1]['size_qty'] = "";
                $items[$key+1]['color_qty'] = "";
                $items[$key+1]['color_price'] = "";
                $items[$key+1]['material_qty'] = "";
                $items[$key+1]['material'] = "";
                $items[$key+1]['material_price'] = "";
                $items[$key+1]['max_quantity'] = "";
                $items[$key+1]['size_price'] = "";
                $items[$key+1]['size'] = "";
                $items[$key+1]['color'] = "";
                $items[$key+1]['customizable_gallery'] = null;
                $items[$key+1]['customizable_name'] = null;
                $items[$key+1]['customizable_number'] = null;
                $items[$key+1]['customizable_logo'] = null;
                $items[$key+1]['agree_terms'] = null;
                $items[$key+1]['license'] = '';
                $items[$key+1]['dp'] = '0';
                $items[$key+1]['keys'] = '';
                $items[$key+1]['values'] = '';
            }

            $cartArray = [];
            $cartArray['items'] = $items;
            $cartArray['totalQty'] = count($data['order_items']);
            $cartArray['totalPrice'] = (double) $data['paid_amount'];

            $order->cart = $cartArray;
            $order->order_number = $data['id'];
            $order->payment_status = $data['status'] == 'paid' ? 'paid' : 'unpaid';

            $order->save();
            Log::notice("Pedido: ". $data['id'] . " foi sincronizado no e-Commerce.");
        }
        /* Just creates a collection with all request() sent and returns it to browser. */
        /*$notification = MeliResource::collection($request->all());
        Log::notice("MELI Notification: ", [$notification]);*/
        return json_encode($request->all());
    }

    private function convertMeliProductToStore($item)
    {
        if (!$product = Product::where('mercadolivre_id', $item->id)->first()) {
            return null;
        }
        return $product;
    }
}
