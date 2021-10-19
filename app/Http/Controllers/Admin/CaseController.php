<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
use DB;
use PDF;
use Auth;
use Storage;

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
        $allUsers = User::where("role", "=", 0)->get();
        $users = User::where("role", "=", 1)->get();
        $confirm_status = 0;
        return view('admin.case.index', compact("confirm_status", "users", "allUsers"));
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

    public function confirmStatus(Request $request) {
        $medCas = MedCase::find($request->id);
        $medCas->confirm_status = 1;
        $medCas->save();

        $mediation_status_log = new Mediation_status_log;
        $mediation_status_log->user_id = Auth::user()->id;
        $mediation_status_log->mediation_case_id = $request->id;
        $mediation_status_log->status = 1;
        $mediation_status_log->description = "Request Confirm";
        $mediation_status_log->save();

        // generate pdf
        $invitation = $this->invitation_mediate($request->id);

        $invmodel = new InvitationFiles();
        $invmodel->case_id = $request->id;
        $invmodel->file_name = $invitation;
        $invmodel->save();
        //send invitation 
        $this->sned_invitation($request->id, $invitation);

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
        $mediation_status_log->status = $request->status;
        if (Mediation_status_log::STATUS_WITHDRAWN == $request->status) {
            $mediation_status_log->description = "Request Withdrawn";
            $this->sned_withdrawal($request->case_id);
        } else if (Mediation_status_log::STATUS_RESOLVED == $request->status) {
            $mediation_status_log->description = "Request Resolved";
            $this->sned_resolved($request->case_id);
        } else if (Mediation_status_log::STATUS_UNRESOLVED == $request->status) {
            $mediation_status_log->description = "Request Unresolved";
            $this->sned_unresolved($request->case_id);
        }
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
        $mediation_status_log->description = "Request Closed";
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
            $mediation_case_comment = Mediation_case_comment::select("users.username", "mediation_case_comment.comment", DB::raw("DATE_FORMAT(mediation_case_comment.created_at,'%d-%c-%y %h:%i %p') as created"))->join("users", "mediation_case_comment.user_id", "=", "users.id")->where("type", $request->type)->where("user_id", Auth::user()->id)->where("mediation_case_id", $request->case_id)->get();
        } else {
            $mediation_case_comment = Mediation_case_comment::select("users.username", "mediation_case_comment.comment", DB::raw("DATE_FORMAT(mediation_case_comment.created_at,'%d-%c-%y %h:%i %p') as created"))->join("users", "mediation_case_comment.user_id", "=", "users.id")->where("type", $request->type)->where("type", $request->type)->where("mediation_case_id", $request->case_id)->get();
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
        $this->sned_reject($request->id);
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
        $this->send_mediatorAdd($request->id, $request->midater);
        return response()->json(["msg" => "midater Added"]);
    }

    public function addSession(Request $request) {
        // echo $request->zoomId;

        $dataToInsert = [
            'case_id' => $request->caseId,
            'session_date' => $request->sessionDate . "/" . $request->sessionTime,
            'note' => $request->note,
            'zoom_id' => $request->zoomId,
            'session_party_ids' => json_encode($request->session_party_ids),
            'scheduled_by' => Auth::user()->id,
        ];
        foreach ($request->session_party_ids as $pary_id) {
            $data = InvoledUser::where("userId", $pary_id)->where("userPlanId", $request->caseId)->first();
            $this->sned_session($request->caseId, $data->userEmail, $data->name, $request->sessionDate . "/" . $request->sessionTime);
        }
        $mediator = Mediators_mediation_cases_status::select("email", "username")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                ->where("mediators_mediation_cases_status.mediation_case_id", "=", $request->caseId)
                ->where("mediators_mediation_cases_status.status", "=", 1)
                ->first();
        if ($mediator) {
            $id = "M" . sprintf("%06d", $request->caseId);
            SendGrid::send($mediator->email, env('L10_SCHEDULING_OF_SESSION', ''), ["-caseid-" => $id, "-insert_date-" => $request->sessionDate . "/" . $request->sessionTime,"-type-"=>"Mediator"], $mediator->username);
        }
        DB::table('manage_session')->insert($dataToInsert);

        return true;
    }

    public function getAddedSesion(Request $request) {


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

    public function json($role = 0) {
        $cases = MedCase::select("mediation_case.*", DB::raw("CONCAT(users.first_name,' ',users.last_name,' - ',users.organization) as mediator_username"), "mediators_mediation_cases_status.mediator_id as mediator_id", "mediators_mediation_cases_status.status as mediator_status")
                ->leftJoin("mediators_mediation_cases_status", function($join) {
                    $join->on("mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id");
                    $join->where("mediators_mediation_cases_status.id", "=", DB::raw("(select max(`mediators_mediation_cases_status2`.`id`) from mediators_mediation_cases_status as mediators_mediation_cases_status2 Where `mediators_mediation_cases_status2`.`mediation_case_id`=`mediation_case`.`id`)"));
                })
                ->leftJoin("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                ->where("mediation_case.confirm_status", "=", $role)
                ->get();
            // dd($cases);
        $arraydata = array();
        foreach ($cases as $d) {
            $arraydata[] = [
                "date" => date('d-m-Y', strtotime($d->created_at)),
                "case" => $d,
                "party" => InvoledUser::select('name', 'isOnboarded', "userId")->where(['userPlanid' => $d->id])->get(),
                "status_log" => Mediation_status_log::select("status", "description", DB::raw("DATE_FORMAT(created_at,'%d-%c-%y %h:%i %p') as created"))->where(['mediation_case_id' => $d->id])->orderByDesc('id')->limit(1)->get(),
            ];
        }
        return response()->json(["data" => $arraydata]);
    }

    public function invitation_mediate($id) {
        $data["case"] = MedCase::where("id", "=", $id)->first();
        $data["party"] = InvoledUser::where("userPlanId", "=", $id)->get();
        $pdf = PDF::loadView('pdf.invitation_mediation', $data);
        $name = 'Invitation_mediate_M' . sprintf('%06d', $data["case"]->id) . time() . '.pdf';
        Storage::put('public/mediation/' . $data["case"]->id . '/' . $name, $pdf->output());
        return $name;
    }

    public function updatecase(Request $request, $id) {
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

            $pone=$inv;


            //update responding party




            for ($i = 0; $i < count($r['email']); $i++) {

                $invid = $r['invid'][$i];

                if ($invid != '') {

                    $inv = InvoledUser::find($invid);
                } else {
                    $inv = new InvoledUser();
                }



                //$inv->userId=;
                if ($inv->userEmail != $r['email'][$i]) {

                    $inv->joinCode = $this->joinCode();
                }

                $inv->userPlanId = $med->id;

                if($inv->userEmail!=$r['email'][$i]){

                    $inv->userEmail = $r['email'][$i];


                    $invitation = $this->invitation_mediate($id);

                    $invmodel = new InvitationFiles();
                    $invmodel->case_id = $request->id;
                    $invmodel->file_name = $invitation;
                    $invmodel->save();

                    
                    $code = $inv->joinCode;
                     $s=SendGrid::send($inv->userEmail, env('L4_INVITATION_TO_COUNTER_PARTIES_FOR_ONBOARDING', ''), ["-caseid-" => "M" . sprintf("%06d", $med->id), "-joincode-" => $inv->joinCode, "-claimant-" => $pone->name], $inv->name, url("/storage/app/public/mediation/" . $med->id . "/" . $invitation));


                }

                
                $inv->userPhone = $r['phone'][$i];
                $inv->name = $r['name'][$i];
                $inv->address1 = $r['add1'][$i];

                if ($r['add2'][$i] == '') {
                    $r['add2'][$i] = 'null';
                }
                $inv->address2 = $r['add2'][$i];
                $inv->city = $r['city'][$i];
                $inv->pincode = $r['pincode'][$i];
                $inv->state = $r['state'][$i];
                $inv->country = $r['country'][$i];
                $inv->isClaimant = $i + 1;



                $inv->created_at = date('Y-m-d H:s:i');
                $inv->updated_at = date('Y-m-d H:s:i');


                $inv->save();
            }



            //remove involed

            if ($r['rminv'] != '') {

                foreach (explode(',', $r['rminv']) as $key => $value) {

                    InvoledUser::find($value)->delete();
                }
            }

            $InvoledUser = InvoledUser::where(['userPlanId' => $med->id])->where('isClaimant', '<>', '0')->get()->toArray();

            $response = 'success';
        }

        return view('admin.case.updatecase', ['user' => $usr, 'InvoledUser' => $InvoledUser, 'medcase' => $med, 'response' => $response]);
    }

    public function joinCode() {

        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $string = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < 8; $i++) {
            $string .= $characters[mt_rand(0, $max)];
        }

        return $string;
    }

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

    public function settelmenUpload(Request $request) {

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

    public function sned_invitation($id, $invitation) {
        $involedUser = InvoledUser::where("userPlanId", $id)->get();

        $initiating_party = "";
        foreach ($involedUser as $inv) {
            if ($inv->isClaimant == 0) {
                $initiating_party = $inv->name;
            } else if ($inv->isOnboarded == 0) {
                $code = $inv->joinCode;
                SendGrid::send($inv->userEmail, env('L4_INVITATION_TO_COUNTER_PARTIES_FOR_ONBOARDING', ''), ["-caseid-" => "M" . sprintf("%06d", $id), "-joincode-" => $code, "-claimant-" => $initiating_party], $inv->name, url("/storage/app/public/mediation/" . $id . "/" . $invitation));
            }
        }
        return true;
    }

    public function sned_reject($id) {
        $involedUser = InvoledUser::where("userPlanId", $id)->get();
        $initiating_party = "";
        foreach ($involedUser as $inv) {
            if ($inv->isClaimant == 0) {
                $party_name = $inv->name;
                $id = "M" . sprintf("%06d", $id);
                SendGrid::send($inv->userEmail, env('L8_CASE_REJECTED', ''), ["-caseid-" => $id, "-responding-" => $party_name], $inv->name);
            }
        }
    }

    public function sned_session($id, $email_id, $email_name, $date) {
        $id = "M" . sprintf("%06d", $id);
        SendGrid::send($email_id, env('L10_SCHEDULING_OF_SESSION', ''), ["-caseid-" => $id, "-insert_date-" => $date,"-type-"=>"Party"], $email_name);
        return true;
    }

    public function sned_withdrawal($id) {
        $involedUser = InvoledUser::where("userPlanId", $id)->get();
        $mediator = Mediators_mediation_cases_status::select("email", "username")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                ->where("mediators_mediation_cases_status.mediation_case_id", "=", $id)
                ->where("mediators_mediation_cases_status.status", "=", 1)
                ->first();
        $id = "M" . sprintf("%06d", $id);
        $initiating_party = "";
        foreach ($involedUser as $inv) {
            if ($inv->isClaimant == 0) {
                SendGrid::send($inv->userEmail, env('L13_WITHDRAWAL_OF_CASE', ''), ["-caseid-" => $id, "-type-" => "Party"], $inv->name);
                $initiating_party = $inv->name;
            } else {
                SendGrid::send($inv->userEmail, env('L14_COMMUNICATION_OF_WITHDRAWAL_TO_OTHER_PARTIES', ''), ["-caseid-" => $id, "-partyname-" => $initiating_party, "-type-" => "Party"], $inv->name);
            }
        }
        if ($mediator) {
            SendGrid::send($mediator->email, env('L13_WITHDRAWAL_OF_CASE', ''), ["-caseid-" => $id, "-responding-" => $initiating_party, "-type-" => "Mediator"], $mediator->username);
        }
        return true;
    }

    public function sned_resolved($id) {
        $involedUser = InvoledUser::where("userPlanId", $id)->get();
        $mediator = Mediators_mediation_cases_status::select("email", "username")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                ->where("mediators_mediation_cases_status.mediation_case_id", "=", $id)
                ->where("mediators_mediation_cases_status.status", "=", 1)
                ->first();
        $id = "M" . sprintf("%06d", $id);
        $initiating_party = "";
        foreach ($involedUser as $inv) {
            if ($inv->isClaimant == 0) {
                $initiating_party = $inv->name;
            }
            SendGrid::send($inv->userEmail, env('L15_CASE_RESOLVED', ''), ["-caseid-" => $id, "-responding-" => $initiating_party, "-type-" => "Party"], $inv->name);
        }
        if ($mediator) {
            SendGrid::send($mediator->email, env('L15_CASE_RESOLVED', ''), ["-caseid-" => $id, "-responding-" => $initiating_party, "-type-" => "Mediator"], $mediator->username);
        }
        return true;
    }

    public function sned_unresolved($id) {
        $involedUser = InvoledUser::where("userPlanId", $id)->get();
        $mediator = Mediators_mediation_cases_status::select("email", "username")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                ->where("mediators_mediation_cases_status.mediation_case_id", "=", $id)
                ->where("mediators_mediation_cases_status.status", "=", 1)
                ->first();

        $id = "M" . sprintf("%06d", $id);
        $initiating_party = "";
        foreach ($involedUser as $inv) {
            if ($inv->isClaimant == 0) {
                $initiating_party = $inv->name;
            }
            SendGrid::send($inv->userEmail, env('L15_CASE_UNRESOLVED', ''), ["-caseid-" => $id, "-responding-" => $initiating_party, "-type-" => "Party"], $inv->name);
        }
        if ($mediator) {
            SendGrid::send($mediator->email, env('L15_CASE_UNRESOLVED', ''), ["-caseid-" => $id, "-responding-" => $initiating_party, "-type-" => "Mediator"], $mediator->username);
        }
        return true;
    }

    public function send_mediatorAdd($id, $mediator_id) {
        $user = User::where("id", $mediator_id)->first();
        $id = "M" . sprintf("%06d", $id);
        SendGrid::send($user->email, env('L17_WHEN_ADMIN_SELECTS_MEDIATOR', ''), ["-caseid-" => $id], $user->name);
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

    public function csvToArray($file)
    {
        
        $rows = array();
        $headers = array();
        if (file_exists($file) && is_readable($file)) {
            $handle = fopen($file, 'r');
           
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

        if ($ext != 'csv') {
            $errormsg .= 'Please upload csv file';
        }
        // dd($errormsg);
        if ($errormsg == '') {
            $csv = $this->csvToArray($tmpName);
            if (count($csv[0]) != 24) {
                $errormsg .= "Invalid csv file";
            }
            $errormsg .= '';
            foreach ($csv as $key => $v) {
                $i = $key + 1;

                for ($n = 1; $n < 16; $n++) {
                    if ($v[$n] == '' and $n != 3) {
                        $errormsg .= "Please fill all the required details to proceed at line no $i ";
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
                if (!filter_var($v[8], FILTER_SANITIZE_NUMBER_INT)) {
                    $errormsg .= "Invalid pincode at line no $i ";
                }

                if (strlen($v[8]) != 6) {
                    $errormsg .= "Invalid pincode at line no $i ";
                }

                //validate date
                if (strpos($v[12], '-')) {
                    $dt = str_replace('-', '/', $v[12]);
                    $v[12] = $dt;
                }

                $dt = explode('/', $v[12]);

                if (count($dt) != 3 and strlen($dt[0]) != 2 and strlen($dt[1]) != 2 and strlen($dt[0]) != 4) {

                    $errormsg .= "Invalid date at line no $i. date format should be dd/mm/YYYY ";
                }

                if ($v[19] != 'Yes') {
                    $errormsg .= "Please confirm that the details provided above are true, accurate, current and complete to proceed at line no $i ";
                }

                if ($v[20] != 'Yes') {

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
        // dd($cldetails);
        //store in database
        foreach ($csv as $k => $value) {
            $data['userid'] = $claimantid;
            $data['disputeCategory'] = $value['0'];
            $data['noOfParties'] = 2;
            $data['amount'] = $value['1'];
            $data['issue'] = $value['14'];
            $data['confirm_status'] = 0;
            $data['otherRespondentDetails'] = $value[23];
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
                if(isset($cldetails->address)) {
                    $iniParty->address1 = $cldetails->address;
                } else {
                    $iniParty->address1 = '';
                }
                if(isset($cldetails->address1)) {
                    $iniParty->address2 = $cldetails->address1;
                } else {
                    $iniParty->address2 = '';
                }
                if(isset($cldetails->city)) {
                    $iniParty->city = $cldetails->city;
                } else {
                    $iniParty->city = '';
                }
                if(isset($cldetails->pincode)) {
                    $iniParty->pincode = $cldetails->pincode;
                } else {
                    $iniParty->pincode = '';
                }
                if(isset($cldetails->state)) {
                    $iniParty->state = $cldetails->state;
                } else {
                    $iniParty->state = '';
                }
                if(isset($cldetails->country)) {
                    $iniParty->country = $cldetails->country;
                } else {
                    $iniParty->country = '';
                }
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
            $resParty->address1 = $value['5'];
            $resParty->address2 = $value['6'];
            $resParty->city = $value['7'];
            $resParty->pincode = $value['8'];
            $resParty->state = $value['9'];
            $resParty->country = $value['10'];
            $resParty->created_at = date('Y-m-d H:s:i');
            $resParty->updated_at = date('Y-m-d H:s:i');
            $resParty->save();


            $otherResEmail = explode(',', $value[21]);
            $otherResMobile = explode(',', $value[22]);


            for($i = 0; $i < count($otherResEmail); $i++) {
                if($i == count($otherResEmail)-1) {
                    $otherDetails[$i] = new InvoledUser();
                    $otherDetails[$i]->userPlanId = $med->id;
                    $otherDetails[$i]->userEmail = $otherResEmail[$i];
                    $otherDetails[$i]->userPhone = $otherResMobile[$i];
                    $otherDetails[$i]->joinCode = $this->joinCode();
                    $otherDetails[$i]->created_at = date('Y-m-d H:s:i');
                    $otherDetails[$i]->updated_at = date('Y-m-d H:s:i');
                    $otherDetails[$i]->save();
                } else {
                    $otherDetails[$i] = new InvoledUser();
                    $otherDetails[$i]->userPlanId = $med->id;
                    $otherDetails[$i]->userEmail = $otherResEmail[$i];
                    $otherDetails[$i]->userPhone = $otherResMobile[$i];
                    $otherDetails[$i]->joinCode = $this->joinCode();
                    $otherDetails[$i]->created_at = date('Y-m-d H:s:i');
                    $otherDetails[$i]->updated_at = date('Y-m-d H:s:i');
                    $otherDetails[$i]->save();
                }

            }
        }

        return redirect('/admin/case/new-request')->with(['success' => 'Success']);
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

}
