<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

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
    public function index($role="user") {
        if($role=="mediator"){
            $role=1;
        }else{
            $role=0;
        }
        return view('admin.users.user',compact("role"));
    }

    /**
     * Show the application users.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function statusChange(Request $request) {
        $user = User::find($request->id);
        $user->status = $request->status;
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
        return view('admin.users.edit', compact("user"));
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
        return redirect()->route("admin.users.list");
    }

    /**
     * Show the application users.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function json($role=0) {
        $users = User::where("role","=",$role)->get();
        return response()->json(["data" => $users]);
    }

}
