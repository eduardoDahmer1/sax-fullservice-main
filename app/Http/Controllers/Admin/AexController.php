<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Classes\Aex;
use App\Models\AexCity;
use App\Models\Generalsetting;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Currency;
use App\Models\OrderTrack;

class AexController extends Controller
{
    public $aex;

    public function __construct()
    {
        $this->middleware('auth:admin');

        parent::__construct();

        $storeSettings = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $this->aex = new Aex($storeSettings->aex_public, $storeSettings->aex_private, $storeSettings->is_aex_production);
    }

    public function updateAexCities()
    {
        $updated_cities = $this->aex->GetCities();

        if (isset($updated_cities->datos)) {
            $cities_codes = [];
            foreach ($updated_cities->datos as $updated_city) {
                $aex_city = AexCity::firstOrCreate([
                    'codigo_ciudad'=>$updated_city->codigo_ciudad
                ]);
                $aex_city->update([
                    'denominacion'=>$updated_city->denominacion,
                    'codigo_departamento'=>$updated_city->codigo_departamento,
                    'codigo_pais'=>$updated_city->codigo_pais,
                    'ubicacion_geografica'=>$updated_city->ubicacion_geografica,
                    'departamento_denominacion'=>$updated_city->departamento_denominacion,
                    'pais_denominacion'=>$updated_city->pais_denominacion
                ]);
                $cities_codes[] = $updated_city->codigo_ciudad;
            }
            AexCity::whereNotIn('codigo_ciudad', $cities_codes)->delete();
            $msg = __('AEX cities updated.');
            return response()->json($msg);
        } else {
            $error = isset($updated_cities->mensaje) ? __('AEX').': '.$updated_cities->mensaje : __('AEX cities not updated.');
            return response()->json(array('errors' => [0 => $error]));
        }
    }

    public function loadAexCities()
    {
        $aex_cities = AexCity::orderBy('denominacion')->get();
        return view('load.aex-cities', compact('aex_cities'));
    }

    public function selectAexCity($order_id)
    {
        $order = Order::Find($order_id);
        if (!isset($order)) {
            return redirect()->route('admin.dashboard')->with('unsuccess', __('Sorry the page does not exist.'));
        }
        $aex_track = explode(';', trim(strstr(strstr($order->internal_note, 'AEXCODE:['), ']', true), 'AEXCODE:['))[0];

        $aex_destination = explode(';', trim(strstr(strstr($order->internal_note, 'AEX:['), ']', true), 'AEX:['))[0];
        $aex_cities = AexCity::orderBy('denominacion')->get();

        return view('admin.order.aex-select-city', compact('order', 'aex_destination', 'aex_cities', 'aex_track'));
    }

    public function requestAex(Request $request)
    {
        $order_id = $request->order_id;
        $order = Order::Find($order_id);
        if (!isset($order)) {
            return redirect()->route('admin.dashboard')->with('unsuccess', __('Sorry the page does not exist.'));
        }
        $order_store_id = explode(';', trim(strstr(strstr($order->internal_note, '#:['), ']', true), '#:['))[0];

        $orderStoreSettings = Generalsetting::find($order_store_id);
        if (!isset($orderStoreSettings)) {
            $orderStoreSettings = $this->storeSettings;
        }

        $aex_track = explode(';', trim(strstr(strstr($order->internal_note, 'AEXCODE:['), ']', true), 'AEXCODE:['))[0];

        $oldCart = $order->cart;
        $cart = new Cart($oldCart);
        $aex = new Aex($orderStoreSettings->aex_public, $orderStoreSettings->aex_private, $orderStoreSettings->is_aex_production);

        $aex_origin = $orderStoreSettings->aex_origin;
        $aex_destination = $request->aex_destination;

        $aex_cities = AexCity::orderBy('denominacion')->get();
        $aex_city = AexCity::where('codigo_ciudad', '=', $aex_destination)->first();

        if (isset(explode(';', trim(strstr(strstr($order->internal_note, 'AEX:['), ']', true), 'AEX:['))[1])) {
            $aex_service = explode(';', trim(strstr(strstr($order->internal_note, 'AEX:['), ']', true), 'AEX:['))[1];
        } else {
            $aex_service = null;
        }

        $aex_value = 0;
        $curr_pyg  = Currency::where('name', '=', "PYG")->first();
        if (empty($curr_pyg->value)) {
            return redirect()->route('admin-order-select-aex-city', $order_id)->with('unsuccess', 'PYG '.__('Unavaiable'));
        }

        $aex_value = $cart->totalPrice * $curr_pyg->value;

        $obj = (object)array();
        $obj->peso = $cart->getWeight();
        $obj->largo = $cart->getLenght();
        $obj->alto = $cart->getHeight();
        $obj->ancho = $cart->getWidth();
        $obj->valor = $aex_value;
        $package = [$obj];

        $aex_request = $aex->requestService($aex_origin, $aex_destination, $order->order_number, $package, 'P');

        if ($aex_request->codigo != 0) {
            return redirect()->route('admin-order-select-aex-city', $order_id)->with('unsuccess', $aex_request->mensaje);
        }

        return view('admin.order.aex-request', compact('aex_request', 'curr_pyg', 'orderStoreSettings', 'aex_service', 'order', 'aex_destination', 'aex_cities', 'aex_track', 'aex_city'));
    }

    public function confirmAex(Request $request)
    {
        $order = Order::Find($request->order_id);
        if (!isset($order)) {
            return redirect()->route('admin.dashboard')->with('unsuccess', __('Sorry the page does not exist.'));
        }
        $order_store_id = explode(';', trim(strstr(strstr($order->internal_note, '#:['), ']', true), '#:['))[0];

        $orderStoreSettings = Generalsetting::find($order_store_id);
        if (!isset($orderStoreSettings)) {
            $orderStoreSettings = $this->storeSettings;
        }

        $aex = new Aex($orderStoreSettings->aex_public, $orderStoreSettings->aex_private, $orderStoreSettings->is_aex_production);
        $aex_origin = $orderStoreSettings->aex_origin;
        $aex_destination = $request->aex_destination;
        $aex_service = $request->service;
        $id_solicitud = $request->id_solicitud;

        // Pickup
        $pickup = [
            'codigo' => $order_store_id,
            'calle_principal' => $orderStoreSettings->aex_calle_principal,
            'calle_transversal_1' => $orderStoreSettings->aex_calle_transversal,
            'numero_casa' => $orderStoreSettings->aex_numero_casa,
            'codigo_ciudad' => $aex_origin,
            'telefono' => $orderStoreSettings->aex_telefono
        ];

        // Delivery
        $delivery = [
            // 'id_punto_entrega' => '',
            'codigo' => $request->order_id,
            'calle_principal' => $request->delivery_calle_principal,
            'calle_transversal_1' => $request->delivery_calle_transversal,
            'numero_casa' => $request->delivery_numero_casa,
            'codigo_ciudad' => $aex_destination,
            'telefono' => $request->delivery_telefono
        ];

        if (isset($request->incluye_envio[$aex_service]) && $request->incluye_envio[$aex_service] == 'f') {
            if (isset($request->point[$aex_service])) {
                $delivery['id_punto_entrega'] = $request->point[$aex_service];
            }
        }

        // Recipient
        $recipient = [
            'codigo' => $order->order_number,
            'numero_documento' => $request->delivery_documento,
            'nombre' => $request->delivery_nombre,
            'email' => $request->delivery_email,
            'telefonos' => [
                ['numero'=>$request->delivery_telefono]
            ]
        ];

        $additional = [];
        if (isset($request->additional[$aex_service])) {
            foreach ($request->additional[$aex_service] as $key=>$value) {
                if ($value == 1) {
                    $additional[] = $key;
                }
            }
        }

        $aex_confirm = $aex->confirmService($id_solicitud, $aex_service, $pickup, $recipient, $delivery, $additional);

        if ($aex_confirm->codigo != 0) {
            return redirect()->route('admin-order-select-aex-city', $order->id)->with('unsuccess', $aex_confirm->mensaje);
        }

        $track = new OrderTrack;
        $track->title = '[AEX] '.__('Preparing shipping');
        $track->text = __('AEX Tracking code').': '.$aex_confirm->datos->numero_guia;
        $track->order_id = $order->id;
        $track->save();

        $order->internal_note = $order->internal_note.' | AEXCODE:['.$aex_confirm->datos->numero_guia.']';
        $order->save();

        return redirect()->route('admin-order-show', $order->id)->with('success', __('Successfuly requested AEX'));
    }
}
