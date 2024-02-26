<?php

namespace App\Providers;

use Exception;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Generalsetting;
use App\Observers\OrderObserver;
use App\View\Components\Header\Theme15;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::component('front.themes.theme-15.components.header', Theme15::class);
        if (!app()->runningInConsole()) {
            // use bootstrap instead of tailwind
            Paginator::useBootstrap();

            $currentUrl = str_replace(
                ['http://', 'https://'],
                '',
                url()->current()
            );
            # Disable Cache for Multistore
            $storeSettings = Generalsetting::whereRaw("'{$currentUrl}' LIKE CONCAT(domain,'%')")->first();

            if (!$storeSettings || empty($storeSettings->domain)) {
                $storeSettings = $this->getStoreSettings();
            }

            # Get populated StoreSettings cache after migrations run (application ready) if current $storeSettings has no ID (empty)
            # This is useful if cache:clear is not run after db:seed
            if (!$storeSettings->id && Schema::hasTable('generalsettings') && Generalsetting::count() > 0) {
                return $this->forgetGeneralSettingsCache();
            }

            # When saving or updating Generalsetting, just forget Cache and instantiate a new object.
            Generalsetting::saving(function () {
                return $this->forgetGeneralSettingsCache();
            });

            Generalsetting::updated(function () {
                return $this->forgetGeneralSettingsCache();
            });

            Order::observe(OrderObserver::class);

            if (!app()->runningInConsole()) {
                if (env('APP_ENV') == 'production') {
                    URL::forceScheme('https');
                }

                $locales = Language::all();
                $storeLocale = $locales->find($storeSettings->lang_id);
                $currencies = Currency::all();
                $storeCurrency = $currencies->find($storeSettings->currency_id);

                $lang = $locales->find(1);

                $this->prepareLocaleFiles($locales);

                app()->instance('storeLocale', $storeLocale);
                app()->instance('locales', $locales);
                app()->instance('storeCurrency', $storeCurrency);
                app()->instance('currencies', $currencies);
                app()->instance('lang', $lang);
            }
            app()->instance('storeSettings', $storeSettings);
        }

        if (app()->runningInConsole()) {
            $storeSettings = new Generalsetting;
            app()->instance('storeSettings', $storeSettings);
        }

        Blade::if('wedding', fn () => config('features.wedding_list'));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (env('REDIRECT_HTTPS')) {
            $this->app['request']->server->set('HTTPS', true);
        }
        Collection::macro('paginate', function ($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);
            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });
    }

    protected function getStoreSettings()
    {
        # Remember Generalsetting Cache for one hour. It will be invalidated if Generalsetting is updated or saved in any way.
        return Cache::remember("storeSettings", 60 * 60, function () {
            # Return empty object if there's no migration yet
            if (!Schema::hasTable('generalsettings')) {
                return new Generalsetting;
            }
            return Generalsetting::where('is_default', 1)->first();
        });
    }

    protected function forgetGeneralSettingsCache()
    {
        if (Cache::has("storeSettings")) {
            Cache::forget("storeSettings");
        }
        $storeSettings = $this->getStoreSettings();
        return $storeSettings;
    }

    private function prepareLocaleFiles($locales)
    {
        $locales->each(function ($data) {
            $currentFile = lang_path($data->file);
            $baseFile = lang_path("base/{$data->locale}.json");

            if (!file_exists($baseFile)) {
                throw new Exception("No base file found for {$data->locale}. Please make sure to add the file to /lang/base/{$data->locale}.json.");
            }

            if (!file_exists($currentFile)) {
                File::copy($baseFile, $currentFile);
            }
        });
    }
}
