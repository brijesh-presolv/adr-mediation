<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Common_function;
use Illuminate\Http\Request;
use App\Models\MedCase;
use App\Models\Mediation_status_log;
use App\Models\Mediation_case_comment;
use App\Models\InvoledUser;
use App\Models\SupportingDocument;
use App\Models\ConsentDisclosures;
use App\Models\User;
use App\Models\InvitationFiles;
use App\Models\Mediators_mediation_cases_status;
use App\Http\Helpers\SendGrid;
use App\Http\Helpers\Whatsapp;
use App\Models\EmailTrack;
use App\Models\Reminder;
use App\Models\WaTemplate;
use App\Models\WhatsappTrack;
use DB;
use PDF;
use Auth;
use Storage;

class CaseController extends Controller
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
     * Show the application users.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function generatePDF($id, $type)
    {
       
        $data["comment"] = Mediation_case_comment::select("users.username", "mediation_case_comment.comment", "mediation_case_comment.type", DB::raw("DATE_FORMAT(mediation_case_comment.created_at,'%d-%c-%y %h:%i %p') as created"))->join("users", "mediation_case_comment.user_id", "=", "users.id")->where("mediation_case_id",$id)->where("mediation_case_comment.type","=",$type)->get();
        
        $data["caseId"] = $id;
        $data["type"] = $type;
        $data["case"] = MedCase::find($id);
        $data["party"] = InvoledUser::where("userPlanId", "=", $id)->get();


        $pdf = PDF::loadView('pdf.commentspdf', $data);
        
        return $pdf->download(($type == 1) ? 'Private_Comments_M' . sprintf('%06d', $id) . '.pdf' : 'Share_Comments_M' . sprintf('%06d', $id) . '.pdf');

        // return $pdf->stream('Commentsfile.pdf');
    }

    public function viewSupporting(Request $request)
    {

        $sessionData = DB::table('manage_files')
            ->join('users', 'users.id', '=', 'manage_files.uploaded_by')
            ->where('manage_files.case_id', $request->id)
            ->get();
        $sn = 1;
        foreach ($sessionData as $value) {
            // if($value->mediator_access == 1) {

            echo "<tr>";
            echo "<td>" . $sn . "</td>";
            echo "<td><a href='" . url("storage/app/" . $value->file_name) . "' target='_blank'>" . pathinfo($value->file_name, PATHINFO_FILENAME) . "</td>";
            echo "<td>" . $value->username . "</td>";
            echo "</tr>";

            $sn++;
            // }
        }
        return;
    }

    public function index()
    {
        $users = User::where("role", "=", 1)->get();
        $allUsers = User::where("role", "=", 0)->get();
        $confirm_status = 0;
        return view('admin.case.index', compact("confirm_status", "users", "allUsers"));
    }

    public function ongoingRequest()
    {
        $users = User::where("role", "=", 1)->get();
        $confirm_status = 1;
        return view('admin.case.ongoing', compact("confirm_status", "users"));
    }

    public function closedRequest()
    {
        $confirm_status = 2;
        return view('admin.case.close', compact("confirm_status"));
    }

    public function rjectedRequest()
    {
        $users = User::where("role", "=", 1)->get();
        $confirm_status = 3;
        return view('admin.case.rjected', compact("confirm_status", "users"));
    }

    public function getConsentAndDisclosures($id)
    {
        $data["case"] = MedCase::where("id", "=", $id)->first();
        $data["party"] = InvoledUser::where("userPlanId", "=", $id)->get();
        $data["consent_disclosures"] = ConsentDisclosures::select('consent_disclosures.*', 'users.first_name', 'users.last_name', 'users.email', 'users.username', 'users.mobile_number', 'users.organization', 'users.signature_photo', 'users.id as medId')->join("users", "consent_disclosures.mediator_id", "=", "users.id")
            ->where("mediation_case_id", "=", $id)
            ->first();
        if (empty($data["case"]) || empty($data["party"]) || empty($data["consent_disclosures"])) {
            return abort(404);
        }
        $pdf = PDF::loadView('pdf.consent_and_disclosures', $data);
        return $pdf->stream('document.pdf');
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


        $case->supporting_document = DB::table('manage_files')->select('manage_files.*', 'users.username')
            ->join('users', 'users.id', '=', 'manage_files.uploaded_by')
            ->where('manage_files.case_id', $case->id)
            ->get();


        return view('admin.case.casedetails', compact("case"));
    }

    public function confirmStatus(Request $request)
    {
        $medCas = MedCase::find($request->id);
        $medCas->confirm_status = 1;
        $medCas->case_status = 1;
        $medCas->save();

        $mediation_status_log = new Mediation_status_log;
        $mediation_status_log->user_id = Auth::user()->id;
        $mediation_status_log->mediation_case_id = $request->id;
        $mediation_status_log->status = 1;
        $mediation_status_log->description = "Request Confirm";
        $mediation_status_log->save();

        $reminder = new Reminder;
        $reminder->case_Id = $request->id;
        $reminder->save();

        // generate pdf
        $invitation = $this->invitation_mediate($request->id);

        $invmodel = InvitationFiles::where('case_id', $request->id)->orderByDesc('id')->limit(1)->first();
        if (!isset($invmodel)) {
            // dd("if");
            $invmodel = new InvitationFiles();
        }
        $invmodel->case_id = $request->id;
        $invmodel->file_name = $invitation;
        $invmodel->save();
        //send invitation 

        $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number", "users.id")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->where("mediators_mediation_cases_status.mediation_case_id", "=", $request->id)
            // ->where("mediators_mediation_cases_status.status", "=", 1)
            ->first();
        $inv_id = "";
        $inv = InvoledUser::select('id')->where('userPlanId', $request->id)->get();
        foreach ($inv as $v) {
            if ($inv_id == "") {
                $inv_id = $v->id;
            } else {
                $inv_id = $inv_id . "," . $v->id;
            }
        }
        Common_function::MedNotification($request->id, "ACPTARB_ADM", Auth::user()->id, isset($mediator) ? $mediator->id : null, $inv_id);


        $this->sned_invitation($request->id, $invitation);

        return response()->json(["msg" => "Onging Case"]);
    }

    public function withdrawStatus(Request $request)
    {
        $user = MedCase::find($request->case_id);
        $user->confirm_status = 2;
        $user->case_status = $request->status;
        $user->withdraw = $request->withdraw_comment;
        $user->save();

        $mediation_status_log = new Mediation_status_log;
        $mediation_status_log->user_id = Auth::user()->id;
        $mediation_status_log->mediation_case_id = $request->case_id;
        $mediation_status_log->status = $request->status;
        $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number", "users.id")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
        ->where("mediators_mediation_cases_status.mediation_case_id", "=", $request->case_id)
        ->where("mediators_mediation_cases_status.status", "=", 1)
        ->first();
        $inv_id = "";
        $inv = InvoledUser::select('id')->where('userPlanId', $request->case_id)->get();
        foreach ($inv as $v) {
            if ($inv_id == "") {
                $inv_id = $v->id;
            } else {
                $inv_id = $inv_id . "," . $v->id;
            }
        }
        if (Mediation_status_log::STATUS_WITHDRAWN == $request->status) {
            $mediation_status_log->description = "Request Withdrawn";
            if (Auth::user()->role == 1) {
                Common_function::MedNotification($request->case_id, "WDRN_BY_MED", Auth::user()->id, isset($mediator) ? $mediator->id : null, $inv_id);
            } else {
                Common_function::MedNotification($request->case_id, "WDRN_BY_ADMIN", Auth::user()->id, isset($mediator) ? $mediator->id : null, $inv_id);
            }
            $this->sned_withdrawal($request->case_id);
        } else if (Mediation_status_log::STATUS_RESOLVED == $request->status) {
            $mediation_status_log->description = "Request Resolved";
            if (Auth::user()->role == 1) {
                Common_function::MedNotification($request->case_id, "RES_BY_MED", Auth::user()->id,isset($mediator) ? $mediator->id : null, $inv_id);
            } else {
                Common_function::MedNotification($request->case_id, "RES_BY_ADMIN", Auth::user()->id, isset($mediator) ? $mediator->id : null, $inv_id);
            }
            $this->sned_resolved($request->case_id);
        } else if (Mediation_status_log::STATUS_UNRESOLVED == $request->status) {
            $mediation_status_log->description = "Request Unresolved";
            if (Auth::user()->role == 1) {
                Common_function::MedNotification($request->case_id, "UNRES_BY_MED", Auth::user()->id, isset($mediator) ? $mediator->id : null, $inv_id);
            } else {
                Common_function::MedNotification($request->case_id, "UNRES_BY_ADMIN", Auth::user()->id, isset($mediator) ? $mediator->id : null, $inv_id);
            }
            $this->sned_unresolved($request->case_id);
        }
        $mediation_status_log->save();

        return response()->json(["msg" => "withdraw Case"]);
    }

    public function closeStatus(Request $request)
    {
        $user = MedCase::find($request->id);
        $user->confirm_status = 2;
        $user->save();

        $mediation_status_log = new Mediation_status_log;
        $mediation_status_log->user_id = Auth::user()->id;
        $mediation_status_log->mediation_case_id = $request->id;
        $mediation_status_log->status = 2;
        $mediation_status_log->description = "Request Closed";
        $mediation_status_log->save();
        return response()->json(["msg" => "Closed Case"]);
    }

    public function commentAction(Request $request)
    {
        $mediation_case_comment = new Mediation_case_comment;
        $mediation_case_comment->user_id = Auth::user()->id;
        $mediation_case_comment->mediation_case_id = $request->case_id;
        $mediation_case_comment->type = $request->type;
        $mediation_case_comment->comment = $request->comment;
        $mediation_case_comment->save();
        $event = "";
        if (Auth::user()->role == 0) {
            if ($request->type == 0) {
                $event = "COMM_USER_SHARE";
            }
        } else if (Auth::user()->role == 1) {
            if ($request->type == 0) {
                $event = "COMM_MED_SHARE";
            } else {
                $event = "COMM_MED_PRIVATE";
            }
        } else {
            if ($request->type == 0) {
                $event = "COMM_ADM_SHARE";
            } else {
                $event = "COMM_ADM_PRIVATE";
            }
        }
        $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number", "users.id")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
        ->where("mediators_mediation_cases_status.mediation_case_id", "=", $request->case_id)
        ->where("mediators_mediation_cases_status.status", "=", 1)
        ->first();
        $inv_id = "";
        $inv = InvoledUser::select('id')->where('userPlanId', $request->case_id)->get();
        foreach ($inv as $v) {
            if ($inv_id == "") {
                $inv_id = $v->id;
            } else {
                $inv_id = $inv_id . "," . $v->id;
            }
        }
        // dd($event);
        if(Auth::user()->role == 1) {
            Common_function::MedNotification($request->case_id, $event, Auth::user()->id, Auth::user()->id, $inv_id);
        } else {
            Common_function::MedNotification($request->case_id, $event, Auth::user()->id, isset($mediator) ? $mediator->id : null, $inv_id);
        }
        return response()->json(["msg" => "Closed Case"]);
    }

    public function commentView(Request $request)
    {
        if ($request->type == 1) {
            if (Auth::user()->role == 2) {
                $view = Mediation_case_comment::where("mediation_case_id", $request->case_id)->where('type', 1)->where('view', 0)->get();
                if (isset($view)) {
                    foreach ($view as $value) {
                        $value->view = 1;
                        $value->save();
                    }
                }
            } else if (Auth::user()->role == 1) {

                $meditor_view = Mediation_case_comment::where("mediation_case_id", $request->case_id)->where('type', 1)->where('view_mediator', 0)->get();
                if (isset($meditor_view)) {
                    foreach ($meditor_view as $value) {
                        $value->view_mediator = 1;
                        $value->save();
                    }
                }
            }
            $mediation_case_comment = Mediation_case_comment::select("users.username", "mediation_case_comment.comment", DB::raw("DATE_FORMAT(mediation_case_comment.created_at,'%d-%c-%y %h:%i %p') as created"))->join("users", "mediation_case_comment.user_id", "=", "users.id")->where("type", $request->type)->where("mediation_case_id", $request->case_id)->get();
        } else {
            if (Auth::user()->role == 2) {
                $view_share = Mediation_case_comment::where("mediation_case_id", $request->case_id)->where('type', 0)->where('view', 0)->get();
                if (isset($view_share)) {
                    foreach ($view_share as $value) {
                        $value->view = 1;
                        $value->save();
                    }
                }
            } else if (Auth::user()->role == 1) {
                $mediator_view_share = Mediation_case_comment::where("mediation_case_id", $request->case_id)->where('type', 0)->where('view_mediator', 0)->get();
                if (isset($mediator_view_share)) {
                    foreach ($mediator_view_share as $value) {
                        $value->view_mediator = 1;
                        $value->save();
                    }
                }
            } else if (Auth::user()->role == 0) {
                $user_view_share = Mediation_case_comment::where("mediation_case_id", $request->case_id)->where('type', 0)->where('view_user', 0)->get();
                if (isset($user_view_share)) {
                    foreach ($user_view_share as $value) {
                        $value->view_user = 1;
                        $value->save();
                    }
                }
            }
            $mediation_case_comment = Mediation_case_comment::select("users.username", "mediation_case_comment.comment", DB::raw("DATE_FORMAT(mediation_case_comment.created_at,'%d-%c-%y %h:%i %p') as created"))->join("users", "mediation_case_comment.user_id", "=", "users.id")->where("type", $request->type)->where("mediation_case_id", $request->case_id)->get();
        }
        return response()->json($mediation_case_comment);
    }

    public function rejectStatus(Request $request)
    {
        $user = MedCase::find($request->id);
        $user->confirm_status = 3;
        $user->case_status = 3;
        $user->save();

        $mediation_status_log = new Mediation_status_log;
        $mediation_status_log->user_id = Auth::user()->id;
        $mediation_status_log->mediation_case_id = $request->id;
        $mediation_status_log->status = 3;
        $mediation_status_log->description = "Request Reject";
        $mediation_status_log->save();
        $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number", "users.id")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
        ->where("mediators_mediation_cases_status.mediation_case_id", "=", $request->id)
        ->where("mediators_mediation_cases_status.status", "=", 1)
        ->first();
        $inv_id = "";
        $inv = InvoledUser::select('id')->where('userPlanId', $request->id)->get();
        foreach ($inv as $v) {
            if ($inv_id == "") {
                $inv_id = $v->id;
            } else {
                $inv_id = $inv_id . "," . $v->id;
            }
        }
        Common_function::MedNotification($request->id, "REJECTED_ADM", Auth::user()->id, isset($mediator) ? $mediator->id : null, $inv_id);
        $this->sned_reject($request->id);
        return response()->json(["msg" => "Rejected Case"]);
    }

    public function storeMultiFile(Request $request)
    {


        $validatedData = $request->validate([
            'files' => 'required',
            'files.*' => 'mimes:csv,txt,xlx,xls,pdf,rar,zip',
            // 'docs_party_ids' => 'required',
        ]);

        if ($request->TotalFiles > 0) {

            for ($x = 0; $x < $request->TotalFiles; $x++) {

                if ($request->hasFile('files' . $x)) {
                    $file = $request->file('files' . $x);

                    $path = $file->storeAs('/supporting/' . $request->caseId, pathinfo(str_replace(" ", "_", $file->getClientOriginalName()), PATHINFO_FILENAME) . "_date_" . date("Y_m_d_H_i_s_a") . "." . $file->extension());
                    $insert[$x]['file_name'] = $path;
                    $insert[$x]['access'] = $request->docs_party_ids;
                    $insert[$x]['mediator_access'] = isset($request->shareMediator) ? $request->shareMediator : 0;
                    $insert[$x]['uploaded_by'] = Auth::user()->id;
                    $insert[$x]['case_id'] = $request->caseId;
                    // $insert[$x]['path'] = $path;
                }
            }
            // dd($insert);
            // die();
            // File::insert($insert);
            DB::table('manage_files')->insert($insert, $insert);
            $mediatorNoti = Mediators_mediation_cases_status::select("email", "username", "mobile_number", "users.id")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                ->where("mediators_mediation_cases_status.mediation_case_id", "=", $request->caseId)
                ->where("mediators_mediation_cases_status.status", "=", 1)
                ->first();
            $inv_id = "";
            $inv = InvoledUser::select('id')->where('userPlanId', $request->caseId)->get();
            foreach ($inv as $v) {
                if ($inv_id == "") {
                    $inv_id = $v->id;
                } else {
                    $inv_id = $inv_id . "," . $v->id;
                }
            }
            if($request->shareMediator == 1) {
                Common_function::MedNotification($request->caseId, "SEND_ADDI_DOC_ADMIN", Auth::user()->id, isset($mediatorNoti) ? $mediatorNoti->id : null, $inv_id);
            } else {
                Common_function::MedNotification($request->caseId, "SEND_ADDI_DOC_ADMIN", Auth::user()->id, null, $inv_id);
            }
            $this->send_upload_file_party($request->caseId, $insert);
            return response()->json(['success' => 'Ajax Multiple fIle has been uploaded']);
        } else {
            return response()->json(["message" => "Please try again."]);
        }
    }

    public function midaterAdd(Request $request)
    {
        $data = Mediators_mediation_cases_status::where("mediation_case_id", "=", $request->id)
            ->where(function ($q) {
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
            $MedCaseStatus = Mediators_mediation_cases_status::where(function ($q) {
                $q->where("status", "=", 0)
                    ->orWhere("status", "=", 1);
            })
                ->where("mediation_case_id", "=", $request->id)
                ->first();
            $MedCaseStatus->mediator_id = $request->midater;
            $MedCaseStatus->status = 0;
            $MedCaseStatus->save();
        }

        //generate pdf
        $invitation = $this->mediator_appointment($request->id, $request->midater);

        $invmodel = InvitationFiles::where('case_id', $request->id)->orderByDesc('id')->limit(1)->first();

        if (!isset($invmodel)) {
            $invmodel = new InvitationFiles();
        }
        $invmodel->case_id = $request->id;
        $invmodel->file_name_mediator_appointment = $invitation;
        $invmodel->save();
        // Common_function::MedNotification($request->id, "MEDI_ADD_ADM", Auth::user()->id);

        $this->send_mediatorAdd($request->id, $request->midater);
        return response()->json(["msg" => "midater Added"]);
    }

    public function mediator_appointment($id, $medid)
    {
        $data["mediator"] = User::find($medid);
        $data["case"] = MedCase::where("id", "=", $id)->first();
        $data["party"] = InvoledUser::where("userPlanId", "=", $id)->get();
        $pdf = PDF::loadView('pdf.mediator_appointment_letter', $data);
        $name = 'mediator_appoinment_letter_M' . sprintf('%06d', $data["case"]->id) . time() . '.pdf';
        Storage::put('public/mediation/' . $data["case"]->id . '/' . $name, $pdf->output());
        return $name;
    }

    public function addSession(Request $request)
    {
        // echo $request->zoomId;
        // dd($request->all());
        $time = date("g:i A", strtotime($request->sessionTime));
        $d = [
            'event' => 'SESS_SCHE',
            'case_id' => $request->caseId,
        ];
        if (isset($request->session_party_ids)) {
            $dataToInsert = [
                'case_id' => $request->caseId,
                'session_date' => $request->sessionDate . "/" . $time,
                'note' => $request->note,
                'zoom_id' => $request->zoomId,
                'session_party_ids' => json_encode($request->session_party_ids),
                'scheduled_by' => Auth::user()->id,
            ];
            $insertData = DB::table('manage_session')->insert($dataToInsert);
            if ($insertData) {
                $inv_id = "";
                foreach ($request->session_party_ids as $party_id) {
                    // dd($party_id);

                    $party = InvoledUser::where("userPlanId", $request->caseId)->where("id", $party_id)->first();
                    if ($inv_id == "") {
                        $inv_id = $party->id;
                    } else {
                        $inv_id = $inv_id . "," . $party->id;
                    }
                    $this->sned_session($request->zoomId, $request->caseId, $party->userEmail, $party->name, $request->sessionDate . "/" . $time, $party->userPhone);
                }
                $mediatorNoti = Mediators_mediation_cases_status::select("email", "username", "mobile_number", "users.id")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                ->where("mediators_mediation_cases_status.mediation_case_id", "=", $request->caseId)
                ->where("mediators_mediation_cases_status.status", "=", 1)
                ->first();
            Common_function::MedNotification($request->caseId, "SESS_SCHE_ADMIN", Auth::user()->id, isset($mediatorNoti) ? $mediatorNoti->id : null, $inv_id);
            }
        } else {
            $allParty = InvoledUser::where("userPlanId", $request->caseId)->get();
            $party_ids = array();
            foreach ($allParty as $party) {
                $party_ids[] = $party->id;
                $this->sned_session($request->zoomId, $request->caseId, $party->userEmail, $party->name, $request->sessionDate . "/" . $time, $party->userPhone);
            }
            // dd($party_ids);
            $dataToInsert = [
                'case_id' => $request->caseId,
                'session_date' => $request->sessionDate . "/" . $time,
                'note' => $request->note,
                'zoom_id' => $request->zoomId,
                'session_party_ids' => json_encode($party_ids),
                'scheduled_by' => Auth::user()->id,
            ];
            DB::table('manage_session')->insert($dataToInsert);
            $mediatorNoti = Mediators_mediation_cases_status::select("email", "username", "mobile_number", "users.id")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                ->where("mediators_mediation_cases_status.mediation_case_id", "=", $request->caseId)
                ->where("mediators_mediation_cases_status.status", "=", 1)
                ->first();
            $inv_id = "";
            $inv = InvoledUser::select('id')->where('userPlanId', $request->caseId)->get();
            foreach ($inv as $v) {
                if ($inv_id == "") {
                    $inv_id = $v->id;
                } else {
                    $inv_id = $inv_id . "," . $v->id;
                }
            }
            Common_function::MedNotification($request->caseId, "SESS_SCHE_ADMIN", Auth::user()->id, isset($mediatorNoti) ? $mediatorNoti->id : null, $inv_id);
        }

        $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->where("mediators_mediation_cases_status.mediation_case_id", "=", $request->caseId)
            ->where("mediators_mediation_cases_status.status", "=", 1)
            ->first();
        if ($mediator) {
            $id = "M" . sprintf("%06d", $request->caseId);
            SendGrid::send($d, $mediator->email, env('L10_SCHEDULING_OF_SESSION', ''), ["-caseid-" => $id, "-insert_date-" => $request->sessionDate . "/" . $time, "-type-" => "Mediator"], $mediator->username);

            $var = ['-dt-', '-cid-', '-link-'];
            $var1 = [$request->sessionDate . "/" . $time, $id, $request->zoomId];
            $content1 = WaTemplate::getcontent('l10_session_schedule');
            $content = str_replace($var, $var1, $content1);
            $dwa1 = [
                'caseid' => $request->caseId,
                'contact' => "+91" . $mediator->mobile_number,
                'content' => ['text' => $content],
                'event' => 'SESS_SCHE'
            ];

            // print_r($dwa1);
            // exit;
            $access = Whatsapp::sendWamessage($dwa1);
        }
        // DB::table('manage_session')->insert($dataToInsert);

        return true;
    }

    public function sessionPdf($id)
    {
        // dd($id);
        $data["case"] = MedCase::find($id);
        $data["party"] = InvoledUser::where("userPlanId", "=", $id)->get();
        $data["mediator"] = Mediators_mediation_cases_status::select("email", "username", "mobile_number", "users.first_name", "users.last_name")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
        ->where("mediators_mediation_cases_status.mediation_case_id", "=", $id)
        ->where("mediators_mediation_cases_status.status", "=", 1)
        ->first();
        $data['caseId'] = $id;
        $data["sessionData"] = DB::table('manage_session')->where('case_id', $id)->get();
        $pdf = PDF::loadView('pdf.view_session', $data);
        return $pdf->download('session_M' . sprintf('%06d', $id) . '.pdf');

        // dd($sessionData);
    }

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
                $dd = InvoledUser::where('id', $d)->where('userPlanId', $request->caseid)->first();
                if (isset($dd)) {
                    if ($dd->name != null) {
                        $user[] = $dd->name;
                    }
                } else {
                    $dd = InvoledUser::where('userId', $d)->where('userPlanId', $request->caseid)->first();
                    if (isset($dd)) {
                        if ($dd->name != null) {
                            $user[] = $dd->name;
                        }
                    }
                }
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

    public function json($role = 0)
    {
        $cases = MedCase::select("mediation_case.*", DB::raw("CONCAT(users.first_name,' ',users.last_name,' - ',users.organization) as mediator_username"), "mediators_mediation_cases_status.mediator_id as mediator_id", "mediators_mediation_cases_status.status as mediator_status", "consent_disclosures.updated_at as update", "consent_disclosures.created_at as create")
            ->leftJoin("mediators_mediation_cases_status", function ($join) {
                $join->on("mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id");
                $join->where("mediators_mediation_cases_status.id", "=", DB::raw("(select max(`mediators_mediation_cases_status2`.`id`) from mediators_mediation_cases_status as mediators_mediation_cases_status2 Where `mediators_mediation_cases_status2`.`mediation_case_id`=`mediation_case`.`id`)"));
            })
            ->leftJoin("consent_disclosures", "consent_disclosures.mediation_case_id", "=", "mediation_case.id")
            ->leftJoin("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->where("mediation_case.confirm_status", "=", $role)->orderBy('mediation_case.id', 'DESC')
            ->get();
        $arraydata = array();
        foreach ($cases as $key => $d) {
            $actionDate = date('d-m-Y', strtotime($d->update));
            $createDate = date('d-m-Y', strtotime($d->create));
            $arraydata[] = [
                "key" => $key + 1,
                "date" => date('d-m-Y', strtotime($d->created_at)),
                "mediator_action_date" => $actionDate,
                "mediator_create_action_date" => $createDate,
                "case" => $d,
                "party" => InvoledUser::select('id', 'name', 'isOnboarded', "userId")->where(['userPlanid' => $d->id])->get(),
                "status_log" => Mediation_status_log::select("status", "description", DB::raw("DATE_FORMAT(created_at,'%d-%c-%y %h:%i %p') as created"))->where(['mediation_case_id' => $d->id])->orderByDesc('id')->limit(1)->get(),
                "private_count" => Mediation_case_comment::where("type", "=", 1)->where('mediation_case_id', $d->id)->count(),
                "private_view_count" =>Mediation_case_comment::where("type", "=", 1)->where('mediation_case_id', $d->id)->where('view', 0)->count(),
                "share_count" => Mediation_case_comment::where("type", "=", 0)->where('mediation_case_id', $d->id)->count(),
                "share_view_count" => Mediation_case_comment::where("type", "=", 0)->where('mediation_case_id', $d->id)->where('view',0)->count(),
            ];
        }
        return response()->json(["data" => $arraydata]);
    }

    public function invitation_mediate($id)
    {
        $data["case"] = MedCase::where("id", "=", $id)->first();
        $data["party"] = InvoledUser::where("userPlanId", "=", $id)->get();
        $pdf = PDF::loadView('pdf.invitation_mediation', $data);
        $name = 'Invitation_mediate_M' . sprintf('%06d', $data["case"]->id) . time() . '.pdf';
        Storage::put('public/mediation/' . $data["case"]->id . '/' . $name, $pdf->output());
        return $name;
    }

    public function updatecase(Request $request, $id)
    {
        $d1 = [
            'event' => 'ACPTARB_ADM_RES',
            'case_id' => $id,
        ];

        $d2 = [
            'event' => 'ACPTARB_ADM_INI',
            'case_id' => $id,
        ];
        $med = MedCase::find($id);
        if (!$med) {
            return abort(404);
        }
        $usr = User::find($med->userid);
        $response = '';

        //fetch all involved users
        $InvoledUser = InvoledUser::where(['userPlanId' => $med->id])->where('isClaimant', '<>', '0')->get()->toArray();
        if ($request->method() == 'POST') {
            $r = $request->post();
            //udpate mediation case
            $med->proposedSolution = $r['proposedSolution'];
            $med->issue = $r['issue'];
            $med->updated_at = date("Y-m-d H:i:s");
            $med->save();
            //if user profile update
            $usr->address = $r['useraddress'];
            $usr->address1 = $r['useraddress1'];
            $usr->city = $r['usercity'];
            $usr->pincode = $r['userpincode'];
            $usr->state = $r['userstate'];
            $usr->country = $r['usercountry'];
            $usr->save();
            // update initiating party
            $inv = InvoledUser::where(['userPlanid' => $med->id, 'userId' => $usr->id])->first();
            if ($inv) {
                $inv->address1 = $usr->address;
                if ($usr->address1 == '') {
                    $usr->address1 = 'null';
                }
                $inv->address2 = $usr->address1;
                $inv->city = $usr->city;
                $inv->pincode = $usr->pincode;
                $inv->state = $usr->state;
                $inv->country = $usr->country;
                $inv->updated_at = date('Y-m-d H:s:i');
                $inv->save();
            }

            $pone = $inv;


            //update responding party




            for ($i = 0; $i < count($r['email']); $i++) {

                $invid = $r['invid'][$i];

                if ($invid != '') {

                    $inv = InvoledUser::find($invid);
                } else {
                    $inv = new InvoledUser();
                }



                //$inv->userId=;
                if ($inv->userEmail != $r['email'][$i] || $inv->userPhone != $r['phone'][$i]) {

                    $inv->joinCode = $this->joinCode();
                }

                $inv->userPlanId = $med->id;

                if ($inv->userEmail != $r['email'][$i]) {

                    $inv->userEmail = $r['email'][$i];

                    // $invitation = $this->invitation_mediate($id);

                    // $invmodel = new InvitationFiles();
                    // $invmodel->case_id = $request->id;
                    // $invmodel->file_name = $invitation;
                    // $invmodel->save();


                    // $code = $inv->joinCode;
                    // $s = SendGrid::send($d, $inv->userEmail, env('L4_INVITATION_TO_COUNTER_PARTIES_FOR_ONBOARDING', ''), ["-caseid-" => "M" . sprintf("%06d", $med->id), "-link-" => $inv->joinCode, "-initiating-" => $pone->name], $inv->name, url("/storage/app/public/mediation/" . $med->id . "/" . $invitation));
                }

                if ($inv->userPhone != $r['phone'][$i]) {
                    $inv->userPhone = $r['phone'][$i];
                }

                $inv->name = $r['name'][$i];
                if (isset($r['add1'][$i])) {
                    $inv->address1 = $r['add1'][$i];

                    if ($r['add2'][$i] == '') {
                        $r['add2'][$i] = 'null';
                    }
                    $inv->address2 = $r['add2'][$i];
                    $inv->city = $r['city'][$i];
                    $inv->pincode = $r['pincode'][$i];
                    $inv->state = $r['state'][$i];
                    $inv->country = $r['country'][$i];
                } elseif (isset($r['fulladdress'][$i])) {
                    $inv->fulladdress = $r['fulladdress'][$i];
                }
                $inv->isClaimant = $i + 1;



                $inv->created_at = date('Y-m-d H:s:i');
                $inv->updated_at = date('Y-m-d H:s:i');


                $inv->save();

                // $s = SendGrid::send($d, $inv->userEmail, env('L4_INVITATION_TO_COUNTER_PARTIES_FOR_ONBOARDING', ''), ["-caseid-" => "M" . sprintf("%06d", $med->id), "-link-" => $inv->joinCode, "-initiating-" => $pone->name], $inv->name, url("/storage/app/public/mediation/" . $med->id . "/" . $invitation));
            }

            //remove involed

            if ($r['rminv'] != '') {

                foreach (explode(',', $r['rminv']) as $key => $value) {

                    InvoledUser::find($value)->delete();
                }
            }

            $invitation = $this->invitation_mediate($id);

            $invmodel = InvitationFiles::where('case_id', $request->id)->orderByDesc('id')->limit(1)->first();

            if (!isset($invmodel)) {
                $invmodel = new InvitationFiles();
            }
            $invmodel->case_id = $request->id;
            $invmodel->file_name = $invitation;
            $invmodel->save();

            $InvoledUserMsg = InvoledUser::where(['userPlanId' => $med->id])->get();
            // dd($InvoledUserMsg);
            $responding_party = "";

            foreach ($InvoledUserMsg as $value) {

                if ($value->isClaimant > 0) {
                    // dd($value->name);
                    if ($responding_party == "") {
                        $responding_party = $value->name;
                    }

                    if ($value->userEmail != null) {
                        $s = SendGrid::send($d1, $value->userEmail, env('L4_INVITATION_TO_COUNTER_PARTIES_FOR_ONBOARDING', ''), ["-caseid-" => "M" . sprintf("%06d", $med->id), "-link-" => $value->joinCode, "-initiating-" => $pone->name], $value->name, url("/storage/app/public/mediation/" . $id . "/" . $invitation));
                    }
                    if ($value->userPhone != null) {

                        $var = ['-cid-', '-ip-'];
                        $var1 = ["M" . sprintf("%06d", $id), $pone->name];
                        $content1 = WaTemplate::getcontent('l4_mediation_party2');
                        $content = str_replace($var, $var1, $content1);
                        $dwa1 = [
                            'caseid' => $id,
                            'contact' => "+91" . $value->userPhone,
                            'content' => ['text' => $content],
                            'event' => 'ACPTARB_ADM_RES'
                        ];

                        $access = Whatsapp::sendWamessage($dwa1);

                        $var_file = ['-caseid-'];
                        $var1_file = ["M" . sprintf("%06d", $id)];
                        $content1_file = WaTemplate::getcontent('mediation_consent_doc');
                        $content_file = str_replace($var_file, $var1_file, $content1_file);
                        $dwa2 = [
                            'caseid' => $id,
                            'contact' => "+91" . $value->userPhone,
                            'content' => ['media' => ['url' => url("/storage/app/public/mediation/" . $id . "/" . $invitation), 'caption' => $content_file]],
                            'event' => 'ACPTARB_ADM_RES'
                        ];
                        $access = Whatsapp::sendWamessage($dwa2);
                    }
                }
            }
            // dd($initiating_phone);
            if ($responding_party != "") {
                // dd($pone);
                if ($pone->userEmail != "") {
                    SendGrid::send($d2, $pone->userEmail, env('L5_INVITATION_TO_INITI_PARTIES_FOR_ONBOARDING', ''), ["-caseid-" => "M" . sprintf("%06d", $id), "-responding-" => $responding_party], $pone->name, url("/storage/app/public/mediation/" . $id . "/" . $invitation));
                }

                if ($pone->userPhone != "") {

                    $var = ['-cid-', '-rp-'];
                    $var1 = ["M" . sprintf("%06d", $id), $responding_party];
                    $content1 = WaTemplate::getcontent('l4_mediation_initiating');
                    $content = str_replace($var, $var1, $content1);
                    $dwa1 = [
                        'caseid' => $id,
                        'contact' => "+91" . $pone->userPhone,
                        'content' => ['text' => $content],
                        // 'casetype' => 2,
                        'event' => 'ACPTARB_ADM_INI'
                    ];

                    $access = Whatsapp::sendWamessage($dwa1);
                    $var_file = ['-caseid-'];
                    $var1_file = ["M" . sprintf("%06d", $id)];
                    $content1_file = WaTemplate::getcontent('mediation_consent_doc');
                    $content_file = str_replace($var_file, $var1_file, $content1_file);
                    $dwa2 = [
                        'caseid' => $id,
                        'contact' => "+91" . $pone->userPhone,
                        'content' => ['media' => ['url' => url("/storage/app/public/mediation/" . $id . "/" . $invitation), 'caption' => $content_file]],
                        'event' => 'ACPTARB_ADM_INI'
                    ];
                    $access = Whatsapp::sendWamessage($dwa2);
                }
            }

            $InvoledUser = InvoledUser::where(['userPlanId' => $med->id])->where('isClaimant', '<>', '0')->get()->toArray();

            $response = 'success';
        }

        return view('admin.case.updatecase', ['user' => $usr, 'InvoledUser' => $InvoledUser, 'medcase' => $med, 'response' => $response]);
    }

    public function joinCode()
    {

        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $string = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < 8; $i++) {
            $string .= $characters[mt_rand(0, $max)];
        }

        return $string;
    }

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

    public function settelmenUpload(Request $request)
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
            $mediatorNoti = Mediators_mediation_cases_status::select("email", "username", "mobile_number", "users.id")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                ->where("mediators_mediation_cases_status.mediation_case_id", "=", $request->caseId)
                ->where("mediators_mediation_cases_status.status", "=", 1)
                ->first();
            $inv_id = "";
            $inv = InvoledUser::select('id')->where('userPlanId', $request->caseId)->get();
            foreach ($inv as $v) {
                if ($inv_id == "") {
                    $inv_id = $v->id;
                } else {
                    $inv_id = $inv_id . "," . $v->id;
                }
            }
            Common_function::MedNotification($request->caseId, "SEND_SETT_AGRE_ADMIN", Auth::user()->id, isset($mediatorNoti) ? $mediatorNoti->id : null, $inv_id);

            $this->send_settlement_agreement_party($request->caseId, $insert);
            return response()->json(["message" => 'Ajax Multiple fIle has been uploaded']);
        } else {
            return response()->json(["message" => "Please try again."]);
        }
    }

    public function sned_invitation($id, $invitation)
    {
        $involedUser = InvoledUser::where("userPlanId", $id)->get();

        $initiating_party = "";
        $initiating_phone = "";
        $initiating_email = "";
        $responding_party = "";
        $ini_userPlanId = "";
        $responding_email = [];
        $responding_phone = [];
        $d1 = [
            'event' => 'ACPTARB_ADM_INI',
            'case_id' => $id,
        ];
        $d2 = [
            'event' => 'ACPTARB_ADM_RES',
            'case_id' => $id,
        ];
        // $mid = "M" . sprintf("%06d", $id);
        // $responding_phone = "";
        foreach ($involedUser as $inv) {
            if ($inv->isClaimant == 0) {
                $initiating_party = $inv->name;
                $ini_userPlanId = $inv->userPlanId;
                $initiating_phone = $inv->userPhone;
                $initiating_email = $inv->userEmail;
            } else if ($inv->isOnboarded == 0) {
                $code = $inv->joinCode;
                if ($inv->name != "") {
                    if ($responding_party == "") {
                        $responding_party = $inv->name;
                    }
                }
                $responding_phone[] = $inv->userPhone;
                if ($inv->userEmail != "") {
                    SendGrid::send($d2, $inv->userEmail, env('L4_INVITATION_TO_COUNTER_PARTIES_FOR_ONBOARDING', ''), ["-caseid-" => "M" . sprintf("%06d", $id), "-link-" => $inv->joinCode, "-initiating-" => $initiating_party], $inv->name, url("/storage/app/public/mediation/" . $id . "/" . $invitation));
                }
                // SendGrid::send($inv->userEmail, env('L4_INVITATION_TO_COUNTER_PARTIES_FOR_ONBOARDING', ''), ["-caseid-" => "M" . sprintf("%06d", $id), "-link-" => $code, "-initiating-" => $initiating_party], $inv->name, url("/storage/app/public/mediation/" . $id . "/" . $invitation));


                // $dwa2 = [
                //     'caseid' => $inv->userPlanId,
                //     'contact' => "+91" . $inv->userPhone,
                //     'content' => ['media' => ['url' => url("/storage/app/public/mediation/" . $id . "/" . $invitation), 'caption' => 'Invitation to Mediate ' . Common_function::getsixdigitid('sc', $inv->userPlanId)]],
                //     'event' => 'ACPTARB_ADM'
                // ];
                // $access = Whatsapp::sendWamessage($dwa2);
            }
            // continue;

        }

        foreach ($responding_phone as $phone) {
            if ($phone != "") {
                $var = ['-cid-', '-ip-'];
                $var1 = ["M" . sprintf("%06d", $id), $initiating_party];
                $content1 = WaTemplate::getcontent('l4_mediation_party2');
                $content = str_replace($var, $var1, $content1);
                $dwa1 = [
                    'caseid' => $inv->userPlanId,
                    'contact' => "+91" . $phone,
                    'content' => ['text' => $content],
                    'event' => 'ACPTARB_ADM_RES'
                ];

                $access = Whatsapp::sendWamessage($dwa1);

                $var_file = ['-caseid-'];
                $var1_file = ["M" . sprintf("%06d", $id)];
                $content1_file = WaTemplate::getcontent('mediation_consent_doc');
                $content_file = str_replace($var_file, $var1_file, $content1_file);
                $dwa2 = [
                    'caseid' => $ini_userPlanId,
                    'contact' => "+91" . $phone,
                    'content' => ['media' => ['url' => url("/storage/app/public/mediation/" . $id . "/" . $invitation), 'caption' => $content_file]],
                    'event' => 'ACPTARB_ADM_RES'
                ];
                $access = Whatsapp::sendWamessage($dwa2);
            }
        }


        if ($responding_party != "") {

            SendGrid::send($d1, $initiating_email, env('L5_INVITATION_TO_INITI_PARTIES_FOR_ONBOARDING', ''), ["-caseid-" => "M" . sprintf("%06d", $id), "-responding-" => $responding_party], $inv->name, url("/storage/app/public/mediation/" . $id . "/" . $invitation));


            $var = ['-cid-', '-rp-'];
            $var1 = ["M" . sprintf("%06d", $id), $responding_party];
            $content1 = WaTemplate::getcontent('l4_mediation_initiating');
            $content = str_replace($var, $var1, $content1);
            $dwa1 = [
                'caseid' => $ini_userPlanId,
                'contact' => "+91" . $initiating_phone,
                'content' => ['text' => $content],
                // 'casetype' => 2,
                'event' => 'ACPTARB_ADM_INI'
            ];

            $access = Whatsapp::sendWamessage($dwa1);

            $var_file = ['-caseid-'];
            $var1_file = ["M" . sprintf("%06d", $id)];
            $content1_file = WaTemplate::getcontent('mediation_consent_doc');
            $content_file = str_replace($var_file, $var1_file, $content1_file);
            $dwa2 = [
                'caseid' => $ini_userPlanId,
                'contact' => "+91" . $initiating_phone,
                'content' => ['media' => ['url' => url("/storage/app/public/mediation/" . $id . "/" . $invitation), 'caption' => $content_file]],
                'event' => 'ACPTARB_ADM_INI'
            ];
            $access = Whatsapp::sendWamessage($dwa2);
        }


        // exit;
        return true;
    }

    public function sned_reject($id)
    {
        $involedUser = InvoledUser::where("userPlanId", $id)->get();
        $initiating_party = "";
        $d = [
            'event' => 'REJECTED_ADM',
            'case_id' => $id,
        ];
        foreach ($involedUser as $inv) {
            if ($inv->isClaimant == 0) {
                $party_name = $inv->name;
                $id = "M" . sprintf("%06d", $id);
                SendGrid::send($d, $inv->userEmail, env('L8_CASE_REJECTED', ''), ["-caseid-" => $id, "-responding-" => $party_name], $inv->name);
            }
        }
    }

    public function sned_session($url, $id, $email_id, $email_name, $date, $userPhone)
    {
        $mid = "M" . sprintf("%06d", $id);
        $d = [
            'event' => 'SESS_SCHE',
            'case_id' => $id,
        ];
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
                'event' => 'SESS_SCHE'
            ];

            // print_r($dwa1);
            // exit;

            $access = Whatsapp::sendWamessage($dwa1);
        }
        return true;
    }

    public function sned_withdrawal($id)
    {
        $involedUser = InvoledUser::where("userPlanId", $id)->get();
        $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->where("mediators_mediation_cases_status.mediation_case_id", "=", $id)
            ->where("mediators_mediation_cases_status.status", "=", 1)
            ->first();
        $mid = "M" . sprintf("%06d", $id);

        $initiating_party = "";
        $initiating_phone = "";
        $responding_party = "";
        $initiating_email = "";
        $responding_email = [];
        $responding_phone = [];
        $d1 = [
            'event' => 'WDRN_PARTY',
            'case_id' => $id,
        ];
        $d2 = [
            'event' => 'WDRN_OTHER_PARTY',
            'case_id' => $id,
        ];
        $d3 = [
            'event' => 'WDRN_MED',
            'case_id' => $id,
        ];
        foreach ($involedUser as $inv) {
            if ($inv->isClaimant == 0) {
                $initiating_party = $inv->name;
                $initiating_phone = $inv->userPhone;
                $initiating_email = $inv->userEmail;

                SendGrid::send($d1, $inv->userEmail, env('L13_WITHDRAWAL_OF_CASE', ''), ["-caseid-" => $mid, "-type-" => "Party"], $inv->name);
            } else {
                if ($inv->name != "") {
                    $responding_party = $inv->name;
                }
                $responding_email[] = $inv->userEmail;
                $responding_phone[] = $inv->userPhone;
            }
        }

        if (isset($responding_email)) {
            foreach ($responding_email as $email) {
                if ($email != "") {
                    SendGrid::send($d2, $email, env('L14_COMMUNICATION_OF_WITHDRAWAL_TO_OTHER_PARTIES', ''), ["-caseid-" => $mid, "-partyname-" => $initiating_party, "-type-" => "Party"], $inv->name);
                }
            }
        }

        if (isset($responding_phone)) {
            foreach ($responding_phone as $phone) {
                if ($phone != "") {
                    $var = ['-cid-', '-cl-'];
                    $var1 = [$mid, $initiating_party];
                    $content1 = WaTemplate::getcontent('withdrawal_responding');
                    $content = str_replace($var, $var1, $content1);
                    $dwa1 = [
                        'caseid' => $id,
                        'contact' => "+91" . $phone,
                        'content' => ['text' => $content],
                        'event' => 'WDRN_OTHER_PARTY'
                    ];

                    // print_r($dwa1);
                    // exit;
                    $access = Whatsapp::sendWamessage($dwa1);
                }
            }
        }

        if ($responding_party != "") {
            $var = ['-cid-', '-rp-'];
            $var1 = [$mid, $responding_party];
            $content1 = WaTemplate::getcontent('withdrawal_initiating');
            $content = str_replace($var, $var1, $content1);
            $dwa1 = [
                'caseid' => $id,
                'contact' => "+91" . $initiating_phone,
                'content' => ['text' => $content],
                // 'casetype' => 2,
                'event' => 'WDRN_PARTY'
            ];

            // print_r($dwa1);
            // exit;

            $access = Whatsapp::sendWamessage($dwa1);
        }
        if ($mediator) {
            SendGrid::send($d3, $mediator->email, env('L13_WITHDRAWAL_OF_CASE', ''), ["-caseid-" => $mid, "-responding-" => $initiating_party, "-type-" => "Mediator"], $mediator->username);

            $var = ['-cid-'];
            $var1 = [$mid];
            $content1 = WaTemplate::getcontent('withdrawal_mediator');
            $content = str_replace($var, $var1, $content1);
            $dwa1 = [
                'caseid' => $id,
                'contact' => "+91" . $mediator->mobile_number,
                'content' => ['text' => $content],
                // 'casetype' => 2,
                'event' => 'WDRN_MED'
            ];

            // print_r($dwa1);
            // exit;

            $access = Whatsapp::sendWamessage($dwa1);
        }
        return true;
    }

    public function sned_resolved($id)
    {
        $involedUser = InvoledUser::where("userPlanId", $id)->get();
        $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->where("mediators_mediation_cases_status.mediation_case_id", "=", $id)
            ->where("mediators_mediation_cases_status.status", "=", 1)
            ->first();
        $mid = "M" . sprintf("%06d", $id);
        $initiating_party = "";
        $d = [
            'event' => 'RESO_ADM',
            'case_id' => $id,
        ];
        foreach ($involedUser as $inv) {
            if ($inv->isClaimant == 0) {
                $initiating_party = $inv->name;
            }
            if ($inv->userEmail != "") {
                SendGrid::send($d, $inv->userEmail, env('L15_CASE_RESOLVED', ''), ["-caseid-" => $mid, "-responding-" => $initiating_party, "-type-" => "Party"], $inv->name);
            }

            if ($inv->userPhone != "") {
                $var = ['-cid-'];
                $var1 = [$mid];
                $content1 = WaTemplate::getcontent('med_resolved_clamant');
                $content = str_replace($var, $var1, $content1);
                $dwa1 = [
                    'caseid' => $id,
                    'contact' => "+91" . $inv->userPhone,
                    'content' => ['text' => $content],
                    'event' => 'RESO_ADM'
                ];

                $access = Whatsapp::sendWamessage($dwa1);
            }
        }
        if ($mediator) {
            SendGrid::send($d, $mediator->email, env('L15_CASE_RESOLVED', ''), ["-caseid-" => $mid, "-responding-" => $initiating_party, "-type-" => "Mediator"], $mediator->username);
            $var = ['-cid-'];
            $var1 = [$mid];
            $content1 = WaTemplate::getcontent('med_resolved_clamant');
            $content = str_replace($var, $var1, $content1);
            $dwa1 = [
                'caseid' => $id,
                'contact' => "+91" . $mediator->mobile_number,
                'content' => ['text' => $content],
                'event' => 'RESO_ADM'
            ];

            $access = Whatsapp::sendWamessage($dwa1);
        }
        return true;
    }

    public function sned_unresolved($id)
    {
        $involedUser = InvoledUser::where("userPlanId", $id)->get();
        $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->where("mediators_mediation_cases_status.mediation_case_id", "=", $id)
            ->where("mediators_mediation_cases_status.status", "=", 1)
            ->first();

        $mid = "M" . sprintf("%06d", $id);
        $initiating_party = "";
        $d = [
            'event' => 'UNRESO_ADM',
            'case_id' => $id,
        ];
        foreach ($involedUser as $inv) {
            if ($inv->isClaimant == 0) {
                $initiating_party = $inv->name;
            }
            if ($inv->userEmail != "") {
                SendGrid::send($d, $inv->userEmail, env('L15_CASE_UNRESOLVED', ''), ["-caseid-" => $mid, "-responding-" => $initiating_party, "-type-" => "Party"], $inv->name);
            }
            if ($inv->userPhone != "") {
                $var = ['-cid-'];
                $var1 = [$mid];
                $content1 = WaTemplate::getcontent('med_unresolved_clamant');
                $content = str_replace($var, $var1, $content1);
                $dwa1 = [
                    'caseid' => $id,
                    'contact' => "+91" . $inv->userPhone,
                    'content' => ['text' => $content],
                    'event' => 'UNRESO_ADM'
                ];

                $access = Whatsapp::sendWamessage($dwa1);
            }
        }
        if ($mediator) {
            SendGrid::send($d, $mediator->email, env('L15_CASE_UNRESOLVED', ''), ["-caseid-" => $id, "-responding-" => $initiating_party, "-type-" => "Mediator"], $mediator->username);
            $var = ['-cid-'];
            $var1 = [$mid];
            $content1 = WaTemplate::getcontent('med_unresolved_clamant');
            $content = str_replace($var, $var1, $content1);
            $dwa1 = [
                'caseid' => $id,
                'contact' => "+91" . $mediator->mobile_number,
                'content' => ['text' => $content],
                'event' => 'UNRESO_ADM'
            ];

            $access = Whatsapp::sendWamessage($dwa1);
        }
        return true;
    }

    public function send_mediatorAdd($id, $mediator_id)
    {
        $user = User::where("id", $mediator_id)->first();
        $mid = "M" . sprintf("%06d", $id);
        $d = [
            'event' => 'MEDI_ADD_ADM',
            'case_id' => $id,
        ];
        SendGrid::send($d, $user->email, env('L17_WHEN_ADMIN_SELECTS_MEDIATOR', ''), ["-caseid-" => $mid], $user->name);
        $var = ['-cid-'];
        $var1 = [$mid];
        $content1 = WaTemplate::getcontent('consent_mediator');
        $content = str_replace($var, $var1, $content1);
        $dwa1 = [
            'caseid' => $id,
            'contact' => "+91" . $user->mobile_number,
            'content' => ['text' => $content],
            'event' => 'MEDI_ADD_ADM'
        ];

        $access = Whatsapp::sendWamessage($dwa1);
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
        // $access = array();

        $d = [
            'event' => 'SEND_ADDI_DOC',
            'case_id' => $id,
        ];
        foreach ($files as $f) {
            $filesE[] = url("storage/app/" . $f["file_name"]);
            $access = explode(',', $f["access"]);
            $mediatorAccess = $f["mediator_access"];
        }
        foreach ($involedUser as $inv) {
            if (is_array($access) && in_array($inv->id, $access)) {

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
                        'event' => 'SEND_ADDI_DOC'
                    ];
                    $accessW = Whatsapp::sendWamessage($dwa1);
                    foreach ($filesE as $file) {

                        $var_file = ['-caseid-'];
                        $var1_file = [$mid];
                        $content1_file = WaTemplate::getcontent('mediation_consent_doc');
                        $content_file = str_replace($var_file, $var1_file, $content1_file);
                        $dwa2 = [
                            'caseid' => $id,
                            'contact' => "+91" . $inv->userPhone,
                            'content' => ['media' => ['url' => $file, 'caption' => $content_file]],
                            'event' => 'SEND_ADDI_DOC'
                        ];
                        $accessW = Whatsapp::sendWamessage($dwa2);
                    }
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
        if ($mediator) {
            if ($mediatorAccess == 1) {

                // $sendEamils[] = $mediator->email;
                $d1 = [
                    'event' => 'SEND_ADDI_DOC_MED',
                    'case_id' => $id,
                ];
                SendGrid::send($d1, $mediator->email, env('L19_ADDITIONAL_DOC_ALL_PARTIES', ''), ["-caseid-" => $mid], null, $filesE);

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
                $accessW = Whatsapp::sendWamessage($dwa1);
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
                    $accessW = Whatsapp::sendWamessage($dwa2);
                }
            }
        }

        if (!empty($sendEamils)) {
            foreach ($sendEamils as $email) {
                SendGrid::send($d, $email, env('L19_ADDITIONAL_DOC_ALL_PARTIES', ''), ["-caseid-" => $mid], null, $filesE);
            }
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
        $d = [
            'event' => 'SEND_SETT_AGRE',
            'case_id' => $id,
        ];
        foreach ($files as $f) {
            $filesE[] = url("storage/app/" . $f["file_path"]);
        }
        foreach ($involedUser as $inv) {
            if ($inv->userEmail != "") {
                $sendEamils[] = $inv->userEmail;
            }

            if ($inv->userPhone != "") {
                // settlement agreement
                $var = ['-cid-'];
                $var1 = [$mid];
                $content1 = WaTemplate::getcontent('settlement_agreement');
                $content = str_replace($var, $var1, $content1);
                $dwa1 = [
                    'caseid' => $id,
                    'contact' => "+91" . $inv->userPhone,
                    'content' => ['text' => $content],
                    'event' => 'SEND_SETT_AGRE'
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
                        'event' => 'SEND_SETT_AGRE'
                    ];
                    $access = Whatsapp::sendWamessage($dwa2);
                }
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
            // $sendEamils[] = $mediator->email;
            $d1 = [
                'event' => 'SEND_SETT_AGRE_MED',
                'case_id' => $id,
            ];
            SendGrid::send($d1, $mediator->email, env('L21_SETTLEMENT_AGREEMENT_ALL_PARTIES', ''), ["-caseid-" => $mid], null, $filesE);

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

            foreach ($filesE as $file) {
                $var_file = ['-caseid-'];
                $var1_file = [$mid];
                $content1_file = WaTemplate::getcontent('mediation_consent_doc');
                $content_file = str_replace($var_file, $var1_file, $content1_file);
                $dwa2 = [
                    'caseid' => $id,
                    'contact' => "+91" . $mediator->mobile_number,
                    'content' => ['media' => ['url' => $file, 'caption' => $content_file]],
                    'event' => 'SEND_SETT_AGRE_MED'
                ];
                $access = Whatsapp::sendWamessage($dwa2);
            }
        }

        foreach ($sendEamils as $email) {
            SendGrid::send($d, $email, env('L21_SETTLEMENT_AGREEMENT_ALL_PARTIES', ''), ["-caseid-" => $mid], null, $filesE);
        }

        return true;
    }
    public function csvToArray($file)
    {
        $rows = array();
        $headers = array();
        if (file_exists($file) && is_readable($file)) {
            $handle = fopen($file, 'r');
            // dd($handle);
            while (!feof($handle)) {
                $row = fgetcsv($handle, 10240, ',', '"');


                if (empty($headers))
                    $headers = $row;
                else if (is_array($row)) {
                    array_splice($row, count($headers));
                    //$rows[] = array_combine($headers, $row);
                    $rows[] = $row;
                }
            }
            fclose($handle);
        } else {
            throw new Exception($file . ' doesn`t exist or is not readable.');
        }
        return $rows;
    }

    public function bulkUpload(Request $request)
    {
        $_SESSION['last_uploaded_id'] = '';

        $uploaded_excel = '';
        $claimantid = $request->claimant;
        // dd($claimantid);
        $cldetails = User::find($claimantid);

        $selectCsv = $request->file('csv');
        $tmpName = $selectCsv->getPathname();

        $ext = pathinfo($selectCsv->getClientOriginalName(), PATHINFO_EXTENSION);
        $errormsg = '';
        // dd($ext);

        if ($ext != 'csv') {
            $errormsg .= 'Please upload csv file';
        }
        if ($errormsg == '') {
            $csv = $this->csvToArray($tmpName);
            if (count($csv[0]) != 15) {
                $errormsg .= "Invalid csv file";
            }

            $errormsg .= '';
            foreach ($csv as $key => $v) {
                $i = $key + 1;

                for ($n = 0; $n < 15; $n++) {
                    if ($v[$n] == '') {

                        if ($n != 10 and $n != 11 and $n != 12 and $n != 7) {
                            $errormsg .= "Please fill all the required details to proceed at line no $i";
                        }
                    }
                }
                if ($v[3] == '') {
                    $errormsg .= "Please Enter EmailId at line no $i ";
                }
                if (!filter_var($v[4], FILTER_SANITIZE_NUMBER_INT)) {
                    $errormsg .= "Invalid mobile number at line no $i ";
                }

                if (strlen($v[4]) != 10) {
                    $errormsg .= "Invalid mobile number at line no $i ";
                }

                // validate pincode
                // if (!filter_var($v[8], FILTER_SANITIZE_NUMBER_INT)) {
                //     $errormsg .= "Invalid pincode at line no $i ";
                // }

                // if (strlen($v[8]) != 6) {
                //     $errormsg .= "Invalid pincode at line no $i ";
                // }

                //validate date
                if ($v[7] != "") {

                    if (strpos($v[7], '-')) {
                        $dt = str_replace('-', '/', $v[7]);
                        $v[7] = $dt;
                    }

                    $dt = explode('/', $v[7]);

                    if (count($dt) != 3 and strlen($dt[0]) != 2 and strlen($dt[1]) != 2 and strlen($dt[0]) != 4) {

                        $errormsg .= "Invalid date at line no $i. date format should be dd/mm/YYYY ";
                    }
                }

                if ($v[13] != 'Yes') {
                    $errormsg .= "Please confirm that the details provided above are true, accurate, current and complete to proceed at line no $i ";
                }

                if ($v[14] != 'Yes') {

                    $errormsg .= "Please accept and agree to abide by Mediation’s Dispute Resolution Rules, Terms & Conditions and Privacy Policy to proceed at line no $i ";
                }
            }
        }
        if ($errormsg != '') {

            return redirect('/admin/case/new-request')->with(['error' => $errormsg]);

            exit();
        }
        if (1 == 1) {

            //save file
            $file = $request->file('csv');
            $destinationPath = 'public/uploaded';

            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;

            if ($file->storeAs($destinationPath, $fileName)) {
                $uploaded_excel .= $fileName;
            }
        }
        //store in database
        foreach ($csv as $k => $value) {
            // dd( count(explode(',', $value[15])) + 1);
            // exit;

            $data['userid'] = $claimantid;
            $data['disputeCategory'] = $value['0'];
            $data['natureOfAgreement'] = $value['6'];
            $data['agreementDate'] = $value['7'];
            $data['noOfParties'] = count(explode(',', $value[10])) + 1;
            $data['amount'] = $value['1'];
            $data['issue'] = $value['8'];
            $data['confirm_status'] = 0;
            $data['otherRespondentDetails'] = $value[12];
            $data['proposedSolution'] = $value[9];
            $med = MedCase::create($data);

            $iniParty = InvoledUser::where(['userPlanid' => $med->id, 'userId' => $claimantid])->first();

            if (!$iniParty) {
                // add initiating party
                $iniParty = new InvoledUser();

                $iniParty->userId = $cldetails->id;
                $iniParty->userPlanId = $med->id;
                $iniParty->userEmail = $cldetails->email;
                $iniParty->userPhone = $cldetails->mobile_number;
                $iniParty->name = $cldetails->first_name . ' ' . $cldetails->last_name;
                if (isset($cldetails->address)) {
                    $iniParty->address1 = $cldetails->address;
                } else {
                    $iniParty->address1 = '';
                }
                if (isset($cldetails->address1)) {
                    $iniParty->address2 = $cldetails->address1;
                } else {
                    $iniParty->address2 = '';
                }
                if (isset($cldetails->city)) {
                    $iniParty->city = $cldetails->city;
                } else {
                    $iniParty->city = '';
                }
                if (isset($cldetails->pincode)) {
                    $iniParty->pincode = $cldetails->pincode;
                } else {
                    $iniParty->pincode = '';
                }
                if (isset($cldetails->state)) {
                    $iniParty->state = $cldetails->state;
                } else {
                    $iniParty->state = '';
                }
                if (isset($cldetails->country)) {
                    $iniParty->country = $cldetails->country;
                } else {
                    $iniParty->country = '';
                }
                $iniParty->isOnboarded = 1;
                // $iniParty->address2 = $cldetails->address1;
                // $iniParty->city = $cldetails->city;
                // $iniParty->pincode = $cldetails->pincode;
                // $iniParty->state = $cldetails->state;
                // $iniParty->country = $cldetails->country;
                $iniParty->created_at = date('Y-m-d H:s:i');
                $iniParty->updated_at = date('Y-m-d H:s:i');
                $iniParty->save();
            }
            //add responding party
            $resParty = new InvoledUser();
            $resParty->userPlanId = $med->id;
            $resParty->userEmail = $value['3'];
            $resParty->userPhone = $value['4'];
            $resParty->name = $value['2'];
            $resParty->joinCode = $this->joinCode();
            $resParty->fulladdress = $value['5'];
            // $resParty->address2 = $value['6'];
            // $resParty->city = $value['7'];
            // $resParty->pincode = $value['8'];
            // $resParty->state = $value['9'];
            // $resParty->country = $value['10'];
            $resParty->isClaimant = 1;
            $resParty->created_at = date('Y-m-d H:s:i');
            $resParty->updated_at = date('Y-m-d H:s:i');
            $resParty->save();


            $otherResEmail = explode(',', $value[10]);
            $otherResMobile = explode(',', $value[11]);

            $forloopcnt = max(count($otherResEmail), count($otherResMobile));

            if ($otherResEmail[0] != "" or $otherResMobile[0] != "") {

                for ($i = 0; $i < $forloopcnt; $i++) {
                    // for($j = 0; $j < count($otherResMobile); $j++) {


                    $otherDetails = new InvoledUser();
                    $otherDetails->userPlanId = $med->id;
                    $otherDetails->userEmail = isset($otherResEmail[$i]) ? trim($otherResEmail[$i])  : "";
                    $otherDetails->userPhone = isset($otherResMobile[$i]) ? trim($otherResMobile[$i]) : "";
                    $otherDetails->joinCode = $this->joinCode();
                    $otherDetails->isClaimant = $i + 1;
                    $otherDetails->created_at = date('Y-m-d H:s:i');
                    $otherDetails->updated_at = date('Y-m-d H:s:i');
                    $otherDetails->save();
                }
            }

            $letter = $this->requestLetter($med->id);
            $med->request_letter = $letter;
            $med->save();
        }

        return redirect('/admin/case/new-request')->with(['success' => 'Success']);
    }

    public function requestLetter($id)
    {
        // $data["mediator"] = User::find($medid);
        $data["case"] = MedCase::where("id", "=", $id)->first();
        $data["ini"] = InvoledUser::select('user_involved_in_agreement.*', 'usr.organization')
            ->leftJoin('users as usr', DB::raw('usr.id'), '=', DB::raw('user_involved_in_agreement.userId'))
            ->where("userPlanId", "=", $id)->where('isClaimant', 0)->first();
        $data["res"] = InvoledUser::where("userPlanId", "=", $id)->where('isClaimant', '<>', 0)->first();
        $pdf = PDF::loadView('pdf.request_letter', $data);
        $name = 'request_letter_M' . sprintf('%06d', $data["case"]->id) . time() . '.pdf';
        Storage::put('public/mediation/' . $data["case"]->id . '/' . $name, $pdf->output());
        return $name;
    }

    public function documentUpload(Request $request, $id)
    {

        $selectDocument = $request->file('document');

        $errormsg = '';

        $med = MedCase::find($id);

        if ($selectDocument !== null) {

            $ext = pathinfo($selectDocument->getClientOriginalName(), PATHINFO_EXTENSION);

            // dd($ext);

            if ($ext != 'pdf' && $ext != 'zip' && $ext != 'rar') {
                $errormsg .= 'Please upload pdf, rar and zip file';
            } else {
                $filename = 'supporting_document' . $med->id . time() . '.' . $selectDocument->getClientOriginalExtension();
                // dd($filename);

                $path = $request->file('document')->storeAs('public/mediation/' . $med->id . '/', $filename);
                $med->documentPath = $filename;
                $med->save();
                return redirect('/admin/case/new-request')->with(['success' => 'Success']);
            }
            // $errormsg .= $request->validate([
            //     'document' => 'mimes:pdf,zip,rar|max:20048',
            // ]);


        } else {
            $errormsg .= "Please Select Document";
        }

        if ($errormsg != '') {

            return redirect('/admin/case/new-request')->with(['error' => $errormsg]);

            exit();
        }
    }

    public function track($id)
    {
        $whatsapp = WhatsappTrack::getByCaseIdWh($id);
        // $casedetails = MedCase::getcasebyId($id);
        $email = EmailTrack::getByCaseId($id);
        // dd($email);

        $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->where("mediators_mediation_cases_status.mediation_case_id", "=", $id)
            ->where("mediators_mediation_cases_status.status", "=", 1)
            ->first();

        $data=[];
        $data['auth']="MED360AUTH";
        $data['app']="P360MED";
        $data['caseid']=$id;
        $url="https://presolv360.com/functions/ivrtrack.php";

        $ivr = json_decode(Curl::getdata($url, $data, 'POST', 'MED360AUTH'),true);

        

        // dd($whatsapp);
        return view('admin.case.track', compact("whatsapp", "id", "mediator", "email","ivr"));
    }

    public function mediatorAccessChange(Request $request)
    {
        $manage_file = SupportingDocument::find($request->manageid);
        $manage_file->mediator_access = $request->mediatorAccess;
        if ($manage_file->save()) {
            if ($request->mediatorAccess == 1) {
                $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                    ->where("mediators_mediation_cases_status.mediation_case_id", "=", $request->caseid)
                    ->where("mediators_mediation_cases_status.status", "=", 1)
                    ->first();

                // dd($mediator);
                if ($mediator) {
                    $mid = "M" . sprintf("%06d", $request->caseid);
                    $filesE = url("storage/app/" . $request->filename_path);

                    // $sendEamils[] = $mediator->email;
                    $d2 = [
                        'event' => 'SEND_ADDI_DOC_MED',
                        'case_id' => $request->caseid,
                    ];
                    SendGrid::send($d2, $mediator->email, env('L19_ADDITIONAL_DOC_ALL_PARTIES', ''), ["-caseid-" => $mid], null, $filesE);

                    $var = ['-cid-'];
                    $var1 = [$mid];
                    $content1 = WaTemplate::getcontent('additional_doc_med');
                    $content = str_replace($var, $var1, $content1);
                    $dwa1 = [
                        'caseid' => $request->caseid,
                        'contact' => "+91" . $mediator->mobile_number,
                        'content' => ['text' => $content],
                        'event' => 'SEND_ADDI_DOC_MED'
                    ];


                    $accessW = Whatsapp::sendWamessage($dwa1);

                    $var_file = ['-caseid-'];
                    $var1_file = [$mid];
                    $content1_file = WaTemplate::getcontent('mediation_consent_doc');
                    $content_file = str_replace($var_file, $var1_file, $content1_file);
                    $dwa2 = [
                        'caseid' => $request->caseid,
                        'contact' => "+91" . $mediator->mobile_number,
                        'content' => ['media' => ['url' => $filesE, 'caption' => $content_file]],
                        'event' => 'SEND_ADDI_DOC_MED'
                    ];
                    $accessW = Whatsapp::sendWamessage($dwa2);
                }
            }
            return response()->json(["code" => 200, "message" => "success"]);
        } else {
            return response()->json(["code" => 200, "message" => "error"]);
        }
    }

    public function docsAccessChange(Request $request)
    {
        // dd($request->all());
        $manage_file = SupportingDocument::find($request->manageid);
        // dd($manage_file);
        if ($request->checkedId != null) {
            // dd($manage_file->access);
            if ($manage_file->access == null) {
                $manage_file->access = $request->checkedId;
            } else {
                $manage_file->access = $manage_file->access . ',' . $request->checkedId;
            }
            // dd($x);
        }
        if ($request->uncheckedId != null) {
            $manageAccess = explode(',', $manage_file->access);
            if (($key = array_search($request->uncheckedId, $manageAccess)) !== false) {
                unset($manageAccess[$key]);
                // dd($key);
            }
            if (empty($manageAccess)) {
                $manage_file->access = null;
            } else {
                $manage_file->access = implode(',', $manageAccess);
            }
            // dd($manageAccess);
        }
        if ($manage_file->save()) {
            if ($request->checkedId != null) {
                $invUser = InvoledUser::select('user_involved_in_agreement.*', 'manage_files.file_name')
                    ->leftJoin("manage_files", "manage_files.case_id", "=", "user_involved_in_agreement.userPlanId")
                    ->where('manage_files.file_name', $request->filename_path)
                    ->find($request->checkedId);
                $mid = "M" . sprintf("%06d", $invUser->userPlanId);
                $d = [
                    'event' => 'SEND_ADDI_DOC',
                    'case_id' => $invUser->userPlanId,
                ];
                $filesE = url("storage/app/" . $invUser->file_name);

                if ($invUser->userEmail != null) {
                    SendGrid::send($d, $invUser->userEmail, env('L19_ADDITIONAL_DOC_ALL_PARTIES', ''), ["-caseid-" => $mid], null, $filesE);
                }

                if ($invUser->userPhone != "") {
                    $var = ['-cid-'];
                    $var1 = [$mid];
                    $content1 = WaTemplate::getcontent('additional_doc');
                    $content = str_replace($var, $var1, $content1);
                    $dwa1 = [
                        'caseid' => $invUser->userPlanId,
                        'contact' => "+91" .  $invUser->userPhone,
                        'content' => ['text' => $content],
                        'event' => 'SEND_ADDI_DOC'
                    ];
                    $accessW = Whatsapp::sendWamessage($dwa1);
                    $var_file = ['-caseid-'];
                    $var1_file = [$mid];
                    $content1_file = WaTemplate::getcontent('mediation_consent_doc');
                    $content_file = str_replace($var_file, $var1_file, $content1_file);
                    $dwa2 = [
                        'caseid' => $invUser->userPlanId,
                        'contact' => "+91" . $invUser->userPhone,
                        'content' => ['media' => ['url' => $filesE, 'caption' => $content_file]],
                        'event' => 'SEND_ADDI_DOC'
                    ];
                    $accessW = Whatsapp::sendWamessage($dwa2);
                }
                // dd($filesE);
            }
            return response()->json(["code" => 200, "message" => "success"]);
        } else {
            return response()->json(["code" => 200, "message" => "error"]);
        }
    }
}
