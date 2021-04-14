<?php

namespace App\Http\Controllers\Mediator;

use Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\InvoledUser;
use DB;
class DashboardController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        return view('mediator.dashboard');
    }
     public function newrequest() {
         return view('mediator.newrequest');
     }

    public function newjson() {

        $loginUser = Auth::user()->id;
        $newrequestData = DB::table('mediators_mediation_cases_status')
            // ->select('mediation_case.*')
            ->join('users', 'users.id', '=', 'mediators_mediation_cases_status.mediator_id')
            ->join('mediation_case', 'mediation_case.id', '=', 'mediators_mediation_cases_status.mediation_case_id')
            ->join('user_involved_in_agreement', 'user_involved_in_agreement.id', '=', 'mediation_case.userid')
            ->where('users.id', '=',$loginUser)
            ->get();
            // dd($newrequestData);
            $arraydata=array();

        foreach($newrequestData as  $d){
             $arraydata[]=[
                "id"=>$d->id,
                "party" =>InvoledUser::select('name','isOnboarded')->where(['userPlanid'=>$d->id])->get(), 
                "comments" => "tesr", 
                "status"=>$d->status,
             ]; 
        }


        return response()->json(["data" => $arraydata]);
        // return view('mediator.newrequest',compact('newrequestData'));



    }
    public function ongoing() {
        return view('mediator.ongoing');
    }
    public function closed() {
        return view('mediator.closed');
    }
    public function profile() {

        $loginUser = Auth::user()->id;
        $profileData = User::find($loginUser);
        return view('mediator.profile', compact('profileData'));
        
    }
    public function users() {
        return view('mediator.users');
    }

    public function updateProfile(Request $request,$id) {
        // $name = $request->input('stud_name');
        echo "string";
        // DB::update('update student set name = ? where id = ?',[$name,$id]);
        // echo "Record updated successfully.<br/>";
        // echo '<a href = "/edit-records">Click Here</a> to go back.';
    }

    public function statusChange(Request $request) {
        // $user = User::find($request->id);
        // $user->status = $request->status;
        // $user->save();
        // return response()->json(["msg" => "staus Update"]);

    DB::table('mediators_mediation_cases_status')
            ->where('mediator_id', 3)
            ->update(['status' =>$request->status]);
    return response()->json(["msg" => "staus Update"]);
    }


}
