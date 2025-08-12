<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class apiadmin {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {

        if (!isset($request->userData['role']) || $request->userData['role'] !== '2') {
            return response()->json(['error' => 'Access denied'], 403);
        }
        
        return $next($request);
    }

}
