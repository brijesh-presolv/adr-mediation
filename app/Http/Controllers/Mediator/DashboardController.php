<?php

namespace App\Http\Controllers\Mediator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        return view('mediator.dashboard');
    }

    public function newrequest() {
        return view('mediator.newrequest');
    }
    public function ongoing() {
        return view('mediator.ongoing');
    }
    public function closed() {
        return view('mediator.closed');
    }
    public function profile() {
        return view('mediator.profile');
    }
    public function users() {
        return view('mediator.users');
    }

}
