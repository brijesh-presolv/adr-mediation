<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Mediation_Details;
use App\Models\AreaOfSpecialization;
use App\Http\Helpers\SendGrid;
use Illuminate\Support\Facades\Storage;
use DB;
use Session;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{

    public function __construct()
    {
    }

    public function userApprove(Request $request)
    {

        $start   = $request->input('iDisplayStart', 0);   
        $length  = $request->input('iDisplayLength', 10); 
        $search  = $request->input('search', '');
        $sortOrder = $request->input('SortOrder', 'desc');
        $columnName = $request->input('columnName', ''); 
        $role=0;

        $users = User::getUserApprove($role, $start, $length, $search, $columnName, $sortOrder);

        $resultData['users']=$users;
        $resultData['pagination']['total_count']=$users->total();
        $resultData['pagination']['current_page']=$users->currentPage();
        $resultData['pagination']['per_page']=$users->perPage();
        $resultData['pagination']['total_page']=$users->lastPage();

        $result['success'] = true;
        $result['message'] = "Data fetched successfully.";
        $result['data'] = $resultData;
        return response()->json($result, 200);
    }

    public function userNewreq(Request $request)
    {
        
        $start   = $request->input('iDisplayStart', 0);   
        $length  = $request->input('iDisplayLength', 10); 
        $search  = $request->input('search', '');
        $sortOrder = $request->input('SortOrder', 'desc');
        $columnName = $request->input('columnName', ''); 
        $role=0;

        $users = User::getUserNewReq($role, $start, $length, $search, $columnName, $sortOrder);

        $resultData['users']=$users;
        $resultData['pagination']['total_count']=$users->total();
        $resultData['pagination']['current_page']=$users->currentPage();
        $resultData['pagination']['per_page']=$users->perPage();
        $resultData['pagination']['total_page']=$users->lastPage();

        $result['success'] = true;
        $result['message'] = "Data fetched successfully.";
        $result['data'] = $resultData;
        return response()->json($result, 200);
    }

    public function userRejected(Request $request)
    {

        $start   = $request->input('iDisplayStart', 0);   
        $length  = $request->input('iDisplayLength', 10); 
        $search  = $request->input('search', '');
        $sortOrder = $request->input('SortOrder', 'desc');
        $columnName = $request->input('columnName', ''); 
        $role=0;

        $users = User::getUserRejected($role, $start, $length, $search, $columnName, $sortOrder);

        $resultData['users']=$users;
        $resultData['pagination']['total_count']=$users->total();
        $resultData['pagination']['current_page']=$users->currentPage();
        $resultData['pagination']['per_page']=$users->perPage();
        $resultData['pagination']['total_page']=$users->lastPage();

        $result['success'] = true;
        $result['message'] = "Data fetched successfully.";
        $result['data'] = $resultData;
        return response()->json($result, 200);

    }

    public function mediatorApprove(Request $request)
    {

        $start   = $request->input('iDisplayStart', 0);   
        $length  = $request->input('iDisplayLength', 10); 
        $search  = $request->input('search', '');
        $sortOrder = $request->input('SortOrder', 'desc');
        $columnName = $request->input('columnName', ''); 
        $role=1;

        $users = User::getMediatorsApprove($role, $start, $length, $search, $columnName, $sortOrder);

        $resultData['users']=$users;
        $resultData['pagination']['total_count']=$users->total();
        $resultData['pagination']['current_page']=$users->currentPage();
        $resultData['pagination']['per_page']=$users->perPage();
        $resultData['pagination']['total_page']=$users->lastPage();

        $result['success'] = true;
        $result['message'] = "Data fetched successfully.";
        $result['data'] = $resultData;
        return response()->json($result, 200);
    }

    public function mediatorNewreq(Request $request)
    {
        
        $start   = $request->input('iDisplayStart', 0);   
        $length  = $request->input('iDisplayLength', 10); 
        $search  = $request->input('search', '');
        $sortOrder = $request->input('SortOrder', 'desc');
        $columnName = $request->input('columnName', ''); 
        $role=1;

        $users = User::getMediatorsNewReq($role, $start, $length, $search, $columnName, $sortOrder);

        $resultData['users']=$users;
        $resultData['pagination']['total_count']=$users->total();
        $resultData['pagination']['current_page']=$users->currentPage();
        $resultData['pagination']['per_page']=$users->perPage();
        $resultData['pagination']['total_page']=$users->lastPage();

        $result['success'] = true;
        $result['message'] = "Data fetched successfully.";
        $result['data'] = $resultData;
        return response()->json($result, 200);
    }

    public function mediatorRejected(Request $request)
    {
        $users = User::where("role", "=", 0)->where('is_deleted', 1)->orderBy('id', 'DESC')->get();

        $start   = $request->input('iDisplayStart', 0);   
        $length  = $request->input('iDisplayLength', 10); 
        $search  = $request->input('search', '');
        $sortOrder = $request->input('SortOrder', 'desc');
        $columnName = $request->input('columnName', ''); 
        $role=1;

        $users = User::getMediatorsRejected($role, $start, $length, $search, $columnName, $sortOrder);

        $resultData['users']=$users;
        $resultData['pagination']['total_count']=$users->total();
        $resultData['pagination']['current_page']=$users->currentPage();
        $resultData['pagination']['per_page']=$users->perPage();
        $resultData['pagination']['total_page']=$users->lastPage();

        $result['success'] = true;
        $result['message'] = "Data fetched successfully.";
        $result['data'] = $resultData;
        return response()->json($result, 200);

    }

    public function update(Request $request)
    {

         $validator = Validator::make($request->all(), [
            'id'   => 'required',
            'first_name'   => 'required|string|max:100',
            'last_name'    => 'required|string|max:100',
            'email'        => 'required|email|unique:users,email,' . $request->id,
            'mobile_number'=> 'required|string|max:10',
            'address'      => 'nullable|string|max:255',
            'pincode'      => 'nullable|string|max:20',
            'city'         => 'nullable|string|max:100',
            'state'        => 'nullable|string|max:100',
            'country'      => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {

                $errors = $validator->errors()->all(); 

                $result['success'] = false;
                $result['message'] = implode(', ', $errors);
                $result['error'] = $validator->errors();
                return response()->json($result, 422);
        }
        $user_id=$request->input('id');

        $user = User::find($user_id);
        if(empty($user)){

            $result['success'] = false;
            $result['message'] = "User data not found";
            $result['error'] = "User data not found";
            return response()->json($result, 404);

        }
    
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->mobile_number = $request->input('mobile_number');
        $user->organization = $request->input('organization');
        $user->country_code = $request->input('country_code');
        $user->address = $request->input('address');
        $user->address1 = $request->input('address1');
        $user->pincode = $request->input('pincode');
        $user->city = $request->input('city');
        $user->state = $request->input('state');
        $user->country = $request->input('country');

        if (isset($request->status)) {

            $user->isDone = $request->input('status');
        }

        if ($request->hasFile('signature_photo')) {
            
            $msg = "";
            $content = file_get_contents($request->signature_photo);
            if (preg_match('/\/JS|\/JavaScript|\/OpenAction/', $content)) {

                $msg = "PDF file contains restricted data , please check and re-upload.";
                $result['success'] = false;
                $result['message'] = $msg;
                $result['error'] = "Profile not updated";
                return response()->json($result, 200);

            }

            if ($user->role == 0) {
                if ($user->signature_photo != null) {

                    $oldFilePath = 'mediation/user/' . $request->input('id') . '/signature/' . $user->signature_photo;
                    Storage::disk('s3')->delete($oldFilePath);
                }
                $extension = $request->file('signature_photo')->getClientOriginalExtension();
                $name = 'User_Signature' . sprintf('%06d', $request->input('id')) . time() . '.' . $extension;
                $finalFilePath='mediation/user/' . $request->input('id') . '/signature/' . $name;
                Storage::disk('s3')->put($finalFilePath, file_get_contents($request->signature_photo));

            } else {

                if(empty($request->signature_photo) && $user->signature_photo == null){

                    $result['success'] = false;
                    $result['message'] = "Mediator signature required";
                    $result['error'] = "Mediator signature required";
                    return response()->json($result, 422);
                }

                if ($user->signature_photo != null) {

                   $oldFilePath = 'mediation/mediator/' . $request->input('id') . '/signature/' . $user->signature_photo;
                    Storage::disk('s3')->delete($oldFilePath);
                }

                $extension = $request->file('signature_photo')->getClientOriginalExtension();
                $name = 'Mediator_Signature' . sprintf('%06d', $request->input('id')) . time() . '.' . $extension;
                $finalFilePath='mediation/mediator/' . $request->input('id') . '/signature/' . $name;
                Storage::disk('s3')->put($finalFilePath, file_get_contents($request->signature_photo));

            }
        }
        if ($request->hasFile('profilePic')) {

            if ($user->profile_pic != null) {
                
                   $oldFilePath = 'mediation/mediator/' . $request->input('id') . '/profile/' . $user->signature_photo;
                    Storage::disk('s3')->delete($oldFilePath);
            }
            $extension = $request->file('profilePic')->getClientOriginalExtension();
            $profilename = 'Mediator_Profile_Pic' . sprintf('%06d', $request->input('id')) . time() . '.' . $extension;
            $finalFilePath='mediation/mediator/' . $request->input('id') . '/profile/' . $profilename;
            $s = Storage::disk('s3')->put($finalFilePath, file_get_contents($request->profilePic));
        }
        if (isset($name)) {
            $user->signature_photo = $name;
        }
        if (isset($profilename)) {
            $user->profile_pic = $profilename;
        }

        $user->save();

        if ($user->role == 1) {

            $area_of_specialization = $request->input('area_of_specialization');
            $years_of_experience = $request->input('years_of_experience');

            $isMedi = Mediation_Details::where("user_id", "=", $request->input('id'))->first();
            if (empty($isMedi)) {
                $mediation_details = new Mediation_Details();
            } else {
                $mediation_details = $isMedi;
            }
            $mediation_details->user_id = $request->input('id');
            $mediation_details->area_of_specialization = $area_of_specialization;
            $mediation_details->no_of_arbitrations = $request->input('no_of_arbitrations');
            $mediation_details->linked_in_profile_link = $request->input('linked_in_profile_link');
            $mediation_details->experience = $request->input('experience');
            $mediation_details->is_accept1 = $request->input('is_accept1');
            $mediation_details->is_accept2 = $request->input('is_accept2');
            $mediation_details->is_accept3 = $request->input('is_accept3');
            $mediation_details->filed1 = $request->input('field1');
            $mediation_details->filed2 = $request->input('field2');
            $mediation_details->filed3 = $request->input('field3');
            //$mediation_details->category = $request->input('category');
            $mediation_details->spoken_language = $request->input('spoken_language');
            $mediation_details->years_of_experience = $years_of_experience;
            $mediation_details->save();
        }

        $resultData['user_id'] = $user_id;
        $result['success'] = true;
        $result['message'] = "profile updated successfully.";
        $result['data'] = $resultData;
        return response()->json($result, 200);
    }

    public function deleteUser(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id'   => 'required'
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors()->all(); 

            $result['success'] = false;
            $result['message'] = implode(', ', $errors);
            $result['error'] = $validator->errors();
            return response()->json($result, 422);
        }

        $user_id=$request->input('user_id');

        $user = User::find($user_id);
        $user->is_deleted = 1;
        $user->save();

        $resultData['user_id'] = $user_id;

        $result['success'] = true;
        $result['message'] = "User deleted successfully.";
        $result['data'] = $resultData;
        return response()->json($result, 200);
    }

    public function ChangeRole(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id'   => 'required',
            'role'   => 'required'
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors()->all(); 

            $result['success'] = false;
            $result['message'] = implode(', ', $errors);
            $result['error'] = $validator->errors();
            return response()->json($result, 422);
        }

        $user_id= $request->input('user_id');

        $user = User::find($user_id);
        $user->role = $request->input('role');
        if ($user->save()) {

            $resultData['user_id'] = $user_id;

            $result['success'] = true;
            $result['message'] = "User role changed successfully.";
            $result['data'] = $resultData;
            return response()->json($result, 200);

        } else {

            $result['success'] = false;
            $result['message'] = "Failed to change user role. Please try again.";
            $result['error']   = "User role update failed";
            return response()->json($result, 422);
        };
    }

    public function statusChangeApprove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'   => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors()->all(); 

            $result['success'] = false;
            $result['message'] = implode(', ', $errors);
            $result['error'] = $validator->errors();
            return response()->json($result, 422);
        }

        $user_id=$request->input('user_id');
        $status=$request->input('status');

        $user = User::find($user_id);
        $user->status = $status;
        $d = [
            'event' => 'APPROVE',
            'userid' => $user_id,
        ];
        if ($user->role == 0 && $status == 1) {
            $err = SendGrid::send($d, $user->email, env('L23_USER_ACCOUNT_ACTIVATION', ''));
        } else if ($status == 1) {
            SendGrid::send($d, $user->email, env('L24_MEDIATOR_ACCOUNT_ACTIVATION', ''));
        }
        $user->save();


        if($status==1){

            $message="User has been approved successfully.";

        }else{

            $message="User has been rejected successfully.";
        }

        $resultData['user_id'] = $user_id;

        $result['success'] = true;
        $result['message'] = $message;
        $result['data'] = $resultData;
        return response()->json($result, 200);
    }

    public function statusChange(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id'   => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors()->all(); 

            $result['success'] = false;
            $result['message'] = implode(', ', $errors);
            $result['error'] = $validator->errors();
            return response()->json($result, 422);
        }

        $user_id=$request->input('user_id');
        $status=$request->input('status');

        $user = User::find($user_id);
        $user->isActive = $status;
        $user->save();


        if($status==1){

            $message="User has been activated successfully.";

        }else{

            $message="User has been deactivated successfully.";
        }

        $resultData['user_id'] = $user_id;

        $result['success'] = true;
        $result['message'] = $message;
        $result['data'] = $resultData;
        return response()->json($result, 200);
    }
    public function addNotes(Request $request){

        $validator = Validator::make($request->all(), [
            'user_id'   => 'required',
            'notes' => 'required'
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors()->all(); 

            $result['success'] = false;
            $result['message'] = implode(', ', $errors);
            $result['error'] = $validator->errors();
            return response()->json($result, 422);
        }

        $user_id=$request->input('user_id');
        $notes=$request->input('notes');


        $is_update = User::where('id', $user_id)->update(['notes' => $notes]);

        

        if($is_update){

            $resultData['user_id'] = $user_id;

            $result['success'] = true;
            $result['message'] = "Notes added successfully.";
            $result['data'] = $resultData;
            return response()->json($result, 200);

        }else{

            $result['success'] = false;
            $result['message'] = "Failed to add notes. Please try again.";
            $result['error'] = "Failed to add notes. Please try again.";
            return response()->json($result, 422);
        }
       
    }

    public function getuserdata($id, Request $request)
    {
        $user = User::find($id);
        if(!empty($user)){


            $resultData['user'] = $user;

            $result['success'] = true;
            $result['message'] = "Data fetch successfully.";
            $result['data'] = $resultData;
            return response()->json($result, 200);


        }else{

            $result['success'] = false;
            $result['message'] = "User data not found.";
            $result['error'] = "User data not found.";
            return response()->json($result, 422);
        }

    }

    public function getMediatordata($id, Request $request)
    {
        $user = User::select('users.*', 'mediation_details.user_id', 'mediation_details.area_of_specialization', 'mediation_details.no_of_arbitrations', 'mediation_details.linked_in_profile_link', 'mediation_details.experience', 'mediation_details.is_accept1', 'mediation_details.is_accept2', 'mediation_details.is_accept3', 'mediation_details.filed1', 'mediation_details.filed2', 'mediation_details.filed3', 'mediation_details.category', 'mediation_details.spoken_language', 'mediation_details.years_of_experience')
                    ->leftJoin("mediation_details", "mediation_details.user_id", "=", "users.id")
                    ->where("users.id", "=", $id)
                    ->first();
                    
        if(!empty($user)){


            $finaldata['userid'] = $id;
            $finaldata['first_name'] = $user->first_name;
            $finaldata['last_name'] = $user->last_name;
            $finaldata['email'] = $user->email;
            $finaldata['username'] = $user->username;
            $finaldata['mobile_number'] = $user->mobile_number;
            $finaldata['organization'] = $user->organization;
            $finaldata['address1'] = $user->address;
            $finaldata['address2'] = $user->address1;
            $finaldata['city'] = $user->city;
            $finaldata['pincode'] = $user->pincode;
            $finaldata['state'] = $user->state;
            $finaldata['country'] = $user->country;
            $finaldata['signature'] = $user->signature_photo;

            $finaldata['area_of_specialization'] = json_decode($user->area_of_specialization, true);
            $finaldata['no_of_arbitrations'] = $user->no_of_arbitrations;
            $finaldata['linked_in_profile_link'] = $user->linked_in_profile_link;
            $finaldata['experience'] = $user->experience;
            $finaldata['terms_condition1'] = $user->is_accept1;
            $finaldata['terms_condition2'] = $user->is_accept2;
            $finaldata['terms_condition3'] = $user->is_accept3;
            $finaldata['years_of_experience'] = $user->experience;
            $finaldata['spoken_language'] = json_decode($user->spoken_language);


            $resultData['user'] = $finaldata;

            $result['success'] = true;
            $result['message'] = "Data fetch successfully.";
            $result['data'] = $resultData;
            return response()->json($result, 200);


        }else{

            $result['success'] = false;
            $result['message'] = "Mediator data not found.";
            $result['error'] = "Mediator data not found.";
            return response()->json($result, 422);
        }

    }

    public function getAreaOfSpecialization(Request $request)
    {

        $area_of_specialization = DB::table('area_of_specialization')->get();
        if(!empty($area_of_specialization)){


            $resultData['area_of_specialization'] = $area_of_specialization;

            $result['success'] = true;
            $result['message'] = "Data fetch successfully.";
            $result['data'] = $resultData;
            return response()->json($result, 200);


        }else{

            $result['success'] = false;
            $result['message'] = "data not found.";
            $result['error'] = "data not found.";
            return response()->json($result, 422);
        }

    }
    
}
