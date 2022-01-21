<?php

namespace App\Http\Controllers\User;

use Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use DB;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }



    public function profile()
    {

        $loginUser = Auth::user()->id;
        $profileData = User::find($loginUser);
        return view('user.profile', compact('profileData'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function updateProfile($id, Request $request)
    {

        if ($request->an == 'cp') {

            $request->validate(
                [
                    'current_password' => ['required', new MatchOldPassword],
                    'new_password' => ['required'],
                    'new_confirm_password' => ['same:new_password', 'required'],
                ],
                [
                    'current_password.required' => 'Enter Current Password*',
                    'new_password.required' => 'Enter new Password*',
                    'new_confirm_password.required' => 'Enter Confirm Password*',
                    'new_confirm_password.same' => 'New password is not matched with confirm password please re-enter*',
                ],
            );

            User::find(auth()->user()->id)->update(['password' => Hash::make($request->new_password)]);
            return redirect('user/profile')->with('key', "Password update succesfully");
        }



        $request->validate(
            [
                'firstName' => ['required'],
                'lastName' => ['required'],
                'email' => ['email', 'required', 'unique:users,email,'.$id],
                'mobile' => ['required', 'unique:users,mobile_number,'.$id],
                'username' => ['required', 'unique:users,username,'.$id]
            ],
            [
                'firstName.required' => 'Please Enter First Name*',
                'lastName.required' => 'Please Enter Last Name*',
                'email.email' => 'invalid email address*',
                'email.required' => 'Please Enter Email*',
                'email.unique' => 'This email already taken*',
                'mobile.required' => 'Please Enter Number*',
                'mobile.unique' => 'This number already taken*',
                'username.unique' => 'This username already taken*',
                'username.required' => 'Please Enter Username*',
            ]
        );

        // dd("hello");


        $dataToUpdate = [
            'first_name' => ucfirst($request->firstName),
            'last_name' => ucfirst($request->lastName),
            'email' => $request->email,
            'mobile_number' => $request->mobile,
            'organization' => $request->orgName,
            'address' => $request->address,
            'address1' => $request->address1,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'pincode' => $request->pincode,
            'username' => $request->username,
        ];

        $user = User::where('id', $id)->update($dataToUpdate);
        if ($user) {
            return redirect('user/profile')->with('key', "Profile Updated Succesfully");
        }
    }

    // * Show the application dashboard.
    // *
    // * @return \Illuminate\Contracts\Support\Renderable
    // */
    public function changePassword($id)
    {

        $data = User::find($id);
        return view('user.changePassword', compact('data'));
    }
}
