<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MedCase;
use App\Models\InvoledUser;
use App\Models\Notification;
use App\Models\Batch;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $usersCount = 0;
        $allCasesCount = 0;
        $respondingPartiesCount = 0;
        $ongoingCount = 0;
        $resolvedCount = 0;
        $newCount = 0;
        $approveUsersCount = User::whereIn("role", [0])->where("status", 1)->where("is_deleted", 0)->count();
        $approveMediatorCount = User::whereIn("role", [1])->where("status", 1)->where("is_deleted", 0)->count();
        $unapproveUsersCount = User::whereIn("role", [0])->where("status", 0)->where("is_deleted", 0)->count();
        $unapproveMediatorCount = User::whereIn("role", [1])->where("status", 0)->where("is_deleted", 0)->count();
        $allCasesCount = MedCase::count();
        // $data = MedCase::get();
        // $respondingPartiesCount = 0;
        // foreach($data as $value){
        //     // dd($value);
        //     $respondingParties = InvoledUser::where('userPlanId', $value->id)->where('isClaimant', "<>", 0)->where('joinCode', null)->where('isOnboarded', 1)->first();
        //     if(isset($respondingParties)) {
        //         $respondingPartiesCount++;
        //     }
        // }
        // $respondingPartiesCount = InvoledUser::where('isClaimant', "<>", 0)->count();
        $respondingPartiesCount = InvoledUser::where('isClaimant', "<>", 0)->where('joinCode', null)->where('isOnboarded', 1)->get()->groupBy('userPlanId');
        // $respondingPartiesCount = MedCase::join('user_involved_in_agreement', 'user_involved_in_agreement.userPlanId', '=', 'mediation_case.id')
        //             ->where('user_involved_in_agreement.isClaimant', "<>", 0)->where('user_involved_in_agreement.joinCode', null)->where('user_involved_in_agreement.isOnboarded', 1)
        //             ->groupBy('') count();
        // $respondingPartiesCount = MedCase::whereHas('user_involed', function ($q){
        //     $q->where('isClaimant', '<>', 0)->where('joinCode', null)->where('isOnboarded', 1);
        // })->count();
        $respondingPartiesCount = count($respondingPartiesCount);
        // dd($respondingPartiesCount);
        $resolvedCount = MedCase::where("case_status", 6)->where("confirm_status", 2)->count();
        $unresolvedCount = MedCase::where("case_status", 7)->where("confirm_status", 2)->count();
        $WithdrawnCount = MedCase::where("case_status", 5)->where("confirm_status", 2)->count();

        // $resolvedCount = MedCase::leftjoin('mediation_status_logs', 'mediation_status_logs.mediation_case_id', '=', 'mediation_case.id')->where("mediation_status_logs.status", 6)->where("mediation_case.confirm_status", 2)->count();
        // $unresolvedCount = MedCase::leftjoin('mediation_status_logs', 'mediation_status_logs.mediation_case_id', '=', 'mediation_case.id')->where("mediation_status_logs.status", 7)->where("mediation_case.confirm_status", 2)->count();
        // $WithdrawnCount = MedCase::leftjoin('mediation_status_logs', 'mediation_status_logs.mediation_case_id', '=', 'mediation_case.id')->where("mediation_status_logs.status", 5)->where("mediation_case.confirm_status", 2)->count();

        $rejectedCount = MedCase::where("confirm_status", 3)->count();
        $ongoingCount = MedCase::where("confirm_status", 1)->count();
        $newCount = MedCase::where("confirm_status", 0)->count();
        return view('admin.dashboard', compact('approveUsersCount', 'WithdrawnCount', 'unresolvedCount', 'rejectedCount', 'approveMediatorCount', 'unapproveMediatorCount', 'unapproveUsersCount', 'allCasesCount', 'respondingPartiesCount', 'resolvedCount', 'ongoingCount', 'newCount'));

        // return view('admin.dashboard', compact('usersCount', 'allCasesCount', 'respondingPartiesCount', 'resolvedCount', 'ongoingCount', 'newCount'));
    }

    public function profile()
    {

        $loginUser = Auth::user()->id;
        $profileData = User::select('*')
            // ->leftJoin("mediation_details", "mediation_details.user_id", "=", "users.id")
            ->where('id', $loginUser)->first();
        // find($loginUser);
        // $mediation_details
        return view('admin.profile', compact('profileData'));
    }

    public function changePassword($id)
    {

        $data = User::find($id);
        return view('admin.changePassword', compact('data'));
    }

    public function updateProfile($id, Request $request)
    {

        // dd($request->all());

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
            return redirect('admin/profile')->with('key', "Password Update Succesfully");
        }



        // $valid =  $request->validate([
        //         'firstName' => ['required'],
        //         'lastName' => ['required'],
        //         'email' => ['email','required','unique:users'],
        //         'mobile_number' => ['unique:users'],
        //     ],
        //     [
        //         'firstName.required'=>'first name cant empty*',
        //         'lastName.required'=>'Last name cant empty*',
        //         'email.email'=>'invalid email address*',
        //         'email.required'=>'Please Enter Email*',
        //         'email.unique'=>'This Email already used*',
        //         'mobile_number.unique'=>'This Mobile No. already used*',
        //     ]
        //     );
        // dd($valid);

        $dataToUpdate = [
            'first_name' => ucfirst($request->firstName),
            'last_name' => ucfirst($request->lastName),
            'email' => $request->email,
            'mobile_number' => $request->mobile,
            'username' => $request->username,
        ];

        // dd($dataToUpdate);

        User::where('id', $id)->update($dataToUpdate);
        return redirect('admin/profile')->with('key', "Profile Updated Succesfully");
    }

    public function Notification()
    {
        $view = Notification::where('view', 0)->get();
        foreach ($view as $item) {
            $item->view = 1;
            $item->save();
        }
        $data = Notification::notificationData();
        // dd($data);
        return view('admin.case.notification', compact('data'));
    }

    public function itmNotification(){
        $batchName = Batch::get();
        return view('admin.case.itmnotification', compact("batchName"));
    }

    public function closeCaseNotification(){
        $batchName = Batch::get();
        return view('admin.case.closecasenotification', compact("batchName"));
    }

    public function sessionScheduleNotification(){
        $batchName = Batch::get();
        return view('admin.case.sessionschedulenotification', compact("batchName"));
    }

    public function bulkUploadNotification(){
        $batchName = Batch::get();
        return view('admin.case.bulkuploadnotification', compact("batchName"));
    }



    // check vapt file content
    public function checkPdfContent(Request $request){
    //   / dd($request->all());
        $file = $request['signature'];
        
        $content = file_get_contents($file);
        if (preg_match('/\/JS|\/JavaScript|\/OpenAction|XSS/', $content)) {
            $msg = "File contains restricted data , please check and re-upload.<br>";
            return json_encode(['code' => 200, 'response' => 'err', 'msg' => $msg]);
        } else {
            return json_encode(['code' => 200, 'response' => 'success']);
        }
    }
    // check vapt file content
}
