<?php

namespace App\Http\Middleware;

use Closure;

class IsMeli
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
        if(!config("mercadolivre.is_active")) return abort(404);
        return $next($request);
    }
}
