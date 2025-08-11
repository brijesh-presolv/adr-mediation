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
            $success['token'] =   $token = Token::createToken(['role' => 'admin', 'id' => 1]); 
            return response()->json(['success' => $success], $this->successStatus); 
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }
/** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
//     public function register(Request $request) 
//     { 
//         $validator = Validator::make($request->all(), [ 
//             'name' => 'required', 
//             'email' => 'required|email', 
//             'password' => 'required', 
//             'c_password' => 'required|same:password', 
//         ]);
// if ($validator->fails()) { 
//             return response()->json(['error'=>$validator->errors()], 401);            
//         }
// $input = $request->all(); 
//         $input['password'] = bcrypt($input['password']); 
//         $user = User::create($input); 
//         $success['token'] =  $user->createToken('MyApp')-> accessToken; 
//         $success['name'] =  $user->name;
// return response()->json(['success'=>$success], $this-> successStatus); 
//     }
// /** 
//      * details api 
//      * 
//      * @return \Illuminate\Http\Response 
//      */ 
//     public function details() 
//     { 
//         $user = Auth::user(); 
//         return response()->json(['success' => $user], $this-> successStatus); 
//     } 
}
