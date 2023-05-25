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
use App\Http\Helpers\SendGrid;
use App\Http\Helpers\Whatsapp;
use App\Models\ConsentDisclosures;
use App\Models\Notification;
use App\Models\WaTemplate;
use Session;
use Auth;
use Validator;
use DB;
use Exception;
use Illuminate\Support\Facades\File;
use PDF;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\UploadTrait;



class MediationController extends Controller
{

    use UploadTrait;
    public function __construct() {
        $this->middleware(function ($request, $next) {
                $userdata = User::getUserdetails(Auth::user()->id);
        
                if ($userdata->address == '' or $userdata->address1 == '' or $userdata->city == '' or $userdata->pincode == '' or $userdata->state == '' or $userdata->country == '') {
                    if ($_SERVER['REQUEST_URI'] != "/user/profile") {
                        Session::put('force', 1);
                        
                        header("Location: ../user/profile");
                        exit();
                    }
                } else {
                    return $next($request);
                }
        });
    }

    public function Notification()
    {
        // $view = Notification::where('view', 0)->get();
        // foreach($view as $item) {
        //     $item->view = 1;
        //     $item->save();
        // }
        $data = Notification::userNotification();
        return view('user.notification', compact('data'));
    }

    public function storeMultiFile(Request $request)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'files' => 'required',
            'files.*' => 'mimes:csv,txt,xlx,xls,pdf,rar,zip',
            // 'docs_party_ids' => 'required',
        ]);
        $inuser = InvoledUser::where('userId', Auth::user()->id)->where('userPlanId', $request->caseId)->first();
        if ($request->docs_party_ids == null) {
            $totalAccess = $inuser->id;
        } else {
            $totalAccess = $inuser->id . "," . $request->docs_party_ids;
        }
        // dd($totalAccess);
        if ($request->TotalFiles > 0) {

            for ($x = 0; $x < $request->TotalFiles; $x++) {

                if ($request->hasFile('files' . $x)) {
                    $file = $request->file('files' . $x);
                    $filename = pathinfo(str_replace(" ", "_", $file->getClientOriginalName()), PATHINFO_FILENAME) . "_date_" . date("Y_m_d_H_i_s_a") . "." . $file->extension();
                    // $path = $file->storeAs('/supporting/' . $request->caseId, pathinfo(str_replace(" ", "_", $file->getClientOriginalName()), PATHINFO_FILENAME) . "_date_" . date("Y_m_d_H_i_s_a") . "." . $file->extension());
                    $savePath = 'mediation_documents/mediation/' . $request->caseId . '/supportingDocument';
                    $finalFilePath = $savePath . '/' . $filename;
                    // Storage::put('public/mediation/' . $data["case"]->id . '/' . $name, $pdf->output());
                    Storage::disk('s3')->put($finalFilePath, file_get_contents($file));
                    $insert[$x]['file_name'] = $filename;
                    $insert[$x]['access'] = $totalAccess;
                    $insert[$x]['mediator_access'] = isset($request->shareMediator) ? $request->shareMediator : 0;
                    $insert[$x]['uploaded_by'] = Auth::user()->id;
                    $insert[$x]['case_id'] = $request->caseId;
                    // $insert[$x]['path'] = $path;
                }
            }
            // dd($insert);
            // die();
            // File::insert($insert);
            DB::table('manage_files')->insert($insert);
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
            if ($request->shareMediator == 1) {
                Common_function::MedNotification($request->caseId, "SEND_ADDI_DOC_USER", Auth::user()->id, isset($mediatorNoti) ? $mediatorNoti->id : null, $inv_id);
            } else {
                Common_function::MedNotification($request->caseId, "SEND_ADDI_DOC_USER", Auth::user()->id, null, $inv_id);
            }
            $this->send_upload_file_party($request->caseId, $insert);
            return response()->json(['success' => 'Ajax Multiple fIle has been uploaded']);
        }
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
            $filesE[] = 'mediation_documents/mediation/' . $id . '/supportingDocument/' . $f["file_name"];
            // $filesE[] = url("storage/app/" . $f["file_name"]);
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
                    $varjson = ['caseid' => $mid];
                    $var = ['-cid-'];
                    $var1 = [$mid];
                    $content1 = WaTemplate::getcontent('additional_doc');
                    $content = str_replace($var, $var1, $content1);
                    $dwa1 = [
                        'caseid' => $id,
                        'contact' =>   $inv->userPhone,
                        'content' => ['text' => $content],
                        'event' => 'SEND_ADDI_DOC',
                        'varjson' => $varjson,
                        'haptik_tmp' => 'l19_additional_doc'

                    ];
                    $accessW = Whatsapp::sendWamessage($dwa1);

                    foreach ($filesE as $file) {
                        $whatsappSend = Storage::disk('s3')->url($file);
                        $varjson_file = ['caseid' => $mid];
                        $var_file = ['-caseid-'];
                        $var1_file = [$mid];
                        $content1_file = WaTemplate::getcontent('mediation_consent_doc');
                        $content_file = str_replace($var_file, $var1_file, $content1_file);
                        $dwa2 = [
                            'caseid' => $id,
                            'contact' =>  $inv->userPhone,
                            'content' => ['media' => ['url' => $whatsappSend, 'caption' => $content_file]],
                            'event' => 'SEND_ADDI_DOC',
                            'varjson' => $varjson_file,
                            'haptik_tmp' => 'mediation_consent_doc'

                        ];
                        $accessW = Whatsapp::sendWamessage($dwa2);
                    }
                }
            }
        }
        if ($mediator) {
            // $sendEamils[] = $mediator->email;
            if ($mediatorAccess == 1) {

                $d2 = [
                    'event' => 'SEND_ADDI_DOC_MED',
                    'case_id' => $id,
                ];
                SendGrid::send($d2, $mediator->email, env('L19_ADDITIONAL_DOC_ALL_PARTIES', ''), ["-caseid-" => $mid], null, $filesE);
                $varjson = ['caseid' => $mid];
                $var = ['-cid-'];
                $var1 = [$mid];
                $content1 = WaTemplate::getcontent('additional_doc_med');
                $content = str_replace($var, $var1, $content1);
                $dwa1 = [
                    'caseid' => $id,
                    'contact' =>  $mediator->mobile_number,
                    'content' => ['text' => $content],
                    'event' => 'SEND_ADDI_DOC_MED',
                    'varjson' => $varjson,
                    'haptik_tmp' => 'l20_additional_doc_med'

                ];
                $accessW = Whatsapp::sendWamessage($dwa1);

                foreach ($filesE as $file) {
                    $whatsappSend = Storage::disk('s3')->url($file);
                    $varjson_file = ['caseid' => $mid];
                    $var_file = ['-caseid-'];
                    $var1_file = [$mid];
                    $content1_file = WaTemplate::getcontent('mediation_consent_doc');
                    $content_file = str_replace($var_file, $var1_file, $content1_file);
                    $dwa2 = [
                        'caseid' => $id,
                        'contact' =>  $mediator->mobile_number,
                        'content' => ['media' => ['url' => $whatsappSend, 'caption' => $content_file]],
                        'event' => 'SEND_ADDI_DOC_MED',
                        'varjson' => $varjson_file,
                        'haptik_tmp' => 'mediation_consent_doc'

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

    public function viewSupporting(Request $request)
    {
        $sessionData = DB::table('manage_files')
            ->join('users', 'users.id', '=', 'manage_files.uploaded_by')
            ->where('manage_files.case_id', $request->id)
            // ->where('mediator_access', 1)
            ->where('access', "!=", null)
            ->get();
        $sn = 1;
        foreach ($sessionData as $value) {
            // if($value->mediator_access == 1) {
            $accessId = explode(',', $value->access);
            foreach ($accessId as $item) {
                $userAccess = InvoledUser::find($item);
                if (isset($userAccess)) {
                    if ($userAccess->userId == Auth::user()->id) {
                        echo "<tr>";
                        echo "<td>" . $sn . "</td>";
                        // echo "<td style='word-break: break-word;'><a href='" . url("storage/app/" . $value->file_name) . "' target='_blank'>" . pathinfo($value->file_name, PATHINFO_FILENAME) . "</td>";
                        if (file_exists("storage/app/" . $value->file_name)) {
                            // dd("hello");
                            echo "<td style='word-break: break-word;'><a href='" . url("storage/app/" . $value->file_name) . "' target='_blank'>" . pathinfo($value->file_name, PATHINFO_FILENAME) . "</a></td>";
                        } else {
                            // dd("else");
                            echo "<td style='word-break: break-word;'><a href='javascript:void(0);'  data-folder='supportingDocument'
                            data-url='" . $value->file_name . "'
                            data-id='" . $request->id . "'
                            class='secureDownload' 
                            data-userid='" . Auth::user()->id . "'>" . pathinfo($value->file_name, PATHINFO_FILENAME) . "</a></td>";
                        }
                        echo "<td>" . $value->username . "</td>";
                        echo "</tr>";

                        $sn++;
                    }
                }
            }
            // }
        }
        return;
    }

    public function requestLetter($id)
    {
        // $data["mediator"] = User::find($medid);
        $data["case"] = MedCase::where("id", "=", $id)->first();
        $data["ini"] = InvoledUser::select('user_involved_in_agreement.*', 'usr.organization', 'usr.signature_photo')
            ->leftJoin('users as usr', DB::raw('usr.id'), '=', DB::raw('user_involved_in_agreement.userId'))
            ->where("userPlanId", "=", $id)->where('isClaimant', 0)->first();
        $data["res"] = InvoledUser::where("userPlanId", "=", $id)->where('isClaimant', '<>', 0)->first();
        $pdf = PDF::loadView('pdf.request_letter', $data);
        $name = 'request_letter_M' . sprintf('%06d', $data["case"]->id) . time() . '.pdf';
        $savePath = 'mediation_documents/mediation/' . $data["case"]->id;
        $finalFilePath = $savePath . '/' . $name;
        // Storage::put('public/mediation/' . $data["case"]->id . '/' . $name, $pdf->output());
        $uploadS3 = $this->uploadOnAWSDirect($finalFilePath, $savePath, $pdf);
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


        // dd($InvoledUser);

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

            // dd(count($r['email']));
            //udpate mediation case

            $med->issue = $r['issue'];
            $med->proposedSolution = $r['proposedSolution'];
            $med->disputeCategory = $r['disputeCategory'];
            $med->amount = $r['amount'];
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


            $respond = 0;

            for ($i = 0; $i < count($r['email']); $i++) {

                if ($r['selected_party'][$i] == 0) {

                    $findUser = User::where(['email'=> $r['email'][$i], 'role' => 0])->first();

                    $inv = new InvoledUser();
                    $inv->userId=isset($findUser->id) ? $findUser->id : 0;
                    $inv->userPlanId = $med->id;
                    $inv->userEmail = $r['email'][$i];
                    $inv->userPhone = $r['phone'][$i];
                    $inv->name = $r['name'][$i];
                   
                    $inv->fulladdress = $r['fulladdress'][$i];
                    $inv->isClaimant = '0';
                    $inv->isOnboarded = '1';
                    $inv->created_at = date('Y-m-d H:s:i');
                    $inv->updated_at = date('Y-m-d H:s:i');
                    $inv->save();
                } else {
                    $inv = new InvoledUser();
                    $inv->userPlanId = $med->id;
                    $inv->userEmail = $r['email'][$i];
                    $inv->userPhone = $r['phone'][$i];
                    $inv->name = $r['name'][$i];
                    $inv->joinCode = $this->joinCode();
                    $inv->fulladdress = $r['fulladdress'][$i];
                    $inv->isClaimant = $respond + 1;

                    $inv->created_at = date('Y-m-d H:s:i');
                    $inv->updated_at = date('Y-m-d H:s:i');

                    $inv->save();
                    $respond++;
                }
            }
            $letter = $this->requestLetter($_GET['id']);
            $med->request_letter = $letter;
            $med->save();
            $d = [
                'event' => 'SUBMIT_FORM',
                'case_id' => $med->id,
            ];

            $cid = "M" . sprintf("%06d", $med->id);
            $inv_id = "";
            $inv = InvoledUser::select('id')->where('userPlanId', $med->id)->get();
            foreach ($inv as $v) {
                if ($inv_id == "") {
                    $inv_id = $v->id;
                } else {
                    $inv_id = $inv_id . "," . $v->id;
                }
            }
            Common_function::MedNotification($med->id, "SUBMIT_FORM", Auth::user()->id, null, $inv_id);

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


            // $InvoledUser = InvoledUser::where(['joincode' => $code, 'userEmail' => $email])->first();
            $InvoledUser = InvoledUser::where(['joincode' => $code])->where(function ($q) use ($email, $phone) {
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
            $InvoledUser->onboardedDate = now();
            $InvoledUser->userid = Auth::user()->id;
            if ($InvoledUser->name == null) {
                $InvoledUser->name = Auth::user()->first_name . ' ' . Auth::user()->last_name;
            }
            if ($InvoledUser->userEmail == null) {
                $InvoledUser->userEmail = Auth::user()->email;
            }
            if ($InvoledUser->userPhone == null) {
                $InvoledUser->userPhone = Auth::user()->mobile_number;
            }
            $d = [
                'event' => 'ONBOAR_USER',
                'case_id' => $InvoledUser->userPlanId,
            ];
            if ($InvoledUser->save()) {
                $mediatorNoti = Mediators_mediation_cases_status::select("email", "username", "mobile_number", "users.id")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                    ->where("mediators_mediation_cases_status.mediation_case_id", "=", $InvoledUser->userPlanId)
                    ->where("mediators_mediation_cases_status.status", "=", 1)
                    ->first();
                $inv_id = "";
                $inv = InvoledUser::select('id')->where('userPlanId', $InvoledUser->userPlanId)->get();
                foreach ($inv as $v) {
                    if ($inv_id == "") {
                        $inv_id = $v->id;
                    } else {
                        $inv_id = $inv_id . "," . $v->id;
                    }
                }

                Common_function::MedNotification($InvoledUser->userPlanId, "ONBOAR_USER", Auth::user()->id, isset($mediatorNoti) ? $mediatorNoti->id : null, $inv_id);

                //fetch init parry
                $mid = "M" . sprintf("%06d", $InvoledUser->userPlanId);

                $InvoledUserP1 = InvoledUser::where(['isClaimant' => '0', 'userPlanId' => $InvoledUser->userPlanId])->first();



                $party_name = $InvoledUser->name;

                // $e = Email::send($InvoledUserP1->userEmail, '8c86c224-75e5-4cfd-8bc2-f3305df4d3f3', ['-caseid-' => $mid, '-partyname-' => $party_name], $InvoledUserP1->name);
                $e = Email::send($d, $InvoledUserP1->userEmail, env('L7_UPON_SUCCESSFUL_ONBOARDING_OF_ANY_COUNTER_PARTY', ''), ['-caseid-' => $mid, '-name-' => $party_name], $InvoledUserP1->name);
                $varjson = ['responding' => $party_name, 'caseid' => $mid];
                $var = ['-rp-', '-cid-'];
                $var1 = [$party_name, $mid];
                $content1 = WaTemplate::getcontent('l7_mediation_onboarded');
                $content = str_replace($var, $var1, $content1);
                $dwa1 = [
                    'caseid' => $InvoledUser->userPlanId,
                    'contact' =>  $InvoledUserP1->userPhone,
                    'content' => ['text' => $content],
                    'event' => 'ONBOAR_USER',
                    'varjson' => $varjson,
                    'haptik_tmp' => 'l7_mediation_onboarded'

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


        // $new = MedCase::Where(['userid' => Auth::user()->id, 'confirm_status' => 0])->orderby('id', 'DESC')->get();

        // $pending = [];

        // foreach ($new as $key => $value) {
        //     // $in = InvoledUser::select('name', 'isOnboarded')->where(['userPlanid' => $value->id])->get();
        //     $in = InvoledUser::select('user_involved_in_agreement.name', 'user_involved_in_agreement.isOnboarded', 'user_involved_in_agreement.isClaimant', "user_involved_in_agreement.userId", "users.organization")->leftjoin('users', 'users.id', '=', 'user_involved_in_agreement.userId')->where(['userPlanid' => $value->id])->get();

        //     $value->party = $in;

        //     $pending[] = $value;
        // }


        return view('user.newrequest', ['response' => Session::get('response')]);
    }

    public function ongoing()
    {


        // $new=InvoledUser::select('user_involved_in_agreement.*','mediation_case.id as caseid')->where(['user_involved_in_agreement.userid'=>Auth::user()->id])->leftJoin('mediation_case', 'user_involved_in_agreement.userPlanId', '=', 'mediation_case.id')->get();


        // $new = MedCase::select('user_involved_in_agreement.*', 'mediation_case.id as caseid', 'mediation_case.created_at as date', 'mediation_case.userid', DB::raw("CONCAT(users.first_name,' ',users.last_name,' - ',users.organization) as mediator"), 'consent_disclosures.id as consent', 'mediators_mediation_cases_status.status as mstatus', 'mediators_mediation_cases_status.updated_at as update', "consent_disclosures.created_at as create")
        //     ->where(['user_involved_in_agreement.userid' => Auth::user()->id, 'mediation_case.confirm_status' => 1])
        //     ->leftJoin("mediators_mediation_cases_status", "mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id")
        //     ->leftJoin("consent_disclosures", "mediation_case.id", "=", "consent_disclosures.mediation_case_id")
        //     ->leftJoin("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
        //     ->leftJoin('user_involved_in_agreement', 'mediation_case.id', '=', 'user_involved_in_agreement.userPlanId')
        //     ->orderby('mediation_case.id', 'DESC')
        //     ->get();

        // $ongoing = [];

        // foreach ($new as $key => $value) {
        //     // $in = InvoledUser::select('id', 'userId', 'name', 'isOnboarded')->where(['userPlanid' => $value->caseid])->get();
        //     $in = InvoledUser::select('user_involved_in_agreement.id', 'user_involved_in_agreement.name', 'user_involved_in_agreement.isOnboarded', 'user_involved_in_agreement.isClaimant', "user_involved_in_agreement.userId", "users.organization")->leftjoin('users', 'users.id', '=', 'user_involved_in_agreement.userId')->where(['userPlanid' => $value->caseid])->get();

        //     $value->party = $in;

        //     $value->casestatus = Mediation_status_log::select("status", "description", DB::raw("DATE_FORMAT(created_at,'%d-%c-%y %h:%i %p') as created"))->where(['mediation_case_id' => $value->caseid])->orderByDesc('id')->limit(1)->first();

        //     $value->share_count = Mediation_case_comment::where("type", 0)->where("mediation_case_id", $value->caseid)->count();
        //     $value->share_view_count = Mediation_case_comment::where("type", 0)->where("mediation_case_id", $value->caseid)->where("view_user", 0)->count();

        //     $ongoing[] = $value;
        // }
        $confirm_status = 1;

        return view('user.ongoing', ['confirm_status' => $confirm_status]);
    }

    public function closed()
    {

        // $new = MedCase::select('user_involved_in_agreement.*', 'mediation_case.id as caseid', 'mediation_case.created_at as date', DB::raw("CONCAT(users.first_name,' ',users.last_name,' - ',users.organization) as mediator"), 'mediation_case.withdraw', 'consent_disclosures.id as consent', 'mediators_mediation_cases_status.status as mstatus', 'mediators_mediation_cases_status.updated_at as update', 'consent_disclosures.created_at as create')
        //     ->where(['user_involved_in_agreement.userid' => Auth::user()->id, 'mediation_case.confirm_status' => 2])
        //     ->leftJoin("mediators_mediation_cases_status", "mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id")
        //     ->leftJoin("consent_disclosures", "mediation_case.id", "=", "consent_disclosures.mediation_case_id")
        //     ->leftJoin("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
        //     ->leftJoin('user_involved_in_agreement', 'mediation_case.id', '=', 'user_involved_in_agreement.userPlanId')
        //     ->orderby('mediation_case.id', 'DESC')
        //     ->get();

        // $closed = [];

        // foreach ($new as $key => $value) {
        //     // $in = InvoledUser::select('name', 'isOnboarded')->where(['userPlanid' => $value->caseid])->get();
        //     $in = InvoledUser::select('user_involved_in_agreement.name', 'user_involved_in_agreement.isOnboarded', 'user_involved_in_agreement.isClaimant', "user_involved_in_agreement.userId", "users.organization")->leftjoin('users', 'users.id', '=', 'user_involved_in_agreement.userId')->where(['userPlanid' => $value->caseid])->get();

        //     $value->party = $in;

        //     $value->casestatus = Mediation_status_log::select("status", "description", DB::raw("DATE_FORMAT(created_at,'%d-%c-%y %h:%i %p') as created"))->where(['mediation_case_id' => $value->caseid])->orderByDesc('id')->limit(1)->first();
        //     $value->share_count = Mediation_case_comment::where("type", 0)->where("mediation_case_id", $value->caseid)->count();
        //     $value->share_view_count = Mediation_case_comment::where("type", 0)->where("mediation_case_id", $value->caseid)->where("view_user", 0)->count();

        //     if (isset($value->casestatus)) {
        //         $value->casestatus->css = '';

        //         if ($value->casestatus->status == 2) {

        //             $value->casestatus->css = 'danger';
        //         } else if ($value->casestatus->status == 5) {

        //             $value->casestatus->css = 'danger';
        //         } else if ($value->casestatus->status == 6) {

        //             $value->casestatus->css = 'success';
        //         } else if ($value->casestatus->status == 7) {

        //             $value->casestatus->css = 'danger';
        //         }
        //     }
        //     $closed[] = $value;
        // }
        $confirm_status = 2;

        return view('user.closed', ['confirm_status' => $confirm_status]);
    }

    public function rejected()
    {

        $new = MedCase::select('user_involved_in_agreement.*', 'mediation_case.id as caseid', 'mediation_case.created_at as date', 'mediation_case.withdraw', 'mediation_case.ref_id')
            ->where(['user_involved_in_agreement.userid' => Auth::user()->id, 'mediation_case.confirm_status' => 3])
            ->leftJoin('user_involved_in_agreement', 'mediation_case.id', '=', 'user_involved_in_agreement.userPlanId')
            ->get();

        $closed = [];

        foreach ($new as $key => $value) {
            // $in = InvoledUser::select('name', 'isOnboarded')->where(['userPlanid' => $value->caseid])->get();
            $in = InvoledUser::select('user_involved_in_agreement.name', 'user_involved_in_agreement.isOnboarded', 'user_involved_in_agreement.isClaimant', "user_involved_in_agreement.userId", "users.organization")->leftjoin('users', 'users.id', '=', 'user_involved_in_agreement.userId')->where(['userPlanid' => $value->caseid])->get();
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

    public function casedetails($id)
    {



        $case = MedCase::select("mediation_case.*", "users.first_name as mfirstname", "users.last_name as mlastname", "mediators_mediation_cases_status.mediator_id as mediator_id", "mediators_mediation_cases_status.status as mediator_status")
            ->leftJoin("mediators_mediation_cases_status", "mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id")
            ->leftJoin("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->where('mediation_case.id', '=', $id)
            ->first();


        // $case->party = InvoledUser::where(['userPlanid' => $case->id])->get();
        $case->party = InvoledUser::select('user_involved_in_agreement.*', 'users.address as useraddress', 'users.address1 as useraddress1', 'users.pincode as userpincode', 'users.city as usercity', 'users.state as userstate', 'users.country as usercountry')
            ->leftJoin("users", "users.id", "=", "user_involved_in_agreement.userId")
            ->where(['user_involved_in_agreement.userPlanid' => $case->id])->get();

        // $case->invitation = InvitationFiles::where(['case_id' => $case->id])->orderByDesc('id')->limit(1)->first();
        $case->invitation = InvitationFiles::where(['case_id' => $case->id])->orderByDesc('id')->get();

        $case->supporting_document = DB::table('manage_files')->select('manage_files.*', 'users.username')
            ->join('users', 'users.id', '=', 'manage_files.uploaded_by')
            ->where('manage_files.case_id', $case->id)
            ->get();

        return view('user.casedetails', compact("case"));
    }

    public function withdraw(Request $request)
    {



        $user = MedCase::find($request->case_id);
        $user->confirm_status = 2;
        $user->withdraw = $request->withdraw_comment;
        $user->save();
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
        //p1

        $cid = "M" . sprintf("%06d", $request->case_id);

        $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->where("mediators_mediation_cases_status.mediation_case_id", "=", $request->case_id)
            ->where("mediators_mediation_cases_status.status", "=", 1)
            ->first();


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
                $varjson = ['caseid' => $cid, 'initiating' => $InvoledUserP1->name];
                $var = ['-cid-', '-cl-'];
                $var1 = [$cid, $InvoledUserP1->name];
                $content1 = WaTemplate::getcontent('withdrawal_responding');
                $content = str_replace($var, $var1, $content1);
                $dwa1 = [
                    'caseid' => $request->case_id,
                    'contact' =>  $value->userPhone,
                    'content' => ['text' => $content],
                    'event' => 'WDRN_OTHER_PARTY',
                    'varjson' => $varjson,
                    'haptik_tmp' => 'l14_withdrawal_responding'

                ];

                $access = Whatsapp::sendWamessage($dwa1);
            }
        }

        $varjson = ['caseid' => $cid, 'responding' => $responding_party];
        $var = ['-cid-', '-rp-'];
        $var1 = [$cid, $responding_party];
        $content1 = WaTemplate::getcontent('withdrawal_initiating');
        $content = str_replace($var, $var1, $content1);
        $dwa1 = [
            'caseid' => $request->case_id,
            'contact' =>  $InvoledUserP1->userPhone,
            'content' => ['text' => $content],
            // 'casetype' => 2,
            'event' => 'WDRN_PARTY',
            'varjson' => $varjson,
            'haptik_tmp' => 'l13_session_schedule'


        ];
        $access = Whatsapp::sendWamessage($dwa1);

        if ($mediator) {
            Email::send($d3, $mediator->email, env('L13_WITHDRAWAL_OF_CASE', ''), ["-caseid-" => $cid, "-responding-" => $InvoledUserP1->name, "-type-" => "Mediator"], $mediator->username);
            $varjson = ['caseid' => $cid];
            $var = ['-cid-'];
            $var1 = [$cid];
            $content1 = WaTemplate::getcontent('withdrawal_mediator');
            $content = str_replace($var, $var1, $content1);
            $dwa1 = [
                'caseid' => $request->case_id,
                'contact' =>  $mediator->mobile_number,
                'content' => ['text' => $content],
                // 'casetype' => 2,
                'event' => 'WDRN_MED',
                'varjson' => $varjson,
                'haptik_tmp' => 'l23_withdrawal_mediator'


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
            // echo "<td><a href='" . url("storage/app/" . $value->file_path) . "' target='_blank'>" . pathinfo($value->file_path, PATHINFO_FILENAME) . "</td>";
            if (file_exists("storage/app/" . $value->file_path)) {
                // dd("hello");
                echo "<td style='word-break: break-word;'><a href='" . url("storage/app/" . $value->file_path) . "' target='_blank'>" . pathinfo($value->file_path, PATHINFO_FILENAME) . "</a></td>";
            } else {
                // dd("else");
                echo "<td style='word-break: break-word;'><a href='javascript:void(0);'  data-folder='settelmentDocument'
                data-url='" . $value->file_path . "'
                data-id='" . $request->id . "'
                class='secureDownload' 
                data-userid='" . Auth::user()->id . "'>" . pathinfo($value->file_path, PATHINFO_FILENAME) . "</a></td>";
            }
            echo "<td>" . ucfirst($value->first_name) . ' ' . ucfirst($value->last_name) . "</td>";
            echo "</tr>";

            $sn++;
        }
        //return;
    }

    public function getConsentAndDisclosures($id)
    {
        $data = ConsentDisclosures::select('consent_disclosures.*', 'users.first_name', 'users.last_name', 'users.email', 'users.username', 'users.mobile_number', 'users.organization', 'users.signature_photo', 'users.id as medId')->join("users", "consent_disclosures.mediator_id", "=", "users.id")
            ->where("mediation_case_id", "=", $id)
            ->first();
        if (isset($data)) {
            if ($data->file_name != null) {
                $dis_file_name = $data->file_name;
                $exist_file = storage_path() . '/app/public/mediation/' . $id . '/' . $dis_file_name;
            } else {
                $dis_file_name = "M" . sprintf("%06d", $id) . "_party.pdf";
                $exist_file = storage_path() . '/app/public/mediation/' . $id . '/' . $dis_file_name;
            }
            // dd($exist_file);
            if (File::exists($exist_file)) {
                $pdf = file_get_contents($exist_file);
                return response($pdf, 200, [
                    'Content-Disposition' => 'attachment; filename="' . "consent_and_disclosures_" . $dis_file_name . '"',
                ]);
            } else {
                $filenametostore = 'mediation_documents/mediation/' . $id . '/' . $data->file_name;
                $s3Client = Storage::cloud()->getAdapter()->getClient();

                $stream = $s3Client->getObject([
                    'Bucket' => env('AWS_BUCKET'),
                    'Key'    => $filenametostore
                ]);

                return response($stream['Body'], 200)->withHeaders([
                    'Content-Type'        => $stream['ContentType'],
                    'Content-Length'      => $stream['ContentLength'],
                    'Content-Disposition' => 'attachment; filename="consent_and_disclosures_' . $data->file_name . '"'
                ]);
            }
        } else {
            return "File Not Found";
        }
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
        if ($selectCsv != null) {

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
                    $i = $key + 1;

                    for ($n = 0; $n < 15; $n++) {
                        if ($v[$n] == '') {

                            if ($n != 10 and $n != 11 and $n != 12 and $n != 7 and $n != 3 and $n != 4) {
                                $errormsg .= "Please fill all the required details to proceed at line no $i";
                            }
                        }
                    }
                    if ($v[4] != "") {
                        if (!filter_var($v[4], FILTER_SANITIZE_NUMBER_INT)) {
                            $errormsg .= "Invalid mobile number at line no $i ";
                        }

                        if (strlen($v[4]) != 10) {
                            $errormsg .= "Invalid mobile number at line no $i ";
                        }
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
                        } else {
                            $errormsg .= "Invalid date at line no $i. date format should be dd/mm/YYYY or dd-mm-YYYY";
                        }
                        if (strpos($v[7], '-') or strpos($v[7], '/')) {

                            $dt = explode('/', $v[7]);

                            if (count($dt) != 3 and strlen($dt[0]) != 2 and strlen($dt[1]) != 2 and strlen($dt[0]) != 4) {

                                $errormsg .= "Invalid date at line no $i. date format should be dd/mm/YYYY or dd-mm-YYYY";
                            }
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

                return redirect('/user/newrequest')->with(['error' => $errormsg]);

                exit();
            }
            // if (1 == 1) {

            //     //save file
            //     $file = $request->file('csv');
            //     $destinationPath = 'storage/uploaded';

            //     $extension = $file->getClientOriginalExtension();
            //     $fileName = time() . '.' . $extension;

            //     if ($file->move($destinationPath, $fileName)) {
            //         $uploaded_excel .= $fileName;
            //     }
            // }
            // dd($csv);
            //store in 
            $csv = mb_convert_encoding($csv, 'UTF-8', 'UTF-8');

            foreach ($csv as $k => $value) {
                // dd();
                $data['userid'] = $uploaded_by;
                $data['disputeCategory'] = $value['0'];
                $data['natureOfAgreement'] = $value['6'];
                $data['agreementDate'] = $value['7'];
                $data['noOfParties'] = count(explode(',', $value[10])) + 1;
                $data['amount'] = $value['1'];
                $data['issue'] = $value['8'];
                $data['confirm_status'] = 0;
                $data['otherRespondentDetails'] = $value[12];
                $data['proposedSolution'] = $value[9];
                $data['bulk_flag'] = 1;

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
                if (strpos($value[10], '/')) {
                    $otherResEmail = explode('/', $value[10]);
                } else {
                    $otherResEmail = explode(',', $value[10]);
                }
                if (strpos($value[11], '/')) {
                    $otherResMobile = explode('/', $value[11]);
                } else {
                    $otherResMobile = explode(',', $value[11]);
                }
                // $otherResEmail = explode(',', $value[10]);
                // $otherResMobile = explode(',', $value[11]);

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
                $inv_id = "";
                $inv = InvoledUser::select('id')->where('userPlanId', $med->id)->get();
                foreach ($inv as $v) {
                    if ($inv_id == "") {
                        $inv_id = $v->id;
                    } else {
                        $inv_id = $inv_id . "," . $v->id;
                    }
                }
                Common_function::MedNotification($med->id, "SUBMIT_FORM", Auth::user()->id, null, $inv_id);

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
        } else {
            return redirect('/user/newrequest')->with(['error' => 'Please Select File']);
        }
    }

    public function documentUpload(Request $request, $id)
    {

        $selectDocument = $request->file('document');

        $errormsg = '';

        $med = MedCase::find($id);

        if ($selectDocument !== null) {

            $ext = pathinfo($selectDocument->getClientOriginalName(), PATHINFO_EXTENSION);
            if ($ext == "pdf" || $ext == "zip" || $ext == "rar") {
                $filename = 'supporting_document' . $med->id . time() . '.' . $selectDocument->getClientOriginalExtension();
                // dd($filename);

                // $path = $request->file('document')->storeAs('public/mediation/' . $med->id . '/', $filename);
                $savePath = 'mediation_documents/mediation/' . $med->id . '/user/supportingDocument';
                $finalFilePath = $savePath . '/' . $filename;
                // Storage::put('public/mediation/' . $data["case"]->id . '/' . $name, $pdf->output());
                Storage::disk('s3')->put($finalFilePath, file_get_contents($selectDocument));

                $med->documentPath = $filename;
                $med->save();
                return redirect('/user/newrequest')->with(['success' => 'Success']);
            } else {
                $errormsg .= "Only Pdf, zip and rar file allowed";
            }
            // $errormsg .= $request->validate([
            //     'document' => 'mimes:pdf,zip,rar|max:20048',
            // ]);
        } else {
            $errormsg .= "Please Select Document";
        }

        if ($errormsg != '') {

            return redirect('/user/newrequest')->with(['error' => $errormsg]);

            exit();
        }
    }

    public function json($role = 0)
    {

        $draw = $_POST['sEcho'];
        $row = $_POST['iDisplayStart'];
        $rowperpage = $_POST['iDisplayLength']; // Rows display per page
        $indexColumn = $_POST['iSortCol_0'];
        $columnName = $_POST['mDataProp_' . $indexColumn]; // Column name
        $columnSortOrder = $_POST['sSortDir_0']; // asc or desc
        $searchValue = $_POST['sSearch'];

        $casescount = MedCase::getCaseCountOngoingUser($searchValue, $role);
        $cases = MedCase::getCaseOngoingUser($searchValue, $columnName, $columnSortOrder, $draw, $row, $rowperpage, $role);

        $arraydata = array();

        foreach ($cases as $key => $value) {

            $arraydata[] = [
                "key" => $key + 1,
                "date" => date('d-m-Y', strtotime($value->date)),
                "casestatus" => Mediation_status_log::select("status", "description", DB::raw("DATE_FORMAT(created_at,'%d-%c-%y %h:%i %p') as created"))->where(['mediation_case_id' => $value->caseid])->orderByDesc('id')->limit(1)->first(),
                "case" => $value,
                "party" => InvoledUser::select('user_involved_in_agreement.id', 'user_involved_in_agreement.name', 'user_involved_in_agreement.isOnboarded', 'user_involved_in_agreement.isClaimant', "user_involved_in_agreement.userId", "users.organization")->leftjoin('users', 'users.id', '=', 'user_involved_in_agreement.userId')->where(['userPlanid' => $value->caseid])->get(),
                "share_count" => Mediation_case_comment::where("type", "=", 0)->where('mediation_case_id', $value->caseid)->count(),
                "share_view_count" => Mediation_case_comment::where("type", "=", 0)->where('mediation_case_id', $value->caseid)->where('view', 0)->count(),
                "mediator_create_action_date" =>  date('d-m-Y', strtotime($value->create)),
            ];
        }


        // foreach ($new as $key => $value) {
        //     // $in = InvoledUser::select('name', 'isOnboarded')->where(['userPlanid' => $value->caseid])->get();


        //     if (isset($value->casestatus)) {
        //         $value->casestatus->css = '';

        //         if ($value->casestatus->status == 2) {

        //             $value->casestatus->css = 'danger';
        //         } else if ($value->casestatus->status == 5) {

        //             $value->casestatus->css = 'danger';
        //         } else if ($value->casestatus->status == 6) {

        //             $value->casestatus->css = 'success';
        //         } else if ($value->casestatus->status == 7) {

        //             $value->casestatus->css = 'danger';
        //         }
        //     }
        //     $closed[] = $value;
        // }

        return response()->json(["sEcho" => intval($draw), "iTotalRecords" => $casescount, "iTotalDisplayRecords" => $casescount, "aaData" => $arraydata]);
    }

    public function NewReq()
    {
        $draw = $_POST['sEcho'];
        $row = $_POST['iDisplayStart'];
        $rowperpage = $_POST['iDisplayLength']; // Rows display per page
        $indexColumn = $_POST['iSortCol_0'];
        $columnName = $_POST['mDataProp_' . $indexColumn]; // Column name
        $columnSortOrder = $_POST['sSortDir_0']; // asc or desc
        $searchValue = $_POST['sSearch'];

        $casescount = MedCase::getCaseCountNewReqUser($searchValue);
        $cases = MedCase::getCaseNewReqUser($searchValue, $columnName, $columnSortOrder, $draw, $row, $rowperpage);

        $arraydata = array();
        foreach ($cases as $key => $value) {

            $arraydata[] = [
                "key" => $key + 1,
                "case" => $value,
                "date" => date('d-m-Y', strtotime($value->created_at)),
                "party" => InvoledUser::select('user_involved_in_agreement.name', 'user_involved_in_agreement.isOnboarded', 'user_involved_in_agreement.isClaimant', "user_involved_in_agreement.userId", "users.organization")->leftjoin('users', 'users.id', '=', 'user_involved_in_agreement.userId')->where(['userPlanid' => $value->userPlanId])->get(),
            ];
        }
        return response()->json(["sEcho" => intval($draw), "iTotalRecords" => $casescount, "iTotalDisplayRecords" => $casescount, "aaData" => $arraydata]);
    }
}
