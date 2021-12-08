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
use App\Http\Helpers\Whatsapp;
use App\Models\Mediators_mediation_cases_status;
use App\Models\WaTemplate;
use DB;
use PDF;
use Illuminate\Support\Facades\Storage;

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
        return view('mediator.dashboard');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function newrequest()
    {
        $mediationDetails = Mediation_Details::where("user_id", "=", Auth::user()->id)->first();
        return view('mediator.newrequest', compact('mediationDetails'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function newjson()
    {

        $loginUser = Auth::user()->id;
        $newrequestData = DB::table('mediation_case')
            //->select('mediation_case.*')
            ->join('mediators_mediation_cases_status', 'mediation_case.id', '=', 'mediators_mediation_cases_status.mediation_case_id')
            ->where(['mediators_mediation_cases_status.mediator_id' => $loginUser, 'mediators_mediation_cases_status.status' => 0])
            ->where("mediation_case.confirm_status", "!=", 2)->orderBy('mediation_case.id', 'DESC')
            ->get();
        // dd($newrequestData);
        $arraydata = array();


        foreach ($newrequestData as $key => $d) {
            $arraydata[] = [
                "key" => $key + 1,
                "id" => $d->mediation_case_id,
                "party" => InvoledUser::select('name', 'userPhone', 'address1', 'address2', 'userEmail', 'isOnboarded')->where(['userPlanid' => $d->mediation_case_id])->whereNotNull('address1')->get(),
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
    public function ongoing()
    {
        $confirm_status = 1;
        return view('mediator.ongoing', compact('confirm_status'));
    }

    /**
     * Show closed cases.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function closed()
    {
        $confirm_status = 2;
        return view('mediator.close', compact("confirm_status"));
    }

    /**
     * Show the profile of user dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function profile()
    {

        $loginUser = Auth::user()->id;
        $profileData = User::find($loginUser);
        return view('mediator.profile', compact('profileData'));
    }

    /**
     * Status change for accept and reject the new case.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function statusChange(Request $request)
    {
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
    public function addSession(Request $request)
    {
        // dd($request->session_party_ids);
        // dd("hello");
        
        // exit;
        // foreach ($request->session_party_ids as $pary_id) {
        
        // foreach ($request->session_party_ids as $party_id) {
        //     $party = InvoledUser::where("userPlanId", $request->caseId)->where("userId", $party_id)->where("isOnboarded", 1)->first();
        //     $this->sned_session($request->zoomId, $request->caseId, $party->userEmail, $party->name, $request->sessionDate . "/" . $request->sessionTime, $party->userPhone);
        // }

        if(isset($request->session_party_ids)) {
            foreach ($request->session_party_ids as $party_id) {
                $party = InvoledUser::where("userPlanId", $request->caseId)->where("userId", $party_id)->where("isOnboarded", 1)->first();
                $this->sned_session($request->zoomId, $request->caseId, $party->userEmail, $party->name, $request->sessionDate . "/" . $request->sessionTime, $party->userPhone);
                
            }
            $dataToInsert = [
                'case_id' => $request->caseId,
                'session_date' => $request->sessionDate . "/" . $request->sessionTime,
                'note' => $request->note,
                'zoom_id' => $request->zoomId,
                'session_party_ids' => json_encode($request->session_party_ids),
                'scheduled_by' => Auth::user()->id,
            ];
            DB::table('manage_session')->insert($dataToInsert);

        } else {
            $allParty = InvoledUser::where("userPlanId", $request->caseId)->where("isOnboarded", 1)->get();
            $party_ids = array();
            foreach($allParty as $party) {
                $party_ids[] = $party->userId;
                $this->sned_session($request->zoomId, $request->caseId, $party->userEmail, $party->name, $request->sessionDate . "/" . $request->sessionTime, $party->userPhone);
            }
            // dd($party_ids);
            $dataToInsert = [
                'case_id' => $request->caseId,
                'session_date' => $request->sessionDate . "/" . $request->sessionTime,
                'note' => $request->note,
                'zoom_id' => $request->zoomId,
                'session_party_ids' => json_encode($party_ids),
                'scheduled_by' => Auth::user()->id,
            ];
            DB::table('manage_session')->insert($dataToInsert);
        }
        $mediator = Mediators_mediation_cases_status::select("email", "username")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->where("mediators_mediation_cases_status.mediation_case_id", "=", $request->caseId)
            ->where("mediators_mediation_cases_status.status", "=", 1)
            ->first();
        //  dd($mediator);
        $d = [
            'event' => 'SESS_SCHE_MED',
            'case_id' => $request->caseId,
        ];
        if ($mediator) {
            $id = "M" . sprintf("%06d", $request->caseId);
            SendGrid::send($d, $mediator->email, env('L10_SCHEDULING_OF_SESSION', ''), ["-caseid-" => $id, "-insert_date-" => $request->sessionDate . "/" . $request->sessionTime, "-type-" => "Mediator"], $mediator->username);

            $var = ['-dt-', '-cid-', '-link-'];
            $var1 = [$request->sessionDate . "/" . $request->sessionTime, $id, $request->zoomId];
            $content1 = WaTemplate::getcontent('l10_session_schedule');
            $content = str_replace($var, $var1, $content1);
            $dwa1 = [
                'caseid' => $request->caseId,
                'contact' => "+91" . $mediator->mobile_number,
                'content' => ['text' => $content],
                'event' => 'SESS_SCHE_MED'
            ];


            $access = Whatsapp::sendWamessage($dwa1);
        }
        return true;
    }

    /**
     * get added session data to view on ongoing.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getAddedSesion(Request $request)
    {


        $sessionData = DB::table('manage_session')->where('case_id', $request->caseid)->get();
        $sn = 1;
        $dataArray = array();

        foreach ($sessionData as $value) {
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
    public function viewSupporting(Request $request)
    {

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
    public function viewSettelment(Request $request)
    {

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
    public function rejectedCaseView()
    {
        $loginUser = Auth::user()->id;
        $rejected_case = DB::table('mediators_mediation_cases_status')
            ->select('mediators_mediation_cases_status.*', 'user_involved_in_agreement.userid')
            ->join('mediation_case', 'mediation_case.id', '=', 'mediators_mediation_cases_status.mediation_case_id')
            ->join(
                'user_involved_in_agreement',
                function ($join) {
                    $join->on('user_involved_in_agreement.userPlanId', '=', 'mediation_case.id')
                        ->where('user_involved_in_agreement.isClaimant', '=', '0');
                }
            )
            ->where(['mediators_mediation_cases_status.mediator_id' => $loginUser, 'mediators_mediation_cases_status.status' => 2])->orderBy('mediation_case.id', 'DESC')->get();

        //dd($rejected_case);
        return view('mediator.reject', compact("rejected_case"));
    }

    public function json($role = 0)
    {
        $cases = MedCase::select("mediation_case.*", "users.username as mediator_username", "mediators_mediation_cases_status.mediator_id as mediator_id", "mediators_mediation_cases_status.status as mediator_status")
            ->leftJoin("mediators_mediation_cases_status", "mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id")
            ->leftJoin("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->where("mediation_case.confirm_status", "=", $role)
            ->where('mediator_id', "=", Auth::user()->id)->orderBy('mediation_case.id', 'DESC')
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

    public function casedetails($id)
    {


        $case = MedCase::select("mediation_case.*", "users.first_name as mfirstname", "users.last_name as mlastname", "mediators_mediation_cases_status.mediator_id as mediator_id", "mediators_mediation_cases_status.status as mediator_status")
            ->leftJoin("mediators_mediation_cases_status", "mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id")
            ->leftJoin("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->where('mediation_case.id', '=', $id)
            ->first();


        $case->party = InvoledUser::where(['userPlanid' => $case->id])->get();

        $case->invitation = InvitationFiles::where(['case_id' => $case->id])->orderByDesc('id')->limit(1)->first();


        $case->supporting_document = SupportingDocument::where(['case_id' => $case->id])->get();


        return view('mediator.casedetails', compact("case"));
    }

    public function jsonOngoing($role = 0)
    {
        $cases = DB::table('mediators_mediation_cases_status')
            ->select("mediation_case.*")
            ->join('users', 'users.id', '=', 'mediators_mediation_cases_status.mediator_id')
            ->join('mediation_case', 'mediation_case.id', '=', 'mediators_mediation_cases_status.mediation_case_id')
            ->where('confirm_status', "=", 1)
            ->where(['mediator_id' => Auth::user()->id, 'mediators_mediation_cases_status.status' => 1])->orderBy('mediation_case.id', 'DESC')->get();
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
    public function storeMultiFile(Request $request)
    {

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

    public function getConsentAndDisclosures($id)
    {
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
    public function settelmenSaveClose(Request $request)
    {

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

    public function sned_session($url, $id, $email_id, $email_name, $date, $userPhone)
    {
        $d = [
            'event' => 'SESS_SCHE_ADM',
            'case_id' => $id,
        ];
        $mid = "M" . sprintf("%06d", $id);
        if ($email_id != "") {
            SendGrid::send($d, $email_id, env('L10_SCHEDULING_OF_SESSION', ''), ["-caseid-" => $mid, "-insert_date-" => $date, "-type-" => "Party"], $email_name);
        }

        if ($userPhone != "") {
            $var = ['-dt-', '-cid-', '-link-'];
            $var1 = [$date, $mid, $url];
            $content1 = WaTemplate::getcontent('l10_session_schedule');
            $content = str_replace($var, $var1, $content1);
            $dwa1 = [
                'caseid' => $id,
                'contact' => "+91" . $userPhone,
                'content' => ['text' => $content],
                'event' => 'SESS_SCHE_ADM'
            ];

            $access = Whatsapp::sendWamessage($dwa1);
        }
        return true;
    }

    public function send_attechment_party($id)
    {
        $data["case"] = MedCase::where("id", "=", $id)->first();
        $data["party"] = InvoledUser::where("userPlanId", "=", $id)->get();
        $data["consent_disclosures"] = ConsentDisclosures::join("users", "consent_disclosures.mediator_id", "=", "users.id")
            ->where("mediation_case_id", "=", $id)
            ->first();
        if (empty($data["case"]) || empty($data["party"]) || empty($data["consent_disclosures"])) {
            return abort(404);
        }
        $pdf = PDF::loadView('pdf.consent_and_disclosures', $data);
        Storage::put('public/mediation/' . $data["case"]->id . '/' . "M" . sprintf("%06d", $id) . "_party.pdf", $pdf->output());
        $involedUser = InvoledUser::where("userPlanId", $id)->get();
        $mid = "M" . sprintf("%06d", $id);
        $d = [
            'event' => 'SEND_APPO_MED',
            'case_id' => $id,
        ];
        foreach ($involedUser as $inv) {
            if ($inv->userEmail != "") {
                SendGrid::send($d, $inv->userEmail, env('L18_MEDIATOR_ACCEPTANCE_ALL_PARTIES', ''), ["-caseid-" => $mid], null, url('storage/app/public/mediation/' . $data["case"]->id . '/' . $mid . "_party.pdf"));
            }
            if ($inv->userPhone != "") {
                $var = ['-cid-'];
                $var1 = [$mid];
                $content1 = WaTemplate::getcontent('mediator_appointment');
                $content = str_replace($var, $var1, $content1);
                $dwa1 = [
                    'caseid' => $id,
                    'contact' => "+91" .  $inv->userPhone,
                    'content' => ['text' => $content],
                    'event' => 'SEND_APPO_MED'
                ];
                $access = Whatsapp::sendWamessage($dwa1);

                $var_file = ['-caseid-'];
                $var1_file = [$mid];
                $content1_file = WaTemplate::getcontent('mediation_consent_doc');
                $content_file = str_replace($var_file, $var1_file, $content1_file);
                $dwa2 = [
                    'caseid' => $id,
                    'contact' => "+91" . $inv->userPhone,
                    'content' => ['media' => ['url' => url("/storage/app/public/mediation/" . $data["case"]->id . "/" . $mid . "_party.pdf"), 'caption' => $content_file]],
                    'event' => 'SEND_APPO_MED'
                ];
                $access = Whatsapp::sendWamessage($dwa2);
            }
        }

        return true;
    }

    public function send_upload_file_party($id, $files)
    {

        $involedUser = InvoledUser::where("userPlanId", $id)->get();
        $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->where("mediators_mediation_cases_status.mediation_case_id", "=", $id)
            ->where("mediators_mediation_cases_status.status", "=", 1)
            ->first();
        $mid = "M" . sprintf("%06d", $id);
        $sendEamils = array();
        $filesE = array();
        foreach ($files as $f) {
            $filesE[] = url("storage/app/" . $f["file_name"]);
        }
        $d = [
            'event' => 'SEND_ADDI_DOC_MED',
            'case_id' => $id,
        ];
        foreach ($involedUser as $inv) {

            if ($inv->userEmail != "") {
                $sendEamils[] = $inv->userEmail;
            }
            // additional_doc
            if ($inv->userPhone != "") {
                $var = ['-cid-'];
                $var1 = [$mid];
                $content1 = WaTemplate::getcontent('additional_doc');
                $content = str_replace($var, $var1, $content1);
                $dwa1 = [
                    'caseid' => $id,
                    'contact' => "+91" .  $inv->userPhone,
                    'content' => ['text' => $content],
                    'event' => 'SEND_ADDI_DOC_MED'
                ];
                $access = Whatsapp::sendWamessage($dwa1);
                foreach ($filesE as $file) {
                    $var_file = ['-caseid-'];
                    $var1_file = [$mid];
                    $content1_file = WaTemplate::getcontent('mediation_consent_doc');
                    $content_file = str_replace($var_file, $var1_file, $content1_file);
                    $dwa2 = [
                        'caseid' => $id,
                        'contact' => "+91" . $inv->userPhone,
                        'content' => ['media' => ['url' => $file, 'caption' => $content_file]],
                        'event' => 'SEND_ADDI_DOC_MED'
                    ];
                    $access = Whatsapp::sendWamessage($dwa2);
                }
            }
            // $dwa2 = [
            //     'caseid' => $id,
            //     'contact' => "+91" .  $inv->userPhone,
            //     'content' => ['media' => ['url' => $filesE, 'caption' => 'Additional Document ' . $mid]],
            //     'event' => 'SEND_ADDI_DOC_ADM'
            // ];
            // $access = Whatsapp::sendWamessage($dwa2);
        }
        // echo ("error");
        // exit;
        if ($mediator) {
            $sendEamils[] = $mediator->email;

            $var = ['-cid-'];
            $var1 = [$mid];
            $content1 = WaTemplate::getcontent('additional_doc_med');
            $content = str_replace($var, $var1, $content1);
            $dwa1 = [
                'caseid' => $id,
                'contact' => "+91" . $mediator->mobile_number,
                'content' => ['text' => $content],
                'event' => 'SEND_ADDI_DOC_MED'
            ];
            $access = Whatsapp::sendWamessage($dwa1);
            foreach ($filesE as $file) {

                $var_file = ['-caseid-'];
                $var1_file = [$mid];
                $content1_file = WaTemplate::getcontent('mediation_consent_doc');
                $content_file = str_replace($var_file, $var1_file, $content1_file);
                $dwa2 = [
                    'caseid' => $id,
                    'contact' => "+91" . $mediator->mobile_number,
                    'content' => ['media' => ['url' => $file, 'caption' => $content_file]],
                    'event' => 'SEND_ADDI_DOC_MED'
                ];
                $access = Whatsapp::sendWamessage($dwa2);
            }
        }
        // dd($sendEamils);

        foreach ($sendEamils as $email) {
            SendGrid::send($d, $email, env('L19_ADDITIONAL_DOC_ALL_PARTIES', ''), ["-caseid-" => $mid], null, $filesE);
        }

        return true;
    }

    public function send_settlement_agreement_party($id, $files)
    {

        $involedUser = InvoledUser::where("userPlanId", $id)->get();
        $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->where("mediators_mediation_cases_status.mediation_case_id", "=", $id)
            ->where("mediators_mediation_cases_status.status", "=", 1)
            ->first();
        $mid = "M" . sprintf("%06d", $id);
        $sendEamils = array();
        $filesE = array();
        foreach ($files as $f) {
            $filesE[] = url("storage/app/" . $f["file_path"]);
        }
        $d = [
            'event' => 'SEND_SETT_AGRE_MED',
            'case_id' => $id,
        ];
        foreach ($involedUser as $inv) {
            if ($inv->userEmail != "") {
                $sendEamils[] = $inv->userEmail;
            }
            // settlement agreement
            if ($inv->userPhone != "") {
                $var = ['-cid-'];
                $var1 = [$mid];
                $content1 = WaTemplate::getcontent('settlement_agreement');
                $content = str_replace($var, $var1, $content1);
                $dwa1 = [
                    'caseid' => $id,
                    'contact' => "+91" . $inv->userPhone,
                    'content' => ['text' => $content],
                    'event' => 'SEND_SETT_AGRE_MED'
                ];
                $access = Whatsapp::sendWamessage($dwa1);
            }
            // $dwa2 = [
            //     'caseid' => $id,
            //     'contact' => "+91" . $inv->userPhone,
            //     'content' => ['media' => ['url' => $filesE, 'caption' => 'settlement agreement ' . $mid]],
            //     'event' => 'SEND_SETT_AGRE_ADM'
            // ];
            // $access = Whatsapp::sendWamessage($dwa2);
        }
        if ($mediator) {
            $sendEamils[] = $mediator->email;

            $var = ['-cid-'];
            $var1 = [$mid];
            $content1 = WaTemplate::getcontent('settlement_agreement_med');
            $content = str_replace($var, $var1, $content1);
            $dwa1 = [
                'caseid' => $id,
                'contact' => "+91" . $mediator->mobile_number,
                'content' => ['text' => $content],
                'event' => 'SEND_SETT_AGRE_MED'
            ];


            $access = Whatsapp::sendWamessage($dwa1);
        }
        foreach ($sendEamils as $email) {

            SendGrid::send($d, $email, env('L21_SETTLEMENT_AGREEMENT_ALL_PARTIES', ''), ["-caseid-" => $mid], null, $filesE);
        }
        return true;
    }
}
