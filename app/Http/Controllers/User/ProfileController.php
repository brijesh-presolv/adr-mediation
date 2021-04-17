<?php

namespace App\Http\Controllers\User;

use Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use DB;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        
    }



    public function profile() {

        $loginUser = Auth::user()->id;
        $profileData = User::find($loginUser);
        return view('user.profile', compact('profileData'));
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function updateProfile($id,Request $request) {
        
        if($request->an == 'cp'){

            $request->validate([
                    'current_password' => ['required', new MatchOldPassword],
                    'new_password' => ['required'],
                    'new_confirm_password' => ['same:new_password','required'],
                ],
                [
                    'current_password.required'=>'Enter Current Password*',    
                    'new_password.required'=>'Enter new Password*',    
                    'new_confirm_password.required'=>'Enter Confirm Password*',    
                    'new_confirm_password.same'=>'New password is not matched with confirm password please re-enter*',    
                ],
                );

              User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
                return redirect('user/profile')->with('key', "Password update succesfully");
        }



         $request->validate([
                'firstName' => ['required'],
                'lastName' => ['required'],
                'email' => ['email','required'],
            ],
            [
                'firstName.required'=>'first name cant empty*',    
                'lastName.required'=>'Last name cant empty*',    
                'email.email'=>'invalid email address*',     
                'email.required'=>'Please Enter Email*',     
            ]
            );

        
        $dataToUpdate = [
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'email' => $request->email,
            'mobile_number' => $request->mobile,
            'organization' => $request->orgName,
            'username' => $request->username,
        ];

        User::where('id',$id)->update($dataToUpdate);
        return redirect('user/profile')->with('key', "profile updated succesfully");


    }

     // * Show the application dashboard.
     // *
     // * @return \Illuminate\Contracts\Support\Renderable
     // */
    public function changePassword($id) {
        
        $data = User::find($id);
         return view('user.changePassword',compact('data'));

    }


}
















