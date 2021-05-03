<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class IsMediator {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        if (Auth::check() && (Auth::user()->role == 1)) {
            if (Auth::user()->isDone == 0) {
                if ($request->path() == "mediator/profile/update" || $request->path() == "mediator/profile/profile-save") {
                    return $next($request);
                } else {
                    return redirect()->route("mediator.profile");
                }
            } else {
                return $next($request);
            }
        } else {
            abort(404);
        }
    }

}
