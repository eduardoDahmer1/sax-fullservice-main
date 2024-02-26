<?php

namespace App\Http\Controllers\Front;

use App\Models\Order;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Traits\Gateway;

class CieloController extends Controller
{
    use Gateway;

    const PAYMENT_STATUS_WAITING = "1";
    const PAYMENT_STATUS_PAID = "2";
    const PAYMENT_STATUS_DENIED = "3";
    const PAYMENT_STATUS_EXPIRED = "4";
    const PAYMENT_STATUS_CANCELED = "5";
    const PAYMENT_STATUS_NOT_FINISHED = "6";
    const PAYMENT_STATUS_AUTHORIZED = "7";
    const PAYMENT_STATUS_CHARGEBACK = "8";

    private $apiUrl;

    public function __construct()
    {
        parent::__construct();

        $this->enabled = config("gateways.cielo");
        $this->checkCurrency = "BRL";
        $this->name = "Cielo";
        $this->credentials = [
            "merchantId" => $this->storeSettings->cielo_merchantid,
        ];

        $this->apiUrl = "https://cieloecommerce.cielo.com.br/api/public/v1/orders";
    }

    protected function payment()
    {
        // Cielo requires shipping cost to be send separately
        $cart_total_without_shipping = $this->cartTotal["before_costs"] + $this->order['packing_cost'];
        $cart_total_without_shipping_currency = $cart_total_without_shipping * $this->order['currency_value'];

        // Cielo requires phone number to be between 10 and 11 digits
        // and only numbers
        $phone = preg_replace('/[^0-9]/', null, $this->order->customer_phone);
        if(strlen($phone) > 11) {
            $phone = substr($phone, 2);
        }

        if(strlen($phone) < 10) {
            $this->paymentErrors['phone'] = __('Invalid Phone number. It should have 10 or 11 digits');
        }

        // Cielo requires zipcode to have exactly 8 digits
        $zipcode = $this->order->customer_zip;
        $zipcode = preg_replace('/[^0-9]/', null, $zipcode);

        if(strlen($zipcode) < 8) {
            $this->paymentErrors['zipcode'] = __('Invalid Zipcode. It should have 8 digits');
        }

        // Cielo required document to be between 11 and 14 digits
        $document = $this->order->customer_document;
        $document = preg_replace('/[^0-9]/', null, $document);

        if(strlen($document) < 11 || (strlen($document) > 11 && strlen($document) < 14)) {
            $this->paymentErrors['document'] = __('Invalid CPF/CNPJ. Please check the Document field');
        }

        // Cielo checks address number and it must be less than 9 digits
        $addressNumber = $this->order->customer_address_number;

        if(strlen($addressNumber) > 8) {
            $this->paymentErrors['address_number'] = __('Invalid Address Number. Maximum characters is 8');
        }
        

        $orderData = [
            "OrderNumber" => $this->order->order_number,
            "Cart" => [
                "Items" => [
                    [
                        "Name" => "Pedido #{$this->order->order_number}",
                        "UnitPrice" => $cart_total_without_shipping_currency * 100, //converting to cents
                        "Quantity" => 1,
                        "Type" => "Asset"
                    ]
                ]
            ],
            "Shipping" => [
                "TargetZipCode" => $zipcode,
                "Type" => "FixedAmount",
                "Services" => [
                    [
                        "Name" => $this->order->shipping_type,
                        "Price" => $this->order->shipping_cost * 100
                    ]
                ],
                "Address" => [
                    "Street" => $this->order->customer_address,
                    "Number" => $this->order->customer_address_number,
                    "Complement" => $this->order->customer_complement,
                    "District" => $this->order->customer_district,
                    "City" => $this->order->customer_city,
                    "State" => session()->get('session_order')['customer_state_initials'],
                ]
            ],
            "Customer" => [
                "Identity" => $document,
                "FullName" => $this->order->customer_name,
                "Email" => $this->order->customer_email,
                "Phone" => $phone,
            ],
            "Options" => [
                "ReturnUrl" => action('Front\PaymentController@payreturn')
            ]
        ];

        $json = json_encode($orderData);

        $headers = [
            "MerchantId: {$this->credentials["merchantId"]}",
            'Content-Type: application/json'
        ];

        $apiRequest = curl_init($this->apiUrl);

        curl_setopt($apiRequest, CURLOPT_POST, true);
        curl_setopt($apiRequest, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($apiRequest, CURLOPT_POSTFIELDS, $json);
        curl_setopt($apiRequest, CURLOPT_RETURNTRANSFER, true);

        $apiResponse = curl_exec($apiRequest);
        $apiError = curl_error($apiRequest);

        curl_close($apiRequest);

        $cieloResponse = json_decode($apiResponse);

        if (isset($cieloResponse->settings->checkoutUrl)) {
            $this->paymentUrl = $cieloResponse->settings->checkoutUrl;
            return;
        } else {
            Log::debug('cielo_response', [$cieloResponse]);
        }
    }

    public function cieloCallback(Request $request)
    {
        $data = $request->all();

        if (isset($data["checkout_cielo_order_number"])) {
            $info = [];
            $order = Order::where('order_number', $data["order_number"])->first();

            if (isset($order)) {
                $info["charge_id"] = $data["checkout_cielo_order_number"];
                $info["txnid"] = $data["order_number"];

                if (
                    $data["payment_status"] == self::PAYMENT_STATUS_PAID ||
                    $data["payment_status"] == self::PAYMENT_STATUS_AUTHORIZED
                ) {
                    $info["payment_status"] = "Completed";
                    if ($order->dp == 1) {
                        $info["status"] = "completed";
                    }
                }

                $order->update($info);

                $notification = new Notification;
                $notification->order_id = $order->id;
                $notification->save();
                if(Session::has("order")){
                    Session::forget('order');
                }
            }
        } else {
            Log::debug('cielo_response', [$request->all()]);
        }
    }
}
