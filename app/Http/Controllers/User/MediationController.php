<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Common_function;
use Illuminate\Http\Request;
use App\Models\MedCase;
use App\Models\Mediation_status_log;
use App\Models\Mediation_case_comment;
use App\Models\InvoledUser;
use App\Models\SupportingDocument;
use App\Models\User;
use App\Models\InvitationFiles;
use App\Models\Mediators_mediation_cases_status;
use App\Http\Helpers\SendGrid as Email;
use App\Http\Helpers\Whatsapp;
use App\Models\ConsentDisclosures;
use App\Models\WaTemplate;
use Session;
use Auth;
use Validator;
use DB;
use Exception;
use Illuminate\Support\Facades\Storage;
use PDF;

class MediationController extends Controller
{

    public function commentView(Request $request)
    {
        if ($request->type == 1) {
            $mediation_case_comment = Mediation_case_comment::select("users.username", "mediation_case_comment.comment", DB::raw("DATE_FORMAT(mediation_case_comment.created_at,'%d-%c-%y %h:%i %p') as created"))->join("users", "mediation_case_comment.user_id", "=", "users.id")->where("type", $request->type)->where("mediation_case_id", $request->case_id)->get();
        } else {
            $mediation_case_comment = Mediation_case_comment::select("users.username", "mediation_case_comment.comment", DB::raw("DATE_FORMAT(mediation_case_comment.created_at,'%d-%c-%y %h:%i %p') as created"))->join("users", "mediation_case_comment.user_id", "=", "users.id")->where("type", $request->type)->where("mediation_case_id", $request->case_id)->get();
        }
        return response()->json($mediation_case_comment);
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

    public function invoke(Request $request)
    {



        if (!isset($_GET['id'])) {

            return abort(404);
        } else if (isset($_GET['id'])) {

            $med = MedCase::find($_GET['id']);


            if (!$med) {

                return abort(404);
            }
        }

        
        //fetch all involved users

        $InvoledUser = InvoledUser::where(['userPlanId' => $med->id])->where('isClaimant', '<>', '0')->get()->toArray();

        if ($request->method() == 'POST' && $InvoledUser == null) {

            

            

            // //upload file
            // $filename = '';
            // if ($request->file('document') !== null) {

            //     $request->validate([
            //         'document' => 'mimes:pdf,zip,jpg,jpeg,png|max:20048',
            //     ]);

            //     $filename = 'supporting_document' . $med->id . time() . '.' . $request->document->extension();


            //     $path = $request->file('document')->storeAs('public/mediation/' . $med->id . '/', $filename);
            // }


            $r = $request->post();



            //udpate mediation case
            $med->issue = $r['issue'];
            $med->proposedSolution = $r['proposedSolution'];
            $med->disputeCategory = $r['disputeCategory'];
            $med->amount = $r['amount'];

            // $med->documentPath = $filename;
            $med->updated_at = date("Y-m-d H:i:s");
            $med->save();

            //if user profile update

            $usr = User::find(Auth::user()->id);

            if (Auth::user()->address == '') {
                $usr->address = $r['useraddress'];
                $usr->address1 = $r['useraddress1'];
                $usr->city = $r['usercity'];
                $usr->pincode = $r['userpincode'];
                $usr->state = $r['userstate'];
                $usr->country = $r['usercountry'];
                $usr->save();
            }


            // add initiating party



            $inv = InvoledUser::where(['userPlanid' => $med->id, 'userId' => $usr->id])->first();

            if (!$inv) {

                $inv = new InvoledUser();
                $inv->userId = $usr->id;
                $inv->userPlanId = $med->id;
                $inv->userEmail = $usr->email;
                $inv->userPhone = $usr->mobile_number;
                $inv->name = ucfirst($usr->first_name) . ' ' . ucfirst($usr->last_name);
                $inv->address1 = $usr->address;

                if ($usr->address1 == '') {
                    $usr->address1 = '';
                }
                $inv->address2 = $usr->address1;
                $inv->city = $usr->city;
                $inv->pincode = $usr->pincode;
                $inv->state = $usr->state;
                $inv->country = $usr->country;
                $inv->isClaimant = '0';
                $inv->isOnboarded = '1';




                $inv->created_at = date('Y-m-d H:s:i');
                $inv->updated_at = date('Y-m-d H:s:i');


                $inv->save();
            }


            //add responding party




            for ($i = 0; $i < count($r['email']); $i++) {


                $inv = new InvoledUser();
                //$inv->userId=;
                $inv->userPlanId = $med->id;
                $inv->userEmail = $r['email'][$i];
                $inv->userPhone = $r['phone'][$i];
                $inv->name = $r['name'][$i];
                $inv->joinCode = $this->joinCode();
                $inv->fulladdress = $r['fulladdress'][$i];

                // if ($r['add2'][$i] == '') {
                //     $r['add2'][$i] = '';
                // }
                // $inv->address2 = $r['add2'][$i];
                // $inv->city = $r['city'][$i];
                // $inv->pincode = $r['pincode'][$i];
                // $inv->state = $r['state'][$i];
                // $inv->country = $r['country'][$i];
                $inv->isClaimant = $i + 1;



                $inv->created_at = date('Y-m-d H:s:i');
                $inv->updated_at = date('Y-m-d H:s:i');


                $inv->save();
            }
            $letter = $this->requestLetter($_GET['id']);
            // dd($med);
            $med->request_letter = $letter;
            $med->save();
            $d = [
                'event' => 'SUBMIT_FORM',
                'case_id' => $med->id,
            ];

            $cid = "M" . sprintf("%06d", $med->id);


            $e = Email::send($d, $usr->email, env('EMAIL_L1', ''), ['-caseId-' => $cid,], $usr->first_name . ' ' . $usr->last_name);



            return redirect()->route('user.newrequest')->with(['response' => 'success']);

            exit();
        }

        return view('user.invoke', ['user' => Auth::user(), 'InvoledUser' => $InvoledUser, 'medcase' => $med]);
    }

    public function newcase(Request $request)
    {


        if ($request->session()->has('newcase')) {

            $r = $request->session()->get('newcase');

            $med = new MedCase();

            $med->userid = Auth::user()->id;

            $med->disputeCategory = $r['cat'];

            $med->noOfParties = $r['npd'];

            $med->amount = $r['damount'];

            $med->confirm_status = 0;

            $med->created_at = date('Y-m-d H:i:s');

            $med->updated_at = date('Y-m-d H:i:s');

            if ($med->save()) {

                $request->session()->forget('newcase');

                return redirect()->route('user.invoke', 'id=' . $med->id);
            }
        }
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

    public function join(Request $request)
    {



        if ($request->post()) {

            $r = $request->post();

            $code = $r['joincode'];

            $email = Auth::user()->email;
            $phone = Auth::user()->mobile_number;


            $InvoledUser = InvoledUser::where(['joincode' => $code])->where(function($q) use($email, $phone) {
                $q->orWhere('userEmail', $email)->orWhere('userPhone', $phone);
            })->first();


            if (!$InvoledUser) {

                return response()->json(['response' => 'Invalid']);
            }

            $case = MedCase::where(['id' => $InvoledUser->userPlanId, 'confirm_status' => 1])->first();

            if (!$case) {

                return response()->json(['response' => 'Invalid']);
            }

            $InvoledUser->joincode = null;
            $InvoledUser->isOnboarded = '1';
            $InvoledUser->userid = Auth::user()->id;
            if($InvoledUser->name == null) {
                $InvoledUser->name = Auth::user()->first_name . ' ' . Auth::user()->last_name;
            }

            $d = [
                'event' => 'ONBOAR_USER',
                'case_id' => $InvoledUser->userPlanId,
            ];
            if ($InvoledUser->save()) {


                //fetch init parry
                $mid = "M" . sprintf("%06d", $InvoledUser->userPlanId);

                $InvoledUserP1 = InvoledUser::where(['isClaimant' => '0', 'userPlanId' => $InvoledUser->userPlanId])->first();



                $party_name = $InvoledUser->name;

                // $e = Email::send($InvoledUserP1->userEmail, '8c86c224-75e5-4cfd-8bc2-f3305df4d3f3', ['-caseid-' => $mid, '-partyname-' => $party_name], $InvoledUserP1->name);
                $e = Email::send($d, $InvoledUserP1->userEmail, env('L7_UPON_SUCCESSFUL_ONBOARDING_OF_ANY_COUNTER_PARTY', ''), ['-caseid-' => $mid, '-name-' => $party_name], $InvoledUserP1->name);

                $var = ['-rp-', '-cid-'];
                $var1 = [$party_name, $mid];
                $content1 = WaTemplate::getcontent('l7_mediation_onboarded');
                $content = str_replace($var, $var1, $content1);
                $dwa1 = [
                    'caseid' => $InvoledUser->userPlanId,
                    'contact' => "+91" . $InvoledUserP1->userPhone,
                    'content' => ['text' => $content],
                    'event' => 'ONBOAR_USER'
                ];

                // print_r($dwa1);
                // exit;
                $access = Whatsapp::sendWamessage($dwa1);



                return response()->json(['response' => 'success', 'code' => 201]);
            }
        } else {

            return response()->json(['response' => 'error', 'code' => 404]);
        }
    }

    public function newrequest(Request $request)
    {



        // $new=InvoledUser::select('user_involved_in_agreement.*','mediation_case.id as caseid')->where(['user_involved_in_agreement.userid'=>Auth::user()->id])->leftJoin('mediation_case', 'user_involved_in_agreement.userPlanId', '=', 'mediation_case.id')->get();


        $new = MedCase::Where(['userid' => Auth::user()->id, 'confirm_status' => 0])->orderby('id', 'DESC')->get();

        $pending = [];

        foreach ($new as $key => $value) {
            $in = InvoledUser::select('name', 'isOnboarded')->where(['userPlanid' => $value->id])->get();
            $value->party = $in;

            $pending[] = $value;
        }


        return view('user.newrequest', ['pending' => $pending, 'response' => Session::get('response')]);
    }

    public function ongoing()
    {


        // $new=InvoledUser::select('user_involved_in_agreement.*','mediation_case.id as caseid')->where(['user_involved_in_agreement.userid'=>Auth::user()->id])->leftJoin('mediation_case', 'user_involved_in_agreement.userPlanId', '=', 'mediation_case.id')->get();


        $new = MedCase::select('user_involved_in_agreement.*', 'mediation_case.id as caseid', 'mediation_case.created_at as date', 'mediation_case.userid', DB::raw("CONCAT(users.first_name,' ',users.last_name,' - ',users.organization) as mediator"), 'consent_disclosures.id as consent', 'mediators_mediation_cases_status.status as mstatus')
            ->where(['user_involved_in_agreement.userid' => Auth::user()->id, 'mediation_case.confirm_status' => 1])
            ->leftJoin("mediators_mediation_cases_status", "mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id")
            ->leftJoin("consent_disclosures", "mediation_case.id", "=", "consent_disclosures.mediation_case_id")
            ->leftJoin("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->leftJoin('user_involved_in_agreement', 'mediation_case.id', '=', 'user_involved_in_agreement.userPlanId')
            ->orderby('mediation_case.id', 'DESC')
            ->get();

        $ongoing = [];

        foreach ($new as $key => $value) {
            $in = InvoledUser::select('name', 'isOnboarded')->where(['userPlanid' => $value->caseid])->get();
            $value->party = $in;

            $value->casestatus = Mediation_status_log::select("status", "description", DB::raw("DATE_FORMAT(created_at,'%d-%c-%y %h:%i %p') as created"))->where(['mediation_case_id' => $value->caseid])->orderByDesc('id')->limit(1)->first();

            $ongoing[] = $value;
        }

        return view('user.ongoing', ['ongoing' => $ongoing]);
    }

    public function closed()
    {

        $new = MedCase::select('user_involved_in_agreement.*', 'mediation_case.id as caseid', 'mediation_case.created_at as date', DB::raw("CONCAT(users.first_name,' ',users.last_name,' - ',users.organization) as mediator"), 'mediation_case.withdraw', 'consent_disclosures.id as consent', 'mediators_mediation_cases_status.status as mstatus')
            ->where(['user_involved_in_agreement.userid' => Auth::user()->id, 'mediation_case.confirm_status' => 2])
            ->leftJoin("mediators_mediation_cases_status", "mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id")
            ->leftJoin("consent_disclosures", "mediation_case.id", "=", "consent_disclosures.mediation_case_id")
            ->leftJoin("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->leftJoin('user_involved_in_agreement', 'mediation_case.id', '=', 'user_involved_in_agreement.userPlanId')
            ->orderby('mediation_case.id', 'DESC')
            ->get();

        $closed = [];

        foreach ($new as $key => $value) {
            $in = InvoledUser::select('name', 'isOnboarded')->where(['userPlanid' => $value->caseid])->get();
            $value->party = $in;

            $value->casestatus = Mediation_status_log::select("status", "description", DB::raw("DATE_FORMAT(created_at,'%d-%c-%y %h:%i %p') as created"))->where(['mediation_case_id' => $value->caseid])->orderByDesc('id')->limit(1)->first();

            $value->casestatus->css = '';

            if ($value->casestatus->status == 2) {

                $value->casestatus->css = 'danger';
            } else if ($value->casestatus->status == 5) {

                $value->casestatus->css = 'danger';
            } else if ($value->casestatus->status == 6) {

                $value->casestatus->css = 'success';
            } else if ($value->casestatus->status == 7) {

                $value->casestatus->css = 'danger';
            }

            $closed[] = $value;
        }

        return view('user.closed', ['closed' => $closed]);
    }

    public function rejected()
    {

        $new = MedCase::select('user_involved_in_agreement.*', 'mediation_case.id as caseid', 'mediation_case.created_at as date', 'mediation_case.withdraw')
            ->where(['user_involved_in_agreement.userid' => Auth::user()->id, 'mediation_case.confirm_status' => 3])
            ->leftJoin('user_involved_in_agreement', 'mediation_case.id', '=', 'user_involved_in_agreement.userPlanId')
            ->get();

        $closed = [];

        foreach ($new as $key => $value) {
            $in = InvoledUser::select('name', 'isOnboarded')->where(['userPlanid' => $value->caseid])->get();
            $value->party = $in;

            $value->casestatus = Mediation_status_log::select("status", "description", DB::raw("DATE_FORMAT(created_at,'%d-%c-%y %h:%i %p') as created"))->where(['mediation_case_id' => $value->caseid])->orderByDesc('id')->limit(1)->first();

            $value->casestatus->css = 'danger';


            $closed[] = $value;
        }

        return view('user.rejected', ['closed' => $closed]);
    }

    public function sessions(Request $request)
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
                $user[] = ucfirst($dd->first_name) . " " . ucfirst($dd->last_name);
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

        return view('user.casedetails', compact("case"));
    }

    public function withdraw(Request $request)
    {



        $user = MedCase::find($request->case_id);
        $user->confirm_status = 2;
        $user->withdraw = $request->withdraw_comment;
        $user->save();
        
        //p1

        $cid = "M" . sprintf("%06d", $request->case_id);

        $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->where("mediators_mediation_cases_status.mediation_case_id", "=", $request->case_id)
            ->where("mediators_mediation_cases_status.status", "=", 1)
            ->first();

        $d1 = [
            'event' => 'WDRN_PARTY',
            'case_id' => $request->case_id,
        ];
        $d2 = [
            'event' => 'WDRN_OTHER_PARTY',
            'case_id' => $request->case_id,
        ];
        $d3 = [
            'event' => 'WDRN_MED',
            'case_id' => $request->case_id,
        ];

        $InvoledUserP1 = InvoledUser::where(['isClaimant' => '0', 'userPlanId' => $request->case_id])->first();

        $e = Email::send($d1, $InvoledUserP1->userEmail, env('L13_WITHDRAWAL_OF_CASE', ''), ['-caseid-' => $cid, '-type-' => 'Party'], $InvoledUserP1->name);


        //other

        $InvoledUser = InvoledUser::where(['userPlanId' => $request->case_id])->where('isClaimant', '<>', '0')->get();



        $responding_party = "";
        foreach ($InvoledUser as $key => $value) {
            if ($value->name != "") {
                $responding_party = $value->name;
            }

            if ($value->userEmail != "") {
                $e = Email::send($d2, $value->userEmail, env('L14_COMMUNICATION_OF_WITHDRAWAL_TO_OTHER_PARTIES', ''), ['-caseid-' => $cid, '-partyname-' => $InvoledUserP1->name], $value->name);
            }

            if ($value->userPhone != "") {
                $var = ['-cid-', '-cl-'];
                $var1 = [$cid, $InvoledUserP1->name];
                $content1 = WaTemplate::getcontent('withdrawal_responding');
                $content = str_replace($var, $var1, $content1);
                $dwa1 = [
                    'caseid' => $request->case_id,
                    'contact' => "+91" . $value->userPhone,
                    'content' => ['text' => $content],
                    'event' => 'WDRN_OTHER_PARTY'
                ];

                $access = Whatsapp::sendWamessage($dwa1);
            }
        }

        $var = ['-cid-', '-rp-'];
        $var1 = [$cid, $responding_party];
        $content1 = WaTemplate::getcontent('withdrawal_initiating');
        $content = str_replace($var, $var1, $content1);
        $dwa1 = [
            'caseid' => $request->case_id,
            'contact' => "+91" . $InvoledUserP1->userPhone,
            'content' => ['text' => $content],
            // 'casetype' => 2,
            'event' => 'WDRN_PARTY'
        ];
        $access = Whatsapp::sendWamessage($dwa1);

        if ($mediator) {
            Email::send($d3, $mediator->email, env('L13_WITHDRAWAL_OF_CASE', ''), ["-caseid-" => $cid, "-responding-" => $InvoledUserP1->name, "-type-" => "Mediator"], $mediator->username);

            $var = ['-cid-'];
            $var1 = [$cid];
            $content1 = WaTemplate::getcontent('withdrawal_mediator');
            $content = str_replace($var, $var1, $content1);
            $dwa1 = [
                'caseid' => $request->case_id,
                'contact' => "+91" . $mediator->mobile_number,
                'content' => ['text' => $content],
                // 'casetype' => 2,
                'event' => 'WDRN_MED'
            ];

            $access = Whatsapp::sendWamessage($dwa1);
        }


        $mediation_status_log = new Mediation_status_log;
        $mediation_status_log->user_id = Auth::user()->id;
        $mediation_status_log->mediation_case_id = $request->case_id;
        $mediation_status_log->status = 5;
        $mediation_status_log->description = "Request Withdrawn";
        $mediation_status_log->save();

        return response()->json(["msg" => "Case withdrawn"]);
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
            echo "<td>" . ucfirst($value->first_name) . ' ' . ucfirst($value->last_name) . "</td>";
            echo "</tr>";

            $sn++;
        }
        //return;
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
        $uploaded_by = $request->uploaded_by;
        /*upload*/
        $uploaded_excel = '';
        $claimantid = $request->claimant;
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
            // dd(count($csv[0]));
            if (count($csv[0]) != 15) {
                $errormsg .= "Invalid csv file";
            }
            $errormsg .= '';
            foreach ($csv as $key => $v) {
                // dd($v[5]);
                $i = $key + 1;

                for ($n = 1; $n < 15; $n++) {
                    if ($v[$n] == '') {
                        if ($n != 10 and $n != 11 and $n != 12) {

                        $errormsg .= "Please fill all the required details to proceed at line no $i ";
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


                //validate date
                if (strpos($v[7], '-')) {
                    $dt = str_replace('-', '/', $v[7]);
                    $v[7] = $dt;
                }

                $dt = explode('/', $v[7]);

                if (count($dt) != 3 and strlen($dt[0]) != 2 and strlen($dt[1]) != 2 and strlen($dt[0]) != 4) {

                    $errormsg .= "Invalid date at line no $i. date format should be dd/mm/YYYY ";
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

            return redirect('/user/newrequest')->with(['error' => $errormsg]);

            exit();
        }
        if (1 == 1) {

            //save file
            $file = $request->file('csv');
            $destinationPath = 'storage/uploaded';

            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;

            if ($file->move($destinationPath, $fileName)) {
                $uploaded_excel .= $fileName;
            }
        }
        // dd($csv);
        //store in database
        foreach ($csv as $k => $value) {
            // dd();
            $data['userid'] = $uploaded_by;
            $data['disputeCategory'] = $value['0'];
            $data['natureOfAgreement'] = $value['6'];
            $data['noOfParties'] = count(explode(',', $value[10])) + 1;
            $data['amount'] = $value[1];
            $data['issue'] = $value[8];
            $data['confirm_status'] = 0;
            $data['otherRespondentDetails'] = $value[12];
            $data['proposedSolution'] = $value[9];
            $med = MedCase::create($data);

            $iniParty = InvoledUser::where(['userPlanid' => $med->id, 'userId' => $uploaded_by])->first();

            if (!$iniParty) {
                // add initiating party
                $iniParty = new InvoledUser();

                $iniParty->userId = $cldetails->id;
                $iniParty->userPlanId = $med->id;
                $iniParty->userEmail = $cldetails->email;
                $iniParty->userPhone = $cldetails->mobile_number;
                $iniParty->name = $cldetails->first_name . ' ' . $cldetails->last_name;
                $iniParty->address1 = $cldetails->address;
                $iniParty->address2 = $cldetails->address1;
                $iniParty->city = $cldetails->city;
                $iniParty->pincode = $cldetails->pincode;
                $iniParty->state = $cldetails->state;
                $iniParty->country = $cldetails->country;
                $iniParty->isOnboarded = 1;

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
            // $resParty->address1 = $value['5'];
            // $resParty->address2 = $value['6'];
            // $resParty->city = $value['7'];
            // $resParty->pincode = $value['8'];
            // $resParty->state = $value['9'];
            // $resParty->country = $value['10'];
            $resParty->isClaimant = 1;
            $resParty->created_at = date('Y-m-d H:s:i');
            $resParty->updated_at = date('Y-m-d H:s:i');
            $resParty->save();


            // $otherDetails = array_merge(["email" => explode(',', $value[21]), 'mobile' => explode(',', $value[22])]);

            $otherResEmail = explode(',', $value[10]);
            $otherResMobile = explode(',', $value[11]);

            $forloopcnt = max(count($otherResEmail), count($otherResMobile));

            if ($otherResEmail[0] != "" or $otherResMobile[0] != "") {

                for ($i = 0; $i < $forloopcnt; $i++) {
                    // for($j = 0; $j < count($otherResMobile); $j++) {


                    $otherDetails = new InvoledUser();
                    $otherDetails->userPlanId = $med->id;
                    $otherDetails->userEmail = isset($otherResEmail[$i]) ? trim($otherResEmail[$i]) : "";
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
            // foreach($otherDetails as $values) {
            //     foreach($values)
            // }
            // $otherResEmail = explode(',', $value[21]);
            // $otherResMobile = explode(',', $value[22]);



        }

        // $var = ['--caseid--'];
        // $var1 = [Common_function::getsixdigitid('sc', $med->id)];
        // $content1 = WaTemplate::getcontent('P23');
        // $content = str_replace($var, $var1, $content1);
        // $dwa1 = [
        //     'caseid' => $med->id,
        //     'contact' => $cldetails->mobile_number,
        //     'content' => ['text' => $content],
        //     'casetype' => 1,
        //     'event' => 'DRCN_ARBTR'
        // ];

        // $access = Whatsapp::sendWamessage($dwa1);


        // print_r($dwa1);
        // exit;


        return redirect('/user/newrequest')->with(['success' => 'Success']);
    }

    public function documentUpload(Request $request, $id)
    {

        $selectDocument = $request->file('document');

        $errormsg = '';

        $med = MedCase::find($id);

        if ($selectDocument !== null) {

            // $errormsg .= $request->validate([
            //     'document' => 'mimes:pdf,zip,rar|max:20048',
            // ]);

            $filename = 'supporting_document' . $med->id . time() . '.' . $selectDocument->getClientOriginalExtension();
            // dd($filename);

            $path = $request->file('document')->storeAs('public/mediation/' . $med->id . '/', $filename);
            $med->documentPath = $filename;
            $med->save();
            return redirect('/user/newrequest')->with(['success' => 'Success']);
        } else {
            $errormsg .= "Please Select Document";
        }

        if ($errormsg != '') {

            return redirect('/user/newrequest')->with(['error' => $errormsg]);

            exit();
        }
    }
}
