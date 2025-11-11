<?php

namespace App\Http\Controllers\API\User;

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

            
            $profileData = User::find($userId);

            $finaldata['userid'] = $userId;
            $finaldata['first_name'] = $profileData->first_name;
            $finaldata['last_name'] = $profileData->last_name;
            $finaldata['email'] = $profileData->email;
            $finaldata['username'] = $profileData->username;
            $finaldata['mobile_number'] = $profileData->mobile_number;
            $finaldata['organization'] = $profileData->organization;
            $finaldata['address1'] = $profileData->address;
            $finaldata['address2'] = $profileData->address1;
            $finaldata['city'] = $profileData->city;
            $finaldata['pincode'] = $profileData->pincode;
            $finaldata['state'] = $profileData->state;
            $finaldata['country'] = $profileData->country;
            $finaldata['country_code'] = $profileData->country_code;
            //$finaldata['signature'] = $profileData->signature_photo;

            $filenametoget = 'mediation/user/' . $userId . '/signature/' . $profileData->signature_photo;
            if (Storage::disk('s3')->exists($filenametoget)) {
                $finaldata['signature'] = Storage::disk('s3')->get($filenametoget);
            } else {
                $finaldata['signature'] = "";  
            }
        

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
            
            // Inputs
            $first_name = $request->input('first_name');
            $last_name = $request->input('last_name');
            $email = $request->input('email');
            $username = $request->input('username');
            $mobile_number = $request->input('mobile_number');
            $organization = $request->input('organization');
            $address = $request->input('address');
            $address1 = $request->input('address1');
            $city = $request->input('city');
            $pincode = $request->input('pincode');
            $state = $request->input('state');
            $country = $request->input('country');
            $country_code = $request->input('country_code');
            $signature_photo = $request->file('signature_photo');
            $new_password = $request->input('new_password');

        
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'mobile_number' => 'required|string|max:11',
                'email' => ['email', 'required', 'unique:users,email,'.$userId],
                'username' => ['required', 'unique:users,username,'.$userId],
                'pincode' => 'nullable|string|max:8',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:100',
                'country' => 'nullable|string|max:100',
                
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
            $dataToUpdate->organization = $organization;
            $dataToUpdate->address = $address;
            $dataToUpdate->address1 = $address1;
            $dataToUpdate->city = $city;
            $dataToUpdate->state = $state;
            $dataToUpdate->country = $country;
            $dataToUpdate->country_code = $country_code;
            $dataToUpdate->pincode = $pincode;
            $dataToUpdate->username = $username;


            if($request->hasFile('signature_photo')) {


                if ($dataToUpdate->signature_photo != null) {

                    $oldFilePath = 'mediation/user/' . $userId . '/signature/' . $dataToUpdate->signature_photo;
                    Storage::disk('s3')->delete($oldFilePath);
                }
                $extension = $request->file('signature_photo')->getClientOriginalExtension();
                $name = 'User_Signature' . sprintf('%06d', $userId) . time() . '.' . $extension;
                $finalFilePath='mediation/user/' . $userId . '/signature/' . $name;
                Storage::disk('s3')->put($finalFilePath, file_get_contents($signature_photo));
                $dataToUpdate->signature_photo = $name;



                // if($dataToUpdate->signature_photo != null) {
                //     Storage::disk('local')->delete('public/user/' . $userId . '/signature/' . $dataToUpdate->signature_photo);
                // }
                // $extension = $request->file('signature_photo')->getClientOriginalExtension();
                // $name = 'User_Signature' . sprintf('%06d', $userId) . time() . '.' . $extension;
                // Storage::disk('local')->put('public/user/' . $userId . '/signature/' . $name, file_get_contents($signature_photo));
                // $dataToUpdate->signature_photo = $name;
                
            }


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

            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string|min:6',
                'password'     => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).+$/', 
                // Laravel automatically checks new_password == password_confirmation
            ]);

            if ($validator->fails()) {

                $errors = $validator->errors()->all();

                $result['success'] = false;
                $result['message'] = implode(', ', $errors);
                $result['error'] = $validator->errors();
                return response()->json($result, 422);
            }

            $user = User::find($userId);

            if (!Hash::check($request->current_password, $user->password)) {

                $result['success'] = false;
                $result['message'] = "Current password is incorrect.";
                $result['error'] = "Current password is incorrect.";
                return response()->json($result, 422);
            }

        $user->password = Hash::make($request->password);
        $user->save();

            $result['success'] = true;
            $result['message'] = "Password changed successfully.";
            return response()->json($result, 200);

        } catch (Exception $e) {

            $result['success'] = false;
            $result['message'] = "Fetching case data failded";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
        }
    }

}
