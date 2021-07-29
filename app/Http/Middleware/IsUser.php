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

        if (Auth::user()->isActive=='0' or Auth::user()->status=='0') {
            Auth::logout();
            return redirect('login')->with('warning','Account Under Review.');
        }
        
            return $next($request);
      }
        } else {
            abort(404);
        }
    }

}
