<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Config;

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

               $credentials = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));

            if (!$credentials) {

                return response()->json(['error' => 'Unauthorized'], 401);
            }

             $request->userData = (array) $credentials; // store decoded data

           }else{
                return response()->json(['error' => 'Unauthorized'], 401);
           }

        } catch (\Exception $e) {

            return response()->json(['error' => 'Invalid token'], 401);
        }

        return $next($request);
    }
}
