<?php

namespace App\Http\Controllers\User;

use Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use DB;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Session;

class ProfileController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            $userdata = User::getUserdetails(Auth::user()->id);
            if ($userdata->address == '' or $userdata->city == '' or $userdata->pincode == '' or $userdata->state == '' or $userdata->country == '') {
               // if ($_SERVER['REQUEST_URI'] != "/user/profile") {
                    Session::put('force', 1);
                   // header("Location: ../user/profile");
                   // exit();
                //}
            
            } else {
            }
            return $next($request);
           
        });
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
                    'signature' => 'nullable|mimes:jpg,jpeg,png|max:4048',  // 2MB size limit
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
                'mobile' => ['required'],
                'username' => ['required', 'unique:users,username,'.$id]
            ],
            [
                'firstName.required' => 'Please Enter First Name*',
                'lastName.required' => 'Please Enter Last Name*',
                'email.email' => 'invalid email address*',
                'email.required' => 'Please Enter Email*',
                'email.unique' => 'This email already taken*',
                'mobile.required' => 'Please Enter Number*',
                'username.unique' => 'This username already taken*',
                'username.required' => 'Please Enter Username*',
            ]
        );

        $request->validate([
            'signature' => 'nullable|mimes:jpg,jpeg,png|max:4048',  // 2MB size limit
            'profilePic' => 'nullable|mimes:jpg,jpeg,png|max:4048',  // 2MB size limit
        ]);

        // dd("hello");


        $dataToUpdate = User::find($id);
        // dd("hello");
        $dataToUpdate->first_name = ucfirst($request->firstName);
        $dataToUpdate->last_name = ucfirst($request->lastName);
        $dataToUpdate->email = $request->email;
        $dataToUpdate->mobile_number = $request->mobile;
        $dataToUpdate->organization = $request->orgName;
        $dataToUpdate->address = $request->address;
        $dataToUpdate->address1 = $request->address1;
        $dataToUpdate->city = $request->city;
        $dataToUpdate->state = $request->state;
        $dataToUpdate->country = $request->country;
        $dataToUpdate->pincode = $request->pincode;
        $dataToUpdate->username = $request->username;
        // dd($request->file('signature'));
        if($request->hasFile('signature')) {

            if($dataToUpdate->signature_photo != null) {
                Storage::disk('local')->delete('public/user/' . $request->id . '/signature/' . $dataToUpdate->signature_photo);
            }
            $extension = $request->file('signature')->getClientOriginalExtension();
            $name = 'User_Signature' . sprintf('%06d', $request->id) . time() . '.' . $extension;
            Storage::disk('local')->put('public/user/' . $request->id . '/signature/' . $name, file_get_contents($request->signature));
            $dataToUpdate->signature_photo = $name;
        }

        // $user = User::where('id', $id)->update($dataToUpdate);
        if($dataToUpdate->address == '' or $dataToUpdate->city == '' or $dataToUpdate->state == '' or $dataToUpdate->pincode == '' or $dataToUpdate->country == '') {
            $dataToUpdate->save();
            
            Session::put('force', 1);
            return redirect('user/profile')->with('key', "Please update profile to continue.");
        } else {
            if ($dataToUpdate->save()) {
                
                    Session::put('force', 0);
                    return redirect('user/profile')->with('key', "Profile Updated Succesfully");
                
            }
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
