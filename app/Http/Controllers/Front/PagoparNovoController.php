<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CallbackPagoparPost;
use App\Models\Cart;
use App\Models\Currency;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Product;
use App\Traits\Gateway;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class PagoparNovoController extends Controller
{
    use Gateway;

    private $baseUrl;
    private $apiUrl;
    private $client;
    const CITY = "1";
    const CATEGORY = "909";

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

        $this->client = new Client();

        $this->baseUrl = 'https://www.pagopar.com';
        $this->apiUrl = "https://api.pagopar.com/api";
    }

    protected function build_data_structure($items, $order_id, $amount, $token, $currency, $shipping_cost)
    {
        $products = [];
        foreach ($items as $product) {
            $products[] = [
                "ciudad" => self::CITY,
                "nombre" => $product["item"]["name"],
                "cantidad" => $product["qty"],
                "categoria" => self::CATEGORY,
                "public_key" => $this->credentials["publicKey"],
                "url_imagen" => $product["item"]["photo"],
                "descripcion" => $product["item"]["details"] ?? "Producto sin descripción",
                "id_producto" => $product["item"]["id"],
                "precio_total" => intval($product["item"]["price"]*$product["qty"]*$currency->value),
                "vendedor_telefono" => "",
                "vendedor_direccion" => "",
                "vendedor_direccion_referencia" => "",
                "vendedor_direccion_coordenadas" => ""
            ];
        }
        if ($shipping_cost > 0) {
            $products[] = [
                "ciudad" => self::CITY,
                "nombre" => "Flete",
                "cantidad" => 1,
                "categoria" => self::CATEGORY,
                "public_key" => $this->credentials["publicKey"],
                "url_imagen" => "",
                "descripcion" => "Costo Envio",
                "id_producto" => 0,
                "precio_total" => $shipping_cost * $currency->value,
                "vendedor_telefono" => "",
                "vendedor_direccion" => "",
                "vendedor_direccion_referencia" => "",
                "vendedor_direccion_coordenadas" => ""
            ];
        }
        return [
            'token' => $token,
            'comprador' => [
                "ruc" => $this->order->customer_document,
                "email" => $this->order->customer_email,
                "ciudad" => null,
                "nombre" => $this->order->customer_name,
                "telefono" => $this->order->customer_phone,
                "direccion" => "",
                "documento" => $this->order->customer_document,
                "coordenadas" => "",
                "razon_social" => $this->order->customer_name,
                "tipo_documento" => "CI",
                "direccion_referencia" => null
            ],
            "public_key"=> $this->credentials['publicKey'],
            "monto_total"=> $amount,
            "tipo_pedido"=> "VENTA-COMERCIO",
            "compras_items"=> $products,
            "fecha_maxima_pago"=> date('Y-m-d H:i:s', strtotime("+3 days")),
            "id_pedido_comercio"=> $order_id,
            "descripcion_resumen"=> "Integracion"
        ];
    }

    protected function payment()
    {
        $order_id = "{$this->order->id}";
        $amount = intval($this->cartTotalCurrency["after_costs"]);
        $token = sha1($this->credentials["privateKey"] . $order_id."{$amount}");
        $currency = Currency::where("name", $this->checkCurrency)->first();
        $shipping_cost = $this->order["shipping_cost"];
        
        $cart = new Cart($this->order->cart);
        $items = $cart->items;

        $data = $this->build_data_structure($items, $order_id, $amount, $token, $currency, $shipping_cost);

        $request = $this->client->post($this->apiUrl."/comercios/1.1/iniciar-transaccion", [
            'json' => $data
        ]);

        $response = $request->getBody();

        $decoded_data = json_decode($response);

        if ($decoded_data->respuesta === true) {
            $this->order->charge_id = $decoded_data->resultado[0]->data;
            $this->order->save();

            $this->paymentUrl = $this->baseUrl."/pagos/".$decoded_data->resultado[0]->data;
        }
    
        Log::debug("Resposta pagopar", [$decoded_data, $data]);
    }

    public function callback(CallbackPagoparPost $request)
    {
        $data = $request->validated();

        $order = Order::where("charge_id", $data["resultado"][0]["hash_pedido"])->first();

        if (!$order) {
            return response()->json(["Order not find"], 406);
        }

        if (sha1($this->credentials["privateKey"] . $order->charge_id) != $data["resultado"][0]["token"]) {
            return response()->json(["Token doesn't match"], 406);
        }

        $order->payment_status = $data["resultado"][0]["pagado"] ? "Completed" : "Pending";
        $order->save();

        $notification = new Notification;
        $notification->order_id = $order->id;
        $notification->save();

        if (Session::has("order")) {
            Session::forget('order');
        }

        Log::debug("Callbak Pagopar", $data);
        return response()->json($data["resultado"]);
    }

    public function checkOrderStatus($hash)
    {
        $data = [
            "hash_pedido" => $hash,
            "token" => sha1($this->credentials["privateKey"] . "CONSULTA"),
            "token_publico" => $this->credentials["publicKey"]
        ];

        $request = $this->client->post($this->apiUrl."/pedidos/1.1/traer", [
            'json' => $data
        ]);

        $response = $request->getBody();

        $decoded_data = json_decode($response);

        if ($decoded_data->respuesta === true) {
            $order = Order::where("charge_id", $decoded_data->resultado[0]->hash_pedido)->first();

            if (!$order) {
                return response()->json(["Order not find"], 406);
            }

            $order->payment_status = $decoded_data->resultado[0]->pagado ? "Completed" : "Pending";
            $order->save();

            $notification = new Notification;
            $notification->order_id = $order->id;
            $notification->save();

            if (Session::has("order")) {
                Session::forget('order');
            }
        }

        Log::debug("Check Order Status Pagopar", [$decoded_data, $data]);
        return response()->json($decoded_data->resultado);
    }
}
