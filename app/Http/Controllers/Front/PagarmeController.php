<?php

namespace App\Http\Controllers\Front;

use Exception;
use App\Models\Order;
use App\Models\Country;
use App\Traits\Gateway;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class PagarmeController extends Controller
{
    use Gateway;

    public function __construct()
    {
        parent::__construct();

        $this->enabled = config("gateways.pagarme");
        $this->checkCurrency = "BRL";
        $this->name = "Pagarme";
        $this->credentials = [
            "encryptionKey" => $this->storeSettings->pagarme_encryption_key,
            "apiKey" => $this->storeSettings->pagarme_api_key
        ];
    }

    protected function payment()
    {
        $orderData = [
            "encryption_key" => $this->credentials["encryptionKey"],
            "amount" => round($this->cartTotalCurrency["after_costs"] * 100, 3),
            "installments" => $this->storeSettings->pagarme_installments,
            "order_id" => $this->order->id,
            "order_number" => $this->order->order_number,
        ];

        $errors = [];

        $phone = $this->order->customer_phone;
        $phone = preg_replace('/[^0-9]/', null, $phone); // remove non numbers
        $phone = "+" . $phone;

        $zipcode = $this->order->customer_zip;
        $zipcode = preg_replace('/[^0-9]/', null, $zipcode);

        $document = $this->order->customer_document;
        $document = preg_replace('/[^0-9]/', null, $document);

        // CPF is 11 chars and CNPJ is 14. Pagarme just accepts correct values
        if(strlen($document) < 11 || (strlen($document) > 11 && strlen($document) < 14)) {
            $errors['document'] = __('Invalid CPF/CNPJ. Please check the Document field');
        }

        $customerData = [
            "id" => (empty($this->order->user_id) ? $document : $this->order->user_id),
            "name" => $this->order->customer_name,
            "email" => $this->order->customer_email,
            "document" => $this->order->customer_document,
            "document_type" => (strlen($document) > 11 ? "cnpj" : "cpf"),
            "type" => (strlen($document) > 11 ? "corporation" : "individual"),
            "phone" => $phone,
            "address" => $this->order->customer_address,
            "address_number" => $this->order->customer_address_number,
            "zip" => $zipcode,
            "country" => strtolower(Country::find($this->request->country)->country_code),
            "state" => $this->order->customer_state,
            "city" => $this->order->customer_city,
            "district" => $this->order->customer_district,
            "complement" => (empty($this->order->customer_complement) ? "N/A" : $this->order->customer_complement),
        ];

        $this->paymentJson = [
            "orderData" => $orderData,
            "customerData" => $customerData,
            "errors" => $errors
        ];
        return;
    }

    public function pagarmeCallback(Request $request)
    {
        $data = $request->all();
        $info = [];
        $token = $data["checkout"]["token"];
        $orderNumber = $data["originalOrder"]["orderData"]["order_number"];
        $order = Order::where('order_number', $orderNumber)->first();
        if (isset($order)) {
            $orderAmount = $order->pay_amount * 100;

            $info["charge_id"] = $token;
            $info["txnid"] = $orderNumber;

            $order->update($info);

            $pagarme = new \PagarMe\Client($this->credentials['apiKey']);
            try {
                $transaction = $pagarme->transactions()->capture([
                    'id' => $token,
                    'amount' => $orderAmount
                ]);

                $info["payment_status"] = "Completed";
                if ($order->dp == 1) {
                    $info["status"] = "completed";
                }

                $order->update($info);

                $notification = new Notification;
                $notification->order_id = $order->id;
                $notification->save();

                
                $json = [
                    "success" => $transaction->id
                ];
                
                if ($transaction->boleto_url) {

                    $info["payment_status"] = "Pending";
                    $order->update($info);

                    $message = __('Please generate your billet clicking on the button. It will open in a new tab.');
                    $url = $transaction->boleto_url;
                    $urlTitle = __('Generate billet');

                    $request->session()->put('gateway_message', $message);
                    $request->session()->put('gateway_url', $url);
                    $request->session()->put('gateway_url_title', $urlTitle);
                }
                
                if(Session::has("order")){
                    Session::forget('order');
                }
                return json_encode($json);

            } catch (Exception $e) {
                return json_encode([
                    "unsuccess" => $e->getmessage()
                ]);
            }
        }
    }
}
