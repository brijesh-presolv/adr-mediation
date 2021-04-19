<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
class IsUser {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        if (Auth::check() && (Auth::user()->role == 0)) {

            if(Auth::user()->emailotp!=null){
          
          return redirect('verify');
      }  else{
            return $next($request);
      }
        } else {
            abort(404);
        }
    }

}
