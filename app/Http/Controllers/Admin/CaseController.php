<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MedCase;
use App\Models\InvoledUser;
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
    public function index() {
        $users = User::where("role", "=", 1)->get();
        $confirm_status = 0;
        return view('admin.case.index', compact("confirm_status", "users"));
    }

    public function ongoingRequest() {
        $users = User::where("role", "=", 1)->get();
        $confirm_status = 1;
        return view('admin.case.ongoing', compact("confirm_status", "users"));
    }

    public function closedRequest() {
        $users = User::where("role", "=", 1)->get();
        $confirm_status = 2;
        return view('admin.case.index', compact("confirm_status", "users"));
    }

    public function rjectedRequest() {
        $users = User::where("role", "=", 1)->get();
        $confirm_status = 3;
        return view('admin.case.rjected', compact("confirm_status", "users"));
    }

    public function confirmStatus(Request $request) {
        $user = MedCase::find($request->id);
        $user->confirm_status = 1;
        $user->save();
        return response()->json(["msg" => "Onging Case"]);
    }

    public function closeStatus(Request $request) {
        $user = MedCase::find($request->id);
        $user->confirm_status = 2;
        $user->save();
        return response()->json(["msg" => "Closed Case"]);
    }

    public function rejectStatus(Request $request) {
        $user = MedCase::find($request->id);
        $user->confirm_status = 3;
        $user->save();
        return response()->json(["msg" => "Rejected Case"]);
    }

    public function midaterAdd(Request $request) {
        $data = Mediators_mediation_cases_status::where("mediation_case_id", "=", $request->id)
                ->where(function($q) {
                    $q->where("status", "=", 0)
                    ->orWhere("status", "=", 1);
                })
                ->count();
        if ($data == 0) {
            Mediators_mediation_cases_status::create([
                'mediator_id' => $request->midater,
                'mediation_case_id' => $request->id,
                'status' => 0,
                'user_type' => 1,
            ]);
        } else {
            $MedCaseStatus = Mediators_mediation_cases_status::where(function($q) {
                        $q->where("status", "=", 0)
                        ->orWhere("status", "=", 1);
                    })
                    ->where("mediation_case_id", "=", $request->id)
                    ->first();
            $MedCaseStatus->mediator_id = $request->midater;
            $MedCaseStatus->status = 0;
            $MedCaseStatus->save();
        }
        return response()->json(["msg" => "midater Added"]);
    }

    public function json($role = 0) {
        $cases = MedCase::select("mediation_case.*", "users.username as mediator_username", "mediators_mediation_cases_status.mediator_id as mediator_id", "mediators_mediation_cases_status.status as mediator_status")
                ->leftJoin("mediators_mediation_cases_status", "mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id")
                ->leftJoin("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                ->where("mediation_case.confirm_status", "=", $role)
                ->get();
        $arraydata = array();
        foreach ($cases as $d) {
            $arraydata[] = [
                "date" => date('d-m-Y', strtotime($d->created_at)),
                "case" => $d,
                "party" => InvoledUser::select('name', 'isOnboarded')->where(['userPlanid' => $d->id])->get(),
            ];
        }
        return response()->json(["data" => $arraydata]);
    }

}
