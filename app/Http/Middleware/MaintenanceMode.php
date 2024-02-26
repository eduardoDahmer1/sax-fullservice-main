<?php

namespace App\Http\Middleware;

use App\Models\Generalsetting;
use Closure;
use Illuminate\Support\Facades\Auth;

class MaintenanceMode
{
    public function handle($request, Closure $next)
    {
        $gs = resolve('storeSettings');

        if (env("ACTIVE_PAGE_COMING_SOON", false) && !Auth::guard('admin')->check()) {
            abort(307);
        }

        if ($gs->is_maintain == 1 && !Auth::guard('admin')->check()) {
            abort(503);
        }

        return $next($request);
    }
}
