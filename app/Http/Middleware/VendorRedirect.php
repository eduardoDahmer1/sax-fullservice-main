<?php

namespace App\Http\Middleware;

use Closure;

class VendorRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!config("features.marketplace")) return redirect()->route('front.index');
        return $next($request);
    }
}
