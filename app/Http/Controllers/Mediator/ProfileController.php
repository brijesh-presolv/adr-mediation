<?php

namespace App\Http\Controllers\Mediator;

use Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Mediation_Details;
use App\Models\AreaOfSpecialization;
use Illuminate\Http\Request;
use DB;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        
    }

    public function profileUpdate() {
        $areaOfSpecialization = AreaOfSpecialization::all();
        $medi = Mediation_Details::where("user_id", "=", Auth::user()->id)->first();
        return view('mediator.user.profile', compact('areaOfSpecialization', 'medi'));
    }

    public function profileSave(Request $request) {
        $user = User::find($request->id);
        $user->first_name = ucfirst($request->first_name);
        $user->last_name = ucfirst($request->last_name);
        $user->mobile_number = $request->mobile_number;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->address1 = $request->address1;
        $user->pincode = $request->pincode;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->country = $request->country;
        $user->isDone = 1;
        if($request->hasFile('signature')) {
            if($user->signature_photo != null) {
                Storage::delete('public/mediator/' . $request->id . '/signature/' . $user->signature_photo);
            }
            $extension = $request->file('signature')->getClientOriginalExtension();
            $name = 'Mediator_Signature' . sprintf('%06d', $request->id) . time() . '.' . $extension;
            $s = Storage::put('public/mediator/' . $request->id . '/signature/' . $name, file_get_contents($request->signature));
        }
        if($request->hasFile('profilePic')) {
            if($user->profile_pic != null) {
                Storage::delete('public/mediator/' . $request->id . '/profile/' . $user->profile_pic);
            }
            $extension = $request->file('profilePic')->getClientOriginalExtension();
            $profilename = 'Mediator_Profile_Pic' . sprintf('%06d', $request->id) . time() . '.' . $extension;
            $s = Storage::put('public/mediator/' . $request->id . '/profile/' . $profilename, file_get_contents($request->profilePic));
        } 
        if(isset($name)) {
            $user->signature_photo = $name;
        }
        if(isset($profilename)) {
            $user->profile_pic = $profilename;
        }
        $user->save();
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

        //Auth::logout();
        return redirect()->route('mediator.profile.firstupdate')->with('message', 'success|please waiting for approval.');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function updateProfile($id, Request $request) {

        if ($request->an == 'cp') {

            $request->validate([
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
            return redirect('mediator/profile')->with('key', "Password update succesfully");
        }



        $request->validate([
            'firstName' => ['required'],
            'lastName' => ['required'],
            'email' => ['email', 'required'],
                ],
                [
                    'firstName.required' => 'first name cant empty*',
                    'lastName.required' => 'Last name cant empty*',
                    'email.email' => 'invalid email address*',
                    'email.required' => 'Please Enter Email*',
                ]
        );


        $dataToUpdate = [
            'first_name' => ucfirst($request->firstName),
            'last_name' => ucfirst($request->lastName),
            'email' => $request->email,
            'mobile_number' => $request->mobile,
            'organization' => $request->orgName,
            'username' => $request->username,
        ];

        $exp = [
            'experience' => $request->expName,
        ];

        User::where('id', $id)->update($dataToUpdate);
        Mediation_Details::where('user_id', $id)->update($exp);
        return redirect('mediator/profile')->with('key', "profile update succesfully");
    }

    // * Show the application dashboard.
    // *
    // * @return \Illuminate\Contracts\Support\Renderable
    // */
    public function changePassword($id) {

        $data = User::find($id);
        return view('mediator.changePassword', compact('data'));
    }

}
