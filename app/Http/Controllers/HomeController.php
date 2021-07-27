<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

use App\Models\User;
use Session;
use App\Http\Helpers\SendGrid; 

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



        $email=SendGrid::send('prashant@bombayblokes.com', '0980bfd2-1743-4106-a861-eb64204cae94', ['otp'=>'123455']);

        var_dump($email);

        exit();




        if(Auth::user()->emailotp!=null){


        if($request->method()=='POST'){

            $r=$request->post();

            $usr=User::find(Auth::user()->id);

            if($r['emailotp']==$usr->emailotp){

                $usr->emailotp=null;
                $usr->smsotp=null;

                
                if($usr->save()){


                    if($request->session()->has('newcase')){

                        return redirect()->route('user.newcase');

                    } else{

                         if (!Auth::user()->isActive) {
                                Auth::logout();
                                return redirect('login')->with('warning','Account Under Review.');
                            }
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
