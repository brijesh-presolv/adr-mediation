<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Helpers\SendGrid;
use App\Http\Helpers\SendGrid as Email;
use App\Http\Helpers\Whatsapp;
use App\Models\InvoledUser;
use App\Models\MedCase;
use App\Models\Reminder;
use App\Models\WaTemplate;
use App\Models\WhatsappLog;
use App\Models\WhatsappChatbot;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Helpers\Curl;
use App\Models\WhatsAppQue;
use App\Models\WhatsappTrack;
use App\Models\WhatsappBotQue;
use App\Models\WhatsappBotReport;
use App\Models\SettlementPayment;
use App\Models\ManageSession;
use App\Models\UserStopWhatsapp;
use Illuminate\Support\Facades\Auth;

use App\Http\Helpers\Token;

use App\Http\Controllers\API\PaymentController;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;

class UserController extends Controller 
{
public $successStatus = 200;
/** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(Request $request){ 
        //if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
        if(Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])){ 
            $user = Auth::user(); 


            // for user checking if user is approved 
            if (!Auth::user()->isActive) {
                Auth::logout();
                $this->logout();
                
                $result['success'] = false;
                $result['message'] = "Your user account is under Admin review.";
                $result['error'] = $e->getMessage();
                return response()->json($result, 500);
            }
            // for user checking if user is approved 
            
            $data['userid'] = $user->id;
            $data['role'] = $user->role;
            $data['email'] = $user->email;
            $data['name'] = $user->first_name;
            $result['success'] = "true";
            $result['message'] = "User has logged in successfully.";
            $result['data'] = $data;
            $result['token'] = Token::createToken($data); 
            $result['expiry_token'] = 900;

            return response()->json($result, $this->successStatus)
                                ->cookie(
                                        'auth_token',           // cookie name
                                        $result['token'],       // cookie value
                                        15,                     // minutes
                                        '/',
                                        null,                   // domain (or '.yourdomain.com' if frontend + backend share domain)
                                        true,                   // secure = true (required for cross-site cookies on HTTPS)
                                        true,                   // httpOnly
                                        false,                  // raw
                                        'None'                  // SameSite=None (allow cross-site)
                                    ); 
        } 
        else{ 

            $result['success'] = false;
            $result['message'] = "Unauthorized request.";
            $result['error'] = "Unauthorized";
            return response()->json($result, 401);
        } 
    }

     public function register(Request $request)
    {
        
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'first_name'     => 'required|string|max:255',
                'last_name'     => 'required|string|max:255',
                'email'    => 'required|string|email|unique:users',
                'password' => 'required|string|min:6',
                'mobile_number' => 'required|digits:10',
                'is_agree' => 'required',
                'actype' => 'required',
            ]);

            if ($validator->fails()) {

                $errors = $validator->errors()->all(); 

                $result['success'] = false;
                $result['message'] = $implode(', ', $errors);
                $result['error'] = $validator->errors();
                return response()->json($result, 422);
            }

            if ($request->input('actype') == 0) {
                $role = 0;
            } else if ($request->input('actype') == 1) {
                $role = 1;
            }else{
                $role = 0;
            }

            if($request->input('is_agree') == 1) {
                $is_agree = 1;
            } else {
                $is_agree = 0;
            }

            // Create user

            $user = User::create([
                'first_name' => $request->input('first_name'),
                'last_name' =>  $request->input('last_name'),
                'username' => $request->input('username'),
                'mobile_number' =>  $request->input('mobile_number'),
                'organization' => $request->input('organization'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'role' => $role, 
                'emailotp' => rand('100000', '999999'),
                'smsotp' => rand('100000', '999999'),
                'is_agree' => $is_agree

            ]);

            $data['userid'] = $user->id;

            $result['success'] = true;
            $result['message'] = "User registered successfully.";
            $result['data'] = $data;
            return response()->json($result, 201);

        } catch (Exception $e) {

            $result['success'] = false;
            $result['message'] = "Registration failed.";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
        }
    }

    public function logout(Request $request)
    {

        $cookie = Cookie::forget('auth_token');

        $result['success'] = true;
        $result['message'] = "User logged out successfully.";
        return response()->json($result, 200)->withCookie($cookie);

    }


    public function otpVerify(Request $request) {
        //Inputs
        $otp = $request->input('otp');
        $userId = $request->input('userid');

        $usr = User::find($userId);

        if ($otp == $usr->emailotp || $otp == $usr->smsotp) {

            $usr->emailotp = null;
            $usr->smsotp = null;
            $d = [
                'event' => 'VARIFY_EMAIL',
                'userid' => $userId,
            ];

            if ($usr->save()) {

                if ($usr->role == '1') {

                    $type = 'Mediator';

                    Email::send($d, $usr->email, env('EMAIL1_OF_VERIFY', ''), ['-type-' => $type], $usr->first_name . ' ' . $usr->last_name);

                }
                $type = 'User';

                Email::send($d, $usr->email, env('EMAIL1_OF_VERIFY', ''), ['-type-' => $type], $usr->first_name . ' ' . $usr->last_name);


                $data['userid'] = $usr->id;
                $result['success'] = "true";
                $result['message'] = "OTP verified successfully.";
                $result['data'] = $data;

                return response()->json($result, 200);
            }
        } else {
                $data['userid'] = $usr->id;
                $result['success'] = "true";
                $result['message'] = "OTP verification failed.";
                $result['data'] = $data;

                return response()->json($result, 500);
        }

    }

}
