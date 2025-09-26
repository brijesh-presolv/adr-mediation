<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedCase;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Helpers\Curl;
use App\Models\ManageSession;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers\Token;
use Illuminate\Support\Facades\Validator;
use App\Http\Helpers\Common_function;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\InvoledUser;
use App\Models\InvitationFiles;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\BulkLog;
use App\Models\EmailTrack;
use App\Models\Notification;
use App\Models\SendWhatsappChoice;
use App\Models\ConsentDisclosures;
use App\Http\Helpers\SendGrid;
use App\Http\Traits\UploadTrait;
use App\Rules\MatchOldPassword;
use PDF;
use DateTime;
use DateTimeZone;
use Carbon\Carbon;
use App\Http\Helpers\Zoom;


class ProfileController extends Controller 
{
    use UploadTrait;

    public function getProfileData(Request $request){
        try{
            $token = $request->cookie('auth_token');
            if (!$token) {

                $result['success'] = false;
                $result['message'] = 'Unauthorized: Missing token';
                $result['error'] = 'Unauthorized: Missing token';
                return response()->json($result, 401);
            }

            $JWT_KEY = env('JWT_KEY');
            $jwtData = JWT::decode($token, new Key(base64_decode($JWT_KEY), 'HS512'));
            $userId = $jwtData->data->userid;
            //$userId = 2;

            
            $profileData = User::select('*')->where('id', $userId)->first();

            $finaldata['userid'] = $userId;
            $finaldata['first_name'] = $profileData->first_name;
            $finaldata['last_name'] = $profileData->last_name;
            $finaldata['email'] = $profileData->email;
            $finaldata['username'] = $profileData->username;
            $finaldata['mobile_number'] = $profileData->mobile_number;
            
            $result['success'] = true;
            $result['message'] = "User profile data fetched successfully.";
            $result['data'] = $finaldata;
            return response()->json($result, 200);
        } catch (Exception $e) {
            $result['success'] = false;
            $result['message'] = "User profile data loading failed.";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
        }
    }


    public function editProfileData(Request $request) {
        try{

            $token = $request->cookie('auth_token');
            if (!$token) {

                $result['success'] = false;
                $result['message'] = 'Unauthorized: Missing token';
                $result['error'] = 'Unauthorized: Missing token';
                return response()->json($result, 401);
            }

            $JWT_KEY = env('JWT_KEY');
            $jwtData = JWT::decode($token, new Key(base64_decode($JWT_KEY), 'HS512'));
            $userId = $jwtData->data->userid;
            //$userId = 2;

            // Inputs
            $first_name = $request->input('first_name');
            $last_name = $request->input('last_name');
            $email = $request->input('email');
            $username = $request->input('username');
            $mobile_number = $request->input('mobile_number');
            
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'mobile_number' => 'required',
                'email' => ['email', 'required', 'unique:users,email,'.$userId],
                'username' => ['required', 'unique:users,username,'.$userId]
            ]);


            if ($validator->fails()) {

                $errors = $validator->errors()->all();

                $result['success'] = false;
                $result['message'] = implode(', ', $errors);
                $result['error'] = $validator->errors();
                return response()->json($result, 422);
            }



            $dataToUpdate = User::find($userId);
            $dataToUpdate->first_name = ucfirst($first_name);
            $dataToUpdate->last_name = ucfirst($last_name);
            $dataToUpdate->email = $email;
            $dataToUpdate->mobile_number = $mobile_number;
            $dataToUpdate->username = $username;
            
            if ($dataToUpdate->save()) {
                $data['userId'] = $userId;
                $result['success'] = true;
                $result['message'] = "User profile updated successfully.";
                $result['data'] = $data;
                return response()->json($result, 200);
            }
        } catch (Exception $e) {

            $result['success'] = false;
            $result['message'] = "Fetching case data failded";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
        }
    }

    public function changePassword(Request $request)
    {

        try{

            $token = $request->cookie('auth_token');
            if (!$token) {

                $result['success'] = false;
                $result['message'] = 'Unauthorized: Missing token';
                $result['error'] = 'Unauthorized: Missing token';
                return response()->json($result, 401);
            }

            $JWT_KEY = env('JWT_KEY');
            $jwtData = JWT::decode($token, new Key(base64_decode($JWT_KEY), 'HS512'));
            $userId = $jwtData->data->userid;

            //$userId = 2;

            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string|min:6',
                'password'     => 'required|string|min:8|confirmed', 
                // Laravel automatically checks new_password == password_confirmation
            ]);


            //Inputs
            $current_pwd = $request->current_password;
            $new_pwd = $request->new_password;

            if ($validator->fails()) {

                $errors = $validator->errors()->all();

                $result['success'] = false;
                $result['message'] = implode(', ', $errors);
                $result['error'] = $validator->errors();
                return response()->json($result, 422);
            }

            $user = User::find($userId);

            if (!Hash::check($current_pwd, $user->password)) {

                $result['success'] = false;
                $result['message'] = "Current password is incorrect.";
                $result['error'] = "Current password is incorrect.";
                return response()->json($result, 422);
            }

            $user->password = Hash::make($new_pwd);
            $user->save();

            $result['success'] = true;
            $result['message'] = "Password changed successfully.";
            return response()->json($result, 422);

        } catch (Exception $e) {

            $result['success'] = false;
            $result['message'] = "Fetching case data failded";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
        }
    }

}
