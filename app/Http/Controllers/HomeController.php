<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

use App\Models\User;
use Session;
use App\Http\Helpers\SendGrid as Email;

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



        if(Auth::user()->emailotp!=null){


        if($request->method()=='POST'){

            $r=$request->post();

            $usr=User::find(Auth::user()->id);

            if($r['emailotp']==$usr->emailotp){

                $usr->emailotp=null;
                $usr->smsotp=null;

                
                if($usr->save()){


                    if($usr->role=='1'){

                        return redirect()->route('mediator.dashboard');
                    }


                    if($request->session()->has('newcase')){

                        //return redirect()->route('user.newcase');

                        if (!Auth::user()->isActive) {
                                Auth::logout();
                                return redirect('login')->with('warning','Account Under Review.');
                            }

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



    public function forgotpassword(Request $request){

        if($request->post()){

            $u=$request->post();



            $usr=User::where(['username'=>$u['username']])->first();

            if($usr){

                
                if($usr->role==0){

                    $type='User';
                } else{

                    $type='Mediator';
                }


                 $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                 $pwd=substr(str_shuffle($chars),0,8);


                Email::send($usr->email,'760f8edf-ada7-4b23-8c44-7d0770195cdb',['-type-'=>$type,'-pwd-'=>$pwd],$usr->name);
                
                echo json_encode(['response'=>'success']);

                exit;

            }


        }

        echo json_encode(['response'=>'error']);


 

    }

    public function forgotusername(Request $request){


        if($request->post()){

            $u=$request->post();

            
            $usr=User::where(['email'=>$u['email']])->first();

            if($usr){

                if($usr->role==0){

                    $type='User';
                } else{

                    $type='Mediator';
                }

                Email::send($usr->email,'aad4779e-f892-46ed-b6d8-7b75195f45b9',['-type-'=>$type,'-name-'=>$usr->username],$usr->name);
                
                echo json_encode(['response'=>'success']);

                exit;

            }



            
        }

        echo json_encode(['response'=>'error']);

        
    }


    // public function login(){

    //     return view('login');
    // }

}
