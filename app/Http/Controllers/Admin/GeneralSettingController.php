<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Artisan;
use Image;
use Carbon\Carbon;
use App\Models\State;
use App\Models\Country;
use App\Models\Currency;
use App\Models\MelhorenvioConf;
use App\Models\MelhorenvioCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Generalsetting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\AexCity;
use App\Models\FedexConf;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class GeneralSettingController extends Controller
{
    protected $rules =
    [
        'logo'              => 'mimes:jpeg,jpg,png,svg,webp',
        'favicon'           => 'mimes:jpeg,jpg,png,svg,webp',
        'loader'            => 'mimes:gif,svg,webp',
        'admin_loader'      => 'mimes:gif,svg,webp',
        'affilate_banner'   => 'mimes:jpeg,jpg,png,svg,webp',
        'error_banner'      => 'mimes:jpeg,jpg,png,svg,webp',
        'invoice_logo'      => 'mimes:jpeg,jpg,png,svg,webp',
        'user_image'        => 'mimes:jpeg,jpg,png,svg,webp',
        'footer_logo'       => 'mimes:jpeg,jpg,png,svg,webp',
        'maintenance_banner'       => 'mimes:jpeg,jpg,png,svg,webp',
    ];

    public function __construct()
    {
        $this->middleware('auth:admin');

        parent::__construct();
    }


    private function setEnv($key, $value, $prev)
    {
        file_put_contents(app()->environmentFilePath(), str_replace(
            $key . '=' . $prev,
            $key . '=' . $value,
            file_get_contents(app()->environmentFilePath())
        ));
    }

    // Genereal Settings All post requests will be done in this method
    public function generalupdate(Request $request)
    {
        //--- Validation Section
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        else {
            $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
            $input = $this->withRequiredFields($request->all(), ['title'], $data);

            if ($file = $request->file('logo')) {
                $name = time() . $file->getClientOriginalName();
                $oldName = (GeneralSetting::where('logo', $data->logo)->count() > 1 ? null : $data->logo);
                $data->upload($name, $file, $oldName);
                $input['logo'] = $name;
            }
            if ($file = $request->file('favicon')) {
                $name = time() . $file->getClientOriginalName();
                $oldName = (GeneralSetting::where('favicon', $data->favicon)->count() > 1 ? null : $data->favicon);
                $data->upload($name, $file, $oldName);
                $input['favicon'] = $name;
            }
            if ($file = $request->file('loader')) {
                $name = time() . $file->getClientOriginalName();
                $oldName = (GeneralSetting::where('loader', $data->loader)->count() > 1 ? null : $data->loader);
                $data->upload($name, $file, $oldName);
                $input['loader'] = $name;
            }
            if ($file = $request->file('admin_loader')) {
                $name = time() . $file->getClientOriginalName();
                $oldName = (GeneralSetting::where('admin_loader', $data->admin_loader)->count() > 1 ? null : $data->admin_loader);
                $data->upload($name, $file, $oldName);
                $input['admin_loader'] = $name;
            }
            if ($file = $request->file('affilate_banner')) {
                $name = time() . $file->getClientOriginalName();
                $oldName = (GeneralSetting::where('affilate_banner', $data->affilate_banner)->count() > 1 ? null : $data->affilate_banner);
                $data->upload($name, $file, $oldName);
                $input['affilate_banner'] = $name;
            }
            if ($file = $request->file('error_banner')) {
                $field = $request->all();
                $name = time() . $file->getClientOriginalName();
                $oldName = (GeneralSetting::where('error_banner', $data->error_banner)->count() > 1 ? null : $data->error_banner);
                $data->upload($name, $file, $oldName);
                $input['error_banner'] = $name;
                $data->update($field);
            }

            if ($file = $request->file('invoice_logo')) {
                $name = time() . $file->getClientOriginalName();
                $oldName = (GeneralSetting::where('invoice_logo', $data->invoice_logo)->count() > 1 ? null : $data->invoice_logo);
                $data->upload($name, $file, $oldName);
                $input['invoice_logo'] = $name;
            }
            if ($file = $request->file('user_image')) {
                $name = time() . $file->getClientOriginalName();
                $oldName = (GeneralSetting::where('user_image', $data->user_image)->count() > 1 ? null : $data->user_image);
                $data->upload($name, $file, $oldName);
                $input['user_image'] = $name;
            }

            if ($file = $request->file('footer_logo')) {
                $name = time() . $file->getClientOriginalName();
                $oldName = (GeneralSetting::where('footer_logo', $data->footer_logo)->count() > 1 ? null : $data->footer_logo);
                $data->upload($name, $file, $oldName);
                $input['footer_logo'] = $name;
            }

            if ($file = $request->file('maintenance_banner')) {
                $name = time() . $file->getClientOriginalName();
                $oldName = (GeneralSetting::where('maintenance_banner', $data->maintenance_banner)->count() > 1 ? null : $data->maintenance_banner);
                $data->upload($name, $file, $oldName);
                $input['maintenance_banner'] = $name;
            }

            //default product percent is 0 if not sent
            if ($request->has('product_percent')) {
                $input['product_percent'] = $input['product_percent'] ?: 0;
            }

            if ($request->has('ref_color')) {
                $input['ref_color'] = $request->ref_color ?: null;
            }

            $data->update($input);
            //--- Logic Section Ends

            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');

            //--- Redirect Section
            $msg = __('Data Updated Successfully.');
            return response()->json($msg);
            //--- Redirect Section Ends
        }
    }

    public function generalupdatepayment(Request $request)
    {
        //--- Validation Section
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        else {
            $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
            $input = $this->withRequiredFields($request->all(), ['title'], $data);
            $prev = $data->molly_key;

            if ($request->vendor_ship_info == "") {
                $input['vendor_ship_info'] = 0;
            }

            if ($request->instamojo_sandbox == "") {
                $input['instamojo_sandbox'] = 0;
            }

            if ($request->paypal_mode == "") {
                $input['paypal_mode'] = 'live';
            } else {
                $input['paypal_mode'] = 'sandbox';
            }

            if ($request->paytm_mode == "") {
                $input['paytm_mode'] = 'live';
            } else {
                $input['paytm_mode'] = 'sandbox';
            }

            if ($request->bancard_mode == "") {
                $input['bancard_mode'] = 'live';
            } else {
                $input['bancard_mode'] = 'sandbox';
            }
            $data->update($input);


            $this->setEnv('MOLLIE_KEY', $data->molly_key, $prev);
            // Set Molly ENV

            //--- Logic Section Ends

            //--- Redirect Section
            $msg = __('Data Updated Successfully.');
            return response()->json($msg);
            //--- Redirect Section Ends
        }
    }

    public function generalupdateMelhorenvio(Request $request)
    {
        //--- Validation Section

        //--- Validation Section Ends

        //--- Logic Section

        $generalSettings = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $input = $request->all();

        if (!MelhorenvioConf::find($generalSettings->melhorenvio_id)) {
            $melhorenvio = MelhorenvioConf::create(['selected_services' => []]);
            $generalSettings->melhorenvio_id = $melhorenvio->id;
            $generalSettings->update();
        }
        $generalSettings->melhorenvio->update($input);
        //--- Logic Section Ends
        //--- Redirect Section
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    public function generalUpdateFedex(Request $request)
    {
        $generalSettings = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;

        $input = $request->all();

        $msg = __('Data Updated Successfully.');

        if (!FedexConf::find($generalSettings->fedex_id)) {
            $fedex = FedexConf::create($input);
            $generalSettings->fedex_id = $fedex->id;
            $generalSettings->update();
            return response()->json($msg);
        }

        $generalSettings->fedex->update($input);
        return response()->json($msg);
    }

    public function updatePopUp(Request $request)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $input = $this->withRequiredFields($request->all(), ['title'], $data);

        if (!empty($request['popup_background']) && $request['popup_background'] != $data->popup_background) {
            $image = $request->popup_background;
            list($type, $image) = explode(';', $image);
            list(, $image)      = explode(',', $image);
            $image = base64_decode($image);
            $image_name = time().Str::random(8).'.jpg';
            $path = 'storage/images/' . $image_name;
            file_put_contents($path, $image);
            if ($data->popup_background != null) {
                if (file_exists(public_path() . '/storage/images/' . $data->popup_background)) {
                    unlink(public_path() . '/storage/images/' . $data->popup_background);
                }
            }
            $input['popup_background'] = $image_name;
        }
        $data->update($input);

        $msg = __('Data Updated Successfully.');
        return response()->json($msg);
    }

    public function logo()
    {
        return view('admin.generalsetting.logo');
    }

    public function userimage()
    {
        return view('admin.generalsetting.user_image');
    }

    public function fav()
    {
        return view('admin.generalsetting.favicon');
    }

    public function load()
    {
        return view('admin.generalsetting.loader');
    }

    public function storeconf()
    {
        return view('admin.generalsetting.storeconf');
    }

    public function marketplaceconf()
    {
        return view('admin.generalsetting.marketplaceconf');
    }

    public function productconf()
    {
        return view('admin.generalsetting.productconf');
    }

    public function cartconf()
    {
        return view('admin.generalsetting.cartconf');
    }

    public function paymentconf()
    {
        return view('admin.generalsetting.paymentconf');
    }

    public function shippingconf()
    {
        $countries = Country::all();
        return view('admin.generalsetting.shippingconf', compact('countries'));
    }

    public function correiosconf()
    {
        return view('admin.generalsetting.correiosconf');
    }

    public function aexconf()
    {
        $aex_cities = AexCity::orderBy('denominacion')->get();
        return view('admin.generalsetting.aexconf', compact('aex_cities'));
    }

    public function melhorenvioconf()
    {
        $generalSettings = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;

        if (!MelhorenvioConf::find($generalSettings->melhorenvio_id)) {
            $melhorenvio = MelhorenvioConf::create(['selected_services' => []]);
            $generalSettings->melhorenvio_id = $melhorenvio->id;
            $generalSettings->update();
        }

        $states = State::whereHas('country', function ($query) {
            $query->where('country_code', 'BR');
        })->get();

        $melhorenvio_companies = MelhorenvioCompany::with('services')->orderBy('id')->get();
        return view('admin.generalsetting.melhorenvioconf', compact('states', 'melhorenvio_companies'));
    }

    public function loadMelhorenvioCompanies()
    {
        $melhorenvio_companies = MelhorenvioCompany::with('services')->orderBy('id')->get();
        return view('load.melhorenvio-companies', compact('melhorenvio_companies'));
    }

    public function fedexconf()
    {
        $generalSettings = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;

        if (!FedexConf::find($generalSettings->fedex_id)) {
            $fedex = FedexConf::create();
            $generalSettings->fedex_id = $fedex->id;
            $generalSettings->update();
        }

        $fedex = FedexConf::first();
        return view('admin.generalsetting.fedexconf', compact('fedex'));
    }

    public function contents()
    {
        return view('admin.generalsetting.websitecontent');
    }

    public function header()
    {
        return view('admin.generalsetting.header');
    }

    public function footer()
    {
        return view('admin.generalsetting.footer');
    }

    public function paymentsinfo()
    {
        return view('admin.generalsetting.paymentsinfo');
    }

    public function paymentsinfoBancard()
    {
        return view('admin.generalsetting.paymentsinfo-bancard');
    }

    public function paymentsinfoMercadopago()
    {
        return view('admin.generalsetting.paymentsinfo-mercadopago');
    }

    public function paymentsinfoPagarme()
    {
        return view('admin.generalsetting.paymentsinfo-pagarme');
    }

    public function paymentsinfoPagseguro()
    {
        return view('admin.generalsetting.paymentsinfo-pagseguro');
    }

    public function paymentsinfoCielo()
    {
        return view('admin.generalsetting.paymentsinfo-cielo');
    }

    public function paymentsinfoPagopar()
    {
        return view('admin.generalsetting.paymentsinfo-pagopar');
    }

    public function paymentsinfoRede()
    {
        return view('admin.generalsetting.paymentsinfo-rede');
    }

    public function paymentsinfoPaghiper()
    {
        return view('admin.generalsetting.paymentsinfo-paghiper');
    }
    public function paymentsinfoPayPal()
    {
        return view('admin.generalsetting.paymentsinfo-paypal');
    }

    public function paymentsinfoPay42()
    {
        return view('admin.generalsetting.paymentsinfo-pay42');
    }

    public function paymentsinfoDeposit()
    {
        return view('admin.generalsetting.paymentsinfo-deposit');
    }

    public function paymentsinfoGateway()
    {
        return view('admin.generalsetting.paymentsinfo-gateway');
    }

    public function affilate()
    {
        if (!config("features.marketplace")) {
            return redirect()->route('admin.dashboard')->withErrors("Marketplace is disabled");
        }

        return view('admin.generalsetting.affilate');
    }

    public function errorbanner()
    {
        return view('admin.generalsetting.error_banner');
    }

    public function policy()
    {
        return view('admin.generalsetting.policy');
    }

    public function crowpolicy()
    {
        return view('admin.generalsetting.crowpolicy');
    }

    public function vendorpolicy()
    {
        return view('admin.generalsetting.vendorpolicy');
    }

    public function privacypolicy()
    {
        return view('admin.generalsetting.privacypolicy');
    }

    public function popup()
    {
        return view('admin.generalsetting.popup');
    }

    public function maintain()
    {
        return view('admin.generalsetting.maintain');
    }

    public function integrations()
    {
        return view('admin.generalsetting.integrations');
    }

    public function integrationsJivochat()
    {
        return view('admin.generalsetting.integrations-jivochat');
    }

    public function integrationsDisqus()
    {
        return view('admin.generalsetting.integrations-disqus');
    }

    public function integrationsCronjob()
    {
        if (!config('features.marketplace')) {
            return redirect()->route('admin.dashboard')->with('unsuccess', 'Você não tem acesso a esta página.');
        }
        return view('admin.generalsetting.integrations-cronjob');
    }

    public function integrationsFtp()
    {
        return view('admin.generalsetting.integrations-ftp');
    }

    public function integrationsXml()
    {
        return view('admin.generalsetting.integrations-xml');
    }

    public function ispopup($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_popup = $status;
        $data->update();
    }
    public function isnewsletterpopup($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_newsletter_popup = $status;
        $data->update();
    }

    public function iswhatsapp($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_whatsapp = $status;
        $data->update();
    }

    public function iszipvalidation($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_zip_validation = $status;
        $data->update();
    }

    public function isattrcards($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_attr_cards = $status;
        $data->update();
    }

    public function mship($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->multiple_shipping = $status;
        $data->update();
    }


    public function mpackage($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->multiple_packaging = $status;
        $data->update();
    }

    public function instamojo($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_instamojo = $status;
        $data->update();
    }


    public function paystack($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_paystack = $status;
        $data->update();
    }


    public function paytm($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_paytm = $status;
        $data->update();
    }



    public function molly($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_molly = $status;
        $data->update();
    }

    public function razor($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_razorpay = $status;
        $data->update();
    }

    public function bancard($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_bancard = $status;
        $data->update();
    }

    public function mercadopago($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_mercadopago = $status;
        $data->update();
    }

    public function pagarme($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_pagarme = $status;
        $data->update();
    }

    public function pagseguro($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_pagseguro = $status;
        $data->update();
    }

    public function pagseguro_sandbox($sandbox)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_pagseguro_sandbox = $sandbox;
        $data->update();
    }

    public function cielo($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_cielo = $status;
        $data->update();
    }

    public function pagopar($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_pagopar = $status;
        $data->update();
    }

    public function rede($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_rede = $status;
        $data->update();
    }

    public function rede_sandbox($sandbox)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_rede_sandbox = $sandbox;
        $data->update();
    }

    public function paypal($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_paypal = $status;
        $data->update();
    }

    public function paypal_sandbox($sandbox)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_paypal_sandbox = $sandbox;
        $data->update();
    }

    public function paghiper($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_paghiper = $status;
        $data->update();
    }

    public function paghiperIsDiscount($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->paghiper_is_discount = $status;
        $data->update();
    }

    public function paghiperPix($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_paghiper_pix = $status;
        $data->update();
    }

    public function pay42_pix($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_pay42_pix = $status;
        $data->update();
    }

    public function pay42Currency($currency)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->pay42_currency = $currency;
        $data->update();
    }

    public function pay42_sandbox($sandbox)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_pay42_sandbox = $sandbox;
        $data->update();
    }

    public function pay42_card($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_pay42_card = $status;
        $data->update();
    }

    public function pay42_billet($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_pay42_billet = $status;
        $data->update();
    }

    public function paghiperPixIsDiscount($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->paghiper_pix_is_discount = $status;
        $data->update();
    }

    public function stripe($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->stripe_check = $status;
        $data->update();
    }

    public function guest($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        if ($status == true) {
            $data->is_cart_abandonment = false;
            $data->is_complete_profile = false;
        }
        $data->guest_checkout = $status;
        $data->update();
    }

    public function completeProfile($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        if ($status) {
            $data->guest_checkout = false;
        }
        $data->is_complete_profile = $status;
        $data->update();
    }

    public function isemailverify($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_verification_email = $status;
        $data->update();
    }


    public function cod($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->cod_check = $status;
        $data->update();
    }

    public function bankdeposit($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->bank_check = $status;
        $data->update();
    }

    public function comment($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_comment = $status;
        $data->update();
    }
    public function rating($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_rating = $status;
        $data->update();
    }
    public function isaffilate($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_affilate = $status;
        $data->update();
    }

    public function issmtp($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_smtp = $status;
        $data->update();
    }

    public function talkto($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_talkto = $status;
        $data->update();
    }

    public function jivochat($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_jivochat = $status;
        $data->update();
    }

    public function teamShowWhatsapp($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->team_show_whatsapp = $status;
        $data->update();
    }

    public function teamShowHeader($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->team_show_header = $status;
        $data->update();
    }

    public function teamShowFooter($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->team_show_footer = $status;
        $data->update();
    }

    public function brands($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_brands = $status;
        $data->update();
    }

    public function issubscribe($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_subscribe = $status;
        $data->update();
    }

    public function isloader($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_loader = $status;
        $data->update();
    }

    public function stock($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->show_stock = $status;
        $data->update();
    }

    public function productprices($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->show_product_prices = $status;
        $data->update();
    }

    public function iscartandbuyavailable($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_cart_and_buy_available = $status;
        $data->update();
    }

    public function showproductswithoutstock($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->show_products_without_stock = $status;
        $data->update();
    }

    public function showproductswithoutstockbaw($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->show_products_without_stock_baw = $status;
        $data->update();
    }

    public function iscorreios($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_correios = $status;
        $data->update();
    }

    public function isaex($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_aex = $status;
        $data->update();
    }

    public function aex_production($production)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_aex_production = $production;
        $data->update();
    }

    public function aex_insurance($insurance)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_aex_insurance = $insurance;
        $data->update();
    }

    public function ismelhorenvio($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_melhorenvio = $status;
        $data->update();
    }

    public function isfedex($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_fedex = $status;
        $data->update();
    }

    public function melhorenvio_production($production)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->melhorenvio->production = $production;
        $data->melhorenvio->update();
    }

    public function melhorenvio_insurance($insurance)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->melhorenvio->insurance = $insurance;
        $data->melhorenvio->update();
    }

    public function melhorenvio_receipt($receipt)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->melhorenvio->receipt = $receipt;
        $data->melhorenvio->update();
    }

    public function melhorenvio_ownhand($ownhand)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->melhorenvio->ownhand = $ownhand;
        $data->melhorenvio->update();
    }

    public function melhorenvio_collect($collect)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->melhorenvio->collect = $collect;
        $data->melhorenvio->update();
    }

    public function fedex_production($production)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->fedex->production = $production;
        $data->fedex->update();
    }

    public function referencecode($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->reference_code = $status;
        $data->update();
    }

    public function iscart($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_cart = $status;
        $data->update();
    }

    public function isSimplifiedCheckout($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_simplified_checkout = $status;
        $data->update();
    }

    public function isStandardCheckout($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_standard_checkout = $status;
        $data->update();
    }

    public function ishome($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_home = $status;
        $data->update();
    }

    public function isadminloader($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_admin_loader = $status;
        $data->update();
    }

    public function isdisqus($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_disqus = $status;
        $data->update();
    }

    public function iscomprasparaguai($status)
    {
        $stores = Generalsetting::All();
        foreach ($stores as $data) {
            $data->is_comprasparaguai = $status;
            $data->update();
        }
    }

    public function storecomprasparaguai($id)
    {
        $stores = Generalsetting::All();
        foreach ($stores as $data) {
            $data->store_comprasparaguai = $id;
            $data->update();
        }
    }

    public function islojaupdate($status)
    {
        $stores = Generalsetting::All();
        foreach ($stores as $data) {
            $data->is_lojaupdate = $status;
            $data->update();
        }
    }

    public function storelojaupdate($id)
    {
        $stores = Generalsetting::All();
        foreach ($stores as $data) {
            $data->store_lojaupdate = $id;
            $data->update();
        }
    }

    public function iscontact($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_contact = $status;
        $data->update();
    }
    public function isblog($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_blog = $status;
        $data->update();
    }
    public function isfaq($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_faq = $status;
        $data->update();
    }
    public function language($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_language = $status;
        $data->update();
    }
    public function currency($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_currency = $status;
        $data->update();
    }
    public function currencyvalues($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->show_currency_values = $status;
        $data->update();
    }
    public function switchCurrencyHighlight($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->switch_highlight_currency = $status;
        $data->update();
    }
    public function regvendor($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->reg_vendor = $status;
        $data->update();
    }

    public function iscapcha($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_capcha = $status;
        $data->update();
    }

    public function isreport($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_report = $status;
        $data->update();
    }

    public function attributeclickable($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->attribute_clickable = $status;
        $data->update();
    }

    public function isinvoicephoto($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_invoice_photo = $status;
        $data->update();
    }

    public function issecure($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_secure = $status;
        $data->update();
    }

    public function ismaintain($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_maintain = $status;
        $data->update();
    }

    public function isdarkmode($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_dark_mode = $status;
        $data->update();
    }

    public function iscartabandonment($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        if ($data->guest_checkout == true) {
            $data->is_cart_abandonment = false;
        } else {
            $data->is_cart_abandonment = $status;
        }
        $data->update();
    }

    public function isbackinstock($status)
    {
        $data = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
        $data->is_back_in_stock = $status;
        $data->update();
    }

    public function removePopUpImage()
    {
        $pop_up = Generalsetting::all();

        foreach ($pop_up as $data) {
            if ($data->popup_background != null) {
                if (file_exists(public_path().'/storage/images/'.$data->popup_background)) {
                    unlink(public_path().'/storage/images/'.$data->popup_background);

                    $input['popup_background'] = null;
                    $data->update($input);
                    $msg = __('Image Deleted Successfully');
                    return response()->json([
                    'status'=>true,
                    'message' => $msg
                ]);
                }
            }
        }
    }
    public function removemaintenanceBanner()
    {
        $maintenance_banner = Generalsetting::all();

        foreach ($maintenance_banner as $data) {
            if ($data->maintenance_banner != null) {
                if (file_exists(public_path().'/storage/images/'.$data->maintenance_banner)) {
                    unlink(public_path().'/storage/images/'.$data->maintenance_banner);

                    $input['maintenance_banner'] = null;
                    $data->update($input);
                    $msg = __('Image Deleted Successfully');
                    return response()->json([
                    'status'=>true,
                    'message' => $msg
                ]);
                }
            }
        }
    }

    public function removeErrorBanner()
    {
        $error_banner = Generalsetting::all();

        foreach ($error_banner as $data) {
            if ($data->error_banner != null) {
                if (file_exists(public_path().'/storage/images/'.$data->error_banner)) {
                    unlink(public_path().'/storage/images/'.$data->error_banner);

                    $input['error_banner'] = null;
                    $data->update($input);
                    $msg = __('Image Deleted Successfully');
                    return response()->json([
                    'status'=>true,
                    'message' => $msg
                ]);
                }
            }
        }
    }
}
