<?php

namespace App\Http\Controllers\Front;

use App\Models\Order;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Gateway;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class PagoparController extends Controller
{
    use Gateway;

    private $baseUrl;
    private $apiUrl;

    public function __construct()
    {
        parent::__construct();

        $this->enabled = config("gateways.pagopar");
        $this->checkCurrency = "PYG";
        $this->name = "Pagopar";
        $this->credentials = [
            "publicKey" => $this->storeSettings->pagopar_public_key,
            "privateKey" => $this->storeSettings->pagopar_private_key,
        ];

        $this->baseUrl = 'https://api.pagopar.com';
        $this->apiUrl = "{$this->baseUrl}/api/comercios/1.1";
    }

    public function payment()
    {
        $amount = intval($this->cartTotalCurrency["after_costs"]);
        $id_pedido_comercio = "{$this->order->id}";
        $token = sha1($this->credentials['privateKey'] . $id_pedido_comercio . $amount ); // sha1(Private_key + id_pedido_comercio . Monto_total)
        $orderData = [
            'token' => $token,
            'comprador' => [
                "ruc" => $this->order->customer_document,
                "email" => $this->order->customer_email,
                "ciudad" => $this->order->customer_city,
                "nombre" => $this->order->customer_name,
                "telefono" => $this->order->customer_phone,
                "direccion" => $this->order->customer_address,
                "documento" => $this->order->customer_document,
                "coordenadas" => "",
                "razon_social" => $this->order->customer_name,
                "tipo_documento" => "CI",
                "direccion_referencia" => null
            ],
            "public_key"=> $this->credentials['publicKey'],
            "monto_total"=> $amount,
            "tipo_pedido"=> "VENTA-COMERCIO",
            "compras_items"=> [
                [
                    "ciudad"=> "1",
                    "nombre"=> 'Compras Tienda Online',
                    "cantidad"=> 1,
                    "categoria"=> "909",
                    "public_key"=> $this->credentials['publicKey'],
                    "url_imagen"=> '',
                    "descripcion"=> 'Compra en '.$this->storeSettings->title,
                    "id_producto"=> $this->order->order_number,
                    "precio_total"=> $amount,
                    "vendedor_telefono"=> "",
                    "vendedor_direccion"=> "",
                    "vendedor_direccion_referencia"=> "",
                    "vendedor_direccion_coordenadas"=> ""
                ]
            ],
            "fecha_maxima_pago"=> date('Y-m-d H:i:s', strtotime("+3 days")),
            "id_pedido_comercio"=> $id_pedido_comercio,
            "descripcion_resumen"=> ""
        ];
        $json = json_encode($orderData);
        
        $headers = array(
            'Content-Type: application/json'
        );

        $session = curl_init("{$this->apiUrl}/iniciar-transaccion");

        curl_setopt($session, CURLOPT_POST, true);
        curl_setopt($session, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($session, CURLOPT_POSTFIELDS, $json);
        curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_SSLVERSION, 6);

        $response = curl_exec($session);
        $error = curl_error($session);

        curl_close($session);

        $pagoparResponse = json_decode($response);
        if ($pagoparResponse->respuesta){
            $this->order->charge_id = $pagoparResponse->resultado[0]->data;
            $this->order->save();

            $this->paymentUrl = "https://www.pagopar.com/pagos/{$pagoparResponse->resultado[0]->data}";
            return;
        }

        Log::debug('pagopar_payment_response', [$pagoparResponse]);
    }


    public function pagoparCallback(Request $request)
    {
        Log::debug('pagopar_callback_debug', [$request->all()]);
        if(!isset($request->resultado[0]['hash_pedido']))
            return NULL;

        $pagoparRequest = $request->resultado[0];
        $hash = $request->resultado[0]['hash_pedido'];

        # Token que veio do Pagopar tem que ser igual ao Token gerado através da Private Key
        $requestToken = $pagoparRequest['token'];
        $tokenCheck = sha1($this->credentials['privateKey'] . $hash); //sha1(private_key + hash_pedido)

        if($requestToken !== $tokenCheck)
        {
            Log::debug('pagopar_invalid_token_response', ['Tentativa de forçar alteração de pagamento detectada. Token INVÁLIDO.']);
            return NULL;
        }

        $order = Order::where('charge_id', $hash)->first();
        if(!$order)
            return NULL;

        $order->payment_status = $pagoparRequest['pagado'] ? 'Completed' : 'Pending';
        $order->txnid = $pagoparRequest['numero_pedido'];

        $order->update();

        $notification = new Notification;
        $notification->order_id = $order->id;
        $notification->save();

        if(Session::has("order")){
            Session::forget('order');
        }

        return response()->json([
            [
                "pagado" => $pagoparRequest['pagado'],
                "forma_pago" => $pagoparRequest['forma_pago'],
                "fecha_pago" => $pagoparRequest['fecha_pago'],
                "monto" => $pagoparRequest['monto'],
                "fecha_maxima_pago" => $pagoparRequest['fecha_maxima_pago'],
                "hash_pedido" => $order->charge_id,
                "numero_pedido" => $order->txnid,
                "cancelado" => $pagoparRequest['cancelado'],
                "forma_pago_identificador" => $pagoparRequest['forma_pago_identificador'],
                "token" => $tokenCheck,
            ]
        ]);
    }

    public function pagoparFinish(Request $request)
    {
        Log::debug('pagopar-finish-request', [$request->all()]);

        if(!$request->hash)
        {
            Log::error('pagopar-finish-without-hash', ['Trying to reach pagoparFinish method without hash, aborting.']);
            return redirect(route('front.checkout'))->with('unsuccess', __("Erro ao processar pagamento da {$this->name}. Por favor, contacte o administrador."));
        }

        # ensure that order actually exists before trying to send request to pagopar
        $order = Order::where('charge_id', $request->hash)->first();

        if (!$order) {
            Log::error('pagopar-finish-no-order', ['No Order was found even with hash, aborting.']);
            return redirect(route('front.checkout'))->with('unsuccess', __("Erro ao processar pagamento da {$this->name}. Por favor, contacte o administrador."));
        }

        $token = sha1($this->credentials['privateKey'] . "CONSULTA");

        $data = json_encode([
            'hash_pedido' => $request->hash,
            'token' => $token,
            'token_publico' => $this->credentials['publicKey']
        ]);

        $headers = array(
            'Content-Type: application/json'
        );

        $session = curl_init("{$this->baseUrl}/api/pedidos/1.1/traer");

        curl_setopt($session, CURLOPT_POST, true);
        curl_setopt($session, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($session, CURLOPT_POSTFIELDS, $data);
        curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_SSLVERSION, 6);

        $response = curl_exec($session);
        $error = curl_error($session);

        curl_close($session);

        $pagoparResponse = json_decode($response);
        $pagoparOrder = $pagoparResponse->resultado[0];

        $order->payment_status = $pagoparOrder->pagado ? 'Completed' : 'Pending';
        $order->txnid = $pagoparOrder->numero_pedido;

        $order->update();

        $notification = new Notification;
        $notification->order_id = $order->id;
        $notification->save();

        if(Session::has("order")){
            Session::forget('order');
        }

        return redirect(route('payment.return'));
    }

}
