<?php

namespace App\Http\Controllers\Mediator;

use Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MedCase;
use App\Models\Mediation_Details;
use App\Models\Mediation_status_log;
use App\Models\Mediation_case_comment;
use App\Models\ConsentDisclosures;
use Illuminate\Http\Request;
use App\Models\InvoledUser;
use App\Models\SupportingDocument;
use App\Models\InvitationFiles;
use App\Http\Helpers\SendGrid;
use DB;
use PDF;
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
        $mediationDetails = Mediation_Details::where("user_id", "=", Auth::user()->id)->first();
        return view('mediator.newrequest', compact('mediationDetails'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function newjson() {

        $loginUser = Auth::user()->id;
        $newrequestData = DB::table('mediation_case')
                //->select('mediation_case.*')
                ->join('mediators_mediation_cases_status', 'mediation_case.id', '=', 'mediators_mediation_cases_status.mediation_case_id')
                ->where(['mediators_mediation_cases_status.mediator_id' => $loginUser, 'mediators_mediation_cases_status.status' => 0])
                ->where("mediation_case.confirm_status", "!=",2 )
                ->get();
        // dd($newrequestData);
        $arraydata = array();


        foreach ($newrequestData as $d) {
            $arraydata[] = [
                "id" => $d->mediation_case_id,
                "party" => InvoledUser::select('name', 'userPhone', 'address1', 'address2', 'userEmail', 'isOnboarded')->where(['userPlanid' => $d->mediation_case_id])->get(),
                "comments" => "tesr",
                "caseId" => $d->mediation_case_id,
                "case_issue" => $d->issue,
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
        if ($request->status == 1) {
            $caseid = $request->mediation_case_id;
            $consentDisclosures = ConsentDisclosures::where("mediation_case_id", "=", $caseid)->first();
            if (empty($consentDisclosures)) {
                $consentDisclosures = new ConsentDisclosures();
                $consentDisclosures->mediation_case_id = $caseid;
                $consentDisclosures->mediator_id = Auth::user()->id;
                $consentDisclosures->consent1 = $request->consent1;
                $consentDisclosures->consent2 = $request->consent2;
                $consentDisclosures->consent3 = $request->consent3;
                $consentDisclosures->consent4 = $request->consent4;
                $consentDisclosures->consent5 = $request->consent5;
                $consentDisclosures->particulars1 = $request->particulars1;
                $consentDisclosures->particulars2 = $request->particulars2;
                $consentDisclosures->particulars3 = $request->particulars3;
                $consentDisclosures->particulars4 = $request->particulars4;
            } else {
                $consentDisclosures->mediation_case_id = $caseid;
                $consentDisclosures->mediator_id = Auth::user()->id;
                $consentDisclosures->consent1 = $request->consent1;
                $consentDisclosures->consent2 = $request->consent2;
                $consentDisclosures->consent3 = $request->consent3;
                $consentDisclosures->consent4 = $request->consent4;
                $consentDisclosures->consent5 = $request->consent5;
                $consentDisclosures->particulars1 = $request->particulars1;
                $consentDisclosures->particulars2 = $request->particulars2;
                $consentDisclosures->particulars3 = $request->particulars3;
                $consentDisclosures->particulars4 = $request->particulars4;
            }
            $consentDisclosures->save();
            $this->send_attechment_party($caseid);
        } else {
            $caseid = $request->caseid;
            
        }
        
        
        DB::table('mediators_mediation_cases_status')
                ->where('mediator_id', Auth::user()->id)
                ->where('mediation_case_id', $caseid)
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
            'session_party_ids' => json_encode($request->session_party_ids),
            'scheduled_by' => Auth::user()->id,
        ];

        DB::table('manage_session')->insert($dataToInsert);
        foreach ($request->session_party_ids as $pary_id) {
            $data = InvoledUser::where("userId", $pary_id)->where("userPlanId", $request->caseId)->first();
            $this->sned_session($request->caseId, $data->userEmail, $data->name,$request->sessionDate . "/" . $request->sessionTime);
        }
        $mediator = Mediators_mediation_cases_status::select("email", "username")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                ->where("mediators_mediation_cases_status.mediation_case_id", "=", $request->caseId)
                ->where("mediators_mediation_cases_status.status", "=", 1)
                ->first();
        if ($mediator) {
            $id = "M" . sprintf("%06d", $request->caseId);
            SendGrid::send($mediator->email, env('L10_SCHEDULING_OF_SESSION', ''), ["-caseid-" => $id, "-insert_date-" => $request->sessionDate . "/" . $request->sessionTime,"-type-"=>"Mediator"], $mediator->username);
        }
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
            $dataArray = array();
            if (!is_null($value->session_party_ids)) {
                $dataArray = json_decode($value->session_party_ids);
            }
            $user = array();
            foreach ($dataArray as $d) {
                $dd = User::find($d);
                $user[] = $dd->first_name . " " . $dd->last_name;
            }
            echo "<tr>";
            echo "<td>" . $sn . "</td>";
            echo "<td>" . $value->created_at . "</td>";
            echo "<td>" . $value->session_date . "</td>";
            echo "<td>" . $value->zoom_id . "</td>";
            echo "<td>" . $value->note . "</td>";
            echo "<td>" . implode("<br>", $user) . "</td>";
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
     * get added session data to view on ongoing.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function viewSettelment(Request $request) {

        $sessionData = DB::table('document_settlements')
                ->join('users', 'users.id', '=', 'document_settlements.uploaded_by')
                ->where('document_settlements.mediation_case_id', $request->id)
                ->get();
        $sn = 1;
        foreach ($sessionData as $value) {

            echo "<tr>";
            echo "<td>" . $sn . "</td>";
            echo "<td><a href='" . url("storage/app/" . $value->file_path) . "' target='_blank'>" . pathinfo($value->file_path, PATHINFO_FILENAME) . "</td>";
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
                        ->join('mediation_case', 'mediation_case.id', '=', 'mediators_mediation_cases_status.mediation_case_id')
                        ->join('user_involved_in_agreement',
 			function ($join) {
            		$join->on( 'user_involved_in_agreement.userPlanId', '=', 'mediation_case.id')
                	 ->where('user_involved_in_agreement.isClaimant', '=', '0');
       			 })
                        ->where(['mediators_mediation_cases_status.mediator_id' => $loginUser, 'mediators_mediation_cases_status.status' => 2])->get();

        //dd($rejected_case);
        return view('mediator.reject', compact("rejected_case"));
    }

    public function json($role = 0) {
        $cases = MedCase::select("mediation_case.*", "users.username as mediator_username", "mediators_mediation_cases_status.mediator_id as mediator_id", "mediators_mediation_cases_status.status as mediator_status")
                ->leftJoin("mediators_mediation_cases_status", "mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id")
                ->leftJoin("users","users.id", "=", "mediators_mediation_cases_status.mediator_id")
                ->where("mediation_case.confirm_status", "=", $role)
                ->where('mediator_id', "=", Auth::user()->id)
                ->get();
        $arraydata = array();
        foreach ($cases as $d) {
            $arraydata[] = [
                "date" => date('d-m-Y', strtotime($d->created_at)),
                "case" => $d,
                "party" => InvoledUser::select('name', 'isOnboarded', 'userId')->where(['userPlanid' => $d->id])->get(),
                "status_log" => Mediation_status_log::select("status", "description", DB::raw("DATE_FORMAT(created_at,'%d-%c-%y %h:%i %p') as created"))->where(['mediation_case_id' => $d->id])->orderByDesc('id')->limit(1)->get(),
            ];
        }
        return response()->json(["data" => $arraydata]);
    }

    public function casedetails($id) {


        $case = MedCase::select("mediation_case.*", "users.first_name as mfirstname", "users.last_name as mlastname", "mediators_mediation_cases_status.mediator_id as mediator_id", "mediators_mediation_cases_status.status as mediator_status")
                ->leftJoin("mediators_mediation_cases_status", "mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id")
                ->leftJoin("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                ->where('mediation_case.id', '=', $id)
                ->first();


        $case->party = InvoledUser::where(['userPlanid' => $case->id])->get();

        $case->invitation = InvitationFiles::where(['case_id' => $case->id])->orderByDesc('id')->limit(1)->first();


        $case->supporting_document = SupportingDocument::where(['case_id' => $case->id])->get();


        return view('admin.case.casedetails', compact("case"));
    }

    public function jsonOngoing($role = 0) {
        $cases = DB::table('mediators_mediation_cases_status')
                        ->select("mediation_case.*")
                        ->join('users', 'users.id', '=', 'mediators_mediation_cases_status.mediator_id')
                        ->join('mediation_case', 'mediation_case.id', '=', 'mediators_mediation_cases_status.mediation_case_id')
                        ->where('confirm_status', "=", 1)
                        ->where(['mediator_id' => Auth::user()->id, 'mediators_mediation_cases_status.status' => 1])->get();
        $arraydata = array();
        foreach ($cases as $d) {
            $arraydata[] = [
                "date" => date('d-m-Y', strtotime($d->created_at)),
                "case" => $d,
                "party" => InvoledUser::select('name', 'isOnboarded', 'userId')->where(['userPlanid' => $d->id])->get(),
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
            DB::table('manage_files')->insert($insert, $insert);
            $this->send_upload_file_party($request->caseId, $insert);
            return response()->json(['success' => 'Ajax Multiple fIle has been uploaded']);
        } else {
            return response()->json(["message" => "Please try again."]);
        }
    }

    public function getConsentAndDisclosures($id) {
        $data["case"] = MedCase::where("id", "=", $id)->first();
        $data["party"] = InvoledUser::where("userPlanId", "=", $id)->get();
        $data["consent_disclosures"] = ConsentDisclosures::join("users", "consent_disclosures.mediator_id", "=", "users.id")
                ->where("mediation_case_id", "=", $id)
                ->first();
        if (empty($data["case"]) || empty($data["party"]) || empty($data["consent_disclosures"])) {
            return abort(404);
        }
        $pdf = PDF::loadView('pdf.consent_and_disclosures', $data);
        return $pdf->stream('document.pdf');
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
                    $insert[$x]['file_path'] = $path;
                    $insert[$x]['uploaded_by'] = Auth::user()->id;
                    $insert[$x]['mediation_case_id'] = $request->caseId;
                    //MedCase::where('id', $request->caseId)
                    //        ->update(['document_settelment' => $path, "confirm_status" => 2]);
                }
            }
            DB::table('document_settlements')->insert($insert);
            $this->send_settlement_agreement_party($request->caseId, $insert);
            return response()->json(["message" => 'Ajax Multiple fIle has been uploaded']);
        } else {
            return response()->json(["message" => "Please try again."]);
        }
    }

    public function sned_session($id, $email_id, $email_name,$date) {
        $id = "M" . sprintf("%06d", $id);
        SendGrid::send($email_id, env('L10_SCHEDULING_OF_SESSION', ''), ["-caseid-" => $id,"-insert_date-"=>$date,"-type-"=>"Party"], $email_name);
        return true;
    }

    public function send_attechment_party($id) {
        $data["case"] = MedCase::where("id", "=", $id)->first();
        $data["party"] = InvoledUser::where("userPlanId", "=", $id)->get();
        $data["consent_disclosures"] = ConsentDisclosures::join("users", "consent_disclosures.mediator_id", "=", "users.id")
                ->where("mediation_case_id", "=", $id)
                ->first();
        if (empty($data["case"]) || empty($data["party"]) || empty($data["consent_disclosures"])) {
            return abort(404);
        }
        $pdf = PDF::loadView('pdf.consent_and_disclosures', $data);
        Storage::put('public/mediation/' . $data["case"]->id . '/' . "M" . sprintf("%06d", $id)."_party.pdf", $pdf->output());
        $involedUser = InvoledUser::where("userPlanId", $id)->get();
        $id = "M" . sprintf("%06d", $id);
        $sendEamils = array();
        foreach ($involedUser as $inv) {
            $sendEamils[] = $inv->userEmail;
        }
        SendGrid::send($sendEamils, env('L18_MEDIATOR_ACCEPTANCE_ALL_PARTIES', ''), ["-caseid-" => $id], null, url('storage/app/public/mediation/' . $data["case"]->id . '/' . $id."_party.pdf"));


        return true;
    }

    public function send_upload_file_party($id, $files) {

        $involedUser = InvoledUser::where("userPlanId", $id)->get();
        $id = "M" . sprintf("%06d", $id);
        $sendEamils = array();
        foreach ($involedUser as $inv) {
            $sendEamils[] = $inv->userEmail;
        }

        $filesE = array();
        foreach ($files as $f) {
            $filesE[] = url("storage/app/" . $f["file_name"]);
        }
        SendGrid::send($sendEamils, env('L19_ADDITIONAL_DOC_ALL_PARTIES', ''), ["-caseid-" => $id], null, $filesE);

        return true;
    }

    public function send_settlement_agreement_party($id, $files) {

        $involedUser = InvoledUser::where("userPlanId", $id)->get();
        $id = "M" . sprintf("%06d", $id);
        $sendEamils = array();
        foreach ($involedUser as $inv) {
            $sendEamils[] = $inv->userEmail;
        }
        $filesE = array();
        foreach ($files as $f) {
            $filesE[] = url("storage/app/" . $f["file_path"]);
        }
        SendGrid::send($sendEamils, env('L21_SETTLEMENT_AGREEMENT_ALL_PARTIES', ''), ["-caseid-" => $id], null, $filesE);
        return true;
    }

}
