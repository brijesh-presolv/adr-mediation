<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Session;
use App\Http\Helpers\SendGrid as Email;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller {

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {

        return view('welcome');
    }

    public function verify(Request $request) {



        if (Auth::user()->emailotp != null) {


            if ($request->method() == 'POST') {

                $r = $request->post();

                $usr = User::find(Auth::user()->id);

                if ($r['emailotp'] == $usr->emailotp) {

                    $usr->emailotp = null;
                    $usr->smsotp = null;


                    if ($usr->save()) {





                        if ($usr->role == '1') {

                            $type = 'Mediator';

                            Email::send($usr->email, '92f1d3c4-077b-4e6a-b9db-3f9a3fda2111', ['-type-' => $type], $usr->first_name . ' ' . $usr->last_name);



                            return redirect()->route('mediator.dashboard');
                        }

                        $type = 'User';

                        Email::send($usr->email, '92f1d3c4-077b-4e6a-b9db-3f9a3fda2111', ['-type-' => $type], $usr->first_name . ' ' . $usr->last_name);


                        if ($request->session()->has('newcase')) {

                            //return redirect()->route('user.newcase');

                            if (!Auth::user()->isActive) {
                                Auth::logout();
                                return redirect('login')->with('warning', 'Account Under Review.');
                            }

                            return redirect()->route('user.dashboard');

                        } else {

                            if (!Auth::user()->isActive) {
                                Auth::logout();
                                return redirect('login')->with('warning', 'Account Under Review.');
                            }

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

    public function mediation(Request $request) {




        if ($request->post()) {

            $request->session()->put('newcase', $request->post());
        }


        if (Auth::user() and Auth::user()->role == 0) {

            return redirect()->route('user.newcase');
        } else {

            return redirect()->route('login');
        }
    }

    public function forgotpassword(Request $request) {

        if ($request->post()) {

            $u = $request->post();



            $usr = User::where(['username' => $u['username']])->first();

            if ($usr) {


                if ($usr->role == 0) {

                    $type = 'User';
                } else {

                    $type = 'Mediator';
                }


                $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                $pwd = substr(str_shuffle($chars), 0, 8);

                $usr->password = Hash::make($pwd);

                $usr->save();


                Email::send($usr->email, '760f8edf-ada7-4b23-8c44-7d0770195cdb', ['-type-' => $type, '-pwd-' => $pwd], $usr->first_name . ' ' . $usr->last_name);

                echo json_encode(['response' => 'success']);

                exit;
            }
        }

        echo json_encode(['response' => 'error']);
    }

    public function forgotusername(Request $request) {


        if ($request->post()) {

            $u = $request->post();


            $usr = User::where(['email' => $u['email']])->first();

            if ($usr) {

                if ($usr->role == 0) {

                    $type = 'User';
                } else {

                    $type = 'Mediator';
                }

                Email::send($usr->email, 'aad4779e-f892-46ed-b6d8-7b75195f45b9', ['-type-' => $type, '-name-' => $usr->username], $usr->first_name . ' ' . $usr->last_name);

                echo json_encode(['response' => 'success']);

                exit;
            }
        }

        echo json_encode(['response' => 'error']);
    }

    public function resendotp(Request $request) {


        if ($request->post()) {

            $u = $request->post();


            $usr = User::where(['username' => $u['id']])->first();

            if ($usr) {

                if ($usr->role == '0') {
                    $email = Email::send($usr->email, '5e3c0043-6349-4dc6-9886-23795ccb5f27', ['-otp-' => strval($usr->emailotp)], $usr->first_name . ' ' . $usr->last_name);
                } else if ($usr->role == '1') {

                    $email = Email::send($usr->email, '0980bfd2-1743-4106-a861-eb64204cae94', ['-otp-' => strval($usr->emailotp)], $usr->first_name . ' ' . $usr->last_name);
                }

                echo json_encode(['response' => 'success']);

                exit;
            }
        }

        echo json_encode(['response' => 'error']);
    }

    // public function login(){
    //     return view('login');
    // }
}
