<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Mediation_Details;
use App\Models\AreaOfSpecialization;
use App\Http\Helpers\SendGrid;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application users.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($role = "user")
    {
        if ($role == "mediator") {
            $role = 1;
        } else {
            $role = 0;
        }
        return view('admin.users.user', compact("role"));
    }

    /**
     * Show the application users.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function statusChangeApprove(Request $request)
    {
        $user = User::find($request->id);
        $user->status = $request->status;
        $d = [
            'event' => 'APPROVE',
            'userid' => $request->id,
        ];
        if ($user->role == 0 && $request->status == 1) {
            $err = SendGrid::send($d, $user->email, env('L23_USER_ACCOUNT_ACTIVATION', ''));
        } else if ($request->status == 1) {
            SendGrid::send($d, $user->email, env('L24_MEDIATOR_ACCOUNT_ACTIVATION', ''));
        }

        /********** Commented for no mendate signature field  ********/
        // if($user->signature_photo != ''){
        //     $user->save();
        //     $signature_status = 1;
        // } else {
        //     $signature_status = 0;
        // }
        /********** Commented for no mendate signature field  ********/

        $user->save();
        
        /********** Commented for no mendate signature field  ********/
        //return response()->json(["msg" => "Category Name Update", "signature_status" => $signature_status]);
        /********** Commented for no mendate signature field  ********/

        return response()->json(["msg" => "Category Name Update"]);
    }

    /**
     * Show the application users.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function statusChange(Request $request)
    {
        $user = User::find($request->id);
        $user->isActive = $request->status;
        $user->save();
        return response()->json(["msg" => "Category Name Update"]);
    }

    /**
     * Show the application users.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id, Request $request)
    {
        $user = User::findOrFail($id);
        $areaOfSpecialization = AreaOfSpecialization::all();
        $medi = Mediation_Details::where("user_id", "=", $id)->first();
        return view('admin.users.edit', compact("user", "medi", "areaOfSpecialization"));
    }

    /**
     * Show the application users.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update(Request $request)
    {
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

    /**
     * Show the application users.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function jsonApprove($role = 0)
    {
        $users = User::where("role", "=", $role)->where('status', 1)->where('is_deleted', 0)->get();
        return response()->json(["data" => $users]);
    }

    public function jsonNewreq($role = 0)
    {
        $users = User::where("role", "=", $role)->where('status', 0)->where('is_deleted', 0)->get();
        return response()->json(["data" => $users]);
    }

    public function jsonUnapprove($role = 0)
    {
        $users = User::where("role", "=", $role)->where('is_deleted', 1)->get();
        return response()->json(["data" => $users]);
    }

    public function deleteUser(Request $request)
    {
        $user = User::find($request->user_id);
        // dd($user);
        $user->is_deleted = 1;
        $user->save();
        return true;
    }

    public function ChangeRole(Request $request)
    {
        $user = User::find($request->userId);
        $user->role = $request->role;
        if ($user->save()) {
            return json_encode(["message" => "success"]);
        } else {
            return json_encode(["message" => "error"]);
        };
    }
}
