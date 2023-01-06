<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Session;

class DashboardController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware(function ($request, $next) {
            $userdata = User::getUserdetails(Auth::user()->id);
    
            if ($userdata->address == '' or $userdata->address1 == '' or $userdata->city == '' or $userdata->pincode == '' or $userdata->state == '' or $userdata->country == '') {
                if ($_SERVER['REQUEST_URI'] != "/user/profile") {
                    Session::put('force', 1);
                    
                    header("Location: ../user/profile");
                    exit();
                }
            } else {
                return $next($request);
            }
           
        });
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        return view('user.dashboard');
    }

}
