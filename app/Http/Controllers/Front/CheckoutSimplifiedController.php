<?php

namespace App\Http\Controllers\Front;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Currency;
use App\Models\OrderTrack;
use App\Models\Pagesetting;
use App\Models\VendorOrder;
use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Classes\GeniusMailer;
use App\Models\PaymentGateway;

use App\Models\UserNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class CheckoutSimplifiedController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!app()->runningInConsole()) {
            if (!$this->storeSettings->is_cart) {
                return app()->abort(404);
            }
        }
    }

    public function loadpayment($slug1, $slug2)
    {
        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::find($this->storeSettings->currency_id);
        }
        $payment = $slug1;
        $pay_id = $slug2;
        $gateway = '';
        if ($pay_id != 0) {
            $gateway = PaymentGateway::findOrFail($pay_id);
        }
        return view('load.payment', compact('payment', 'pay_id', 'gateway', 'curr'));
    }

    public function create(Request $request)
    {
        if (!$this->storeSettings->is_simplified_checkout || empty($request->all())) {
            return view('errors.404');
        }

        if (!Session::has('cart')) {
            return redirect()->route('front.cart')->with('success', __("You don't have any product to checkout."));
        }

        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::find($this->storeSettings->currency_id);
        }
        $first_curr = Currency::where('id', '=', 1)->first();

        $order = new Order;

        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        $totalQty = $cart->totalQty;
        $item_number = Str::random(4).time();
        $coupon_disc = Session::get('coupon') / $curr->value;
        $success_url = action('Front\PaymentController@payreturnSimplifidCheckout');

        foreach ($cart->items as $key => $prod) {
            if (!empty($prod['max_quantity']) && ($prod['qty'] > $prod['max_quantity'])) {
                return redirect()->route('front.cart')->with('unsuccess', __('Max quantity of :prod  is :qty!', ['prod' => $prod['item']['name'], 'qty' => $prod['max_quantity']]));
            }

            if (!empty($prod['item']['stock']) && ($prod['qty'] > $prod['item']['stock'])) {
                return redirect()->route('front.cart')->with('unsuccess', __('The stock of :prod  is :qty!', ['prod' => $prod['item']['name'], 'qty' => $prod['item']['stock']]));
            }
        }

        if (Session::has('coupon_total')) {
            $cart_total = Session::get('coupon_total') / $curr->value;
        } elseif (Session::has('coupon_total1')) {
            $cart_total = Session::get('coupon_total1') / $curr->value;
        } else {
            $cart_total = $oldCart->totalPrice * (1+($this->storeSettings->tax / 100));
        }

        $cart_total = $cart_total + $order['packing_cost'] + $order['shipping_cost'];   //Cart total on currency 1
        $cart_total_currency = $cart_total * $curr->value;  // Cart total on default currency

        $order['cart'] = [
            'items' => $cart->items,
            'totalQty' => $cart->totalQty,
            'totalPrice' => $cart->totalPrice
        ];

        $order['totalQty'] = $totalQty;
        $order['pay_amount'] = round($cart_total, 2);
        $order['method'] = 'Simplified';
        $order['order_number'] = $item_number;
        $order['payment_status'] = "Pending";

        $order['user_id'] = $request->user_id;
        $order['tax'] = $this->storeSettings->tax;
        $order['coupon_code'] = Session::get('coupon');
        $order['coupon_discount'] = $coupon_disc;
        $order['payment_status'] = "Pending";
        $order['currency_sign'] = $curr->sign;
        $order['currency_value'] = $curr->value;

        $order['customer_email'] = " ";

        if (!$request->name) {
            $order['customer_name'] = " ";
        } else {
            $order['customer_name'] = $request->name;
        }

        if (!$request->phone) {
            $order['customer_phone'] = " ";
        } else {
            $order['customer_phone'] = $request->phone;
        }

        $order['customer_country'] = " ";
        $order['shipping_cost'] = 0;

        $order->save();

        $track = new OrderTrack;
        $track->title = __('Pending');
        $track->text = __('You have successfully placed your order.');
        $track->order_id = $order->id;
        $track->save();

        $notification = new Notification;
        $notification->order_id = $order->id;
        $notification->save();

        if ($request->coupon_id != "") {
            $coupon = Coupon::findOrFail($request->coupon_id);
            $coupon->used++;
            if ($coupon->times != null) {
                $i = (int)$coupon->times;
                $i--;
                $coupon->times = (string)$i;
            }
            $coupon->update();
        }

        foreach ($cart->items as $prod) {
            $x = (string) $prod['size_qty'];
            $y = (string) $prod['color_qty'];
            $z = (string) $prod['material_qty'];
            $product = Product::findOrFail($prod['item']['id']);
            if (!empty($x)) {
                $x = (int) $x;
                $x = $x - $prod['qty'];
                $temp = $product->size_qty;
                $temp[$prod['size_key']] = $x;
                $temp1 = implode(',', $temp);
                $product->size_qty =  $temp1;
                $product->stock -= $prod['qty'];
                $product->update();
            } elseif (!empty($y)) {
                $y = (int) $y;
                $y = $y - $prod['qty'];
                $temp = $product->color_qty;
                $temp[$prod['color_key']] = $y;
                $temp1 = implode(',', $temp);
                $product->color_qty =  $temp1;
                $product->stock -= $prod['qty'];
                $product->update();
            } elseif (!empty($z)) {
                $z = (int) $z;
                $z = $z - $prod['qty'];
                $temp = $product->material_qty;
                $temp[$prod['material_key']] = $z;
                $temp1 = implode(',', $temp);
                $product->material_qty =  $temp1;
                $product->stock -= $prod['qty'];
                $product->update();
            } else {
                $x = (string) $prod['stock'];
                if ($x != null) {
                    if ($product->stock != null) {
                        $product->stock -= $prod['qty'];
                    }
                    $product->update();
                }
            }
            if ($product->stock <= 5) {
                $notification = new Notification;
                $notification->product_id = $product->id;
                $notification->save();
            }
        }

        $notf = null;

        foreach ($cart->items as $prod) {
            if ($prod['item']['user_id'] != 0) {
                $vorder =  new VendorOrder;
                $vorder->order_id = $order->id;
                $vorder->user_id = $prod['item']['user_id'];
                $notf[] = $prod['item']['user_id'];
                $vorder->qty = $prod['qty'];
                $vorder->price = $prod['price'];
                $vorder->order_number = $order->order_number;
                $vorder->save();
            }
        }

        if (!empty($notf)) {
            $users = array_unique($notf);

            foreach ($users as $user) {
                $notification = new UserNotification;
                $notification->user_id = $user;
                $notification->order_number = $order->order_number;
                $notification->save();
            }
        }
        if (Session::has('temporder')) {
            Session::forget('temporder');
        }
        Session::put('temporder', $order);
        Session::put('tempcart', $cart);

        Session::forget('cart');

        Session::forget('already');
        Session::forget('coupon');
        Session::forget('coupon_code');
        Session::forget('coupon_total');
        Session::forget('coupon_total1');
        Session::forget('coupon_percentage');

        //Sending Email To Admin
        if ($this->storeSettings->is_smtp == 1) {
            $data = [
                'to' => Pagesetting::find(1)->contact_email,
                'subject' => __("New Order Received!"),
                'body' => $this->storeSettings->title . "<br>" .__("Hello Admin!")."<br>".__("Your store has received a new order.")."<br>".
                    __("Order Number is")." ".$order->order_number.".".__("Please login to your panel to check.")."<br>".
                    __("Thank you"),
            ];

            $mailer = new GeniusMailer();
            $mailer->sendAdminMail($data, $order->id);
        } else {
            $to = Pagesetting::find(1)->contact_email;
            $subject = __("New Order Received!");
            $msg =  $this->storeSettings->title . "<br>" .__("Hello Admin!")."<br>".__("Your store has received a new order.")."<br>".
                __("Order Number is")." ".$order->order_number.".".__("Please login to your panel to check.")."<br>".
                __("Thank you");
            $headers = "From: ".$this->storeSettings->from_name."<".$this->storeSettings->from_email.">";
            mail($to, $subject, $msg, $headers);
        }
        return redirect($success_url);
    }
}
