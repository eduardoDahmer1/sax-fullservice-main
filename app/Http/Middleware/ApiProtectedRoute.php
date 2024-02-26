<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Http\Middleware\BaseMiddleware;
use PHPOpenSourceSaver\JWTAuth\Exceptions\{TokenExpiredException, TokenInvalidException};

class ApiProtectedRoute extends BaseMiddleware
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
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $exception) {
            if ($exception instanceof TokenInvalidException){
                return response()->json(['status' => 'Token is Invalid'], 401);
            }else if ($exception instanceof TokenExpiredException){
                return response()->json(['status' => 'Token is Expired'], 401);
            }else{
                return response()->json(['status' => 'Authorization Token not found'], 401);
            }
        }

        if(!config('features.api')){
            return response()->json(['status' => 'Store without API permission'], 401);
        }

        if(!auth('admin-api')->user()->sectionCheck('api')){
            return response()->json(['status' => 'User without API permission'], 401);
        }

        return $next($request);
    }
}
