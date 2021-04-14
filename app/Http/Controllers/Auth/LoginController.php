<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Illuminate\Http\Request;

class LoginController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles authenticating users for the application and
      | redirecting them to your home screen. The controller uses a trait
      | to conveniently provide its functionality to your applications.
      |
     */

use AuthenticatesUsers;



    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;


    protected function redirectTo(){
       if (Auth::check() && (Auth::user()->role == 0)) {
           return redirect()->route('user.dashboard');
        } else if (Auth::check() && (Auth::user()->role == 1)) {
           return redirect()->route('mediator.dashboard');
        } else if (Auth::check() && (Auth::user()->role == 2)) {
            return redirect()->route('admin.dashboard');
        }

    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest')->except('logout');
    }

    protected function credentials(\Illuminate\Http\Request $request) {
        //return $request->only($this->username(), 'password');
        return ['email' => $request->{$this->username()}, 'password' => $request->password, 'isActive' => 1];
    }

    public function logout(Request $request) {
          Auth::logout();
          return redirect('/login');
    }

}
