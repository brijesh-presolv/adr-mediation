<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller {

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        if (Auth::check() && (Auth::user()->role == 0)) {
           return redirect()->route('user.dashboard');
        } else if (Auth::check() && (Auth::user()->role == 1)) {
           return redirect()->route('arbitrator.dashboard');
        } else if (Auth::check() && (Auth::user()->role == 2)) {
            return redirect()->route('admin.dashboard');
        }
    }

}
