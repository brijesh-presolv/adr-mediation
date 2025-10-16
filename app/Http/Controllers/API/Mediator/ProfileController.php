<?php

namespace App\Http\Controllers\API\Mediator;

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
use App\Models\Mediation_Details;
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
            $userProfileData = Mediation_Details::where("user_id", "=", $userId)->first();

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
            $finaldata['signature'] = $profileData->signature_photo;

            $finaldata['area_of_specialization'] = json_decode($userProfileData['area_of_specialization']);
            $finaldata['no_of_arbitrations'] = $userProfileData['no_of_arbitrations'];
            $finaldata['linked_in_profile_link'] = $userProfileData['linked_in_profile_link'];
            $finaldata['experience'] = $userProfileData['experience'];
            $finaldata['terms_condition1'] = $userProfileData['is_accept1'];
            $finaldata['terms_condition2'] = $userProfileData['is_accept2'];
            $finaldata['terms_condition3'] = $userProfileData['is_accept3'];
            $finaldata['years_of_experience'] = $userProfileData['years_of_experience'];
            $finaldata['spoken_language'] = json_decode($userProfileData['spoken_language']);

            $result['success'] = true;
            $result['message'] = "Mediator profile data fetched successfully.";
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
            $mobile_number = $request->input('mobile_number');
            $address = $request->input('address');
            $address1 = $request->input('address1');
            $city = $request->input('city');
            $pincode = $request->input('pincode');
            $state = $request->input('state');
            $country = $request->input('country');
            $signature_photo = $request->file('signature_photo');
            $profile_pic = $request->file('profile_pic');


            $area_of_specialization = $request->input('area_of_specialization');
            $no_of_arbitrations = $request->input('no_of_arbitrations');
            $linked_in_profile_link = $request->input('linked_in_profile_link');
            $experience = $request->input('experience');
            $years_of_experience = $request->input('years_of_experience');
            $language = $request->input('spoken_language');
            $is_accept1 = $request->input('is_accept1');
            $is_accept2 = $request->input('is_accept2');
            $is_accept3 = $request->input('is_accept3');
           
        
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'mobile_number' => 'required',
                'email' => ['email', 'required', 'unique:users,email,'.$userId],
                'area_of_specialization' => 'array',
                'spoken_language' => 'array'
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
                $dataToUpdate->address = $address;
                $dataToUpdate->address1 = $address1;
                $dataToUpdate->city = $city;
                $dataToUpdate->state = $state;
                $dataToUpdate->country = $country;
                $dataToUpdate->pincode = $pincode;
                $dataToUpdate->isDone = 1;
            
                if($request->hasFile('signature')) {
                    if($dataToUpdate->signature_photo != null) {
                        Storage::disk('local')->delete('public/mediator/' . $userId . '/signature/' . $dataToUpdate->signature_photo);
                    }
                    $extension = $request->file('signature_photo')->getClientOriginalExtension();
                    $name = 'Mediator_Signature' . sprintf('%06d', $userId) . time() . '.' . $extension;
                    Storage::disk('local')->put('public/mediator/' . $userId . '/signature/' . $name, file_get_contents($signature_photo));
                    $dataToUpdate->signature_photo = $name;
                }

                if($request->hasFile('profilePic')) {
                    if($dataToUpdate->profile_pic != null) {
                        Storage::disk('local')->delete('public/mediator/' . $userId . '/profile/' . $dataToUpdate->profile_pic);
                    }
                    $extension = $request->file('profile_pic')->getClientOriginalExtension();
                    $profilename = 'Mediator_Profile_Pic' . sprintf('%06d', $userId) . time() . '.' . $extension;
                    Storage::disk('local')->put('public/mediator/' . $userId . '/profile/' . $profilename, file_get_contents($profile_pic));
                    $dataToUpdate->profile_pic = $profilename;
                } 

                $dataToUpdate->save();
                $isMedi = Mediation_Details::where("user_id", "=", $userId)->first();
                if (empty($isMedi)) {
                    $mediation_details = new Mediation_Details();
                } else {
                    $mediation_details = $isMedi;
                }
                $mediation_details->user_id = $userId;
                $mediation_details->area_of_specialization = $area_of_specialization;
                $mediation_details->no_of_arbitrations = $no_of_arbitrations;
                $mediation_details->linked_in_profile_link = $linked_in_profile_link;
                $mediation_details->experience = $experience;
                $mediation_details->years_of_experience = $years_of_experience;
                $mediation_details->spoken_language = $language;
                $mediation_details->is_accept1 = $is_accept1;
                $mediation_details->is_accept2 = $is_accept2;
                $mediation_details->is_accept3 = $is_accept3;
                $mediation_details->save();


           
                $data['userId'] = $userId;
                $result['success'] = true;
                $result['message'] = "Mediator profile updated successfully.";
                $result['data'] = $data;
                return response()->json($result, 200);
            
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
                'password'     => 'required|string|min:8|confirmed', 
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
