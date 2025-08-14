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

           if ($request->cookie('auth_token') || $request->header('token')) {

                //$token = $request->cookie('auth_token');

               if($request->cookie('auth_token')){

                    $token = $request->cookie('auth_token');
               }else{
                    $token = $request->header('token');
               }

                $JWT_KEY = env('JWT_KEY');
                $credentials = JWT::decode($token, new Key(base64_decode($JWT_KEY), 'HS512'));

            if (!$credentials) {

                $result['success'] = false;
                $result['message'] = "Unauthorized.";
                $result['error'] = "Unauthorized";
                return response()->json($result, 401);
                
            }

             $request->userData = (array) $credentials; // store decoded data

           }else{
                $result['success'] = false;
                $result['message'] = "Unauthorized.";
                $result['error'] = "Unauthorized";
                return response()->json($result, 401);
           }

        } catch (\Exception $e) {

            $result['success'] = false;
            $result['message'] = "Unauthorized.";
            $result['error'] = "Unauthorized";
            return response()->json($result, 401);
        }

        return $next($request);
    }
}
