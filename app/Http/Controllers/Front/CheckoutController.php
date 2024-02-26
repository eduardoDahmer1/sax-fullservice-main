<?php

namespace App\Http\Controllers\Front;

use DB;
use Auth;
use Validator;
use App\Classes\Aex;
use App\Models\Cart;
use App\Models\City;
use App\Models\User;
use App\Models\Order;
use App\Models\State;
use App\Models\Coupon;
use App\Models\Pickup;
use GuzzleHttp\Client;
use App\Models\AexCity;
use App\Models\Country;
use App\Models\Package;
use App\Models\Product;
use App\Models\Currency;
use App\Models\Shipping;
use App\Models\OrderTrack;
use App\Models\BankAccount;
use App\Models\Pagesetting;
use App\Models\VendorOrder;
use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Classes\GeniusMailer;
use App\Classes\MelhorEnvio;
use App\Models\Generalsetting;
use App\Models\PaymentGateway;
use App\Models\Shipping_prices;
use App\Models\UserNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB as FacadesDB;
use App\Helpers\Helper;
use App\Models\CartAbandonment;
use Illuminate\Database\Eloquent\Builder;

class CheckoutController extends Controller
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
    public function checkout(Request $request)
    {

        if (!$this->storeSettings->is_standard_checkout) {
            return view('errors.404');
        }

        if ($request->abandonment && Auth::guard('web')->check()) {
            $ca = CartAbandonment::where('user_id', Auth::user()->id)->first();
            if ($ca != null) {
                Session::put('cart', $ca->temp_cart);
                return redirect()->route('front.checkout');
            }
        }
        if (!Session::has('cart')) {
            // Clear Cart Abandonment for current user if his Cart is empty
            if (!$this->storeSettings->guest_checkout && $this->storeSettings->is_cart_abandonment && Auth::guard('web')->check()) {
                CartAbandonment::where('user_id', Auth::user()->id)->delete();
            }
            return redirect()->route('front.cart')->with('success', __("You don't have any product to checkout."));
        }
        $dp = 1;
        $vendor_shipping_id = 0;
        $vendor_packing_id = 0;
        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::find($this->storeSettings->currency_id);
        }
        $first_curr = Currency::where('id', '=', 1)->first();
        $gateways =  PaymentGateway::where('status', '=', 1)->get();
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        $products = $cart->items;

        //Pega o id de todos os produtos do carrinho
        foreach ($products as $data) {
            $productsId[] = $data['item']->id;
            $productsQty[] = $data['qty'];
        }

        //busca os locais de retirada com as condicoes.
        $allPickups = Pickup::all();
        
        // $pickups = Pickup::whereHas('products', function (Builder $query) use ($productsId, $productsQty) {
        //     $query->whereIn('product_id', $productsId)->where('pickup_product.stock', ">", 0);
        //     $conditions = [];
        //     foreach ($productsId as $key => $productId) {
        //         $conditions[] = "SUM(CASE WHEN product_id = {$productId} THEN pickup_product.stock >= {$productsQty[$key]} ELSE 0 END)";
        //     }
        //     $query->havingRaw(implode(' + ', $conditions) . ' = ' . count($productsId));
        // })->get();

        // $thisPickup = false;

        // if($pickups->count() == 1) {
        //     $thisPickup = $pickups->first();
        // }

        if ($this->storeSettings->multiple_shipping == 1) {
            $user = null;
            foreach ($cart->items as $prod) {
                $user[] = $prod['item']['user_id'];
            }
            $users = array_unique($user);
            if (count($users) == 1) {
                $shipping_data  = Shipping::where('user_id', '=', $users[0])->where('status', '=', 1)->get();
                if (count($shipping_data) == 0) {
                    $shipping_data  = Shipping::where('user_id', '=', 0)->where('status', '=', 1)->get();
                } else {
                    $vendor_shipping_id = $users[0];
                }
            } else {
                $shipping_data  = Shipping::where('user_id', '=', 0)->where('status', '=', 1)->get();
            }
        } else {
            $shipping_data  = Shipping::where('user_id', '=', 0)->where('status', '=', 1)->get();
        }
        // Packaging
        if ($this->storeSettings->multiple_packaging == 1) {
            $user = null;
            foreach ($cart->items as $prod) {
                $user[] = $prod['item']['user_id'];
            }
            $users = array_unique($user);
            if (count($users) == 1) {
                $package_data  = Package::where('user_id', '=', $users[0])->get();
                if (count($package_data) == 0) {
                    $package_data  = Package::where('user_id', '=', 0)->get();
                } else {
                    $vendor_packing_id = $users[0];
                }
            } else {
                $package_data  = Package::where('user_id', '=', 0)->get();
            }
        } else {
            $package_data  = Package::where('user_id', '=', 0)->get();
        }
        foreach ($products as $prod) {
            if ($prod['item']['type'] == 'Physical') {
                $dp = 0;
                break;
            }
        }
        if ($dp == 1) {
            $ship  = 0;
        }
        $total = $cart->totalPrice;
        $coupon = Session::has('coupon') ? Session::get('coupon') : 0;
        

        if ($this->storeSettings->tax != 0) {
            $tax = ($total / 100) * $this->storeSettings->tax;
            $total = $total + $tax;
        }
        if (!Session::has('coupon_total')) {
            $total = $total - $coupon;
            $total = $total + 0;
        } else {
            $total = Session::get('coupon_total');
            
            $total = str_replace($curr->sign, '', $total) + round(0 * $curr->value, 2);
            $total = $total / $curr->value;
        }

        // If a user is Authenticated then there is no problm user can go for checkout
        $ck = false;
        if (!Auth::guard('web')->check()) {
            // If guest checkout is activated then user can go for checkout except if have any digital product
            if ($this->storeSettings->guest_checkout == 1) {
                foreach ($products as $prod) {
                    if ($prod['item']['type'] != 'Physical') {
                        if (!Auth::guard('web')->check()) {
                            $ck = true;
                        }
                    }
                }
            } else {
                $ck = true;
            }
        }
        if (Auth::guard('web')->check() && !$this->storeSettings->guest_checkout && $this->storeSettings->is_complete_profile) {
            $user = Auth::guard('web')->user();
            if (!$user->document) {
                return redirect()->route("user-profile")->with('unsuccess', __("You need to complete your profile before checking out."));
            }
        }
        $bankAccounts =  BankAccount::where('status', '=', 1)->get();
        if (!empty($this->storeSettings->country_ship)) {
            $countries = Country::where('country_code', '=', $this->storeSettings->country_ship)->get();
        } else {
            $countries = Country::all();
        }
        foreach ($cart->items as $key => $prod) {
            if (!empty($prod['max_quantity']) && ($prod['qty'] > $prod['max_quantity'])) {
                return redirect()->route('front.cart')->with('unsuccess', __('Max quantity of :prod  is :qty!', ['prod' => $prod['item']['name'], 'qty' => $prod['max_quantity']]));
            }

            if (!empty($prod['item']['stock']) && ($prod['qty'] > $prod['item']['stock'])) {
                return redirect()->route('front.cart')->with('unsuccess', __('The stock of :prod  is :qty!', ['prod' => $prod['item']['name'], 'qty' => $prod['item']['stock']]));
            }
            $db_prod = Product::find($prod['item']->id);
            if ($prod['item']->stock != $db_prod->stock) {
                if ($db_prod->stock < $prod['qty']) {
                    if ($db_prod->stock == 0) {
                        return redirect()->route('front.cart')->with('unsuccess', __('Product :prod has no stock!', ['prod' => $prod['item']['name']]));
                    }
                    return redirect()->route('front.cart')->with('unsuccess', __('Insufficient stock of :prod!', ['prod' => $prod['item']['name']]));
                }
            }
        }

        if (!Auth::guard('web')->check()) {
            $city = City::with('state.country')->where('name', 'like', "%{'cidade'}")->first();
            if ($city) {
                $city_id = $city->id;
                $state_id = $city->state_id;
                $country_id = $city->state->country_id;
                $state_name = $city->state->name;
                $country_name = $city->state->country->country_name;
                $city_name = $city->name;
            } else {
                $city = "";
                $city_id = "";
                $state_id = "";
                $country_id = "";
                $state_name = "";
                $country_name = "";
                $city_name = "";
            }
        } else {
            $city = City::with('state.country')->where('id', Auth::guard('web')->user()->city_id)->first();
            if ($city) {
                $city_id = $city->id;
                $state_id = $city->state_id;
                $country_id = $city->state->country_id;
                $state_name = $city->state->name;
                $country_name = $city->state->country->country_name;
                $city_name = $city->name;
            } else {
                $city = "";
                $city_id = "";
                $state_id = "";
                $country_id = "";
                $state_name = "";
                $country_name = "";
                $city_name = "";
            }
        }

        $aex_cities = AexCity::orderBy('denominacion')->get();

        if (!$this->storeSettings->guest_checkout && $this->storeSettings->is_cart_abandonment && Auth::guard('web')->check()) {
            $cas = CartAbandonment::where('user_id', Auth::user()->id)->get();
            if ($cas->count() == 0) {
                $ca = new CartAbandonment();
                $ca->user_id = Auth::user()->id;
                $ca->temp_cart = $request->session()->get('cart')->toJson();
                $ca->save();
            } else {
                $ca = $cas->first();
                // Update Cart if user already has a Cart Abandonment in progress
                $ca->temp_cart = $request->session()->get('cart')->toJson();
                $ca->email_sent = false;
                $ca->update();
            }
        }

        if (env('ENABLE_REDPLAY_DIGITAL_PRODUCT', false)) {
            return view('front.checkout_redplay', [
                'customer' => $request->session()->get('temporder'),
                'products' => $cart->items, 'totalPrice' => $total, 'allPickups' => $allPickups, 'totalQty' => $cart->totalQty,
                'gateways' => $gateways, 'shipping_cost' => 0, 'checked' => $ck, 'digital' => $dp, 'curr_checkout' => $curr, 'first_curr' => $first_curr,
                'shipping_data' => $shipping_data, 'package_data' => $package_data, 'vendor_shipping_id' => $vendor_shipping_id,
                'vendor_packing_id' => $vendor_packing_id,
                'bank_accounts' => $bankAccounts, 'countries' => $countries, 'cities' => $city, 'city_id' => $city_id, 'state_id' => $state_id,
                'country_id' => $country_id, 'state_name' => $state_name, 'country_name' => $country_name, 'city_name' => $city_name,
                'aex_cities' => $aex_cities
            ]);
        }
        // dd(Auth::guard('web')->user());
        return view('front.checkout', [
            'customer' => $request->session()->get('temporder'),
            'products' => $cart->items, 'totalPrice' => $total, 'allPickups' => $allPickups, 'totalQty' => $cart->totalQty,
            'gateways' => $gateways, 'shipping_cost' => 0, 'checked' => $ck, 'digital' => $dp, 'curr_checkout' => $curr, 'first_curr' => $first_curr,
            'shipping_data' => $shipping_data, 'package_data' => $package_data, 'vendor_shipping_id' => $vendor_shipping_id,
            'vendor_packing_id' => $vendor_packing_id,
            'bank_accounts' => $bankAccounts, 'countries' => $countries, 'cities' => $city, 'city_id' => $city_id, 'state_id' => $state_id,
            'country_id' => $country_id, 'state_name' => $state_name, 'country_name' => $country_name, 'city_name' => $city_name,
            'aex_cities' => $aex_cities
        ]);
    }

    public function getCep(Request $request)
    {
        $SIGEP = 'https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente';
        $zipcode = $request->cep;
        $zipcode = preg_replace('/[^0-9]/', null, $zipcode);
        $body = trim('
            <?xml version="1.0"?>
            <soapenv:Envelope
                xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
                xmlns:cli="http://cliente.bean.master.sigep.bsb.correios.com.br/">
                <soapenv:Header/>
                <soapenv:Body>
                    <cli:consultaCEP>
                        <cep>' . $zipcode . '</cep>
                    </cli:consultaCEP>
                </soapenv:Body>
            </soapenv:Envelope>
        ');
        $client = new Client();
        $response = $client->post($SIGEP, [
            'http_errors' => false,
            'body' => $body,
            'headers' => [
                'Content-Type' => 'application/xml; charset=utf-8',
                'cache-control' => 'no-cache',
            ],
        ]);
        $xml = $response->getBody()->getContents();
        $parse = simplexml_load_string(str_replace([
            'soap:', 'ns2:',
        ], null, $xml));
        $parsedXML = json_decode(json_encode($parse->Body), true);
        if (array_key_exists('Fault', $parsedXML)) {
            return [
                'error' => $parsedXML['Fault']['faultstring'],
            ];
        };
        $address = $parsedXML['consultaCEPResponse']['return'];
        $zipcode = preg_replace('/^([0-9]{5})([0-9]{3})$/', '${1}-${2}', $address['cep']);
        $complement = [];
        if (array_key_exists('complemento', $address)) {
            $complement[] = $address['complemento'];
        }
        if (array_key_exists('complemento2', $address)) {
            $complement[] = $address['complemento2'];
        }

        //search and return IDs from database
        $city_id = "";
        $state_id = "";
        $country_id = "";
        $state_name = "";

        $cities = City::with('state.country')->where('name', 'like', "%{$address['cidade']}")->get();

        if ($cities->count() > 0) {
            foreach ($cities->toArray() as $city) {
                //check if city belongs to correct state. It is possible to have
                //same city name in different states (Cascavel is in Recife
                //and Parana)
                if (strtolower($city['state']['initial']) === strtolower($address['uf'])) {
                    $city_id = $city['id'];
                    $state_id = $city['state']['id'];
                    $country_id = $city['state']['country']['id'];
                    $state_name = $city['state']['name'];
                    break;
                }
            }
        }

        return [
            'zipcode' => $zipcode,
            'street' => $address['end'],
            'complement' => $complement,
            'district' => $address['bairro'],
            'city' => $address['cidade'],
            'uf' => $address['uf'],
            'city_id' => $city_id,
            'state_id' => $state_id,
            'state_name' => $state_name,
            'country_id' => $country_id,
        ];
    }

    public function getShippingsOptions(Request $request)
    {
        $responseShippings = [];
        $found = false;
        $html = "";
        $cep = preg_replace('/[^0-9]/', '', $request->zip_code);

        /*
        * Métodos de Envio por Região
        * Se o CEP digitado estiver na faixa de algum dos métodos ativos e com uso de região ativado [evita duplicidade de shipping em momentos indevidos]
        */
        $shippingsByRegion = Shipping::where('status', 1)
                                ->where('city_id', $request->city_id)
                                ->where('is_region', 1)
                                ->where('cep_start', '!=', null)
                                ->where('cep_end', '!=', null)
                                ->where('cep_start', '<=', (int)$cep)->where('cep_end', '>=', (int)$cep)
                                ->get();

        // 1 - Get shippings by city - all except those with region enabled
        $shippingsByCity = Shipping::where([
                                'status' => 1,
                                'city_id' => $request->city_id
                            ])
                            ->whereRaw('(is_region is null or is_region = 0)')
                            ->get();

        // 2 - Get shippings by state
        $shippingsByState = Shipping::where([
                                    'status' => 1,
                                    'state_id' => $request->state_id
                                ])
                                ->whereNull('city_id')
                                ->get();

        // 3 - Get shippings by country
        $shippingsByCountry = Shipping::where([
                                    'status' => 1,
                                    'country_id' => $request->country_id
                                ])
                                ->whereNull('city_id')
                                ->whereNull('state_id')
                                ->get();

        // 4 - Get shippings without location
        $shippingsWithoutLocation = Shipping::where('status', 1)
                                        ->whereNull('city_id')
                                        ->whereNull('state_id')
                                        ->whereNull('country_id')
                                        ->get();

        if ($shippingsByRegion->count() && !$found) {
            $responseShippings = $shippingsByRegion->toArray();
            $found = true;
        }

        if ($shippingsByCity->count() && !$found) {
            $responseShippings = $shippingsByCity->toArray();
            $found = true;
        }

        if ($shippingsByState->count() && !$found) {
            $responseShippings = $shippingsByState->toArray();
            $found = true;
        }

        if ($shippingsByCountry->count() && !$found) {
            $responseShippings = $shippingsByCountry->toArray();
            $found = true;
        }

        if ($shippingsWithoutLocation->count() && !$found) {
            $responseShippings = $shippingsWithoutLocation->toArray();
            $found = true;
        }

        if (!$found && $this->storeSettings->is_simplified_checkout !== 1 && config('features.consult_the_price_by_whatsapp')) {
            $html = __("Please, contact us because you cannot buy this item(s) due to admin preferences.");
            return response()->json([
                'is_simplified_checkout' => false,
                'content' => $html
            ]);
        }

        if (!$found && config('features.consult_the_price_by_whatsapp')) {
            $html = __('We will talk in WhatsApp because you are not in a state that we send the products to');
            return response()->json([
                'success' => false,
                'content' => $html
            ]);
        }

        // Calculate prices using Cart
        if ($found) {
            if (Session::has('currency')) {
                $curr = Currency::find(Session::get('currency'));
            } else {
                $curr = Currency::find($this->storeSettings->currency_id);
            }
            $first_curr = Currency::where('id', '=', 1)->first();
            $curr_sign = $curr->sign;
            $decimal_digits = $curr->decimal_digits;
            $decimal_separator = $curr->decimal_separator;
            $thousands_separator = $curr->thousands_separator;

            $oldCart = Session::get('cart');
            $cart = new Cart($oldCart);
            $products = $cart->items;

            $total = $cart->totalPrice;
            $coupon = Session::has('coupon') ? Session::get('coupon') : 0;
            if ($this->storeSettings->tax != 0) {
                $tax = ($total / 100) * $this->storeSettings->tax;
                $total = $total + $tax;
            }
            if (!Session::has('coupon_total')) {
                $total = $total - $coupon;
                $total = $total + 0;
            } else {
                $total = Session::get('coupon_total');
                $total = str_replace($curr->sign, '', $total) + round(0 * $curr->value, 2);
            }

            $correios_weight = 0;
            foreach ($products as $prod) {
                if (empty($prod['item']['original']['weight'])) {
                    $correios_weight = $correios_weight + ($this->storeSettings->correios_weight * $prod['qty']);
                } else {
                    $correios_weight = $correios_weight + ($prod['item']['original']['weight'] * $prod['qty']);
                }
            }

            $peso = $correios_weight;
            if (empty($peso)) {
                $peso = 1;
            }

            // Build final html result
            foreach ($responseShippings as $shipping) {
                $ship_price = $shipping['price'];

                if ($shipping['shipping_type'] == "Fixed Weight") {
                    $ship_price = $shipping['price'] * $peso;
                }

                if ($shipping['shipping_type'] == "Percentage Price") {
                    $percentagevalue = $shipping['price'] / 100;
                    $ship_price = $total * $percentagevalue;
                }

                // Apply free shipping if shipping method is fixed with over price
                if ($shipping['shipping_type'] != 'Free' &&
                    $shipping['price_free_shipping'] > 0 &&
                    $cart->totalPrice >= $shipping['price_free_shipping']) {
                    $ship_price = 0;
                }

                Session::forget('NORMAL-SHIP-' . $shipping['id']);

                Session::put('NORMAL-SHIP-' . $shipping['id'], $ship_price);

                $html .= '<div class="radio-design normal-sheep" id="normal-ship">
                <input type="radio" class="shipping normal-ship-input" id="normal-shepping' . $shipping['id'] . '"
                  name="shipping" data-price="' . $ship_price*$curr->value . '" data-id="' . $shipping['id'] . '" value="' . $shipping['id'] . '">
                <span class="checkmark"></span>
                <label for="normal-shepping' . $shipping['id'] . '">
                  ' . $shipping['title'];
                if ($ship_price != 0) {
                    $html .= ' + ' . $curr_sign . number_format($ship_price*$curr->value, $decimal_digits, $decimal_separator, $thousands_separator);
                }
                $html .= '<small>' . $shipping['subtitle'] . '</small>
                        <small>' . $shipping['delivery_time'] . '</small>
                        </label>
                        </div>';
            }
        }

        $dest_zipcode = '';
        if (!empty($request->zip_code)) {
            $dest_zipcode = $request->zip_code;
            $dest_zipcode = preg_replace('/[^0-9]/', '', $dest_zipcode);
        }
        $local_cep_start = preg_replace('/[^0-9]/', '', $this->storeSettings->localcep_start);
        $local_cep_end = preg_replace('/[^0-9]/', '', $this->storeSettings->localcep_end);

        $correiosPac = '';
        $correiosSedex = '';
        $melhorenvioOption = '';

        $regional = false;
        if (isset($local_cep_start) && isset($local_cep_end) && $dest_zipcode >= $local_cep_start && $dest_zipcode <= $local_cep_end) {
            $regional = true;
        }

        if (empty($dest_zipcode) && empty($html)) {
            $html = '<p class="melhorenvio-sheep">' .__('Shipping'). '<br><small>' . __('Input Zip Code') . '</small></p>';
        } elseif ($regional && empty($html)) {
            $html = __('Regional Zip Code. Please check regional shipping prices in checkout.');
        } elseif (!$regional) {
            // Get Correios shipping options, if available
            if ($this->storeSettings->is_correios) {
                $correiosPac = $this->getCorreios($request->merge(['servico'=>'PAC']));
                $correiosSedex = $this->getCorreios($request->merge(['servico'=>'SEDEX']));
            }

            // Get Melhorenvio shipping options, if available
            if (config("features.melhorenvio_shipping") && $this->storeSettings->is_melhorenvio) {
                $melhorenvioOption = $this->getMelhorenvio($request);
            }
        }
        // Get AEX shipping options, if available
        $aexOption = '';

        if (config("features.aex_shipping") && $this->storeSettings->is_aex && isset($request->codigo_ciudad)) {
            $aexOption = $this->getAex($request);
        }

        return response()->json([
            'success' => true,
            'content' => $html . $correiosPac . $correiosSedex . $melhorenvioOption . $aexOption
        ]);
    }

    public function getCorreios(Request $request)
    {
        if ($this->storeSettings->is_correios) {
            $destino = $request->zip_code;
            $cep_origem = $this->storeSettings->correios_cep;
            $cep_origem = preg_replace('/[^0-9]/', '', $cep_origem);
            $cep_destino = '';
            if (!empty($destino)) {
                $cep_destino = $destino;
                $cep_destino = preg_replace('/[^0-9]/', '', $cep_destino);
            }

            $oldCart = Session::get('cart');
            $cart = new Cart($oldCart);

            if (Session::has('currency')) {
                $curr = Currency::find(Session::get('currency'));
            } else {
                $curr = Currency::find($this->storeSettings->currency_id);
            }

            $total = $cart->totalPrice;
            $coupon = Session::has('coupon') ? Session::get('coupon') : 0;
            if ($this->storeSettings->tax != 0) {
                $tax = ($total / 100) * $this->storeSettings->tax;
                $total = $total + $tax;
            }
            if (!Session::has('coupon_total')) {
                $total = $total - $coupon;
                $total = $total + 0;
            } else {
                $total = Session::get('coupon_total');
                $total = str_replace($curr->sign, '', $total) + round(0 * $curr->value, 2);
            }

            $valor = 0;

            $servico = '04510';
            switch ($request->servico) {
                case 'PAC':
                    $servico = '04510';
                    break;
                case 'SEDEX':
                    $servico = '04014';
                    break;
            }

            $formato = 1;
            $mao_propria = 'n';
            $valor_declarado = $valor;
            $aviso_recebimento = 's';
            $diametro = 0;

            if (empty($valor_declarado)) {
                $valor_declarado = 0;
            }
            if ($valor_declarado < 0) {
                $valor_declarado = 0;
            }
            if ($valor_declarado > 10000) {
                $valor_declarado = 10000;
            }
            $url = 'http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdEmpresa=&sDsSenha=';
            $url = $url . '&sCepOrigem=' . $cep_origem;
            $url = $url . '&sCepDestino=' . $cep_destino;
            $url = $url . '&nVlPeso=' . $cart->getWeight();
            $url = $url . '&nCdFormato=' . $formato;
            $url = $url . '&nVlComprimento=' . $cart->getLenght();
            $url = $url . '&nVlAltura=' . $cart->getHeight();
            $url = $url . '&nVlLargura=' . $cart->getWidth();
            $url = $url . '&sCdMaoPropria=' . $mao_propria;
            $url = $url . '&nVlValorDeclarado=' . $valor_declarado;
            $url = $url . '&sCdAvisoRecebimento=' . $aviso_recebimento;
            $url = $url . '&nCdServico=' . $servico;
            $url = $url . '&nVlDiametro=' . $diametro;
            $url = $url . '&StrRetorno=xml';
            $retorno = ['erro' => 'Serviço temporariamente indisponível, tente novamente mais tarde.'];
            $frete_calcula = simplexml_load_string(file_get_contents($url));
            $frete = $frete_calcula->cServico;
            $temp_brl  = Currency::where('name', '=', "BRL")->first();
            if (empty($temp_brl->value)) {
                $retorno = ['erro' => 'BRL não cadastrado.'];
            } elseif ($frete->Erro == '0') {
                $valor = (Helper::toFloat($frete->Valor) / $temp_brl->value);
                $retorno = ['servico' => $servico, 'valor' => $valor, 'valor_original' => $frete->Valor, 'prazo' => $frete->PrazoEntrega];
            } elseif ($frete->Erro == '7') {
                $retorno = ['erro' => 'Serviço indisponível, tente mais tarde.'];
            } else {
                $retorno = ['erro' => 'Erro no cálculo do frete. Erro:' . $frete->MsgErro];
            }

            if (!empty($retorno['erro'])) {
                $html = '<p class="' . $request->servico . '-sheep">' . $request->servico . ' ' . __('Unavaiable') . '<br><small>' . $retorno['erro'] . '</small></p>';
                return $html;
            } else {
                $curr_sign = $curr->sign;
                $decimal_digits = $curr->decimal_digits;
                $decimal_separator = $curr->decimal_separator;
                $thousands_separator = $curr->thousands_separator;

                $valor = $retorno['valor'];
                switch ($frete->Codigo[0]) {
                    case '04510':
                        $servico = 'PAC';
                        Session::put('correios_pac_cep', $cep_destino);
                        Session::put('correios_pac_valor', $valor);
                        break;
                    case '04014':
                        $servico = 'SEDEX';
                        Session::put('correios_sedex_cep', $cep_destino);
                        Session::put('correios_sedex_valor', $valor);
                        break;
                }

                $valor_format = $curr_sign . number_format($retorno['valor'] * $curr->value, $decimal_digits, $decimal_separator, $thousands_separator);
                $prazo = $retorno['prazo'][0];

                $html = '<div class="radio-design ' . $request->servico . '-sheep" id="' . $request->servico . '-ship">
                <input type="radio" class="shipping ' . $request->servico . '-ship-input" id="' . $request->servico . '-shepping" name="shipping"
                  data-price="' . $valor * $curr->value . '" data-id="' . $request->servico . '" value="' . $request->servico . '">
                <span class="checkmark"></span>
                <label for="' . $request->servico . '-shepping" id="' . $request->servico . '-label">
                  ' . __('Correios') . ' ' . $request->servico . ' + ' . $valor_format . '
                  <small>' . $prazo . ' ' . __('days') . '</small>
                </label>
              </div>';

                return $html;
            }
        }
    }

    public function getAex(request $request)
    {
        if ($this->storeSettings->is_aex && config("features.aex_shipping")) {
            $codigo_ciudad = $request->codigo_ciudad;
            $aex_origin = $this->storeSettings->aex_origin;

            $oldCart = Session::get('cart');
            $cart = new Cart($oldCart);

            $total = $cart->totalPrice;
            $coupon = Session::has('coupon') ? Session::get('coupon') : 0;

            if (Session::has('currency')) {
                $curr = Currency::find(Session::get('currency'));
            } else {
                $curr = Currency::find($this->storeSettings->currency_id);
            }

            if ($this->storeSettings->tax != 0) {
                $tax = ($total / 100) * $this->storeSettings->tax;
                $total = $total + $tax;
            }
            if (!Session::has('coupon_total')) {
                $total = $total - $coupon;
                $total = $total + 0;
            } else {
                $total = Session::get('coupon_total');
                $total = str_replace($curr->sign, '', $total) + round(0 * $curr->value, 2);
            }

            $temp_pyg  = Currency::where('name', '=', "PYG")->first();
            if (empty($temp_pyg->value)) {
                $html = '<p class="aex-sheep">AEX ' . __('Unavaiable') . '<br><small>PYG '.__('Unavaiable').'</small></p>';
                return $html;
            }

            $aex_value = $total * $temp_pyg->value;

            $obj = (object)array();
            $obj->peso = $cart->getWeight();
            $obj->largo = $cart->getLenght();
            $obj->alto = $cart->getHeight();
            $obj->ancho = $cart->getWidth();
            $obj->valor = $aex_value;
            $package = [$obj];

            $aex = new Aex($this->storeSettings->aex_public, $this->storeSettings->aex_private, $this->storeSettings->is_aex_production);
            $aex_response = $aex->getRates($aex_origin, $codigo_ciudad, $package, 'P');

            if ($aex_response->codigo != 0) {
                $html = '<p class="aex-sheep">AEX ' . __('Unavaiable') . '<br><small>' . $aex_response->mensaje . '</small></p>';
                return $html;
            } else {
                $html = "";
                foreach ($aex_response->datos as $aex_service) {
                    $value = Helper::toFloat($aex_service->costo_servicio) / $temp_pyg->value;
                    if (isset($aex_service->adicionales)) {
                        foreach ($aex_service->adicionales as $adicional) {
                            if ($this->storeSettings->is_aex_insurance || $adicional->obligatorio == 't') {
                                $value += Helper::toFloat($adicional->costo) / $temp_pyg->value;
                            }
                        }
                    }

                    Session::put('aex_destination_'.$aex_service->id_tipo_servicio, $codigo_ciudad);
                    Session::put('aex_value_'.$aex_service->id_tipo_servicio, $value);
                    Session::put('aex_service_'.$aex_service->id_tipo_servicio, 'AEX-'.$aex_service->denominacion);
                    $value_format = $curr->sign . number_format(
                        $value * $curr->value,
                        $curr->decimal_digits,
                        $curr->decimal_separator,
                        $curr->thousands_separator
                    );

                    $estimated = $aex_service->tiempo_entrega;

                    $type_punto_entrega = "";
                    if (empty($aex_service->puntos_entrega)) {
                        $html .=
                        '<div class="radio-design aex-sheep" id="aex-ship">
                            <input type="radio" data-itemtype="standard" onclick="excluirPonto()" class="shipping aex-ship-input" id="aex-shepping" name="shipping"
                            data-price="' . $value * $curr->value . '" data-id="AEX_'.$aex_service->id_tipo_servicio.'" value="AEX_'.$aex_service->id_tipo_servicio.'">
                            <span class="checkmark"></span>
                            <label for="aex-shepping" id="aex-label">
                            ' . __('AEX') . ' - ' . $aex_service->denominacion . ' + ' . $value_format . '
                            <small>' . $estimated . ' ' . __('hours') . '</small>
                            <small>' . $aex_service->descripcion . '</small>
                            </label>
                        </div>';
                    }

                    if (isset($aex_service->puntos_entrega)) {
                        foreach ($aex_service->puntos_entrega as $punto) {
                            $type_punto_entrega .=
                                '<div class="radio-design punto-option">
                                        <input type="radio" class="punto_entrega" name="puntoentrega" onclick="gerarPonto('.$punto->id.')" value="'.$punto->punto_entrega.'">
                                        <input type="hidden" class="punto_id" name="puntoid" value="'.$punto->id.'">
                                        <span class="checkmark"></span>
                                        <label for="puntoentrega" id="puntoentrega-label">
                                            ' . $punto->punto_entrega . '
                                            <small>' . $punto->horario_atencion . '</small>
                                            <small>' . $punto->direccion . '</small>
                                            <small>' . __('Latitud') . '  ' . $punto->latitud . '</small>
                                            <small>' . __('Longitud') . '  ' . $punto->longitud . '</small>
                                            <small>' . __('Telefono') . '  ' . $punto->telefono . '</small>
                                        </label>
                                </div>';
                        }
                        $html .=
                                        '<div class="radio-design aex-sheep">
                                            <input type="radio" class="aex-ship-input shipping" id="parent" name="shipping"
                                            data-price="' . $value * $curr->value . '" data-id="AEX_'.$aex_service->id_tipo_servicio.'" value="AEX_'.$aex_service->id_tipo_servicio.'">
                                            <span class="checkmark"></span>
                                            <label for="aex-shepping" id="aex-label">
                                                ' . __('AEX') . ' - ' . $aex_service->denominacion . ' + ' . $value_format . '
                                                <small>' . $estimated . ' ' . __('hours') . '</small>
                                                <small>' . $aex_service->descripcion . '</small>
                                                '.$type_punto_entrega.'
                                            </label>
                                        </div>';
                    }
                }
                return $html;
            }
        }
    }

    public function getMelhorenvio(request $request)
    {
        if ($this->storeSettings->is_melhorenvio && config("features.melhorenvio_shipping") && $this->storeSettings->melhorenvio->token) {
            $dest_zipcode = $request->zip_code;
            $melhorenvio_origin = $this->storeSettings->melhorenvio->from_postal_code;

            $oldCart = Session::get('cart');
            $cart = new Cart($oldCart);

            $total = $cart->totalPrice;
            $coupon = Session::has('coupon') ? Session::get('coupon') : 0;

            if (Session::has('currency')) {
                $curr = Currency::find(Session::get('currency'));
            } else {
                $curr = Currency::find($this->storeSettings->currency_id);
            }

            if ($this->storeSettings->tax != 0) {
                $tax = ($total / 100) * $this->storeSettings->tax;
                $total = $total + $tax;
            }
            if (!Session::has('coupon_total')) {
                $total = $total - $coupon;
                $total = $total + 0;
            } else {
                $total = Session::get('coupon_total');
                $total = str_replace($curr->sign, '', $total) + round(0 * $curr->value, 2);
            }

            $temp_brl  = Currency::where('name', '=', "BRL")->first();
            if (empty($temp_brl->value)) {
                $html = '<p class="melhorenvio-sheep">' .__('Shipping'). ' ' . __('Unavaiable') . '<br><small>BRL '.__('Unavaiable').'</small></p>';
                return $html;
            }

            $insurance_value = $this->storeSettings->melhorenvio->insurance ? $total * $temp_brl->value : 0;

            // Utilizando o calculo via lista de itens
            $package = (object)[
                "height" => $cart->getHeight(),
                "width"  => $cart->getWidth(),
                "length"  => $cart->getLenght(),
                "weight"  => $cart->getWeight()
            ];

            /* $items_with_measures = $cart->getItemsWithMeasures(); */

            $options = (object)[
                "insurance_value" => $insurance_value,
                "receipt"  => $this->storeSettings->melhorenvio->receipt,
                "own_hand"  => $this->storeSettings->melhorenvio->ownhand
                //Collect not working on Melhor Envio yet
                //"collect"  => $this->storeSettings->melhorenvio->collect
            ];

            $services = implode(",", $this->storeSettings->melhorenvio->selected_services);

            $melhorenvio = new MelhorEnvio($this->storeSettings->melhorenvio->token, $this->storeSettings->melhorenvio->production);
            $melhorenvio_response = $melhorenvio->getRates($melhorenvio_origin, $dest_zipcode, $package, null, $options, $services);

            if (isset($melhorenvio_response->message)) {
                $html = '<p class="melhorenvio-sheep">' .__('Shipping'). ' ' . __('Unavaiable') . '<br><small>' . $melhorenvio_response->message . '</small></p>';
                return $html;
            } else {
                $html = "";

                foreach ($melhorenvio_response as $melhorenvio_service) {
                    if (!isset($melhorenvio_service->error)) {
                        $value = Helper::toFloat($melhorenvio_service->custom_price) / $temp_brl->value;

                        Session::put('melhorenvio_destination_'.$melhorenvio_service->id, $dest_zipcode);
                        Session::put('melhorenvio_value_'.$melhorenvio_service->id, $value);
                        Session::put('melhorenvio_service_'.$melhorenvio_service->id, $melhorenvio_service->company->name.' - '.$melhorenvio_service->name);
                        $value_format = $curr->sign . number_format(
                            $value * $curr->value,
                            $curr->decimal_digits,
                            $curr->decimal_separator,
                            $curr->thousands_separator
                        );

                        $estimated_min = $melhorenvio_service->custom_delivery_range->min;
                        $estimated_max = $melhorenvio_service->custom_delivery_range->max;

                        $html = $html.'<div class="radio-design melhorenvio-sheep" id="melhorenvio-ship">
                                    <input type="radio" class="shipping melhorenvio-ship-input" id="melhorenvio-shepping" name="shipping"
                                    data-price="' . $value * $curr->value . '" data-id="MELHORENVIO_'.$melhorenvio_service->id.'" value="AEX_'.$melhorenvio_service->id.'">
                                    <span class="checkmark"></span>
                                    <label for="melhorenvio-shepping" id="melhorenvio-label">
                                    ' . $melhorenvio_service->company->name . ' - ' . $melhorenvio_service->name . ' + ' . $value_format . '
                                    <small>' . __(':min to :max days', ['min'=>$estimated_min, 'max'=>$estimated_max]) . '</small>
                                    </label>
                                    </div>';
                    } else {
                        $html = $html.'<div class="radio-design melhorenvio-sheep" id="melhorenvio-ship">
                                    <label>' . $melhorenvio_service->company->name . ' - ' . $melhorenvio_service->name . '
                                    <small>' . $melhorenvio_service->error . '</small>
                                    </label>
                                    </div>';
                    }
                }

                return $html;
            }
        }
    }
}
