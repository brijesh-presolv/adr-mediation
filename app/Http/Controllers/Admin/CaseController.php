<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MedCase;
use App\Models\User;
use App\Models\Mediators_mediation_cases_status;

class CaseController extends Controller {

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
    public function index($confirm_status = "newrequest") {
        $users = User::where("role", "=", 1)->get();
        if ($confirm_status == "rejectrequest") {
            $confirm_status = 2;
        } else if ($confirm_status == "confirmrequest") {
            $confirm_status = 1;
        } else {
            $confirm_status = 0;
        }
        return view('admin.case.index', compact("confirm_status", "users"));
    }

    public function confirmStatus(Request $request) {
        $user = MedCase::find($request->id);
        $user->confirm_status = 1;
        $user->save();
        return response()->json(["msg" => "Category Name Update"]);
    }

    public function rejectStatus(Request $request) {
        $user = MedCase::find($request->id);
        $user->confirm_status = 2;
        $user->save();
        return response()->json(["msg" => "Category Name Update"]);
    }

    public function midaterAdd(Request $request) {
        return Mediators_mediation_cases_status::create([
                    'mediator_id' => $request->midater,
                    'mediation_case_id' => $request->id,
                    'status' => 0,
                    'user_type' => 1,
        ]);
        return response()->json(["msg" => "midater Added"]);
    }

    public function json($role = 0) {
        $case = MedCase::select("user_involved_in_agreement.username", "mediation_case.*","mediators_mediation_cases_status.mediator_id")
                ->join("user_involved_in_agreement", "user_involved_in_agreement.userPlanId", "=", "mediation_case.id")
                ->leftJoin("mediators_mediation_cases_status", "mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id")
                ->where("mediation_case.confirm_status", "=", $role)
                ->where("user_involved_in_agreement.isClaimant", "=", 1)
                ->get();
        return response()->json(["data" => $case]);
    }

}
