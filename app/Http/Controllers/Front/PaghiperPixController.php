<?php

namespace App\Http\Controllers\Front;

use App\Events\WatchPix;
use App\Models\Order;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Traits\Gateway;
use Illuminate\Support\Facades\Session;

class PaghiperPixController extends Controller
{
    use Gateway;

    public function __construct()
    {
        parent::__construct();

        $this->enabled = config("gateways.paghiper");
        $this->checkCurrency = "BRL";
        $this->name = "PIX PagHiper";
        $this->credentials = [
            "paghiper_token" => $this->storeSettings->paghiper_token,
            "paghiper_api_key" => $this->storeSettings->paghiper_api_key,
            "paghiper_pix_days_due_date" => $this->storeSettings->paghiper_pix_days_due_date
        ];

        $this->apiUrl = "https://pix.paghiper.com/invoice/create/";
    }

    protected function payment()
    {

        $phone = preg_replace('/[^0-9]/', '', $this->order->customer_phone);
        if (strlen($phone) > 11) {
            $phone = substr($phone, 2);
        }

        if (strlen($phone) < 10) {
            $this->paymentErrors['phone'] = __('Invalid Phone number. It should have 10 or 11 digits');
        }

        $zipcode = $this->order->customer_zip;
        $zipcode = preg_replace('/[^0-9]/', '', $zipcode);

        $cart_total_without_shipping = $this->cartTotal["before_costs"] + $this->order['packing_cost'];
        $cart_total_without_shipping_currency = $cart_total_without_shipping * $this->order['currency_value'];

        if ($this->storeSettings->paghiper_pix_is_discount) {
            $total = $cart_total_without_shipping_currency = $cart_total_without_shipping * $this->order['currency_value'];
            $discount = ($total / 100) * $this->storeSettings->paghiper_pix_discount;
            $discount_cents = $discount * 100;
        } else $discount_cents = 0;

        $orderData = array(
            'apiKey' => $this->credentials["paghiper_api_key"],
            'order_id' => $this->order->id, // código interno do lojista para identificar a transacao.
            'payer_email' => $this->order->customer_email,
            'payer_name' => $this->order->customer_name, // nome completo ou razao social
            'payer_cpf_cnpj' => $this->order->customer_document, // cpf ou cnpj
            'payer_phone' => $phone, // fixou ou móvel
            'notification_url' => action('Front\PaymentController@payreturn'),
            'discount_cents' => (int)$discount_cents, // em centavos
            'shipping_price_cents' => $this->order->shipping_cost * 100, // em centavos
            'shipping_methods' => $this->order->shipping_type,
            'number_ntfiscal' => 'NF-' . $this->order->id,
            'fixed_description' => true,
            'days_due_date' => $this->credentials["paghiper_pix_days_due_date"], // dias para vencimento do boleto
            'items' => array(
                array(
                    'description' => 'Pedido #' . $this->order->order_number,
                    'quantity' => '1',
                    'item_id' => $this->order->id,
                    'price_cents' => $cart_total_without_shipping_currency * 100
                ),
            ),
        );

        $data_post = json_encode($orderData);

        $mediaType = "application/json"; // formato da requisição
        $charSet = "UTF-8";
        $headers = array();
        $headers[] = "Accept: " . $mediaType;
        $headers[] = "Accept-Charset: " . $charSet;
        $headers[] = "Accept-Encoding: " . $mediaType;
        $headers[] = "Content-Type: " . $mediaType . ";charset=" . $charSet;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        $json = json_decode($result, true);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        # If any exception...
        if($httpCode != 201) {
            Log::debug('paghiper_pix_response', [$json]);
            return redirect(route('front.checkout'));
        }

        $pix_qr_code = $json['pix_create_request']['pix_code']['qrcode_image_url'];
        $pix_copy_paste = $json['pix_create_request']['pix_code']['emv'];

        Session::put('pix_qrcode', $pix_qr_code);
        Session::put('pix_copy_paste', $pix_copy_paste);
        Session::put('pix_days_due_date', $this->credentials["paghiper_pix_days_due_date"]);
        $order_id = $json['pix_create_request']['order_id'];
        $order = Order::where('id', $order_id)->first();
        if (isset($order)) {
            $txnid = $json['pix_create_request']['transaction_id'];
            $pay_amount = (float)$json['pix_create_request']['value_cents'] / 100;
            if ($order->update(['txnid' => $txnid, 'pay_amount' => $pay_amount])) {
                $this->paymentUrl = action('Front\PaymentController@payreturn');
                if (Session::has("order")) {
                    Session::forget('order');
                }
                event(new WatchPix($order));
                return;
            }
        }
    }
}
