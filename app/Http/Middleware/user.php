<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Config;
use Firebase\JWT\Key;

class user {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {

        $authdata = $request->attributes->get('authdata');

        if (!isset($authdata['data']->userid) || $authdata['data']->role != 0) {

            $result['success'] = false;
            $result['message'] = "Access denied.";
            $result['error'] = "Access denied";
            return response()->json($result, 403);

        }
        
        return $next($request);
    }

}
