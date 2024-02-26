<?php

namespace App\Http\Controllers\Front;

use App\Models\Order;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Gateway;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class MercadopagoController extends Controller
{
    use Gateway;

    public function __construct()
    {
        parent::__construct();

        $this->enabled = config("gateways.mercado_pago");
        $this->checkCurrency = "BRL";
        $this->name = "Mercado Pago";
        $this->credentials = [
            "accessToken" => $this->storeSettings->mercadopago_access_token,
        ];
    }

    protected function payment()
    {
        $notify_url = action('Front\MercadopagoController@mercadopagoCallback');

        \MercadoPago\SDK::setAccessToken($this->credentials["accessToken"]);

        $preference = new \MercadoPago\Preference();
        $preference->back_urls = [
            "success" => $notify_url,
            "failure" => $notify_url,
            "pending" => $notify_url,
        ];
        $preference->auto_return = "approved";

        $item = new \MercadoPago\Item();
        $item->title = $this->order->order_number;
        $item->quantity = 1;
        $item->currency_id = "BRL";
        $item->unit_price = $this->cartTotalCurrency["after_costs"];

        $preference->items = array($item);
        $preference->save();

        Session::put('mercadopago', ['order_number' => $this->order->order_number]);

        $this->paymentUrl = $preference->init_point;
        return;
    }

    public function mercadopagoCallback(Request $request)
    {
        if ($request->has('collection_id') && $request->collection_status == 'approved') {
            $order_id = Session::get('mercadopago')['order_number'];
            $order = Order::where('order_number', $order_id)->first();

            if (isset($order)) {
                $data['charge_id'] = $request->collection_id;
                $data['txnid'] = $order_id;
                $data['payment_status'] = 'Completed';

                if ($order->dp == 1) {
                    $data['status'] = 'completed';
                }

                $order->update($data);

                $notification = new Notification;
                $notification->order_id = $order->id;
                $notification->save();

                if(Session::has('temporder')){
                    Session::forget('temporder');
                }
                Session::put('temporder', $order);
            }
            if(Session::has("order")){
                Session::forget('order');
            }
            return redirect(route('payment.return'));
        } else {
            Log::debug('mercadopago_callback_response', [$request->all()]);
            return redirect(route('front.checkout'));
        }
    }
}
