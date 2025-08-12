<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Helpers\SendGrid;
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

class UserController extends Controller 
{
public $successStatus = 200;
/** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(Request $request){ 
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            
            $data['userid'] = $user->id;
            $data['role'] = $user->role;
            $result['success'] = "true";
            $result['success_msg'] = "Authenticated successfully.";
            $result['data'] = $data;
            $result['token'] = Token::createToken(['role' => 'admin', 'id' => 1]); 
            $result['expiry_token'] = 900;

            return response()->json(['result' => $result], $this->successStatus); 
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }

     public function register(Request $request)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'name'     => 'required|string|max:255',
                'email'    => 'required|string|email|unique:users',
                'password' => 'required|string|min:6',
                'role'     => 'required|in:user,admin,mediator'
            ]);

            if ($validator->fails()) {

                $result['success'] = false;
                $result['message'] = "Validation failed";
                $result['message'] = $validator->errors();
                return response()->json($result, 422);
            }

            // Create user

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'username' => $request->username,
                'mobile_number' => $request->mobile_number,
                'organization' => $request->organization,
                'email' => $$request->email,
                'password' => Hash::make($request->password),
                'role' => 0, 
                'emailotp' => rand('100000', '999999'),
                'smsotp' => rand('100000', '999999'),
                'is_agree' => $is_agree

            ]);

            $data['userid'] = $user->id;
            $data['role'] = $user->role;
            $result['success'] = "true";
            $result['message'] = "User registered successfully.";
            $result['data'] = $data;
            return response()->json($result, 201);

        } catch (Exception $e) {


            $result['success'] = "false";
            $result['message'] = "Registration failed.";
            $result['message'] = $e->getMessage();
            return response()->json($result, 500);
        }
    }

}
