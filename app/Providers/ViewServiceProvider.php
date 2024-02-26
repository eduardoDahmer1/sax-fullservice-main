<?php

namespace App\Providers;

use App\Models\AdminLanguage;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Generalsetting;
use App\Models\Language;
use App\Models\Notification;
use App\Models\Page;
use App\Models\Pagesetting;
use App\Models\Seotool;
use App\Models\Service;
use App\Models\Socialsetting;
use App\Models\TeamMemberCategory;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (!app()->runningInConsole()) {
            $storeSettings = resolve('storeSettings');
            $storeLocale = resolve('storeLocale');
            $locales = resolve('locales');
            $storeCurrency = resolve('storeCurrency');
            $currencies = resolve('currencies');
            app()->instance('adminLocale', AdminLanguage::where('is_default', '=', 1)->first());
            view()->composer('admin.*', function ($settings) {
                $settings->with('notifications_count', Notification::count());
            });

            view()->composer('front.themes.*', function ($settings) {
                $categories = Category::with('subs_order_by.childs_order_by')->orderBy('presentation_position', 'asc')->where('status', 1)->get();

                $settings->with('categories', $categories);
                $settings->with('pheader', Page::where('header', '=', 1)->get());
                $settings->with('pfooter', Page::where('footer', '=', 1)->get());
                $settings->with('socials', Socialsetting::find(1));
                $settings->with('fblogs', Blog::orderBy('created_at', 'desc')->limit(3)->get());
                $settings->with('twhatsapp', TeamMemberCategory::withWhatsApp()->with('team_members')->get());
            });

            view()->composer('*', function ($settings) use ($storeSettings, $storeLocale, $locales, $storeCurrency, $currencies) {
                $settings->with('seo', Seotool::find(1));
                $settings->with('gs', $storeSettings);
                $ps = PageSetting::where('store_id', $storeSettings->id)->first();
                $services = Service::where('user_id', '=', 0)->get();
                $settings->with('admstore', Session::has('admstore') ? Session::get('admstore') : $storeSettings);
                $settings->with('stores', Generalsetting::all());
                $settings->with('locales', $locales);
                $settings->with('currencies', $currencies);
                $settings->with('lang', Language::find(1));
                $settings->with('curr', Currency::find(1));
                $settings->with('ps', $ps);
                $settings->with('services', $services);
                $data = $storeLocale;

                if (Session::has('language') && $storeSettings->is_language && config("features.lang_switcher")) {
                    $data = Language::find(Session::get('language'));
                }
                $settings->with('slocale', $data);
                App::setlocale($data->locale);

                if (Route::current() && Route::is('admin*')) {
                    $admin_lang = AdminLanguage::where('is_default', '=', 1)->first();
                    App::setlocale("admin_{$admin_lang->name}");
                }

                $current_locale = strtolower(str_replace('admin_', '', App::getLocale()));
                $datatable_translation = asset('assets/lang/datatable_en.json');
                if (file_exists(public_path()."/assets/lang/datatable_".$current_locale.".json")) {
                    $datatable_translation = asset('assets/lang/datatable_'.$current_locale.'.json');
                }
                $settings->with('current_locale', $current_locale);
                $settings->with('datatable_translation', $datatable_translation);

                $str_document = __('Document');
                if (config('document.cpf')) {
                    $str_document = 'CPF';
                }
                if (config('document.cnpj')) {
                    $str_document = 'CNPJ';
                }
                if (config('document.general')) {
                    $str_document = 'Document';
                }

                $settings->with('customer_doc_str', $str_document);

                if (!Session::has('popup')) {
                    $settings->with('visited', 1);
                }
                Session::put('popup', 1);

                if (!config("features.currency_switcher")) {
                    Session::forget('currency');
                }

                $data = $storeCurrency;

                if (Session::has('currency') && $storeSettings->is_currency && config("features.currency_switcher")) {
                    $data = Currency::find(Session::get('currency'));
                }
                $settings->with('scurrency', $data);
            });
        }
    }
}
