<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

use App\Models\User;
use Session;

class HomeController extends Controller {

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        
        return view('welcome');
    }



     public function verify(Request $request){




        if(Auth::user()->emailotp!=null or Auth::user()->sms!=null){


        if($request->method()=='POST'){

            $r=$request->post();

            $usr=User::find(Auth::user()->id);

            if($r['smsotp']=='123456' and $r['emailotp']=='123456'){

                $usr->emailotp=null;
                $usr->smsotp=null;

                
                if($usr->save()){


                    if($request->session()->has('newcase')){

                        return redirect()->route('user.newcase');

                    } else{

                        return redirect()->route('user.dashboard');

                    }
                }

            }
        }


            return view('auth/verify');
        } else {

            return abort(404);
        }
    }

    public function mediation(Request $request){




         if($request->post()){

            $request->session()->put('newcase',$request->post());
        }


        if (Auth::user() and Auth::user()->role==0) {

                    return redirect()->route('user.newcase');
        } else{

            return redirect()->route('login');
        }


    }


    // public function login(){

    //     return view('login');
    // }

}
