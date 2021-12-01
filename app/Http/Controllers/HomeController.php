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



                        $d = [
                            'event' => 'VARIFY_EMAIL',
                            'userid' => Auth::user()->id,
                        ];

                        if ($usr->role == '1') {

                            $type = 'Mediator';

                            Email::send($d, $usr->email, env('EMAIL1_OF_VERIFY', ''), ['-type-' => $type], $usr->first_name . ' ' . $usr->last_name);



                            return redirect()->route('mediator.dashboard');
                        }

                        $type = 'User';

                        Email::send($d, $usr->email, env('EMAIL1_OF_VERIFY', ''), ['-type-' => $type], $usr->first_name . ' ' . $usr->last_name);


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
                $d = [
                    'event' => 'FORGOT_PASSWORD',
                    'userid' => $usr->id,
                ];

                Email::send($d, $usr->email, env('EMAIL2_OF_FORGOTPASSWORD', ''), ['-type-' => $type, '-pwd-' => $pwd], $usr->first_name . ' ' . $usr->last_name);

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

                $d = [
                    'event' => 'FORGOT_USERNAME',
                    'userid' => $usr->id,
                ];

                Email::send($d, $usr->email, env('EMAIL3_OF_FORGOTUSERNAME', ''), ['-type-' => $type, '-name-' => $usr->username], $usr->first_name . ' ' . $usr->last_name);

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
                $d = [
                    'event' => 'RESEND_OTP',
                    'userid' => $usr->id,
                ];
                if ($usr->role == '0') {
                    $email = Email::send($d, $usr->email, env('EMAIL4_RESENDOTP_OF_USER', ''), ['-otp-' => strval($usr->emailotp)], $usr->first_name . ' ' . $usr->last_name);
                } else if ($usr->role == '1') {

                    $email = Email::send($d, $usr->email, env('EMAIL5_RESENDOTP_OF_MEDIATOR', ''), ['-otp-' => strval($usr->emailotp)], $usr->first_name . ' ' . $usr->last_name);
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
