<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MedCase;
use App\Models\Mediation_status_log;
use App\Models\Mediation_case_comment;
use App\Models\InvoledUser;
use App\Models\User;
use App\Models\Mediators_mediation_cases_status;
use DB;
use Auth;

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
        $confirm_status = 2;
        return view('admin.case.close', compact("confirm_status"));
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
        $mediation_status_log = new Mediation_status_log;
        $mediation_status_log->user_id = Auth::user()->id;
        $mediation_status_log->mediation_case_id = $request->id;
        $mediation_status_log->status = 1;
        $mediation_status_log->description = "Request Confirm";
        $mediation_status_log->save();
        return response()->json(["msg" => "Onging Case"]);
    }

    public function withdrawStatus(Request $request) {
        $user = MedCase::find($request->case_id);
        $user->confirm_status = 2;
        $user->withdraw = $request->withdraw_comment;
        $user->save();

        $mediation_status_log = new Mediation_status_log;
        $mediation_status_log->user_id = Auth::user()->id;
        $mediation_status_log->mediation_case_id = $request->case_id;
        $mediation_status_log->status = 2;
        $mediation_status_log->description = "Request Withdraw";
        $mediation_status_log->save();

        $mediation_status_log = new Mediation_status_log;
        $mediation_status_log->user_id = Auth::user()->id;
        $mediation_status_log->mediation_case_id = $request->case_id;
        $mediation_status_log->status = 2;
        $mediation_status_log->description = "Request Colse";
        $mediation_status_log->save();

        return response()->json(["msg" => "withdraw Case"]);
    }

    public function closeStatus(Request $request) {
        $user = MedCase::find($request->id);
        $user->confirm_status = 2;
        $user->save();

        $mediation_status_log = new Mediation_status_log;
        $mediation_status_log->user_id = Auth::user()->id;
        $mediation_status_log->mediation_case_id = $request->id;
        $mediation_status_log->status = 2;
        $mediation_status_log->description = "Request Colse";
        $mediation_status_log->save();
        return response()->json(["msg" => "Closed Case"]);
    }

    public function commentAction(Request $request) {
        $mediation_case_comment = new Mediation_case_comment;
        $mediation_case_comment->user_id = Auth::user()->id;
        $mediation_case_comment->mediation_case_id = $request->case_id;
        $mediation_case_comment->type = $request->type;
        $mediation_case_comment->comment = $request->comment;
        $mediation_case_comment->save();
        return response()->json(["msg" => "Closed Case"]);
    }

    public function commentView(Request $request) {
        if ($request->type == 1) {
            $mediation_case_comment = Mediation_case_comment::select("users.username", "mediation_case_comment.comment")->join("users", "mediation_case_comment.user_id", "=", "users.id")->where("type", $request->type)->where("user_id", Auth::user()->id)->where("mediation_case_id", $request->case_id)->get();
        } else {
            $mediation_case_comment = Mediation_case_comment::select("users.username", "mediation_case_comment.comment")->join("users", "mediation_case_comment.user_id", "=", "users.id")->where("type", $request->type)->where("type", $request->type)->where("mediation_case_id", $request->case_id)->get();
        }
        return response()->json($mediation_case_comment);
    }

    public function rejectStatus(Request $request) {
        $user = MedCase::find($request->id);
        $user->confirm_status = 3;
        $user->save();

        $mediation_status_log = new Mediation_status_log;
        $mediation_status_log->user_id = Auth::user()->id;
        $mediation_status_log->mediation_case_id = $request->id;
        $mediation_status_log->status = 3;
        $mediation_status_log->description = "Request Reject";
        $mediation_status_log->save();
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

    public function addSession(Request $request) {
        // echo $request->zoomId;

        $dataToInsert = [
            'case_id' => $request->caseId,
            'session_date' => $request->sessionDate . "/" . $request->sessionTime,
            'note' => $request->note,
            'zoom_id' => $request->zoomId,
            'scheduled_by' => Auth::user()->id,
        ];

        DB::table('manage_session')->insert($dataToInsert);

        return true;
    }

    public function getAddedSesion(Request $request) {


        $sessionData = DB::table('manage_session')->where('case_id', $request->caseid)->get();
        $sn = 1;
        foreach ($sessionData as $value) {
            echo "<tr>";
            echo "<td>" . $sn . "</td>";
            echo "<td>" . $value->created_at . "</td>";
            echo "<td>" . $value->session_date . "</td>";
            echo "<td>" . $value->zoom_id . "</td>";
            echo "<td>" . $value->note . "</td>";
            echo "</tr>";

            $sn++;
        }
        // return $sessionData;
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
                "status_log" => Mediation_status_log::select("status", "description", DB::raw("DATE_FORMAT(created_at,'%d-%c-%y %h:%i %p') as created"))->where(['mediation_case_id' => $d->id])->orderByDesc('id')->limit(1)->get(),
            ];
        }
        return response()->json(["data" => $arraydata]);
    }

}
