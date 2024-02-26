<?php

namespace App\Http\Controllers\Front;

use App\Models\Order;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Traits\Gateway;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class BancardController extends Controller
{
    use Gateway;

    private $baseUrl;
    private $apiUrl;

    public function __construct()
    {
        parent::__construct();

        $this->enabled = config("gateways.bancard");
        $this->checkCurrency = "PYG";
        $this->name = "Bancard";
        $this->credentials = [
            "publicKey" => $this->storeSettings->bancard_public_key,
            "privateKey" => $this->storeSettings->bancard_private_key,
        ];

        $this->baseUrl = ($this->storeSettings->bancard_mode == 'sandbox' ? 'https://vpos.infonet.com.py:8888' : 'https://vpos.infonet.com.py');
        $this->apiUrl = "{$this->baseUrl}/vpos/api/0.3";
    }

    protected function payment()
    {
        $shop_process_id = $this->order->id . time();
        $amount = number_format($this->cartTotalCurrency["after_costs"], 2, ".", "");
        $currency = 'PYG';

        $notify_url = action('Front\BancardController@bancardCallback');

        $orderData = [
            "public_key" => $this->credentials["publicKey"],
            "operation" => [
                "token" => md5($this->credentials["privateKey"] . $shop_process_id . $amount . $currency), //md5((private_key + shop_process_id + amount + currency)
                "shop_process_id" => $shop_process_id,
                "amount" => $amount,
                "currency" => $currency,
                "additional_data" => "",
                "description" => "Order #{$this->order->order_number}",
                "return_url" => $notify_url,
                "cancel_url" => $notify_url
            ]
        ];

        if(!empty($this->request->zimple_phone)) {
            $orderData["operation"]["additional_data"] = $this->request->zimple_phone;
            $orderData["operation"]["zimple"] = 'S';
        }

        $json = json_encode($orderData);

        $headers = array(
            'Content-Type: application/json'
        );

        $session = curl_init("{$this->apiUrl}/single_buy");

        curl_setopt($session, CURLOPT_POST, true);
        curl_setopt($session, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($session, CURLOPT_POSTFIELDS, $json);
        curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_SSLVERSION, 6);

        $response = curl_exec($session);
        $error = curl_error($session);

        curl_close($session);

        $bancardResponse = json_decode($response);

        if (isset($bancardResponse->process_id)) {
            $this->order->charge_id = $shop_process_id;
            $this->order->save();

            Session::put('bancard', ['shop_process_id' => $shop_process_id, 'order_number' => $this->order->order_number]);

            //$this->paymentUrl = "{$this->baseUrl}/payment/single_buy?process_id={$bancardResponse->process_id}";
            $this->paymentJson = [
                "process_id" => $bancardResponse->process_id,
                "is_zimple" => (!empty($this->request->zimple_phone))
            ];
            return;
        }

        Log::debug('bancard_store_response', [$bancardResponse]);
    }

    public function bancardCallback(Request $request)
    {
        $shop_process_id = Session::get('bancard')['shop_process_id'];

        $data = [
            "public_key" => $this->credentials["publicKey"],
            "operation" => [
                "token" => md5($this->credentials["privateKey"] . $shop_process_id . "get_confirmation"), //md5((private_key + shop_process_id + "get_confirmation")
                "shop_process_id" => $shop_process_id
            ]
        ];

        $json = json_encode($data);

        $headers = array(
            'Content-Type: application/json'
        );

        $session = curl_init("{$this->apiUrl}/single_buy/confirmations");

        curl_setopt($session, CURLOPT_POST, true);
        curl_setopt($session, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($session, CURLOPT_POSTFIELDS, $json);
        curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_SSLVERSION, 6);

        $response = curl_exec($session);
        $error = curl_error($session);

        curl_close($session);

        $bancardResponse = json_decode($response);

        if (isset($bancardResponse->confirmation) && $bancardResponse->confirmation->response_code == "00") {
            $order_id = Session::get('bancard')['order_number'];
            $order = Order::where('order_number', $order_id)->first();

            if (isset($order)) {
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
            Log::debug('bancard_callback_response', [$bancardResponse]);

            $order = $request->session()->get('order');
            $cartData = $order->cart;

            foreach($cartData['items'] as $product) {
                $productId = $product['item']['id'];
                $qty = $product['qty'];
                $product = Product::find($productId);
                if ($product) {
                    $product->stock += $qty;
                    $product->save();
                }
            }

            $order->delete();
            $request->session()->forget('order');

            if($bancardResponse->confirmation->response_code == "57"){
                return redirect(route('front.checkout'))->with('unsuccess', __("Bancard minimum value not reached. Cart value was below U$1."));
            }
            return redirect(route('front.checkout'))->with('unsuccess', $bancardResponse->confirmation->extended_response_description);
        }
    }

    public function bancardFinish(Request $request)
    {
        $bancardResponse = $request->all(); //the request comes as array

        Log::debug('bancard_callback_finish', $bancardResponse);

        if (isset($bancardResponse['operation']) && $bancardResponse['operation']['response_code'] == "00") {
            $order_id = $bancardResponse['operation']['shop_process_id'];
            $order = Order::where('order_number', $order_id)->first();

            if (isset($order) && $order->payment_status != "Completed") {
                $data['txnid'] = $order_id;
                $data['payment_status'] = 'Completed';

                if ($order->dp == 1) {
                    $data['status'] = 'completed';
                }

                $order->update($data);

                $notification = new Notification;
                $notification->order_id = $order->id;
                $notification->save();
            }

            return response()->json([
                "status" => 200,
                "message" => __("Success")
            ]);
        }

        return response()->json([
            "status" => 404,
            "message" => __("Not Found")
        ], 404);
    }

    public function bancardRollback($shop_process_id)
    {

        $data = [
            "public_key" => $this->credentials["publicKey"],
            "operation" => [
                "token" => md5($this->credentials["privateKey"] . $shop_process_id . "rollback" . "0.00"),
                "shop_process_id" => $shop_process_id
            ]
        ];

        $json = json_encode($data);

        $headers = array(
            'Content-Type: application/json'
        );

        $session = curl_init("{$this->apiUrl}/single_buy/rollback");

        curl_setopt($session, CURLOPT_POST, true);
        curl_setopt($session, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($session, CURLOPT_POSTFIELDS, $json);
        curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_SSLVERSION, 6);

        $response = curl_exec($session);
        $error = curl_error($session);

        curl_close($session);

        $bancardResponse = json_decode($response);

        if (isset($bancardResponse->status) && $bancardResponse->status == "success") {
            $order = Order::where('charge_id', $shop_process_id)->first();

            if (isset($order)) {
                $data['payment_status'] = 'Pending';

                $order->update($data);

                $notification = new Notification;
                $notification->order_id = $order->id;
                $notification->save();
            }

            return response()->json([
                "status" => 200,
                "message" => __("Payment of Order #:order rollbacked successfuly.", ["order" => $order->order_number])
            ]);
        } else {
            Log::debug('bancard_rollback_response', [$bancardResponse]);
            return response()->json([
                "status" => 404,
                "message" => __("Not Found")
            ], 404);
        }
    }

    public function bancardCloseModal(Request $request) {
        $order = $request->session()->get('order');
        $cartData = $order->cart;
        foreach($cartData['items'] as $product) {
            $productId = $product['item']['id'];
            $qty = $product['qty'];
            $product = Product::find($productId);
            if ($product) {
                $product->stock += $qty;
                $product->save();
            }
        }

        $order->delete();
        $request->session()->forget('order');
    }
}
