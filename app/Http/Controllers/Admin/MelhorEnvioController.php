<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Classes\MelhorEnvio;
use App\Models\MelhorenvioCompany;
use App\Models\MelhorenvioService;
use App\Models\Generalsetting;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Currency;
use App\Models\OrderTrack;
use App\Models\MelhorenvioRequest;
use App\Models\State;

class MelhorEnvioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }

    public function updateCompanies()
    {
        $updated_companies = MelhorEnvio::GetCompanies();

        if (isset($updated_companies)) {
            $companies_codes = [];
            $services_codes = [];
            foreach ($updated_companies as $updated_company) {
                MelhorenvioCompany::updateOrCreate([
                    'id'=>$updated_company['id']
                ], [
                    'name'=>$updated_company['name'],
                    'picture'=>$updated_company['picture']
                ]);

                foreach ($updated_company['services'] as $updated_service) {
                    MelhorenvioService::updateOrCreate([
                        'id'=>$updated_service['id']
                    ], [
                        'company_id'=>$updated_company['id'],
                        'name'=>$updated_service['name'],
                        'type'=>$updated_service['type'],
                        'insurance_min'=>$updated_service['restrictions']['insurance_value']['min'],
                        'insurance_max'=>$updated_service['restrictions']['insurance_value']['max']
                    ]);
                    $services_codes[] = $updated_service['id'];
                }
                $companies_codes[] = $updated_company['id'];
            }
            MelhorenvioCompany::whereNotIn('id', $companies_codes)->delete();
            MelhorenvioService::whereNotIn('id', $services_codes)->delete();
            $msg = __('Melhor Envio companies and services updated.');
            return response()->json($msg);
        } else {
            $error = __('Melhor Envio companies and services not updated.');
            return response()->json(array('errors' => [0 => $error]));
        }
    }

    public function confirmPackageOptions($order_id)
    {
        Session::forget('melhorenvio_request');
        $order = Order::Find($order_id);
        if (!isset($order)) {
            return redirect()->route('admin.dashboard')->with('unsuccess', __('Sorry the page does not exist.'));
        }
        $oldCart = $order->cart;
        $cart = new Cart($oldCart);

        $curr_brl  = Currency::where('name', '=', "BRL")->first();
        if (empty($curr_brl->value)) {
            return redirect()->route('admin-order-show', $order_id)->with('unsuccess', 'BRL '.__('Unavaiable'));
        }

        $total = $order->pay_amount - $order->shipping_cost;

        $order_store_id = explode(';', trim(strstr(strstr($order->internal_note, '#:['), ']', true), '#:['))[0];
        $orderStoreSettings = Generalsetting::find($order_store_id);
        if (!isset($orderStoreSettings)) {
            $orderStoreSettings = $this->storeSettings;
        }
        $insurance_value = $orderStoreSettings->melhorenvio->insurance ? $total * $curr_brl->value : 0;

        $package = (object)[
            "height" => $cart->getHeight(),
            "width"  => $cart->getWidth(),
            "length"  => $cart->getLenght(),
            "weight"  => $cart->getWeight()
        ];
        $options = (object)[
            "insurance_value" => $insurance_value,
            "receipt"  => $orderStoreSettings->melhorenvio->receipt,
            "own_hand"  => $orderStoreSettings->melhorenvio->ownhand
            //Collect not working on Melhor Envio yet
            //"collect"  => $orderStoreSettings->melhorenvio->collect
        ];

        $dest_zipcode = $order->shipping_zip == null ? $order->customer_zip : $order->shipping_zip;
        $origin_zipcode = $orderStoreSettings->melhorenvio->from_postal_code;

        return view('admin.order.melhorenvio-confirm-package', compact('order', 'package', 'options', 'origin_zipcode', 'dest_zipcode'));
    }

    public function selectService(Request $request)
    {
        $order = Order::Find($request->order_id);
        if (!isset($order)) {
            return redirect()->route('admin.dashboard')->with('unsuccess', __('Sorry the page does not exist.'));
        }
        if (isset(explode(';', trim(strstr(strstr($order->internal_note, 'MELHORENVIO:['), ']', true), 'MELHORENVIO:['))[1])) {
            $melhorenvio_order_service = explode(';', trim(strstr(strstr($order->internal_note, 'MELHORENVIO:['), ']', true), 'MELHORENVIO:['))[1];
        } else {
            $melhorenvio_order_service = null;
        }
        $order_store_id = explode(';', trim(strstr(strstr($order->internal_note, '#:['), ']', true), '#:['))[0];

        $orderStoreSettings = Generalsetting::find($order_store_id);
        if (!isset($orderStoreSettings)) {
            $orderStoreSettings = $this->storeSettings;
        }

        $curr_brl  = Currency::where('name', '=', "BRL")->first();
        if (empty($curr_brl->value)) {
            return redirect()->route('admin-order-show', $order->id)->with('unsuccess', 'BRL '.__('Unavaiable'));
        }

        $melhorenvio_request = (object)[];
        $melhorenvio_request->order_id = $order->id;
        $melhorenvio_request->package = (object)$request->package;
        $melhorenvio_request->options = (object)$request->options;
        $melhorenvio_request->dest_zipcode = $request->dest_zipcode;
        $melhorenvio_request->origin_zipcode = $request->origin_zipcode;

        $services = implode(",", $orderStoreSettings->melhorenvio->selected_services);

        Session::put('melhorenvio_request', $melhorenvio_request);

        $melhorenvio = new MelhorEnvio($orderStoreSettings->melhorenvio->token, $orderStoreSettings->melhorenvio->production);
        $melhorenvio_rates = $melhorenvio->getRates(
            $melhorenvio_request->origin_zipcode,
            $melhorenvio_request->dest_zipcode,
            $melhorenvio_request->package,
            null,
            $melhorenvio_request->options,
            $services
        );

        if (isset($melhorenvio_rates->message)) {
            return redirect()->route('admin-order-confirm-melhorenvio-package', $order->id)->with('unsuccess', $melhorenvio_rates->message);
        }

        return view(
            'admin.order.melhorenvio-select-service',
            compact(
                'order',
                'orderStoreSettings',
                'melhorenvio_rates',
                'melhorenvio_order_service',
                'curr_brl'
            )
        );
    }

    public function requestMelhorenvio(Request $request)
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
        $melhorenvio_settings = $orderStoreSettings->melhorenvio;

        if (Session::get('melhorenvio_request') != null) {
            $melhorenvio_request = Session::get('melhorenvio_request');
        } else {
            return redirect()->route('admin-order-confirm-melhorenvio-package', $order->id)->with('unsuccess', __('Error'));
        }
        $melhorenvio_request->service = $request->selected_service;
        Session::put('melhorenvio_request', $melhorenvio_request);

        $selected_service = MelhorenvioService::where('id', $melhorenvio_request->service)->with('company')->first();
        if (empty($selected_service->company->id)) {
            return redirect()->route('admin-order-confirm-melhorenvio-package', $order->id)->with('unsuccess', __('Error'));
        }
        $selected_company = $selected_service->company->id;

        $oldCart = $order->cart;
        $cart = new Cart($oldCart);

        $states = State::whereHas('country', function ($query) {
            $query->where('country_code', 'BR');
        })->get();

        $curr_brl  = Currency::where('name', '=', "BRL")->first();
        if (empty($curr_brl->value)) {
            return redirect()->route('admin-order-show', $order->id)->with('unsuccess', 'BRL '.__('Unavaiable'));
        }

        //JadLog require select an agency
        //Todo: Refactor to save previosly on database and push by JS requests
        $jadlog_grouped = [];

        if ($selected_company == 2) {
            $jadlog_agencies = collect(MelhorEnvio::getAgencies(2));
            foreach ($jadlog_agencies as $agency) {
                $state_abbr = $agency['address']['city']['state']['state_abbr'];
                $city_name = $agency['address']['city']['city'];
                $jadlog_grouped[$state_abbr][$city_name][] = $agency;
            }
            foreach ($jadlog_grouped as &$state) {
                $state = collect($state)->sortBy(function ($city, $key) {
                    return $key;
                });
            }
            $jadlog_grouped = collect($jadlog_grouped)->sortBy(function ($state, $key) {
                return $key;
            });
        }

        return view('admin.order.melhorenvio-request', compact('melhorenvio_settings', 'selected_company', 'order', 'cart', 'states', 'jadlog_grouped', 'curr_brl'));
    }

    public function cartMelhorenvio(Request $request)
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

        if (Session::get('melhorenvio_request') != null) {
            $melhorenvio_request = Session::get('melhorenvio_request');
        } else {
            return redirect()->route('admin-order-confirm-melhorenvio-package', $order->id)->with('unsuccess', __('Error'));
        }

        $curr_brl  = Currency::where('name', '=', "BRL")->first();
        if (empty($curr_brl->value)) {
            return redirect()->route('admin-order-show', $order->id)->with('unsuccess', 'BRL '.__('Unavaiable'));
        }

        $melhorenvio = new MelhorEnvio($orderStoreSettings->melhorenvio->token, $orderStoreSettings->melhorenvio->production);

        $from = (object)$request->from;
        $from->postal_code = $melhorenvio_request->origin_zipcode;
        $to = (object)$request->to;
        $to->postal_code = $melhorenvio_request->dest_zipcode;
        $products = [];
        foreach ($request->products as $product) {
            $products[] = (object)$product;
        }
        $options = (object) array_merge((array)$melhorenvio_request->options, $request->options);

        $options->platform = $orderStoreSettings->title;
        $tags = (object)[];
        $tags->tag = $order->id;
        $tags->url = route('admin-order-show', $order->id);
        $options->tags = [$tags];

        $melhorenvio_cart = $melhorenvio->addToCart(
            $melhorenvio_request->service,
            $request->agency,
            $from,
            $to,
            $products,
            $melhorenvio_request->package,
            $options,
            $request->coupon
        );

        if (!empty($melhorenvio_cart->error)) {
            $error = $melhorenvio_cart->error;
            return redirect()->route('admin-order-confirm-melhorenvio-package', $order->id)->with('unsuccess', $error);
        }
        if (!empty($melhorenvio_cart->errors)) {
            $error = __('Invalid Request');
            foreach ($melhorenvio_cart->errors as $key => $value) {
                switch ($key) {
                    case "from.document":
                        $error .= "<br>".__("From CPF");
                        break;
                    case "to.document":
                        $error .= "<br>".__("Recipient CPF");
                        break;
                    case "from.state_register":
                        $error .= "<br>".__("From State Register");
                        break;
                    case "options.invoice.key":
                        $error .= "<br>".__("Invoice Key");
                        break;
                    case "coupon":
                        $error .= "<br>".__("Coupon");
                        break;
                    default:
                        $error .= "<br>".$value[0];
                        break;
                }
            }
            return redirect()->route('admin-order-confirm-melhorenvio-package', $order->id)->with('unsuccess', $error);
        }

        if (isset($melhorenvio_cart->message) && $melhorenvio_cart->message == "Unauthenticated.") {
            $error = __("Unauthenticated.");
            return redirect()->route('admin-order-confirm-melhorenvio-package', $order->id)->with('unsuccess', $error);
        }

        $melhorenvio_balance = $melhorenvio->getBalance();

        return view('admin.order.melhorenvio-checkout', compact('melhorenvio_cart', 'melhorenvio_balance', 'order', 'curr_brl'));
    }

    public function checkoutMelhorenvio(Request $request)
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

        $melhorenvio = new MelhorEnvio($orderStoreSettings->melhorenvio->token, $orderStoreSettings->melhorenvio->production);

        $melhorenvio_orders = [$request->cart_uuid];

        $melhorenvio_checkout = $melhorenvio->checkoutCart($melhorenvio_orders);

        if (!empty($melhorenvio_checkout->error)) {
            $error = $melhorenvio_checkout->error;
            return redirect()->route('admin-order-confirm-melhorenvio-package', $order->id)->with('unsuccess', $error);
        }
        if (!empty($melhorenvio_checkout->errors)) {
            $error = __('Invalid Request');
            if (!empty($melhorenvio_checkout->errors->orders)) {
                foreach ($melhorenvio_checkout->errors->orders as $key => $value) {
                    $error .= "<br>".$value;
                }
            }
            return redirect()->route('admin-order-confirm-melhorenvio-package', $order->id)->with('unsuccess', $error);
        }

        if (!empty($melhorenvio_checkout->purchase->orders)) {
            foreach ($melhorenvio_checkout->purchase->orders as $melhorenvio_order) {
                if ($melhorenvio_order->id == $request->cart_uuid) {
                    $melhorenvio_request = new MelhorenvioRequest;
                    $melhorenvio_request->uuid = $melhorenvio_order->id;
                    $melhorenvio_request->protocol = $melhorenvio_order->protocol;
                    $melhorenvio_request->service_id = $melhorenvio_order->service_id;
                    $melhorenvio_request->agency_id = $melhorenvio_order->agency_id;
                    $melhorenvio_request->price = $melhorenvio_order->price;
                    $melhorenvio_request->status = $melhorenvio_order->status;
                    $melhorenvio_request->authorization_code = $melhorenvio_order->authorization_code;
                    $melhorenvio_request->tracking = $melhorenvio_order->tracking;
                    $melhorenvio_request->order_id = $order->id;

                    $melhorenvio_request->created_at = $melhorenvio_order->created_at;
                    $melhorenvio_request->paid_at = $melhorenvio_order->paid_at;
                    $melhorenvio_request->generated_at = $melhorenvio_order->generated_at;
                    $melhorenvio_request->posted_at = $melhorenvio_order->posted_at;
                    $melhorenvio_request->delivered_at = $melhorenvio_order->delivered_at;
                    $melhorenvio_request->canceled_at = $melhorenvio_order->canceled_at;
                    $melhorenvio_request->expired_at = $melhorenvio_order->expired_at;

                    $melhorenvio_request->save();

                    return redirect()->route('admin-order-show', $order->id)->with('success', __('Successfuly requested Melhor Envio'));
                }
            }
        }

        //In case of an unpredictable error
        return redirect()->route('admin-order-show', $order->id)
            ->with(
                'unsuccess',
                __('Melhor Envio Request Error, please confirm this request:')
                    .' <a href="'.$melhorenvio->url.'/painel"'
                    .' target="_blank" style="background-color:#D03633; color:#fff;">'
                    .$melhorenvio->url.'/painel</a>'
            );
    }

    public function generateMelhorenvio($request_id)
    {
        $melhorenvio_request = MelhorenvioRequest::where('id', $request_id)->first();

        if (!isset($melhorenvio_request)) {
            return redirect()->route('admin.dashboard')->with('unsuccess', __('Sorry the page does not exist.'));
        }
        return view('admin.order.melhorenvio-generate', compact('melhorenvio_request'));
    }

    public function generateConfirmMelhorenvio(Request $request)
    {
        $melhorenvio_request = MelhorenvioRequest::where('id', $request->request_id)->first();

        if (!isset($melhorenvio_request)) {
            return redirect()->route('admin.dashboard')->with('unsuccess', __('Sorry the page does not exist.'));
        }

        $order = Order::Find($melhorenvio_request->order_id);
        if (!isset($order)) {
            return redirect()->route('admin.dashboard')->with('unsuccess', __('Sorry the page does not exist.'));
        }
        $order_store_id = explode(';', trim(strstr(strstr($order->internal_note, '#:['), ']', true), '#:['))[0];

        $orderStoreSettings = Generalsetting::find($order_store_id);
        if (!isset($orderStoreSettings)) {
            $orderStoreSettings = $this->storeSettings;
        }

        $melhorenvio = new MelhorEnvio($orderStoreSettings->melhorenvio->token, $orderStoreSettings->melhorenvio->production);

        $melhorenvio_generate = $melhorenvio->generate([$melhorenvio_request->uuid]);

        if (!empty($melhorenvio_generate->{$melhorenvio_request->uuid}->status)) {
            if ($melhorenvio_generate->{$melhorenvio_request->uuid}->status) {
                return redirect()->route('admin-order-show', $melhorenvio_request->order_id)
                    ->with('success', $melhorenvio_generate->{$melhorenvio_request->uuid}->message);
            } else {
                return redirect()->route('admin-order-show', $melhorenvio_request->order_id)
                    ->with('unsuccess', $melhorenvio_generate->{$melhorenvio_request->uuid}->message);
            }
        } else {
            return redirect()->route('admin-order-show', $melhorenvio_request->order_id)
                ->with(
                    'unsuccess',
                    __('Melhor Envio Request Error, please confirm this request:')
                    .' <a href="'.$melhorenvio->url.'/painel"'
                    .' target="_blank" style="background-color:#D03633; color:#fff;">'
                    .$melhorenvio->url.'/painel</a>'
                );
        }
    }

    public function cancelMelhorenvio($request_id)
    {
        $melhorenvio_request = MelhorenvioRequest::where('id', $request_id)->first();

        if (!isset($melhorenvio_request)) {
            return redirect()->route('admin.dashboard')->with('unsuccess', __('Sorry the page does not exist.'));
        }
        return view('admin.order.melhorenvio-cancel', compact('melhorenvio_request'));
    }

    public function cancelConfirmMelhorenvio(Request $request)
    {
        $melhorenvio_request = MelhorenvioRequest::where('id', $request->request_id)->first();

        if (!isset($melhorenvio_request)) {
            return redirect()->route('admin.dashboard')->with('unsuccess', __('Sorry the page does not exist.'));
        }

        $order = Order::Find($melhorenvio_request->order_id);
        if (!isset($order)) {
            return redirect()->route('admin.dashboard')->with('unsuccess', __('Sorry the page does not exist.'));
        }
        $order_store_id = explode(';', trim(strstr(strstr($order->internal_note, '#:['), ']', true), '#:['))[0];

        $orderStoreSettings = Generalsetting::find($order_store_id);
        if (!isset($orderStoreSettings)) {
            $orderStoreSettings = $this->storeSettings;
        }

        $melhorenvio = new MelhorEnvio($orderStoreSettings->melhorenvio->token, $orderStoreSettings->melhorenvio->production);

        $melhorenvio_cancel = $melhorenvio->cancel($melhorenvio_request->uuid, $request->description);

        if (!empty($melhorenvio_cancel->{$melhorenvio_request->uuid}->canceled)) {
            if ($melhorenvio_cancel->{$melhorenvio_request->uuid}->canceled) {
                return redirect()->route('admin-order-show', $melhorenvio_request->order_id)
                    ->with('success', __("Request canceled"));
            } else {
                return redirect()->route('admin-order-show', $melhorenvio_request->order_id)
                    ->with('unsuccess', __("Request cannot be canceled"));
            }
        } else {
            return redirect()->route('admin-order-show', $melhorenvio_request->order_id)
                ->with(
                    'unsuccess',
                    __('Melhor Envio Request Error, please confirm this request:')
                    .' <a href="'.$melhorenvio->url.'/painel"'
                    .' target="_blank" style="background-color:#D03633; color:#fff;">'
                    .$melhorenvio->url.'/painel</a>'
                );
        }
    }
}
