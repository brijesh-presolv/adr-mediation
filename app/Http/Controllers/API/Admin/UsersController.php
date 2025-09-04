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
        $search  = $request->input('sSearch', '');
        $sortOrder = $request->input('SortOrder', 'desc');
        $columnName = $request->input('columnName', ''); 
        $role=0;

        $users = User::getUserApprove($start, $length, $search, $columnName, $sortOrder);

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
        $search  = $request->input('sSearch', '');
        $sortOrder = $request->input('SortOrder', 'desc');
        $columnName = $request->input('columnName', ''); 
        $role=0;

        $users = User::getUserNewReq($start, $length, $search, $columnName, $sortOrder);

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

    public function userUnapprove(Request $request)
    {
        $users = User::where("role", "=", 0)->where('is_deleted', 1)->orderBy('id', 'DESC')->get();

        $start   = $request->input('iDisplayStart', 0);   
        $length  = $request->input('iDisplayLength', 10); 
        $search  = $request->input('sSearch', '');
        $sortOrder = $request->input('SortOrder', 'desc');
        $columnName = $request->input('columnName', ''); 
        $role=0;

        $users = User::getUserUnapprove($start, $length, $search, $columnName, $sortOrder);

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

        $request->validate([
            'signature' => 'nullable|mimes:jpg,jpeg,png|max:4048',  // 2MB size limit
            'profilePic' => 'nullable|mimes:jpg,jpeg,png|max:4048',  // 2MB size limit
        ]);

         $validator = Validator::make($request->all(), [

            'first_name'   => 'required|string|max:100',
            'last_name'    => 'required|string|max:100',
            'email'        => 'required|email|unique:users,email,' . $request->id,
            'username'     => 'required|string|max:100|unique:users,username,' . $request->id,
            'mobile_number'=> 'required|string|max:15|unique:users,mobile_number,' . $request->id,
            'organization' => 'nullable|string|max:255',
            'country_code' => 'nullable|string|max:10',
            'address'      => 'nullable|string|max:255',
            'address1'     => 'nullable|string|max:255',
            'pincode'      => 'nullable|string|max:20',
            'city'         => 'nullable|string|max:100',
            'state'        => 'nullable|string|max:100',
            'country'      => 'nullable|string|max:100',
            'signature'    => 'nullable|mimes:jpg,jpeg,png|max:4048',  
            'profilePic'   => 'nullable|mimes:jpg,jpeg,png|max:4048',
        ]);

    if ($validator->fails()) {

            $errors = $validator->errors()->all(); 

            $result['success'] = false;
            $result['message'] = $implode(', ', $errors);
            $result['error'] = $validator->errors();
            return response()->json($result, 422);
        }

        $user = User::find($request->id);
        $user->first_name = ucfirst($request->first_name);
        $user->last_name = ucfirst($request->last_name);
        $user->email = $request->email;
        $user->username = $request->username;
        $user->mobile_number = $request->mobile_number;
        $user->organization = $request->organization;
        $user->country_code = $request->country_code;
        $user->address = $request->address;
        $user->address1 = $request->address1;
        $user->pincode = $request->pincode;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->country = $request->country;
        if (isset($request->status)) {
            $user->isDone = $request->status;
        }
        if ($request->hasFile('signature')) {
            
            $msg = "";
            $content = file_get_contents($request->signature);
            if (preg_match('/\/JS|\/JavaScript|\/OpenAction/', $content)) {
                $msg = "PDF file contains restricted data , please check and re-upload.";
                return $msg;

            }

            if ($user->role == 0) {
                if ($user->signature_photo != null) {
                    Storage::disk('local')->delete('public/user/' . $request->id . '/signature/' . $user->signature_photo);
                }
                $extension = $request->file('signature')->getClientOriginalExtension();
                $name = 'User_Signature' . sprintf('%06d', $request->id) . time() . '.' . $extension;
                Storage::disk('local')->put('public/user/' . $request->id . '/signature/' . $name, file_get_contents($request->signature));
            } else {
                if ($user->signature_photo != null) {
                    Storage::disk('local')->delete('public/mediator/' . $request->id . '/signature/' . $user->signature_photo);
                }
                $extension = $request->file('signature')->getClientOriginalExtension();
                $name = 'Mediator_Signature' . sprintf('%06d', $request->id) . time() . '.' . $extension;
                Storage::disk('local')->put('public/mediator/' . $request->id . '/signature/' . $name, file_get_contents($request->signature));
            }
            // if($user->signature_photo != null) {
            //     Storage::delete('public/mediator/' . $request->id . '/signature/' . $user->signature_photo);
            // }
            // $extension = $request->file('signature')->getClientOriginalExtension();
            // $name = 'Mediator_Signature' . sprintf('%06d', $request->id) . time() . '.' . $extension;
            // Storage::put('public/mediator/' . $request->id . '/signature/' . $name, file_get_contents($request->signature));
        }
        if ($request->hasFile('profilePic')) {
            if ($user->profile_pic != null) {
                Storage::disk('local')->delete('public/mediator/' . $request->id . '/profile/' . $user->profile_pic);
            }
            $extension = $request->file('profilePic')->getClientOriginalExtension();
            $profilename = 'Mediator_Profile_Pic' . sprintf('%06d', $request->id) . time() . '.' . $extension;
            $s = Storage::disk('local')->put('public/mediator/' . $request->id . '/profile/' . $profilename, file_get_contents($request->profilePic));
        }
        if (isset($name)) {
            $user->signature_photo = $name;
        }
        if (isset($profilename)) {
            $user->profile_pic = $profilename;
        }



        $user->save();
        if ($user->role == 1) {
            $isMedi = Mediation_Details::where("user_id", "=", $request->id)->first();
            if (empty($isMedi)) {
                $mediation_details = new Mediation_Details();
            } else {
                $mediation_details = $isMedi;
            }
            $mediation_details->user_id = $request->id;
            $mediation_details->area_of_specialization = json_encode($request->area_of_specialization);
            $mediation_details->no_of_arbitrations = $request->no_of_arbitrations;
            $mediation_details->linked_in_profile_link = $request->linked_in_profile_link;
            $mediation_details->experience = $request->experience;
            $mediation_details->is_accept1 = $request->is_accept1;
            $mediation_details->is_accept2 = $request->is_accept2;
            $mediation_details->is_accept3 = $request->is_accept3;
            $mediation_details->filed1 = $request->field1;
            $mediation_details->filed2 = $request->field2;
            $mediation_details->filed3 = $request->field3;
            $mediation_details->save();
        }
        return redirect()->route("admin.users.list");
    }
}
