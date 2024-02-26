<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->storeSettings = resolve('storeSettings');
        $this->storeLocale = resolve('storeLocale');
        $this->adminLocale = resolve('adminLocale');

        # When Lang Switcher is used this variable comes with new Lang ID.
        $this->newLangId = session()->get('language');

        # If Lang Switcher has been used, just sets it as current Store Locale.
        $storeLocale = $this->storeLocale;
        if(config("features.lang_switcher") && $this->storeSettings->is_language && $this->newLangId) {
            $storeLocale = Language::find($this->newLangId);
        }

        # Define App translations based on Route.
        App::setLocale(Route::is('admin*') ? "admin_{$this->adminLocale->name}" : $storeLocale->locale);
        
        return $next($request);
    }
}
