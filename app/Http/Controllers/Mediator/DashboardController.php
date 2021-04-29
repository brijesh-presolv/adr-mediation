<?php

namespace App\Http\Controllers\Mediator;

use Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MedCase;
use App\Models\Mediation_status_log;
use App\Models\Mediation_case_comment;
use Illuminate\Http\Request;
use App\Models\InvoledUser;
use DB;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function newrequest() {
        return view('mediator.newrequest');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function newjson() {

        $loginUser = Auth::user()->id;
        $newrequestData = DB::table('mediators_mediation_cases_status')
                // ->select('mediation_case.*')
                ->join('users', 'users.id', '=', 'mediators_mediation_cases_status.mediator_id')
                ->join('mediation_case', 'mediation_case.id', '=', 'mediators_mediation_cases_status.mediation_case_id')
                ->join('user_involved_in_agreement', 'user_involved_in_agreement.id', '=', 'mediation_case.userid')
                ->where(['users.id' => $loginUser, 'mediators_mediation_cases_status.status' => 0])
                ->get();
        // dd($newrequestData);
        $arraydata = array();


        foreach ($newrequestData as $d) {
            $arraydata[] = [
                "id" => $d->id,
                "party" => InvoledUser::select('name', 'isOnboarded')->where(['userPlanid' => $d->id])->get(),
                "comments" => "tesr",
                "status" => $d->status,
                "caseId" => $d->mediation_case_id,
                "mediator_id" => $d->mediator_id,
                "date" => date('d-m-Y', strtotime($d->created_at)),
            ];
        }

        return response()->json(["data" => $arraydata]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function ongoing() {
         $confirm_status = 1;
        return view('mediator.ongoing', compact('confirm_status'));
    }

    /**
     * Show closed cases.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function closed() {
        $confirm_status = 2;
        return view('mediator.close', compact("confirm_status"));
    }

    /**
     * Show the profile of user dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function profile() {

        $loginUser = Auth::user()->id;
        $profileData = User::find($loginUser);
        return view('mediator.profile', compact('profileData'));
    }

    /**
     * Status change for accept and reject the new case.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function statusChange(Request $request) {

        DB::table('mediators_mediation_cases_status')
                ->where('mediator_id', $request->mediator_id)
                ->where('mediation_case_id', $request->caseid)
                ->update(['status' => $request->status, 'updated_at' => now()]);
        return response()->json(["msg" => "staus Update"]);
    }

    /**
     * create new meeting add session.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function addSession(Request $request) {
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

    /**
     * get added session data to view on ongoing.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getAddedSesion(Request $request) {

        $sessionData = DB::table('manage_session')->where('scheduled_by', $request->mediator_id)->get();
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

    /**
     * get added session data to view on ongoing.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function viewSupporting(Request $request) {

        $sessionData = DB::table('manage_files')
                ->join('users', 'users.id', '=', 'manage_files.uploaded_by')
                ->where('manage_files.case_id', $request->id)
                ->get();
        $sn = 1;
        foreach ($sessionData as $value) {

            echo "<tr>";
            echo "<td>" . $sn . "</td>";
            echo "<td><a href='" . url("storage/app/" . $value->file_name) . "' target='_blank'>" . pathinfo($value->file_name, PATHINFO_FILENAME) . "</td>";
            echo "<td>" . $value->username . "</td>";
            echo "</tr>";

            $sn++;
        }
        return;
    }

    /**
     * show the rejected case.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function rejectedCaseView() {
        $loginUser = Auth::user()->id;
        $rejected_case = DB::table('mediators_mediation_cases_status')
                        ->select('mediators_mediation_cases_status.*', 'user_involved_in_agreement.userid')
                        ->join('users', 'users.id', '=', 'mediators_mediation_cases_status.mediator_id')
                        ->join('mediation_case', 'mediation_case.id', '=', 'mediators_mediation_cases_status.mediation_case_id')
                        ->join('user_involved_in_agreement', 'user_involved_in_agreement.id', '=', 'mediation_case.userid')
                        ->where(['mediator_id' => $loginUser, 'mediators_mediation_cases_status.status' => 2])->get();

        // dd($rejected_case);
        return view('mediator.reject', compact("rejected_case"));
    }

    public function json($role = 0) {
        $cases = MedCase::select("mediation_case.*", "users.username as mediator_username", "mediators_mediation_cases_status.mediator_id as mediator_id", "mediators_mediation_cases_status.status as mediator_status")
                ->leftJoin("mediators_mediation_cases_status", "mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id")
                ->leftJoin("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                ->where("mediation_case.confirm_status", "=", $role)
                ->where('mediator_id', "=", Auth::user()->id)
                ->get();
        $arraydata = array();
        foreach ($cases as $d) {
            $arraydata[] = [
                "date" => date('d-m-Y', strtotime($d->created_at)),
                "case" => $d,
                "party" => InvoledUser::select('name', 'isOnboarded')->where(['userPlanid' => $d->id])->get(),
                "status_log" => Mediation_status_log::select("status", "description", DB::raw("DATE_FORMAT(created_at,'%d-%c-%y %h:%i %p') as created"))->where(['mediation_case_id' => $d->id])->get(),
            ];
        }
        return response()->json(["data" => $arraydata]);
    }
    public function jsonOngoing($role = 0) {
        $cases = DB::table('mediators_mediation_cases_status')
                        ->select("mediation_case.*")
                        ->join('users', 'users.id', '=', 'mediators_mediation_cases_status.mediator_id')
                        ->join('mediation_case', 'mediation_case.id', '=', 'mediators_mediation_cases_status.mediation_case_id')
                        ->join('user_involved_in_agreement', 'user_involved_in_agreement.id', '=', 'mediation_case.userid')
                        ->where('confirm_status',"=",1)
                        ->where(['mediator_id' => Auth::user()->id, 'status' => 1])->get();
        $arraydata = array();
        foreach ($cases as $d) {
            $arraydata[] = [
                "date" => date('d-m-Y', strtotime($d->created_at)),
                "case" => $d,
                "party" => InvoledUser::select('name', 'isOnboarded')->where(['userPlanid' => $d->id])->get(),
                "status_log" => Mediation_status_log::select("status", "description", DB::raw("DATE_FORMAT(created_at,'%d-%c-%y %h:%i %p') as created"))->where(['mediation_case_id' => $d->id])->get(),
            ];
        }
        return response()->json(["data" => $arraydata]);
    }

    /**
     * upload supporting Documents.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function storeMultiFile(Request $request) {

        $validatedData = $request->validate([
            'files' => 'required',
            'files.*' => 'mimes:csv,txt,xlx,xls,pdf',
        ]);

        if ($request->TotalFiles > 0) {

            for ($x = 0; $x < $request->TotalFiles; $x++) {

                if ($request->hasFile('files' . $x)) {
                    $file = $request->file('files' . $x);

                    $path = $file->storeAs('/supporting/' . $request->caseId, pathinfo(str_replace(" ", "_", $file->getClientOriginalName()), PATHINFO_FILENAME) . "_date_" . date("Y_m_d_H_i_s_a") . "." . $file->extension());
                    $insert[$x]['file_name'] = $path;
                    $insert[$x]['uploaded_by'] = Auth::user()->id;
                    $insert[$x]['case_id'] = $request->caseId;
                    // $insert[$x]['path'] = $path;
                }
            }
            // dd($insert);
            // die();
            // File::insert($insert);
            DB::table('manage_files')->insert($insert);

            return response()->json(['success'=>'Ajax Multiple fIle has been uploaded']);
        } else {
            return response()->json(["message" => "Please try again."]);
        }
    }

    /**
     * upload supporting Documents.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function settelmenSaveClose(Request $request) {

        $validatedData = $request->validate([
            'Settelmentfiles' => 'required',
            'Settelmentfiles.*' => 'mimes:csv,txt,xlx,xls,pdf',
        ]);

        if ($request->TotalFiles > 0) {

            for ($x = 0; $x < $request->TotalFiles; $x++) {

                if ($request->hasFile('Settelmentfiles' . $x)) {
                    $file = $request->file('Settelmentfiles' . $x);
                    $path = $file->storeAs('/supporting/' . $request->caseId, pathinfo(str_replace(" ", "_", $file->getClientOriginalName()), PATHINFO_FILENAME) . "_date_" . date("Y_m_d_H_i_s_a") . "." . $file->extension());
                    $insert[$x]['file_name'] = $path;
                    MedCase::where('id', $request->caseId)
                            ->update(['documentPath' => $path, "confirm_status" => 2]);
                }
            }
            return response()->json(["message" => 'Ajax Multiple fIle has been uploaded']);
            //DB::table('manage_files')->insert($insert);
        } else {
            return response()->json(["message" => "Please try again."]);
        }
    }

}
