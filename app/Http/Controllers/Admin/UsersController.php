<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Mediation_Details;
use App\Models\AreaOfSpecialization;

class UsersController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        
    }

    /**
     * Show the application users.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($role = "user") {
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
    public function statusChange(Request $request) {
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
    public function edit(Request $request) {
        $user = User::findOrFail($request->id);
        $areaOfSpecialization = AreaOfSpecialization::all();
        $medi = Mediation_Details::where("user_id", "=", $request->id)->first();
        return view('admin.users.edit', compact("user", "medi", "areaOfSpecialization"));
    }

    /**
     * Show the application users.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update(Request $request) {
        $user = User::find($request->id);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
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
    public function json($role = 0) {
        $users = User::where("role", "=", $role)->get();
        return response()->json(["data" => $users]);
    }

}
