<?php

namespace App\Http\Middleware;
use Auth;
use Closure;

class Permissions
{

    public function handle($request, Closure $next,$data)
    {
        if (Auth::guard('admin')->check()) {
            // Grant that default super will be always able to access
            if(Auth::guard('admin')->user()->id == 1){
                return $next($request);
            }
            // Grant access to other super admins
            if(Auth::guard('admin')->user()->role_id == 0){
                return $next($request);
            }
            if (Auth::guard('admin')->user()->sectionCheck($data)){
                return $next($request);
            }
        }
        return redirect()->route('admin.dashboard')->with('unsuccess',"You don't have access to that section"); 
    }
}
