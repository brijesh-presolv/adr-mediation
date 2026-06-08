<?php

namespace App\Http\Controllers\Auth;

use Session;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;

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

      if(Auth::user()->emailotp!=null){

        Session::put('last_login', Auth::user()->last_login);
        $USER = User::find(Auth::user()->id);
        $USER->last_login = date("Y-m-d h:i:s");
        $USER->save();
          
          
          return route('verify');
        } else
       if (Auth::check() && (Auth::user()->role == 0)) {
            Session::put('last_login', Auth::user()->last_login);
            $USER = User::find(Auth::user()->id);
            $USER->last_login = date("Y-m-d h:i:s");
            $USER->save();


          if(Session::has('newcase')){
             return route('user.newcase');
          }

           return route('user.dashboard');
        } else if (Auth::check() && (Auth::user()->role == 1)) {
            Session::put('last_login', Auth::user()->last_login);
            $USER = User::find(Auth::user()->id);
            $USER->last_login = date("Y-m-d h:i:s");
            $USER->save();

            if(Auth::user()->emailotp!=null){
          
          return route('verify');
      } else if(Auth::user()->isDone!=1) {
        return route('mediator.profile.firstupdate');
      }
           return route('mediator.dashboard');
        } else if (Auth::check() && (Auth::user()->role == 2)) {
            Session::put('last_login', Auth::user()->last_login);
            $USER = User::find(Auth::user()->id);
            $USER->last_login = date("Y-m-d h:i:s");
            $USER->save();
            return route('admin.dashboard');
        } else if (Auth::check() && (Auth::user()->role == 3)) { // for sub user
            Session::put('last_login', Auth::user()->last_login);
            $USER = User::find(Auth::user()->id);
            $USER->last_login = date("Y-m-d h:i:s");
            $USER->save();


        

         return route('user.dashboard');
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
        return ['email' => $request->{$this->username()}, 'password' => $request->password];
    }

    public function logout(Request $request) {
          Auth::logout();
          return redirect('/login');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        // if (!Auth::user()->isActive) {
        //     Auth::logout();
        //     return redirect('login')->with('warning','Account Under Review.');
        // }

        return $request->wantsJson()
                    ? new JsonResponse([], 204)
                    : redirect()->intended($this->redirectPath());
    }

}
