<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Config;
use Firebase\JWT\Key;

class apiauth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        try {

           if ($request->cookie('auth_token')) {

                $token = $request->cookie('auth_token');

                $JWT_KEY = env('JWT_KEY');
                $credentials = JWT::decode($token, new Key(base64_decode($JWT_KEY), 'HS512'));

            if (!$credentials) {

                $result['success'] = false;
                $result['message'] = "Unauthorized request.";
                $result['error'] = "Unauthorized";
                return response()->json($result, 401);
                
            }

             $request->attributes->set('authdata', (array) $credentials);

           }else{
                $result['success'] = false;
                $result['message'] = "Unauthorized request.";
                $result['error'] = "Unauthorized";
                return response()->json($result, 401);
           }

        } catch (\Exception $e) {

            $result['success'] = false;
            $result['message'] = "Unauthorized request.";
            $result['error'] = "Unauthorized";
            return response()->json($result, 401);
        }

        return $next($request);
    }
}
