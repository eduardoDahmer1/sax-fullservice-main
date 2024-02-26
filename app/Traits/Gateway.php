<?php

namespace App\Traits;

use App\Models\Cart;
use App\Models\City;
use App\Models\User;
use App\Models\Order;
use App\Models\State;
use App\Models\Coupon;
use App\Models\Country;
use App\Models\Package;
use App\Models\Product;
use App\Models\Currency;
use App\Models\Shipping;
use App\Models\OrderTrack;
use App\Models\Pagesetting;
use App\Models\VendorOrder;
use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Classes\GeniusMailer;
use App\Models\AexCity;
use App\Models\CustomProduct;
use App\Models\UserNotification;
use App\Tbpedidoitens;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\CartAbandonment;

/**
 * Common logic of payment gateways
 */
trait Gateway
{
    /**
     * Gateway allowed currency.
     * null means all currency are allowed.
     *
     * @var string|null
     */
    private $checkCurrency = null;

    /**
     * Gateway's name
     *
     * @var string
     */
    private $name = "Payment Gateway";

    /**
     * Gateway's enabled status
     *
     * @var boolean
     */
    private $enabled = false;

    /**
     * Gateway's credentials. Free format.
     * All keys provided must be not null to continue
     * Example: [
     *   "token" => "xyz"
     * ];
     *
     * @var array
     */
    private $credentials = [];

    /** @var Order */
    private $order;

    /** @var array */
    private $cartTotal = [];

    /** @var array */
    private $cartTotalCurrency = [];

    /**
     * The URL returned by the gateway to continue the payment capture.
     * Must be set from $this->payment().
     *
     * @var string
     */
    private $paymentUrl = "";

    /**
     * An array of data the gateway can send to process in frontend. This data
     * will be encoded to json and the redirect won't happen even if the
     * paymentUrl is present.
     * Must be set from $this->payment().
     *
     * @var array
     */
    private $paymentJson = [];

    /**
     * An array of errors sent from gateway. If there are erros, the redirect
     * will show them to the frontend.
     *
     * @var array
     */
    private $paymentErrors = [];

    /**
     * Will check final value to not be zero by default.
     * Set it to false in the gateway to bypass this validation.
     *
     * @var bool
     */
    private $checkValue = true;

    /** @var Request */
    private $request;

    /**
     * Default store and manipulation of order and checkout.
     * Will call $this->payment() to allow custom gateway logic.
     *
     * @param Request $request
     * @return void
     */
    protected $stateCode;

    public function store(Request $request)
    {

        $isBrl = Currency::where('is_default', 1)->where('name', 'BRL')->get();

        if (!$this->enabled) {
            if ($request->ajax()) {
                return response()->json([
                    'unsuccess' => __("Sorry, {$this->name} is not available for this store.")
                ], 404);
            }
            return redirect()->back()->with('unsuccess', __("Sorry, {$this->name} is not available for this store."));
        }

        //REMOVIDO TODAS VALIDAÇÕES BRASILEIRA DE COMPRA CPF E CNPJ

        if (config('document.cpf')) {
            // if (strlen($request->customer_document) != 11) {
            //     if ($request->ajax()) {
            //         return response()->json([
            //             'unsuccess' => __("Invalid CPF. Please check the Document field")
            //         ], 404);
            //     }
            //     return redirect()->back()->with('unsuccess', __("Invalid CPF. Please check the Document field"));
            // }
        } elseif (config('document.cnpj')) {
            // if (strlen($request->customer_document) != 14) {
            //     if ($request->ajax()) {
            //         return response()->json([
            //             'unsuccess' => __("Invalid CNPJ. Please check the Document field")
            //         ], 404);
            //     }
            //     return redirect()->back()->with('unsuccess', __("Invalid CNPJ. Please check the Document field"));
            // }
        } elseif (config('document.general') && !empty($isBrl->items)) {
            // if (strlen($request->customer_document) < 11 || (strlen($request->customer_document) > 11 && strlen($request->customer_document) < 14 || strlen($request->customer_document) > 14)) {
            //     if ($request->ajax()) {
            //         return response()->json([
            //             'unsuccess' => __("Invalid CPF/CNPJ. Please check the Document field")
            //         ], 404);
            //     }
            //     return redirect()->back()->with('unsuccess', __("Invalid CPF/CNPJ. Please check the Document field"));
            // }
        }

        if ($request->pass_check) {
            $users = User::where('email', '=', $request->personal_email)->get();

            if (count($users) == 0) {
                if ($request->personal_pass == $request->personal_confirm) {
                    $user = new User;
                    $user->name = $request->personal_name;
                    $user->email = $request->personal_email;
                    $user->document = $request->customer_document;
                    $user->zip = $request->zip;
                    $user->phone = $request->phone;
                    $user->address = $request->address;
                    $user->address_number = $request->address_number;
                    $user->complement = $request->complement;
                    $user->district = $request->district;
                    $user->country = $request->country;
                    $user->state = $request->state;
                    $user->city = $request->city;
                    $user->password = bcrypt($request->personal_pass);
                    $token = md5(time() . $request->personal_name . $request->personal_email);
                    $user->verification_link = $token;
                    $user->affilate_code = md5($request->name . $request->email);
                    $user->email_verified = 'Yes';
                    $user->save();
                    Auth::guard('web')->login($user);
                } else {
                    if ($request->ajax()) {
                        return response()->json([
                            'unsuccess' => __("Confirm Password Doesn't Match.")
                        ], 404);
                    }
                    return redirect()->back()->with('unsuccess', __("Confirm Password Doesn't Match."));
                }
            } else {
                if ($request->ajax()) {
                    return response()->json([
                        'unsuccess' => __("This Email Already Exist.")
                    ], 404);
                }
                return redirect()->back()->with('unsuccess', __("This Email Already Exist."));
            }
        }

        if (!Session::has('cart')) {
            if ($request->ajax()) {
                return response()->json([
                    'unsuccess' => __("You don't have any product to checkout.")
                ], 404);
            }
            return redirect()->route('front.cart')->with('success', __("You don't have any product to checkout."));
        }

        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::find($this->storeSettings->currency_id);
        }

        // Check if currency is supported by the gateway
        if ($this->checkCurrency) {
            if ($curr->name !== $this->checkCurrency) {
                if ($request->ajax()) {
                    return response()->json([
                        'unsuccess' => __("Please Select {$this->checkCurrency} Currency For {$this->name}.")
                    ], 404);
                }
                return redirect()->back()->with('unsuccess', __("Please Select {$this->checkCurrency} Currency For {$this->name}."));
            }
        }

        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);

        // Check credentials for gateway
        if (array_search(null, $this->credentials, true)) {
            if ($request->ajax()) {
                return response()->json([
                    'unsuccess' => __("{$this->name} Error")
                ], 404);
            }
            return redirect()->back()->with('unsuccess', __("{$this->name} Error"));
        }

        foreach ($cart->items as $key => $prod) {
            $db_prod = Product::find($prod['item']->id);
            if ($prod['item']->stock != $db_prod->stock) {
                if ($db_prod->stock < $prod['qty']) {
                    if ($db_prod->stock == 0) {
                        return redirect()->route('front.cart')->with('unsuccess', __('Product :prod has no stock!', ['prod' => $prod['item']['name']]));
                    }
                    return redirect()->route('front.cart')->with('unsuccess', __('Insufficient stock of :prod!', ['prod' => $prod['item']['name']]));
                }
            }
            if (!empty($prod['item']['license']) && !empty($prod['item']['license_qty'])) {
                foreach ($prod['item']['license_qty'] as $ttl => $dtl) {
                    if ($dtl != 0) {
                        $dtl--;
                        $produc = Product::findOrFail($prod['item']['id']);
                        $temp = $produc->license_qty;
                        $temp[$ttl] = $dtl;
                        $final = implode(',', $temp);
                        $produc->license_qty = $final;
                        $produc->update();

                        $temp =  $produc->license;
                        $license = $temp[$ttl];
                        $oldCart = Session::has('cart') ? Session::get('cart') : null;
                        $cart = new Cart($oldCart);
                        $cart->updateLicense($prod['item']['id'], $license);
                        Session::put('cart', $cart);
                        break;
                    }
                }
            }
        }

        $coupon_disc = Session::get('coupon') / $curr->value;
        $this->order = new Order;

        // Shipping validation
        $this->order['shipping_cost'] = 0;
        $this->order['packing_cost'] = 0;
        $this->order['shipping_type'] = __("Pick Up");

        $ship_error = false;
        $temp_shipping = null;

        if ($request->nc_typeShipping == "shipto") {
            if ($request->diff_address == "value1") {
                $temp_zip = $request->shipping_zip;
            } else {
                $temp_zip = $request->zip;
            }
            $temp_zip = preg_replace('/[^0-9]/', '', $temp_zip);
            if ($request->shipping_cost == "SEDEX") {
                // $sedex_cep = Session::get('correios_sedex_cep');
                // if ($temp_zip != $sedex_cep) {
                //     $ship_error = true;
                // } else {
                //     $this->order['shipping_cost'] = Session::get('correios_sedex_valor');
                //     $this->order['shipping_type'] = "Correios SEDEX";
                // }
            } elseif ($request->shipping_cost == "PAC") {
                // $pac_cep = Session::get('correios_pac_cep');
                // if ($temp_zip != $pac_cep) {
                //     $ship_error = true;
                // } else {
                //     $this->order['shipping_cost'] = Session::get('correios_pac_valor');
                //     $this->order['shipping_type'] = "Correios PAC";
                // }
            } elseif (str_starts_with($request->shipping_cost, "AEX_")) {
                // $service_id = explode('_', $request->shipping_cost, 2)[1];
                // $aex_destination = Session::get('aex_destination_'.$service_id);
                // if ($request->aex_city != $aex_destination) {
                //     $ship_error = true;
                // } else {
                //     $this->order['shipping_cost'] = Session::get('aex_value_'.$service_id);
                //     $this->order['shipping_type'] = Session::get('aex_service_'.$service_id);
                //     $aex_city = AexCity::where('codigo_ciudad', $aex_destination)->first();
                //     $aex_internal_note = 'AEX:['.$aex_destination.';'.$service_id.'] '.$aex_city->denominacion.' - '.$aex_city->departamento_denominacion.'|';
                // }
            } elseif (str_starts_with($request->shipping_cost, "MELHORENVIO_")) {
                // $service_id = explode('_', $request->shipping_cost, 2)[1];
                // /* $melhorenvio_destination = Session::get('melhorenvio_destination_'.$service_id); */
                // $melhorenvio_destination = preg_replace('/[^0-9]/', '', Session::get('melhorenvio_destination_'.$service_id));
                // if ($temp_zip != $melhorenvio_destination) {
                //     $ship_error = true;
                // } else {
                //     $this->order['shipping_cost'] = Session::get('melhorenvio_value_'.$service_id);
                //     $this->order['shipping_type'] = Session::get('melhorenvio_service_'.$service_id);
                //     $melhorenvio_internal_note = 'MELHORENVIO:['.$melhorenvio_destination.';'.$service_id.'] '.$this->order['shipping_type'].'|';
                // }
            } else {
                if (empty($request->shipping_cost)) {
                    $ship_error = true;
                } else {
                    $temp_shipping = Shipping::where('id', '=', $request->shipping_cost)->first();
                    if (!empty($temp_shipping)) {
                        $session_ship_price = Session::get('NORMAL-SHIP-' . $request->shipping_cost);
                        if (empty($session_ship_price) &&
                            $temp_shipping->shipping_type != 'Free' &&
                            $temp_shipping->price_free_shipping == 0) {
                            $ship_error = true;
                        } else {
                            $this->order['shipping_cost'] = $session_ship_price;
                            $this->order['shipping_type'] = $temp_shipping->title;
                        }
                    } else {
                        $ship_error = true;
                    }
                }
            }
            // if ($ship_error) {
            //     if ($request->ajax()) {
            //         return response()->json([
            //             'unsuccess' => __('Shipping Error')
            //         ], 404);
            //     }
            //     return redirect()->back()->with('unsuccess', __('Shipping Error'));
            // }
        }

        // Package validation
        $temp_package = Package::where('id', '=', $request->packing_cost)->first();
        if (!empty($temp_package->title)) {
            $this->order['packing_cost'] = $temp_package->price;
            $this->order['packing_type'] = $temp_package->title;
        } else {
            if ($request->ajax()) {
                return response()->json([
                    'unsuccess' => __('Packing Error')
                ], 404);
            }
            return redirect()->back()->with('unsuccess', __('Packing Error'));
        }

        if (empty($this->order['shipping_cost'])) {
            $this->order['shipping_cost'] = 0;
        }
        if (empty($this->order['packing_cost'])) {
            $this->order['packing_cost'] = 0;
        }


        if (Session::has('coupon_total')) {
            $cart_total = Session::get('coupon_total') / $curr->value;
        } elseif (Session::has('coupon_total1')) {
            $cart_total = Session::get('coupon_total1') / $curr->value;
        } else {
            $cart_total = $oldCart->totalPrice * (1 + ($this->storeSettings->tax / 100));
        }

        // Apply free shipping if shipping method is fixed with over price
        if (!empty($temp_shipping) && $temp_shipping->shipping_type != 'Free' &&
            $temp_shipping->price_free_shipping > 0 &&
            $cart_total >= $temp_shipping->price_free_shipping) {
            $this->order['shipping_cost'] = 0;
        }

        // Cart Totals
        $this->cartTotal = [
            "before_costs" => $cart_total,
            "after_costs" => $cart_total + $this->order['packing_cost'] + $this->order['shipping_cost']
        ];

        $this->cartTotalCurrency = [
            "before_costs" => $this->cartTotal["before_costs"] * $curr->value,
            "after_costs" => $this->cartTotal["after_costs"] * $curr->value
        ];

        // Check if the value after all calculations is valid
        if ($this->checkValue) {
            if ($this->cartTotalCurrency["after_costs"] <= 0) {
                if ($request->ajax()) {
                    return response()->json([
                        'unsuccess' => __("No value to pay on {$this->name}")
                    ], 404);
                }
                return redirect()->back()->with('unsuccess', __("No value to pay on {$this->name}"));
            }
        }

        $customer_city = "";
        $customer_city_id = "";
        $customer_state = "";
        $customer_state_id = "";
        $customer_state_initials = "";
        $customer_country = "";
        $customer_country_id = "";
        $shipping_city = "";
        $shipping_city_id = "";
        $shipping_state = "";
        $shipping_state_id = "";
        $shipping_state_initials = "";
        $shipping_country = "";
        $shipping_country_id = "";

        if (!empty($request->city)) {
            $city = City::find($request->city);
            $customer_city = $city->name;
            $customer_city_id = $city->id;
        }
        if (!empty($request->state)) {
            $state = State::find($request->state);
            $customer_state = $state->name;
            $customer_state_id = $state->id;
            $customer_state_initials = $state->initial;
            $this->stateCode = $state->initial;
        }
        if (!empty($request->country)) {
            $country = Country::find($request->country);
            $customer_country = $country->country_name;
            $customer_country_id = $country->id;
        }
        if (!empty($request->shipping_city)) {
            $shipCity = City::find($request->shipping_city);
            $shipping_city = $shipCity->name;
            $shipping_city_id = $shipCity->id;
        }
        if (!empty($request->shipping_state)) {
            $shipState = State::find($request->shipping_state);
            $shipping_state = $shipState->name;
            $shipping_state_id = $shipState->id;
            $shipping_state_initials = $shipState->initial;
        }
        if (!empty($request->shipping_country)) {
            $shipCountry = Country::find($request->shipping_country);
            $shipping_country = $shipCountry->country_name;
            $shipping_country_id = $shipCountry->id;
        }

        $document = preg_replace('/[^0-9]/', '', $request->document);
        if (empty($document)) {
            if ($request->ajax()) {
                return response()->json([
                    'unsuccess' => __("Sorry, document field only accepts numbers")
                ], 404);
            }

            return redirect()->back()->withInput()->with('unsuccess', __("Sorry, document field only accepts numbers"));
        }

        $final_internal_note = '#:['.$this->storeSettings->id.';'.$this->storeSettings->domain.'] | ';
        if (isset($aex_internal_note)) {
            $final_internal_note = $final_internal_note.$aex_internal_note;
        }
        if (isset($melhorenvio_internal_note)) {
            $final_internal_note = $final_internal_note.$melhorenvio_internal_note;
        }

        $customProducts = env("ENABLE_CUSTOM_PRODUCT", false);
        $customProductsNumber = env("ENABLE_CUSTOM_PRODUCT_NUMBER", false);

        $final_order_note = $request->order_note;

        if ($customProducts && !$customProductsNumber) {
            foreach ($cart->items as $prod) {
                if (!empty($prod['customizable_name']) || !empty($prod['customizable_logo']) || !empty($prod['customizable_gallery'])) {
                    $agreeCustomTerms = __('You agreed with the conditions and terms for custom products');
                    $final_order_note .= !empty($final_order_note) ? ' | '.$agreeCustomTerms : $agreeCustomTerms;
                }
            }
        }
        if ($customProductsNumber && !$customProducts) {
            foreach ($cart->items as $prod) {
                if (!empty($prod['customizable_name']) || !empty($prod['customizable_number'])) {
                    $agreeCustomTerms = __('You reviewed your custom products choices.');
                    $final_order_note .= !empty($final_order_note) ? ' | '.$agreeCustomTerms : $agreeCustomTerms;
                }
            }
        }

        
        $cartArray = [];
        $cartArray['items'] = $cart->items;
        $cartArray['totalQty'] = $cart->totalQty;
        $cartArray['totalPrice'] = $cart->totalPrice + $this->order['shipping_cost'];

        $this->order['user_id'] = $request->user_id;
        $this->order['cart'] = $cartArray;
        $this->order['totalQty'] = $request->totalQty;
        $this->order['pay_amount'] = $curr->value != 1 ? $this->cartTotal["after_costs"] : round($this->cartTotal["after_costs"], 2);
        $this->order['method'] = $this->name;
        $this->order['order_number'] = Str::random(4) . time();
        $this->order['shipping'] = $request->shipping;

        $this->order['tax'] = $this->storeSettings->tax;
        $this->order['order_note'] = $final_order_note;
        $this->order['internal_note'] = $final_internal_note;
        $this->order['coupon_code'] = $request->coupon_code;
        $this->order['coupon_discount'] = $coupon_disc;
        $this->order['dp'] = $request->dp;
        $this->order['payment_status'] = "Pending";
        $this->order['currency_sign'] = $curr->sign;
        $this->order['currency_value'] = $curr->value;
        $this->order['vendor_shipping_id'] = $request->vendor_shipping_id;
        $this->order['vendor_packing_id'] = $request->vendor_packing_id;

        $this->order['customer_email'] = $request->email;
        $this->order['customer_name'] = $request->name;
        $this->order['customer_document'] = $request->document;
        $this->order['customer_zip'] = $request->zip;
        $this->order['customer_phone'] = $request->phone;
        $this->order['customer_address'] = $request->address;
        $this->order['customer_address_number'] = $request->address_number;
        $this->order['customer_complement'] = $request->complement;
        $this->order['customer_district'] = $request->district;
        $this->order['customer_city'] = $customer_city;
        $this->order['customer_state'] = $customer_state;
        $this->order['customer_country'] = $customer_country;

        $this->order['shipping_name'] = $request->name;
        $this->order['shipping_document'] = $request->document;
        $this->order['shipping_email'] = $request->email;
        $this->order['shipping_zip'] = $request->zip;
        $this->order['shipping_phone'] = $request->phone;
        $this->order['shipping_address'] = $request->address;
        $this->order['shipping_address_number'] = $request->address_number;
        $this->order['shipping_district'] = $request->district;
        $this->order['shipping_complement'] = $request->complement;
        $this->order['shipping_city'] = $shipping_city;
        $this->order['shipping_state'] = $shipping_state;
        $this->order['shipping_country'] = $shipping_country;
        $this->order['puntoentrega'] = $request->puntoentrega;
        $this->order['puntoid'] = $request->puntoidvalue;
        
        // check if product is digital
        if ($request->shipping == 3) {
            $values = explode('|', $request->pickup_location);
            $this->order['pickup_location'] = $values[0];
            $this->order['store_id'] = intval($values[1]);
        }

        if ($this->order['dp'] == 1) {
            $this->order['status'] = 'completed';
        }

        if (Session::has('affilate')) {
            $val = $request->total / $curr->value;
            $val = $val / 100;
            $sub = $val * $this->storeSettings->affilate_charge;
            $user = User::findOrFail(Session::get('affilate'));
            $user->affilate_income += $sub;
            $user->update();

            $this->order['affilate_user'] = $user->name;
            $this->order['affilate_charge'] = $sub;
        }
        $this->order->save();



        //APOS A ORDEM, ATUALIZO TODOS OS DADOS DO CLIENTE, E SALVO ELES.
        $users = User::where('email', $request->personal_email)->first();
        $users['email'] = $request->email;
        $users['name'] = $request->name;
        $users['document'] = $request->document;
        $users['zip'] = $request->zip;
        $users['phone'] = $request->phone;
        $users['address'] = $request->address;
        $users['address_number'] = $request->address_number;
        $users['complement'] = $request->complement;
        $users['district'] = $request->district;
        $users['city'] = $customer_city;
        $users['state'] = $customer_state;
        $users['country'] = $customer_country;
        $users['birth_date'] = $request->customer_birthday;
        $users['gender'] = $request->customer_gender;
        $users['ruc'] = $request->cpf_brasileiro;
        $users->save();

        if (!$this->storeSettings->guest_checkout && $this->storeSettings->is_cart_abandonment) {
            CartAbandonment::where('user_id', Auth::user()->id)->delete();
        }
        Session::put('order', $this->order);


        if ($customProducts) {
            foreach ($cart->items as $prod) {
                if (!empty($prod['customizable_name']) || !empty($prod['customizable_logo']) || !empty($prod['customizable_gallery'])) {
                    $custom_prod = new CustomProduct;
                    $custom_prod->customizable_name = $prod['customizable_name'];
                    $custom_prod->product_id = $prod['item']['id'];
                    $custom_prod->customizable_logo = $prod['customizable_logo'];
                    $custom_prod->agree_terms = $prod['agree_terms'];
                    $custom_prod->order_id = $this->order['id'];
                    $custom_prod->save();
                }
            }
        }

        /**
        * To enable integration with Solução Empresarial, set the WITH_SOLUCAOEMPRESARIAL=true in your .env file
        * Perform migrations in the path "databse/migrations/solucaoempresarial"
        */
        $solucaoEmpresarial = env("WITH_SOLUCAOEMPRESARIAL", false);

        if ($solucaoEmpresarial) {
            $dataSet = [];
            foreach ($cart->items as $item) {
                $produto = Product::find($item['item']->id);

                $dataSet[] = [
                    'numero_pedido' => $this->order['order_number'],
                    'id_produto' => $item['item']->id,
                    'cod_produto' => $item['item']->ref_code,
                    'descricao' => $produto->name,
                    'marca' => $produto->brand->name,
                    'tipo' => $produto->category->name,
                    'qtd_pedida' => $item['qty'],
                    'preco_unitario' => ($item['price'] / $item['qty']),
                    'preco_total' => $item['price'],
                    'obs_produto' => null,
                ];
            }

            DB::connection('mysqlIntegracao')->table('tbpedidoitens')->insert($dataSet);

            DB::connection('mysqlIntegracao')->table('tbpedidos')->insert(
                [
                    'numero_pedido' => $this->order['order_number'],

                    'cod_cliente' => ($this->order['user_id']) ? $this->order['user_id'] : 0,
                    'nome_cliente' => $this->order['customer_name'],
                    'cod_vendedor' => 0,
                    'nome_vendedor' => 'e-commerce',

                    'subtotal' => round($this->cartTotal["before_costs"], 2),
                    'desconto_porcentual' => 0,
                    'desconto_valor' => 0,
                    'total' => round($this->cartTotal["after_costs"], 2),

                    'nome_entrega' => $this->order['shipping_name'],
                    'cep_entrega' => $this->order['shipping_zip'],
                    'endereco_entrega' => $this->order['shipping_address'],
                    'numero_entrega' => $this->order['shipping_address_number'],
                    'bairro_entrega' => $this->order['shipping_district'],
                    'cidade_entrega' => $this->order['shipping_city'],
                    'estado_entrega' => $shipping_state_initials,
                    'telefone_entrega' => $this->order['shipping_phone'],
                    'celular_entrega' => $this->order['shipping_phone'],
                    'obs_entrega' => $this->order['shipping_complement'],

                    'cpf_cnpj' => $this->order['customer_document'],
                    'rg_ie' => null,

                    'cep' => $this->order['customer_zip'],
                    'endereco' => $this->order['customer_address'],
                    'numero' => $this->order['customer_address_number'],
                    'cod_cidade' => null,
                    'nome_cidade' => $this->order['customer_city'],
                    'uf' => $customer_state_initials,
                    'bairro' => $this->order['customer_district'],
                    'telefone' => $this->order['customer_phone'],
                    'celular' => $this->order['customer_phone'],
                    'email' => $this->order['customer_email'],

                    // 'tipo_contribuinte' => NULL,
                    // 'consumidor_final' => NULL,
                    // 'indicador_presenca' => NULL,

                    'observacao' => $this->order['customer_complement'],
                ]
            );
        }

        if ($this->order->dp == 1) {
            $track = new OrderTrack;
            $track->title = __('Completed');
            $track->text = __('Your order has completed successfully.');
            $track->order_id = $this->order->id;
            $track->save();
        } else {
            $track = new OrderTrack;
            $track->title = __('Pending');
            $track->text = __('You have successfully placed your order.');
            $track->order_id = $this->order->id;
            $track->save();
        }

        $notification = new Notification;
        $notification->order_id = $this->order->id;
        $notification->save();

        if ($request->coupon_id != "") {
            $coupon = Coupon::findOrFail($request->coupon_id);
            $coupon->used++;

            if ($coupon->times != null) {
                $i = (int) $coupon->times;
                $i--;
                $coupon->times = (string) $i;
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
                if ($product->stock <= 5) {
                    $notification = new Notification;
                    $notification->product_id = $product->id;
                    $notification->save();
                }
            } elseif (!empty($y)) {
                $y = (int) $y;
                $y = $y - $prod['qty'];
                $temp = $product->color_qty;
                $temp[$prod['color_key']] = $y;
                $temp1 = implode(',', $temp);
                $product->color_qty =  $temp1;
                $product->stock -= $prod['qty'];
                $product->update();
                if ($product->stock <= 5) {
                    $notification = new Notification;
                    $notification->product_id = $product->id;
                    $notification->save();
                }
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

                    if ($product->stock <= 5) {
                        $notification = new Notification;
                        $notification->product_id = $product->id;
                        $notification->save();
                    }
                }
            }
        }

        $notf = null;

        foreach ($cart->items as $prod) {
            if ($prod['item']['user_id'] != 0) {
                $vorder =  new VendorOrder;
                $vorder->order_id = $this->order->id;
                $vorder->user_id = $prod['item']['user_id'];
                $notf[] = $prod['item']['user_id'];
                $vorder->qty = $prod['qty'];
                $vorder->price = $prod['price'];
                $vorder->order_number = $this->order->order_number;
                $vorder->save();
            }
        }

        if (!empty($notf)) {
            $users = array_unique($notf);

            foreach ($users as $user) {
                $notification = new UserNotification;
                $notification->user_id = $user;
                $notification->order_number = $this->order->order_number;
                $notification->save();
            }
        }

        // add city, state and country IDs to session_order
        $this->order['customer_city_id'] = $customer_city_id;
        $this->order['customer_state_id'] = $customer_state_id;
        $this->order['customer_state_initials'] = $customer_state_initials;
        $this->order['customer_country_id'] = $customer_country_id;
        $this->order['shipping_city_id'] = $shipping_city_id;
        $this->order['shipping_state_id'] = $shipping_state_id;
        $this->order['shipping_state_initials'] = $shipping_state_initials;
        $this->order['shipping_country_id'] = $shipping_country_id;

        if (Session::has('temporder')) {
            Session::forget('temporder');
        }
        Session::put('temporder', $this->order);
        Session::put('session_order', $this->order->toArray());
        Session::put('tempcart', $cart);
        Session::forget('already');
        Session::forget('coupon');
        Session::forget('coupon_code');
        Session::forget('coupon_total');
        Session::forget('coupon_total1');
        Session::forget('coupon_percentage');

        Session::forget('gateway_message');
        Session::forget('gateway_url');
        Session::forget('gateway_url_title');

        // remove city, state and country IDs from order. It only needs to be
        // in the session and not in the gateway processing
        unset($this->order['customer_city_id']);
        unset($this->order['customer_state_id']);
        unset($this->order['customer_state_initials']);
        unset($this->order['customer_country_id']);
        unset($this->order['shipping_city_id']);
        unset($this->order['shipping_state_id']);
        unset($this->order['shipping_state_initials']);
        unset($this->order['shipping_country_id']);

        //Sending Email To Admin
        if ($this->storeSettings->is_smtp == 1) {
            $data = [
                'to' => Pagesetting::find(1)->contact_email,
                'subject' => __("New Order Received!!"),
                'body' => $this->storeSettings->title . "<br>" .__("Hello Admin!") . "<br>" . __("Your store has received a new order.") . "<br>" .
                    __("Order Number is") . " " . $this->order->order_number . "." . __("Please login to your panel to check.") . "<br>" .
                    __("Thank you"),
            ];

            $mailer = new GeniusMailer();
            $mailer->sendAdminMail($data, $this->order->id);
        } else {
            $to = Pagesetting::find(1)->contact_email;
            $subject = __("New Order Received!!");
            $msg = $this->storeSettings->title . "<br>" .__("Hello Admin!") . "<br>" . __("Your store has received a new order.") . "<br>" .
                __("Order Number is") . " " . $this->order->order_number . "." . __("Please login to your panel to check.") . "<br>" .
                __("Thank you");
            $headers = "From: " . $this->storeSettings->from_name . "<" . $this->storeSettings->from_email . ">";
            mail($to, $subject, $msg, $headers);
        }

        //saves the original request
        $this->request = $request;

        $this->payment();

        if (!empty($this->paymentJson)) {
            //Redirect if there are errors present in the result
            if (isset($this->paymentJson['errors']) && count($this->paymentJson['errors']) > 0) {
                $list = "<ul>";
                foreach ($this->paymentJson['errors'] as $err => $msg) {
                    $list .= "<li>{$msg}</li>";
                }
                $list .= "</ul>";
                return redirect()->route('front.cart')->with('unsuccess', __("Error trying to get payment from") . " " . $this->name . $list);
            }

            $json = [
                "gateway" => strtolower($this->name),
            ];
            return response()->json(array_merge($json, $this->paymentJson));
        }

        if (!empty($this->paymentUrl)) {
            return redirect()->away($this->paymentUrl);
        }

        if ($request->ajax()) {
            return response()->json([
                'unsuccess' => __("Error trying to get payment from") . " " . $this->name
            ], 404);
        }

        // Redirect with error messages, if available
        if (!empty($this->paymentErrors)) {
            $list = "<ul>";
            foreach ($this->paymentErrors as $err => $msg) {
                $list .= "<li>{$msg}</li>";
            }
            $list .= "</ul>";
            return redirect()->route('front.cart')->with('unsuccess', __("Error trying to get payment from") . " " . $this->name . $list);
        }

        return redirect()->route('front.cart')->with('unsuccess', __("Error trying to get payment from") . " " . $this->name);
    }

    /**
     * Custom Gateway Logic.
     * Must set $this->paymentUrl with the gateway payment url to redirect or
     * set $this->paymentJson to process in frontend without a redirect.
     *
     * @return void
     */
    protected function payment()
    {
        $this->paymentUrl = "";
        return;
    }
}
