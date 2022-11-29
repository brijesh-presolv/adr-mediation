<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Common_function;
use App\Http\Helpers\Curl;
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
use App\Http\Traits\UploadTrait;
use App\Models\Batch;
use App\Models\BulkLog;
use App\Models\CourierCsv;
use App\Models\CourierPdf;
use App\Models\EmailTrack;
use App\Models\ManageSession;
use App\Models\Notification;
use App\Models\Reminder;
use App\Models\WaTemplate;
use App\Models\WhatsappTrack;
use DB;
use PDF;
use Auth;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\File;
use PDFMerger;
use Storage;
use ZipArchive;

use App\Http\Helpers\Zoom;

class CaseController extends Controller
{
    use UploadTrait;

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

        $data["comment"] = Mediation_case_comment::select("users.username", "mediation_case_comment.comment", "mediation_case_comment.type", DB::raw("DATE_FORMAT(mediation_case_comment.created_at,'%d-%c-%y %h:%i %p') as created"))->join("users", "mediation_case_comment.user_id", "=", "users.id")->where("mediation_case_id", $id)->where("mediation_case_comment.type", "=", $type)->get();

        $data["caseId"] = $id;
        $data["type"] = $type;
        $data["case"] = MedCase::find($id);
        // $data["party"] = InvoledUser::where("userPlanId", "=", $id)->get();
        $data["party"] = InvoledUser::select('user_involved_in_agreement.*', 'users.address as useraddress', 'users.address1 as useraddress1', 'users.pincode as userpincode', 'users.city as usercity', 'users.state as userstate', 'users.country as usercountry')
            ->leftJoin("users", "users.id", "=", "user_involved_in_agreement.userId")
            ->where("user_involved_in_agreement.userPlanId", "=", $id)->get();
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
            echo "";
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
            // }
        }
        return;
    }

    public function index()
    {
        $users = User::where("role", "=", 1)->get();
        $allUsers = User::where("role", "=", 0)->get();
        $batchName = Batch::get();
        $confirm_status = 0;
        return view('admin.case.index', compact("confirm_status", "users", "allUsers", "batchName"));
    }

    public function ongoingRequest()
    {
        $users = User::where("role", "=", 1)->get();
        $confirm_status = 1;
        $batchName = Batch::get();
        return view('admin.case.ongoing', compact("confirm_status", "users", "batchName"));
    }

    public function closedRequest()
    {
        $batchName = Batch::get();
        $confirm_status = 2;
        return view('admin.case.close', compact("confirm_status", "batchName"));
    }

    public function rjectedRequest()
    {
        $batchName = Batch::get();
        $users = User::where("role", "=", 1)->get();
        $confirm_status = 3;
        return view('admin.case.rjected', compact("confirm_status", "users", "batchName"));
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
                    'Content-Disposition' => 'attachment; filename="' . $data->file_name . '"'
                ]);
            }
        } else {
            return "File Not Found";
        }
    }

    public function casedetails($id)
    {


        $case = MedCase::select("mediation_case.*", "users.first_name as mfirstname", "users.last_name as mlastname", "mediators_mediation_cases_status.mediator_id as mediator_id", "mediators_mediation_cases_status.status as mediator_status")
            ->leftJoin("mediators_mediation_cases_status", "mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id")
            ->leftJoin("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->where('mediation_case.id', '=', $id)
            ->first();


        $case->party = InvoledUser::select('user_involved_in_agreement.*', 'users.address as useraddress', 'users.address1 as useraddress1', 'users.pincode as userpincode', 'users.city as usercity', 'users.state as userstate', 'users.country as usercountry')
            ->leftJoin("users", "users.id", "=", "user_involved_in_agreement.userId")
            ->where(['user_involved_in_agreement.userPlanid' => $case->id])->get();

        // $case->invitation = InvitationFiles::where(['case_id' => $case->id])->orderByDesc('id')->limit(1)->first();
        $case->invitation = InvitationFiles::where(['case_id' => $case->id])->orderByDesc('id')->get();

        $case->appointment = InvitationFiles::where(['case_id' => $case->id])->where('file_name_mediator_appointment', '!=', null)->orderByDesc('id')->limit(1)->first();

        $case->supporting_document = DB::table('manage_files')->select('manage_files.*', 'users.username')
            ->join('users', 'users.id', '=', 'manage_files.uploaded_by')
            ->where('manage_files.case_id', $case->id)
            ->get();


        return view('admin.case.casedetails', compact("case"));
    }

    public function confirmStatus(Request $request)
    {

        $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number", "users.id")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->where("mediators_mediation_cases_status.mediation_case_id", "=", $request->id)
            // ->where("mediators_mediation_cases_status.status", "=", 1)
            ->first();
        $inv_id = "";
        // $inv = InvoledUser::select('id')->where('userPlanId', $request->id)->get();
        $inv = InvoledUser::select('user_involved_in_agreement.*', 'users.address as useraddress', 'users.address1 as useraddress1', 'users.pincode as userpincode', 'users.city as usercity', 'users.state as userstate', 'users.country as usercountry')
            ->leftJoin("users", "users.id", "=", "user_involved_in_agreement.userId")
            ->where(['user_involved_in_agreement.userPlanid' => $request->id])->get();
        foreach ($inv as $v) {
            if ($inv_id == "") {
                $inv_id = $v->id;
            } else {
                $inv_id = $inv_id . "," . $v->id;
            }
        }

        if ($inv[0]->address1 != null || $inv[0]->useraddress != null) {


            if (isset($_POST['log_id']) && isset($_POST['allcids'])) {
                if ($_POST['log_id'] == "" && $_POST['allcids'] != "") {
                    $params['allcids'] = json_encode(explode(',', $_POST['allcids']));
                    $log = BulkLog::create([
                        "selected_ids" => $params['allcids'],
                        "uploaded_by" => Auth::user()->id,
                        "total_row" => isset($_POST['total_row']) ? $_POST['total_row'] : "",
                        "log_type" => isset($_POST['log_type']) ? $_POST['log_type'] : "",
                        "updated_at" => date('Y-m-d H:i:s'),
                    ]);
                    $log_id = $log->id;
                    Common_function::MedNotification($request->allcids, "ACPTARB_ADM", Auth::user()->id, isset($mediator) ? $mediator->id : null, null);
                }
            } else {
                Common_function::MedNotification($request->id, "ACPTARB_ADM", Auth::user()->id, isset($mediator) ? $mediator->id : null, $inv_id);
            }

            $medCas = MedCase::find($request->id);
            $medCas->confirm_status = 1;
            $medCas->case_status = 1;
            /*** Discussion field : START ***/
            $medCas->discussion = $request->discussion;
            /*** Discussion field : END ***/
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
            // $invmodel->save();
            //send invitation
            $invmodel->save();
            if ($this->sned_invitation($request->id, $invitation, $medCas->bulk_flag)) {
                if (isset($_POST['log_id']) && $_POST['log_id'] != "") {
                    $success_log = BulkLog::find($_POST['log_id']);
                    // dd($success_log);

                    if ($success_log->inserted_row == null) {
                        $success_log->inserted_row = $request->id;
                        $success_log->save();
                    } else {
                        if (isset($_POST['insertRow'])) {

                            $insert_row = $_POST['insertRow'] . "," . $request->id;
                            // dd(json_encode(explode(',', $insert_row)));
                            $success_log->inserted_row = json_encode(explode(',', $insert_row));
                            $success_log->save();
                        }
                    }

                    return json_encode(['code' => 200, 'response' => 'success', 'log_id' => $_POST['log_id'], 'caseid' => $request->id]);
                } else if (isset($log_id)) {
                    $success_log = BulkLog::find($log_id);
                    // dd($success_log);

                    if ($success_log->inserted_row == null) {
                        $success_log->inserted_row = $request->id;
                        $success_log->save();
                    } else {
                        if (isset($_POST['insertRow'])) {

                            $insert_row = $_POST['insertRow'] . "," . $request->id;
                            // dd(json_encode(explode(',', $insert_row)));
                            $success_log->inserted_row = json_encode(explode(',', $insert_row));
                            $success_log->save();
                        }
                    }
                    return json_encode(['code' => 200, 'response' => 'success', 'log_id' => $log_id, 'caseid' => $request->id]);
                } else {
                    return json_encode(['code' => 200, 'response' => 'success', 'caseid' => $request->id]);
                }
            } else {

                // code for failed row
                if (isset($_POST['log_id']) && $_POST['log_id'] != "") {
                    $faild_log = BulkLog::find($_POST['log_id']);
                    if ($faild_log->failed_row == null) {
                        $faild_log->failed_row = $request->id;
                        $faild_log->save();
                    } else {
                        if (isset($_POST['faildRow'])) {

                            $faild_row = $_POST['faildRow'] . "," . $request->id;
                            // $faild_log->failed_row = $faild_log->failed_row + "," + $request->id;
                            $faild_log->failed_row = json_encode(explode(',', $faild_row));
                            $faild_log->save();
                        }
                    }
                    return json_encode(['code' => 200, 'response' => 'error', 'log_id' => $_POST['log_id'], 'caseid' => $request->id]);
                } else if (isset($log_id)) {
                    $faild_log = BulkLog::find($log_id);
                    if ($faild_log->failed_row == null) {
                        $faild_log->failed_row = $request->id;
                        $faild_log->save();
                    } else {
                        if (isset($_POST['faildRow'])) {

                            $faild_row = $_POST['faildRow'] . "," . $request->id;
                            // $faild_log->failed_row = $faild_log->failed_row + "," + $request->id;
                            $faild_log->failed_row = json_encode(explode(',', $faild_row));
                            $faild_log->save();
                        }
                    }
                    return json_encode(['code' => 200, 'response' => 'error', 'log_id' => $log_id, 'caseid' => $request->id]);
                } else {
                    return json_encode(['code' => 200, 'response' => 'error', 'caseid' => $request->id]);
                }
            }
        } else {
            return json_encode(['code' => 200, 'response' => 'error', 'caseid' => $request->id]);
        }
    }

    public function withdrawStatus(Request $request)
    {

        $status = ($request->status != null) ? $request->status : $request->fsData['status'];
        $withdraw_comment = ($request->withdraw_comment != null) ? $request->withdraw_comment : $request->fsData['withdraw_comment'];
        if ($status != null && $withdraw_comment != null) {

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
            if (isset($_POST['log_id']) && isset($_POST['allcids'])) {
                if ($_POST['log_id'] == "" && $_POST['allcids'] != "") {
                    $params['allcids'] = json_encode(explode(',', $_POST['allcids']));
                    $log = BulkLog::create([
                        "selected_ids" => $params['allcids'],
                        "uploaded_by" => Auth::user()->id,
                        "total_row" => isset($_POST['total_row']) ? $_POST['total_row'] : "",
                        "log_type" => isset($_POST['log_type']) ? $_POST['log_type'] : "",
                        "updated_at" => date('Y-m-d H:i:s'),
                    ]);
                    $log_id = $log->id;
                    if (Mediation_status_log::STATUS_WITHDRAWN == $status) {
                        if (Auth::user()->role == 1) {
                            Common_function::MedNotification($_POST['allcids'], "WDRN_BY_MED", Auth::user()->id, null, null);
                        } else {
                            Common_function::MedNotification($_POST['allcids'], "WDRN_BY_ADMIN", Auth::user()->id, null, null);
                        }
                    } else if (Mediation_status_log::STATUS_RESOLVED == $status) {
                        if (Auth::user()->role == 1) {
                            Common_function::MedNotification($_POST['allcids'], "RES_BY_MED", Auth::user()->id, null, null);
                        } else {
                            Common_function::MedNotification($_POST['allcids'], "RES_BY_ADMIN", Auth::user()->id, null, null);
                        }
                    } else if (Mediation_status_log::STATUS_UNRESOLVED == $status) {
                        if (Auth::user()->role == 1) {
                            Common_function::MedNotification($_POST['allcids'], "UNRES_BY_MED", Auth::user()->id, null, null);
                        } else {
                            Common_function::MedNotification($_POST['allcids'], "UNRES_BY_ADMIN", Auth::user()->id, null, null);
                        }
                    }
                }
            } else {
                if (Mediation_status_log::STATUS_WITHDRAWN == $status) {
                    if (Auth::user()->role == 1) {
                        Common_function::MedNotification($request->case_id, "WDRN_BY_MED", Auth::user()->id, Auth::user()->id, null);
                    } else {
                        Common_function::MedNotification($request->case_id, "WDRN_BY_ADMIN", Auth::user()->id, isset($mediator) ? $mediator->id : null, null);
                    }
                } else if (Mediation_status_log::STATUS_RESOLVED == $status) {
                    if (Auth::user()->role == 1) {
                        Common_function::MedNotification($request->case_id, "RES_BY_MED", Auth::user()->id, Auth::user()->id, null);
                    } else {
                        Common_function::MedNotification($request->case_id, "RES_BY_ADMIN", Auth::user()->id, isset($mediator) ? $mediator->id : null, null);
                    }
                } else if (Mediation_status_log::STATUS_UNRESOLVED == $status) {
                    if (Auth::user()->role == 1) {
                        Common_function::MedNotification($request->case_id, "UNRES_BY_MED", Auth::user()->id, Auth::user()->id, null);
                    } else {
                        Common_function::MedNotification($request->case_id, "UNRES_BY_ADMIN", Auth::user()->id, isset($mediator) ? $mediator->id : null, null);
                    }
                }
            }

            $user = MedCase::find($request->case_id);
            $user->confirm_status = 2;
            $user->case_status = $status;
            $user->withdraw = ($request->withdraw_comment != null) ? $request->withdraw_comment : $request->fsData['withdraw_comment'];

            // $user->withdraw = $request->withdraw_comment;
            if ($user->save()) {

                // dd($user->bulk_flag);
                $mediation_status_log = new Mediation_status_log;
                $mediation_status_log->user_id = Auth::user()->id;
                $mediation_status_log->mediation_case_id = $request->case_id;
                $mediation_status_log->status = ($request->status != null) ? $request->status : $request->fsData['status'];


                if (Mediation_status_log::STATUS_WITHDRAWN == $status) {
                    $mediation_status_log->description = "Request Withdrawn";
                    if ($user->bulk_flag != 1) {
                        $this->sned_withdrawal($request->case_id);
                    }
                } else if (Mediation_status_log::STATUS_RESOLVED == $status) {
                    $mediation_status_log->description = "Request Resolved";
                    if ($user->bulk_flag != 1) {
                        $this->sned_resolved($request->case_id);
                    }
                } else if (Mediation_status_log::STATUS_UNRESOLVED == $status) {
                    $mediation_status_log->description = "Request Unresolved";
                    if ($user->bulk_flag != 1) {
                        $this->sned_unresolved($request->case_id);
                    }
                }
                $mediation_status_log->save();
                if (isset($_POST['log_id']) && $_POST['log_id'] != "") {
                    $success_log = BulkLog::find($_POST['log_id']);
                    // dd($success_log);

                    if ($success_log->inserted_row == null) {
                        $success_log->inserted_row = $request->case_id;
                        $success_log->save();
                    } else {
                        if (isset($_POST['insertRow'])) {

                            $insert_row = $_POST['insertRow'] . "," . $request->case_id;
                            // dd(json_encode(explode(',', $insert_row)));
                            $success_log->inserted_row = json_encode(explode(',', $insert_row));
                            $success_log->save();
                        }
                    }

                    return json_encode(['code' => 200, 'response' => 'success', 'log_id' => $_POST['log_id'], 'caseid' => $request->case_id]);
                } else if (isset($log_id)) {
                    $success_log = BulkLog::find($log_id);
                    // dd($success_log);

                    if ($success_log->inserted_row == null) {
                        $success_log->inserted_row = $request->case_id;
                        $success_log->save();
                    } else {
                        if (isset($_POST['insertRow'])) {

                            $insert_row = $_POST['insertRow'] . "," . $request->case_id;
                            // dd(json_encode(explode(',', $insert_row)));
                            $success_log->inserted_row = json_encode(explode(',', $insert_row));
                            $success_log->save();
                        }
                    }
                    return json_encode(['code' => 200, 'response' => 'success', 'log_id' => $log_id, 'caseid' => $request->case_id]);
                } else {
                    return json_encode(['code' => 200, 'response' => 'success', 'caseid' => $request->case_id]);
                }
            } else {

                // code for failed row
                if (isset($_POST['log_id']) && $_POST['log_id'] != "") {
                    $faild_log = BulkLog::find($_POST['log_id']);
                    if ($faild_log->failed_row == null) {
                        $faild_log->failed_row = $request->case_id;
                        $faild_log->save();
                    } else {
                        if (isset($_POST['faildRow'])) {

                            $faild_row = $_POST['faildRow'] . "," . $request->case_id;
                            // $faild_log->failed_row = $faild_log->failed_row + "," + $request->id;
                            $faild_log->failed_row = json_encode(explode(',', $faild_row));
                            $faild_log->save();
                        }
                    }
                    return json_encode(['code' => 200, 'response' => 'error', 'log_id' => $_POST['log_id'], 'caseid' => $request->case_id]);
                } else if (isset($log_id)) {
                    $faild_log = BulkLog::find($log_id);
                    if ($faild_log->failed_row == null) {
                        $faild_log->failed_row = $request->case_id;
                        $faild_log->save();
                    } else {
                        if (isset($_POST['faildRow'])) {

                            $faild_row = $_POST['faildRow'] . "," . $request->case_id;
                            // $faild_log->failed_row = $faild_log->failed_row + "," + $request->id;
                            $faild_log->failed_row = json_encode(explode(',', $faild_row));
                            $faild_log->save();
                        }
                    }
                    return json_encode(['code' => 200, 'response' => 'error', 'log_id' => $log_id, 'caseid' => $request->case_id]);
                } else {
                    return json_encode(['code' => 200, 'response' => 'error', 'caseid' => $request->case_id]);
                }
            }
        } else {
            return json_encode(['code' => 422, 'response' => 'error', 'msg' => "Please Fill the Required Field"]);
        }
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
        $validatedData = $request->validate([
            'comment' => 'required',
            // 'docs_party_ids' => 'required',
        ]);
        // if ($request->comment != null) {

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
        if (Auth::user()->role == 1) {
            Common_function::MedNotification($request->case_id, $event, Auth::user()->id, Auth::user()->id, $inv_id);
        } else {
            Common_function::MedNotification($request->case_id, $event, Auth::user()->id, isset($mediator) ? $mediator->id : null, $inv_id);
        }
        return response()->json(["code" => 200, "response" => "success", "msg" => "Comment Added"]);
        // }
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
        if (isset($_POST['log_id']) && isset($_POST['allcids'])) {
            if ($_POST['log_id'] == "" && $_POST['allcids'] != "") {
                $params['allcids'] = json_encode(explode(',', $_POST['allcids']));
                $log = BulkLog::create([
                    "selected_ids" => $params['allcids'],
                    "uploaded_by" => Auth::user()->id,
                    "total_row" => isset($_POST['total_row']) ? $_POST['total_row'] : "",
                    "log_type" => isset($_POST['log_type']) ? $_POST['log_type'] : "",
                    "updated_at" => date('Y-m-d H:i:s'),
                ]);
                $log_id = $log->id;
                Common_function::MedNotification($_POST['allcids'], "REJECTED_ADM", Auth::user()->id, null, null);
            }
        } else {
            Common_function::MedNotification($request->id, "REJECTED_ADM", Auth::user()->id, isset($mediator) ? $mediator->id : null, $inv_id);
        }
        $user = MedCase::find($request->id);
        $user->confirm_status = 3;
        $user->case_status = 3;
        if ($user->save()) {
            $mediation_status_log = new Mediation_status_log;
            $mediation_status_log->user_id = Auth::user()->id;
            $mediation_status_log->mediation_case_id = $request->id;
            $mediation_status_log->status = 3;
            $mediation_status_log->description = "Request Reject";
            $mediation_status_log->save();

            $this->sned_reject($request->id);
            if (isset($_POST['log_id']) && $_POST['log_id'] != "") {
                $success_log = BulkLog::find($_POST['log_id']);
                // dd($success_log);

                if ($success_log->inserted_row == null) {
                    $success_log->inserted_row = $request->id;
                    $success_log->save();
                } else {
                    if (isset($_POST['insertRow'])) {

                        $insert_row = $_POST['insertRow'] . "," . $request->id;
                        // dd(json_encode(explode(',', $insert_row)));
                        $success_log->inserted_row = json_encode(explode(',', $insert_row));
                        $success_log->save();
                    }
                }

                return json_encode(['code' => 200, 'response' => 'success', 'log_id' => $_POST['log_id'], 'caseid' => $request->id]);
            } else if (isset($log_id)) {
                $success_log = BulkLog::find($log_id);
                // dd($success_log);

                if ($success_log->inserted_row == null) {
                    $success_log->inserted_row = $request->id;
                    $success_log->save();
                } else {
                    if (isset($_POST['insertRow'])) {

                        $insert_row = $_POST['insertRow'] . "," . $request->id;
                        // dd(json_encode(explode(',', $insert_row)));
                        $success_log->inserted_row = json_encode(explode(',', $insert_row));
                        $success_log->save();
                    }
                }
                return json_encode(['code' => 200, 'response' => 'success', 'log_id' => $log_id, 'caseid' => $request->id]);
            } else {
                return json_encode(['code' => 200, 'response' => 'success', 'caseid' => $request->id]);
            }
        } else {
            if (isset($_POST['log_id']) && $_POST['log_id'] != "") {
                $faild_log = BulkLog::find($_POST['log_id']);
                if ($faild_log->failed_row == null) {
                    $faild_log->failed_row = $request->id;
                    $faild_log->save();
                } else {
                    if (isset($_POST['faildRow'])) {

                        $faild_row = $_POST['faildRow'] . "," . $request->id;
                        // $faild_log->failed_row = $faild_log->failed_row + "," + $request->id;
                        $faild_log->failed_row = json_encode(explode(',', $faild_row));
                        $faild_log->save();
                    }
                }
                return json_encode(['code' => 200, 'response' => 'error', 'log_id' => $_POST['log_id'], 'caseid' => $request->id]);
            } else if (isset($log_id)) {
                $faild_log = BulkLog::find($log_id);
                if ($faild_log->failed_row == null) {
                    $faild_log->failed_row = $request->id;
                    $faild_log->save();
                } else {
                    if (isset($_POST['faildRow'])) {

                        $faild_row = $_POST['faildRow'] . "," . $request->id;
                        // $faild_log->failed_row = $faild_log->failed_row + "," + $request->id;
                        $faild_log->failed_row = json_encode(explode(',', $faild_row));
                        $faild_log->save();
                    }
                }
                return json_encode(['code' => 200, 'response' => 'error', 'log_id' => $log_id, 'caseid' => $request->id]);
            } else {
                return json_encode(['code' => 200, 'response' => 'error', 'caseid' => $request->id]);
            }
        }
    }

    public function storeMultiFile(Request $request)
    {
        

        $validatedData = $request->validate([
            'files.*' => 'required',
            'files.*' => 'mimes:csv,txt,xlx,xls,pdf,rar,zip',
            // 'docs_party_ids' => 'required',
        ]);
        $inv_id = "";
        if ($request->has('docs_party_ids')) {
            $inv_id = $request->docs_party_ids;
        } else {
            $inv = InvoledUser::select('id')->where('userPlanId', $request->caseId)->get();
            foreach ($inv as $v) {
                if ($inv_id == "") {
                    $inv_id = $v->id;
                } else {
                    $inv_id = $inv_id . "," . $v->id;
                }
            }
        }
        $mediatorNoti = Mediators_mediation_cases_status::select("email", "username", "mobile_number", "users.id")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->where("mediators_mediation_cases_status.mediation_case_id", "=", $request->caseId)
            ->where("mediators_mediation_cases_status.status", "=", 1)
            ->first();
        if (isset($request->log_id) && isset($request->allcids)) {
            if ($request->log_id == "null" && $request->allcids != "") {
                $params['allcids'] = json_encode(explode(',', $_POST['allcids']));
                $log = BulkLog::create([
                    "selected_ids" => $params['allcids'],
                    "uploaded_by" => Auth::user()->id,
                    "total_row" => isset($_POST['total_row']) ? $_POST['total_row'] : "",
                    "log_type" => isset($_POST['log_type']) ? $_POST['log_type'] : "",
                    "updated_at" => date('Y-m-d H:i:s'),
                ]);
                $log_id = $log->id;
                if ($request->shareMediator == 1) {
                    Common_function::MedNotification($_POST['allcids'], "SEND_ADDI_DOC_ADMIN", Auth::user()->id, null, null);
                } else {
                    Common_function::MedNotification($_POST['allcids'], "SEND_ADDI_DOC_ADMIN", Auth::user()->id, null, null);
                }
            }
        } else {
            if ($request->shareMediator == 1) {
                Common_function::MedNotification($request->caseId, "SEND_ADDI_DOC_ADMIN", Auth::user()->id, isset($mediatorNoti) ? $mediatorNoti->id : null, $inv_id);
            } else {
                Common_function::MedNotification($request->caseId, "SEND_ADDI_DOC_ADMIN", Auth::user()->id, null, $inv_id);
            }
        }

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
                    // $path = $file->storeAs('/supporting/' . $request->caseId, pathinfo(str_replace(" ", "_", $file->getClientOriginalName()), PATHINFO_FILENAME) . "_date_" . date("Y_m_d_H_i_s_a") . "." . $file->extension());
                    $insert[$x]['file_name'] = $filename;
                    $insert[$x]['access'] = $inv_id;
                    $insert[$x]['mediator_access'] = isset($request->shareMediator) ? $request->shareMediator : 1;
                    $insert[$x]['uploaded_by'] = Auth::user()->id;
                    $insert[$x]['case_id'] = $request->caseId;
                    // $insert[$x]['path'] = $path;
                }
            }

            $insert_manage = DB::table('manage_files')->insert($insert, $insert);

            if ($insert_manage) {
                $this->send_upload_file_party($request->caseId, $insert);

                // dd($request->log_id);
                if (isset($_POST['log_id']) && $_POST['log_id'] != "null") {


                    $success_log = BulkLog::find($request->log_id);


                    if ($success_log->inserted_row == null) {
                        $success_log->inserted_row = $request->caseId;
                        $success_log->save();
                    } else {
                        if (isset($_POST['insertRow'])) {

                            $insert_row = $_POST['insertRow'] . "," . $request->caseId;
                            // dd(json_encode(explode(',', $insert_row)));
                            $success_log->inserted_row = json_encode(explode(',', $insert_row));
                            $success_log->save();
                        }
                    }

                    return json_encode(['code' => 200, 'response' => 'success', 'log_id' => $_POST['log_id'], 'caseid' => $request->caseId]);
                } else if (isset($log_id)) {
                    $success_log = BulkLog::find($log_id);
                    // dd($success_log);

                    if ($success_log->inserted_row == null) {
                        $success_log->inserted_row = $request->caseId;
                        $success_log->save();
                    } else {
                        if (isset($_POST['insertRow'])) {

                            $insert_row = $_POST['insertRow'] . "," . $request->caseId;
                            // dd(json_encode(explode(',', $insert_row)));
                            $success_log->inserted_row = json_encode(explode(',', $insert_row));
                            $success_log->save();
                        }
                    }
                    return json_encode(['code' => 200, 'response' => 'success', 'log_id' => $log_id, 'caseid' => $request->caseId]);
                } else {
                    return json_encode(['code' => 200, 'response' => 'success', 'caseid' => $request->caseId]);
                }
            } else {
                if (isset($_POST['log_id']) && $_POST['log_id'] != "null") {
                    $faild_log = BulkLog::find($_POST['log_id']);
                    if ($faild_log->failed_row == null) {
                        $faild_log->failed_row = $request->caseId;
                        $faild_log->save();
                    } else {
                        if (isset($_POST['faildRow'])) {

                            $faild_row = $_POST['faildRow'] . "," . $request->caseId;
                            // $faild_log->failed_row = $faild_log->failed_row + "," + $request->id;
                            $faild_log->failed_row = json_encode(explode(',', $faild_row));
                            $faild_log->save();
                        }
                    }
                    return json_encode(['code' => 200, 'response' => 'error', 'log_id' => $_POST['log_id'], 'caseid' => $request->caseId]);
                } else if (isset($log_id)) {
                    $faild_log = BulkLog::find($log_id);
                    if ($faild_log->failed_row == null) {
                        $faild_log->failed_row = $request->caseId;
                        $faild_log->save();
                    } else {
                        if (isset($_POST['faildRow'])) {

                            $faild_row = $_POST['faildRow'] . "," . $request->caseId;
                            // $faild_log->failed_row = $faild_log->failed_row + "," + $request->id;
                            $faild_log->failed_row = json_encode(explode(',', $faild_row));
                            $faild_log->save();
                        }
                    }
                    return json_encode(['code' => 200, 'response' => 'error', 'log_id' => $log_id, 'caseid' => $request->caseId]);
                } else {
                    return json_encode(['code' => 200, 'response' => 'error', 'caseid' => $request->caseId]);
                }
            }
        } else {
            if (isset($_POST['log_id']) && $_POST['log_id'] != "null") {
                $faild_log = BulkLog::find($_POST['log_id']);
                if ($faild_log->failed_row == null) {
                    $faild_log->failed_row = $request->caseId;
                    $faild_log->save();
                } else {
                    if (isset($_POST['faildRow'])) {

                        $faild_row = $_POST['faildRow'] . "," . $request->caseId;
                        // $faild_log->failed_row = $faild_log->failed_row + "," + $request->id;
                        $faild_log->failed_row = json_encode(explode(',', $faild_row));
                        $faild_log->save();
                    }
                }
                return json_encode(['code' => 200, 'response' => 'error', 'log_id' => $_POST['log_id'], 'caseid' => $request->caseId]);
            } else if (isset($log_id)) {
                $faild_log = BulkLog::find($log_id);
                if ($faild_log->failed_row == null) {
                    $faild_log->failed_row = $request->caseId;
                    $faild_log->save();
                } else {
                    if (isset($_POST['faildRow'])) {

                        $faild_row = $_POST['faildRow'] . "," . $request->caseId;
                        // $faild_log->failed_row = $faild_log->failed_row + "," + $request->id;
                        $faild_log->failed_row = json_encode(explode(',', $faild_row));
                        $faild_log->save();
                    }
                }
                return json_encode(['code' => 200, 'response' => 'error', 'log_id' => $log_id, 'caseid' => $request->caseId]);
            } else {
                return json_encode(['code' => 200, 'response' => 'error', 'caseid' => $request->caseId]);
            }
        }
    }

    public function midaterAdd(Request $request)
    {
       
        if ($request->midater != null) {
            $inv = InvoledUser::select('user_involved_in_agreement.*', 'users.address as useraddress', 'users.address1 as useraddress1', 'users.pincode as userpincode', 'users.city as usercity', 'users.state as userstate', 'users.country as usercountry')
                ->leftJoin("users", "users.id", "=", "user_involved_in_agreement.userId")
                ->where(['user_involved_in_agreement.userPlanid' => $request->id])->get();

            if ($inv[0]->address1 != null || $inv[0]->useraddress != null) {

                $medcase = MedCase::find($request->id);

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

                if ($medcase->bulk_flag == 0) {
                    $this->send_mediatorAdd($request->id, $request->midater);
                }
                return response()->json(["code" => 200, "response" => "success", "msg" => "midater Added"]);
            } else {
                return response()->json(["code" => 200, "response" => "error", "msg" => "Address not-found of Initiating Party"]);
            }
        } else {
            return response()->json(["code" => 200, "response" => "error", "msg" => "Please Select Mediator"]);
        }
    }

    public function mediator_appointment($id, $medid)
    {
        $data["mediator"] = User::find($medid);
        $data["case"] = MedCase::where("id", "=", $id)->first();
        // $data["party"] = InvoledUser::where("userPlanId", "=", $id)->get();
        $data["party"] = InvoledUser::select('user_involved_in_agreement.*', 'users.address as useraddress', 'users.address1 as useraddress1', 'users.pincode as userpincode', 'users.city as usercity', 'users.state as userstate', 'users.country as usercountry')
            ->leftJoin("users", "users.id", "=", "user_involved_in_agreement.userId")
            ->where("user_involved_in_agreement.userPlanId", "=", $id)->get();
        $pdf = PDF::loadView('pdf.mediator_appointment_letter', $data);
        $name = 'mediator_appoinment_letter_M' . sprintf('%06d', $data["case"]->id) . time() . '.pdf';
        // Storage::put('public/mediation/' . $data["case"]->id . '/' . $name, $pdf->output());
        $savePath = 'mediation_documents/mediation/' . $data["case"]->id;
        $finalFilePath = $savePath . '/' . $name;
        // Storage::put('public/mediation/' . $data["case"]->id . '/' . $name, $pdf->output());
        $uploadS3 = $this->uploadOnAWSDirect($finalFilePath, $savePath, $pdf);
        return $name;
    }



    /******************* Add Session Code : START  ****************************************/
    public function addSession(Request $request)
    {
        //dd($request->all());
        
        /*************************Zoom API : START *******************************/
        if($request->zoom_choice == "directly_zoom" || $request->fsData['zoom_choice'] == "directly_zoom") {
       // $time_zoom = date("H:i:s", strtotime($request->sessionTime)); // old code
        $time_zoom = ($sess_time = strtotime($request->fsData['sessionTime'])) ? date("H:i:s", $sess_time) : date("H:i:s", strtotime($request->sessionTime));
        //$end_time = date("H:i:s", strtotime($request->sessionTime) + 60*60); // old code
        $end_time = ($sess_time = strtotime($request->fsData['sessionTime'])) ? date("H:i:s", $sess_time + 60*60) : date("H:i:s", strtotime($request->sessionTime) + 60*60);
        //$date1 = str_replace('/', '-', $request->sessionDate);   // old code
        $date1 = ($request->fsData['sessionDate']) ? str_replace('/', '-', $request->fsData['sessionDate']) : str_replace('/', '-', $request->sessionDate);  
        $date = date('Y-m-d', strtotime($date1));
        $total = $date.' '.$time_zoom;
        $end_total = $date.' '.$end_time;
        //$date_format_api =  date("Y-m-d\TH:i:s\Z", strtotime($total)); // old code
        $date_format_api =  date("Y-m-d\TH:i:s", strtotime($total));
        $end_date_format_api =  date("Y-m-d\TH:i:s", strtotime($end_total));

        $note = ($request->fsData['note']) ? $request->fsData['note'] : $request->note;
        
        $create_zoom_meeting_response = Zoom::createZoomMeeting($request->caseId, $note, $date_format_api, $end_date_format_api);
        $create_zoom_meeting = json_decode($create_zoom_meeting_response, true);
        // Get zoom api invitation : START //
         $zoom_invitation_response = Zoom::zoomInvitation($create_zoom_meeting['id']);
         $zoom_invitation = json_decode($zoom_invitation_response, true);
        
        /****************************************Zoom API : END **************************/

        /**** Get Zoom URL from invitation ********/
        $zoom_string = $zoom_invitation['invitation'];
        preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $zoom_string, $zoom_match);
        /**** Get Zoom URL from invitation ********/
        
        $created_zoom_link = $zoom_match[0][0];

        $created_zoom_id = $create_zoom_meeting['id'];

        $inserted_zoom_choice = "direct";
        } else {
            $created_zoom_link = ""; 
            $created_zoom_id = ($request->fsData['zoomId']) ? $request->fsData['zoomId']  : $request->zoomId;
            $inserted_zoom_choice = "manual";
        }

        $time = date("g:i A", strtotime($request->sessionTime));
        $d = [
            'event' => 'SESS_SCHE',
            'case_id' => $request->caseId,
        ];
        $medcase = MedCase::find($request->caseId);
        if (isset($request->session_party_ids)) {
            $dataToInsert = [
                'case_id' => $request->caseId,
                'session_date' => $request->sessionDate . "/" . $time,
                'note' => $request->note,
                'zoom_id' => $created_zoom_id,
                'zoom_link' => $created_zoom_link,
                'zoom_link_choice' => $inserted_zoom_choice,
                'session_party_ids' => json_encode($request->session_party_ids),
                'scheduled_by' => Auth::user()->id
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


                    if($request->zoom_choice == "manually_zoom" || $request->fsData['zoom_choice'] == "manually_zoom") {
                        $is_send = $this->sned_session($request->zoomId, $request->caseId, $party->userEmail, $party->name, $request->sessionDate . "/" . $time, $party->userPhone);
                    } else if($request->zoom_choice == "directly_zoom" || $request->fsData['zoom_choice'] == "directly_zoom") {
                    /**** Zoom Invitation ************/
                        $is_send = $this->sned_session_invitation($create_zoom_meeting['id'], $request->caseId, $party->userEmail, $party->name, $request->sessionDate . "/" . $time_zoom, $party->userPhone, $created_zoom_link);
                    /**** Zoom Invitation ************/
                    }
                }
                $mediatorNoti = Mediators_mediation_cases_status::select("email", "username", "mobile_number", "users.id")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                    ->where("mediators_mediation_cases_status.mediation_case_id", "=", $request->caseId)
                    ->where("mediators_mediation_cases_status.status", "=", 1)
                    ->first();
                Common_function::MedNotification($request->caseId, "SESS_SCHE_ADMIN", Auth::user()->id, isset($mediatorNoti) ? $mediatorNoti->id : null, $inv_id);

                // if ($medcase->bulk_flag == 0) {
                if ($mediatorNoti) {
                    $id = "M" . sprintf("%06d", $request->caseId);
                    SendGrid::send($d, $mediatorNoti->email, env('L10_SCHEDULING_OF_SESSION', ''), ["-caseid-" => $id, "-insert_date-" => $request->sessionDate . "/" . $time, "-type-" => "Mediator"], $mediatorNoti->username);

                    $varjson = ['sessionDateTime' => $request->sessionDate . "/" . $time, 'caseid' => $id, 'zoomid' => $request->zoomId];
                    $var = ['-dt-', '-cid-', '-link-'];
                    $var1 = [$request->sessionDate . "/" . $time, $id, $request->zoomId];
                    $content1 = WaTemplate::getcontent('l10_session_schedule');
                    $content = str_replace($var, $var1, $content1);
                    $dwa1 = [
                        'caseid' => $request->caseId,
                        'contact' =>  $mediatorNoti->mobile_number,
                        'content' => ['text' => $content],
                        'event' => 'SESS_SCHE',
                        'varjson' => $varjson,
                        'haptik_tmp' => 'l10_session_schedule',

                    ];

                    $access = Whatsapp::sendWamessage($dwa1);
                }
                // }
                if($is_send){
                return json_encode(['code' => 200, 'response' => 'success']);
                }
            } else {
                return json_encode(['code' => 200, 'response' => 'error']);
            }
        } else {
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
            if (isset($_POST['log_id']) && isset($_POST['allcids'])) {
                if ($_POST['log_id'] == "" && $_POST['allcids'] != "") {
                    $params['allcids'] = json_encode(explode(',', $_POST['allcids']));
                    $log = BulkLog::create([
                        "selected_ids" => $params['allcids'],
                        "uploaded_by" => Auth::user()->id,
                        "total_row" => isset($_POST['total_row']) ? $_POST['total_row'] : "",
                        "log_type" => isset($_POST['log_type']) ? $_POST['log_type'] : "",
                        "updated_at" => date('Y-m-d H:i:s'),
                    ]);
                    $log_id = $log->id;

                    Common_function::MedNotification($_POST['allcids'], "SESS_SCHE_ADMIN", Auth::user()->id, isset($mediatorNoti) ? $mediatorNoti->id : null, $inv_id);
                }
            }

            $allParty = InvoledUser::where("userPlanId", $request->caseId)->get();
            $party_ids = array();
            foreach ($allParty as $party) {
                $party_ids[] = $party->id;
            }
            
            $dataToInsert = [
                'case_id' => $request->caseId,
                'session_date' => ($request->sessionDate != null) ? $request->sessionDate : $request->fsData['sessionDate'] . "/" . $time,
                'note' => ($request->note != null) ? $request->note : $request->fsData['note'],
                'zoom_id' => $created_zoom_id,
                'zoom_link' => $created_zoom_link,
                'zoom_link_choice' => $inserted_zoom_choice,
                'session_party_ids' => json_encode($party_ids),
                'scheduled_by' => Auth::user()->id,
            ];
            $manage_session = DB::table('manage_session')->insert($dataToInsert);
            if ($manage_session) {
                $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                    ->where("mediators_mediation_cases_status.mediation_case_id", "=", $request->caseId)
                    ->where("mediators_mediation_cases_status.status", "=", 1)
                    ->first();
                if ($mediator) {
                    $id = "M" . sprintf("%06d", $request->caseId);
                    SendGrid::send($d, $mediator->email, env('L10_SCHEDULING_OF_SESSION', ''), ["-caseid-" => $id, "-insert_date-" => $request->sessionDate . "/" . $time, "-type-" => "Mediator"], $mediator->username);

                    $varjson = ['sessionDateTime' => $request->sessionDate . "/" . $time, 'caseid' => $id, 'zoomid' => $request->zoomId];
                    $var = ['-dt-', '-cid-', '-link-'];
                    $var1 = [$request->sessionDate . "/" . $time, $id, $request->zoomId];
                    $content1 = WaTemplate::getcontent('l10_session_schedule');
                    $content = str_replace($var, $var1, $content1);
                    $dwa1 = [
                        'caseid' => $request->caseId,
                        'contact' =>  $mediator->mobile_number,
                        'content' => ['text' => $content],
                        'event' => 'SESS_SCHE',
                        'varjson' => $varjson,
                        'haptik_tmp' => 'l10_session_schedule',

                    ];

                    $access = Whatsapp::sendWamessage($dwa1);
                }
                foreach ($allParty as $party) {
                    // $this->sned_session($request->zoomId, $request->caseId, $party->userEmail, $party->name, $request->sessionDate . "/" . $time, $party->userPhone);
                    
                    
                    if($request->fsData['zoom_choice'] == "manually_zoom") {
                        $is_send = $this->sned_session(($request->zoomId != null) ? $request->zoomId  : $request->fsData['zoomId'], $request->caseId, $party->userEmail, $party->name, ($request->sessionDate != null) ? $request->sessionDate : $request->fsData['sessionDate'] . "/" . $time, $party->userPhone);
                    } else if($request->fsData['zoom_choice'] == "directly_zoom") {
                     /**** Zoom Invitation ************/

                     $is_send = $this->sned_session_invitation($create_zoom_meeting['id'], $request->caseId, $party->userEmail, $party->name, $request->sessionDate . "/" . $time_zoom, $party->userPhone, $created_zoom_link);
                     /**** Zoom Invitation ************/
                    }
                }
                if (isset($_POST['log_id']) && $_POST['log_id'] != "") {
                    $success_log = BulkLog::find($_POST['log_id']);
                    // dd($success_log);

                    if ($success_log->inserted_row == null) {
                        $success_log->inserted_row = $request->caseId;
                        $success_log->save();
                    } else {
                        if (isset($_POST['insertRow'])) {

                            $insert_row = $_POST['insertRow'] . "," . $request->caseId;
                            // dd(json_encode(explode(',', $insert_row)));
                            $success_log->inserted_row = json_encode(explode(',', $insert_row));
                            $success_log->save();
                        }
                    }
                    return json_encode(['code' => 200, 'response' => 'success', 'log_id' => $_POST['log_id'], 'caseid' => $request->caseId]);
                } else if (isset($log_id)) {
                    $success_log = BulkLog::find($log_id);
                    // dd($success_log);

                    if ($success_log->inserted_row == null) {
                        $success_log->inserted_row = $request->caseId;
                        $success_log->save();
                    } else {
                        if (isset($_POST['insertRow'])) {

                            $insert_row = $_POST['insertRow'] . "," . $request->caseId;
                            // dd(json_encode(explode(',', $insert_row)));
                            $success_log->inserted_row = json_encode(explode(',', $insert_row));
                            $success_log->save();
                        }
                    }
                    return json_encode(['code' => 200, 'response' => 'success', 'log_id' => $log_id, 'caseid' => $request->caseId]);
                } else {
                    return json_encode(['code' => 200, 'response' => 'success', 'caseid' => $request->caseId]);
                }
            } else {
                if (isset($_POST['log_id']) && $_POST['log_id'] != "") {
                    $faild_log = BulkLog::find($_POST['log_id']);
                    if ($faild_log->failed_row == null) {
                        $faild_log->failed_row = $request->caseId;
                        $faild_log->save();
                    } else {
                        if (isset($_POST['faildRow'])) {

                            $faild_row = $_POST['faildRow'] . "," . $request->id;
                            // $faild_log->failed_row = $faild_log->failed_row + "," + $request->id;
                            $faild_log->failed_row = json_encode(explode(',', $faild_row));
                            $faild_log->save();
                        }
                    }
                    return json_encode(['code' => 200, 'response' => 'error', 'log_id' => $_POST['log_id'], 'caseid' => $request->caseId]);
                } else if (isset($log_id)) {
                    $faild_log = BulkLog::find($log_id);
                    if ($faild_log->failed_row == null) {
                        $faild_log->failed_row = $request->caseId;
                        $faild_log->save();
                    } else {
                        if (isset($_POST['faildRow'])) {

                            $faild_row = $_POST['faildRow'] . "," . $request->caseId;
                            // $faild_log->failed_row = $faild_log->failed_row + "," + $request->id;
                            $faild_log->failed_row = json_encode(explode(',', $faild_row));
                            $faild_log->save();
                        }
                    }
                    return json_encode(['code' => 200, 'response' => 'error', 'log_id' => $log_id, 'caseid' => $request->caseId]);
                } else {
                    return json_encode(['code' => 200, 'response' => 'error', 'caseid' => $request->caseId]);
                }
            }
        }
        return true;
    }
    /******************* Add Session Code : END  ****************************************/

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
            echo "<td>" . $value->zoom_link . "</td>";
            echo "<td>" . $value->note . "</td>";
            echo "<td>" . implode("<br>", $user) . "</td>";
            if ($value->is_deleted == 0) {
                if (Auth::user()->role == 2) {
                    echo "<td>
            <button id='UpdateSession' data-id='" . $value->id . "' data-toggle='modal' data-target='#Session-edit' class='btn btn-sm btn-success px-2'><i class='far fa-edit'></i></button>
            <button id='DeleteSession' data-id='" . $value->id . "' data-toggle='modal' data-zoom-choice='".$value->zoom_link_choice."' data-target='#Session-delete' class='btn btn-sm btn-danger mt-1 px-2'><i class='far fa-trash-alt' style='padding: 0px 2px'></i></button>
            </td>";
                } else if (Auth::user()->role == 1) {
                    if (Auth::user()->id == $value->scheduled_by) {

                        echo "<td>
                    <button id='UpdateSession' data-id='" . $value->id . "' data-toggle='modal' data-target='#Session-edit-mediator' class='btn btn-sm btn-success px-2'><i class='far fa-edit'></i></button>
                    <button id='DeleteSession' data-id='" . $value->id . "' data-toggle='modal' data-zoom-choice='".$value->zoom_link_choice."' data-target='#Session-delete-meditor' class='btn btn-sm btn-danger mt-1 px-2'><i class='far fa-trash-alt' style='padding: 0px 2px'></i></button>
                    </td>";
                    } else {
                        echo "<td>--</td>";
                    }
                }
            } else {
                echo "<td><p style='color:red;'>Deleted</p><button id='viewreason' data-reason='" . $value->delete_reason . "' data-id='" . $value->id . "' data-toggle='modal' data-target='#Session-delete-reason' class='btn btn-sm btn-primary px-2'><i class='fas fa-comment-alt'></i></button>
                </td>";
            }
            echo "</tr>";

            $sn++;
        }
        // return $sessionData;
    }

    public function json($role = 0, $bulk)
    {
        $draw = $_POST['sEcho'];
        $row = $_POST['iDisplayStart'];
        $rowperpage = $_POST['iDisplayLength']; // Rows display per page
        $indexColumn = $_POST['iSortCol_0'];
        $columnName = $_POST['mDataProp_' . $indexColumn]; // Column name
        $columnSortOrder = $_POST['sSortDir_0']; // asc or desc
        $batch_id = "";
        if (isset($_POST['batch_id'])) {
            $batch_id = $_POST['batch_id'];
        }
        $searchValue = $_POST['sSearch'];

        $casescount = MedCase::getCaseCount($searchValue, $role, $batch_id, $bulk);
        $cases = MedCase::getCase($searchValue, $columnName, $columnSortOrder, $draw, $row, $rowperpage, $role, $batch_id, $bulk);


        $arraydata = array();
        foreach ($cases as $key => $d) {
            $actionDate = date('d-m-Y', strtotime($d->update));
            $createDate = date('d-m-Y', strtotime($d->create));
            $admin_approve = date('d-m-Y', strtotime($d->admin_approve));
            $arraydata[] = [
                "key" => $key + 1,
                "date" => date('d-m-Y', strtotime($d->created_at)),
                "mediator_action_date" => $actionDate,
                "mediator_create_action_date" => $createDate,
                "admin_approve" => $admin_approve,
                "case" => $d,
                "party" => InvoledUser::select('user_involved_in_agreement.id', 'user_involved_in_agreement.name', 'user_involved_in_agreement.isOnboarded', 'user_involved_in_agreement.isClaimant', "user_involved_in_agreement.userId", "users.organization")->leftjoin('users', 'users.id', '=', 'user_involved_in_agreement.userId')->where(['userPlanid' => $d->id])->get(),
                "status_log" => Mediation_status_log::select("status", "description", DB::raw("DATE_FORMAT(created_at,'%d-%c-%y %h:%i %p') as created"))->where(['mediation_case_id' => $d->id])->orderByDesc('id')->limit(1)->get(),
                "private_count" => Mediation_case_comment::where("type", "=", 1)->where('mediation_case_id', $d->id)->count(),
                "private_view_count" => Mediation_case_comment::where("type", "=", 1)->where('mediation_case_id', $d->id)->where('view', 0)->count(),
                "share_count" => Mediation_case_comment::where("type", "=", 0)->where('mediation_case_id', $d->id)->count(),
                "share_view_count" => Mediation_case_comment::where("type", "=", 0)->where('mediation_case_id', $d->id)->where('view', 0)->count(),
            ];
        }
        return response()->json(["sEcho" => intval($draw), "iTotalRecords" => $casescount, "iTotalDisplayRecords" => $casescount, "aaData" => $arraydata]);

        // return response()->json(["data" => $arraydata]);
    }

    /********** Delete Session : START  *****************************************************/
    public function deleteSession(Request $request)
    {
        $id = $request->SessId;

        $deleted = ManageSession::find($id);
        $deleted->is_deleted = 1;
        $deleted->delete_reason = $request->reason;

        /**** Zoom Delete *******/
        if($request->delZoomChoice == "direct"){
            $delete_zoom_meeting_response = Zoom::deleteZoomMeeting($deleted->zoom_id);
            $delete_zoom_meeting = json_decode($delete_zoom_meeting_response, true);
        }
        /**** Zoom Delete *******/
       
        if ($deleted->save()) {
            $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number", "users.id")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                ->where("mediators_mediation_cases_status.mediation_case_id", "=", $deleted->case_id)
                ->where("mediators_mediation_cases_status.status", "=", 1)
                ->first();
            $caseid = "M" . sprintf("%06d", $deleted->case_id);
            $d1 = [
                'event' => 'SESS_CEN_PARTY',
                'case_id' => $deleted->case_id,
            ];
            if (!is_null($deleted->session_party_ids)) {
                $dataArray = json_decode($deleted->session_party_ids);
            }
            // $userPhone = array();

            if (isset($dataArray)) {
                foreach ($dataArray as $d) {
                    $dd = InvoledUser::where('id', $d)->where('userPlanId', $deleted->case_id)->first();
                    if (isset($dd)) {
                        if ($dd->userEmail != null) {
                            SendGrid::send($d1, $dd->userEmail, env('L24_CANCELLING_OF_SESSION', ''), ["-cid-" => $caseid, "-date-" => $deleted->session_date, "-type-" => "Party"], $dd->name);
                        }
                        if ($dd->userPhone != null) {

                            $varjson = ['party' => 'Party', 'deleteDate' => $deleted->session_date, "caseid" => $caseid];
                            $var = ['-party-', '-date-', '-caseid-'];
                            $var1 = ["Party", $deleted->session_date, $caseid];
                            $content1 = WaTemplate::getcontent('L24_cancel_mediation_session');
                            $content = str_replace($var, $var1, $content1);
                            $dwa1 = [
                                'caseid' => $deleted->case_id,
                                'contact' =>  $dd->userPhone,
                                'content' => ['text' => $content],
                                'event' => 'SESS_CEN',
                                'varjson' => $varjson,
                                'haptik_tmp' => 'mediation_cancle_session',

                            ];

                            $access = Whatsapp::sendWamessage($dwa1);
                        }
                    } else {
                        $dd = InvoledUser::where('userId', $d)->where('userPlanId', $deleted->case_id)->first();
                        if (isset($dd)) {
                            if ($dd->userEmail != null) {
                                SendGrid::send($d1, $dd->userEmail, env('L24_CANCELLING_OF_SESSION', ''), ["-cid-" => $caseid, "-date-" => $deleted->session_date, "-type-" => "Party"], $dd->name);
                            }
                            if ($dd->userPhone != null) {

                                $varjson = ['party' => 'Party', 'deleteDate' => $deleted->session_date, "caseid" => $caseid];
                                $var = ['-party-', '-date-', '-caseid-'];
                                $var1 = ["Party", $deleted->session_date, $caseid];
                                $content1 = WaTemplate::getcontent('L24_cancel_mediation_session');
                                $content = str_replace($var, $var1, $content1);
                                $dwa1 = [
                                    'caseid' => $deleted->case_id,
                                    'contact' =>  $dd->userPhone,
                                    'content' => ['text' => $content],
                                    'event' => 'SESS_CEN',
                                    'varjson' => $varjson,
                                    'haptik_tmp' => 'mediation_cancle_session',

                                ];

                                $access = Whatsapp::sendWamessage($dwa1);
                            }
                        }
                    }
                }
            }
            $d2 = [
                'event' => 'SESS_CEN',
                'case_id' => $deleted->case_id,
            ];
            if (isset($mediator)) {
                if ($mediator->email != "") {
                    SendGrid::send($d2, $mediator->email, env('L24_CANCELLING_OF_SESSION', ''), ["-cid-" => $caseid, "-date-" => $deleted->session_date, "-type-" => "Mediator"], $mediator->username);
                }
                if ($mediator->mobile_number != "") {

                    $varjson = ['party' => 'Mediator', 'deleteDate' => $deleted->session_date, "caseid" => $caseid];
                    $var = ['-party-', '-date-', '-caseid-'];
                    $var1 = ["Mediator", $deleted->session_date, $caseid];
                    $content1 = WaTemplate::getcontent('L24_cancel_mediation_session');
                    $content = str_replace($var, $var1, $content1);
                    $dwa1 = [
                        'caseid' => $deleted->case_id,
                        'contact' =>  $mediator->mobile_number,
                        'content' => ['text' => $content],
                        'event' => 'SESS_CEN',
                        'varjson' => $varjson,
                        'haptik_tmp' => 'mediation_cancle_session',

                    ];

                    $access = Whatsapp::sendWamessage($dwa1);
                }
            }

            // SendGrid::send($d, $mediator->email, env('L10_SCHEDULING_OF_SESSION', ''), ["-caseid-" => $id, "-insert_date-" => $request->sessionDate . "/" . $time, "-type-" => "Mediator"], $mediator->username);

            return json_encode(["message" => "success"]);
        } else {
            return json_encode(["message" => "error"]);
        };
    }
    /********** Delete Session : END  *****************************************************/

    public function invitation_mediate($id)
    {
        $data["case"] = MedCase::where("id", "=", $id)->first();
        // $data["party"] = InvoledUser::where("userPlanId", "=", $id)->get();
        $data["party"] = InvoledUser::select('user_involved_in_agreement.*', 'users.address as useraddress', 'users.address1 as useraddress1', 'users.pincode as userpincode', 'users.city as usercity', 'users.state as userstate', 'users.country as usercountry')
            ->leftJoin("users", "users.id", "=", "user_involved_in_agreement.userId")
            ->where("user_involved_in_agreement.userPlanId", "=", $id)->get();
        $pdf = PDF::loadView('pdf.invitation_mediation', $data);
        //$name = 'Invitation_mediate_M' . sprintf('%06d', $data["case"]->id) . time() . '.pdf';
        $name = 'Invitation_mediate_M' . sprintf('%06d', $data["case"]->id) . '.pdf'; /********** file name 30 character */
        // Storage::put('public/mediation/' . $data["case"]->id . '/' . $name, $pdf->output());
        $savePath = 'mediation_documents/mediation/' . $data["case"]->id;
        $finalFilePath = $savePath . '/' . $name;
        // Storage::put('public/mediation/' . $data["case"]->id . '/' . $name, $pdf->output());
        $uploadS3 = $this->uploadOnAWSDirect($finalFilePath, $savePath, $pdf);
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
        $InvoledUser = InvoledUser::where(['userPlanId' => $med->id])->get()->toArray();
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
            $inv = InvoledUser::select('user_involved_in_agreement.*', 'users.organization')->leftjoin('users', 'users.id', '=', 'user_involved_in_agreement.userId')->where(['userPlanid' => $med->id, 'userId' => $usr->id])->first();
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


            $respond = 0;


            for ($i = 0; $i < count($r['email']); $i++) {

                $invid = $r['invid'][$i];

                if ($invid != '') {

                    $inv = InvoledUser::find($invid);
                } else {
                    $inv = new InvoledUser();
                }



                //$inv->userId=;
                // if ($inv->userEmail != $r['email'][$i] || $inv->userPhone != $r['phone'][$i]) {

                //     $inv->joinCode = $this->joinCode();
                // }
                if ($r['selected_party'][$i] == 0) {
                    $inv->isClaimant = 0;
                    $inv->joinCode = null;
                } else {
                    if ($inv->userEmail != $r['email'][$i] || $inv->userPhone != $r['phone'][$i]) {

                        $inv->joinCode = $this->joinCode();
                    }
                    if ($inv->userId == 0) {
                        $inv->joinCode = $this->joinCode();
                    } else {
                        $inv->joinCode = null;
                    }
                    $inv->isClaimant = $respond + 1;
                    $respond++;
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

            // $invmodel = InvitationFiles::where('case_id', $request->id)->orderByDesc('id')->limit(1)->first();

            // if (!isset($invmodel)) {
            $invmodel = new InvitationFiles();
            // }
            $invmodel->case_id = $request->id;
            $invmodel->file_name = $invitation;
            $invmodel->save();

            $InvoledUserMsg = InvoledUser::where(['userPlanId' => $med->id])->get();
            // dd($InvoledUserMsg);
            $responding_party = "";
            $finalFilePath = 'mediation_documents/mediation/' . $request->id . '/' . $invitation;
            $whatsappSend = Storage::disk('s3')->url($finalFilePath);
            foreach ($InvoledUserMsg as $value) {

                if ($value->isClaimant > 0) {
                    // dd($value->name);
                    if ($responding_party == "") {
                        $responding_party = $value->name;
                    }

                    if ($value->userEmail != null) {
                        $s = SendGrid::send($d1, $value->userEmail, env('L4_INVITATION_TO_COUNTER_PARTIES_FOR_ONBOARDING', ''), ["-caseid-" => "M" . sprintf("%06d", $med->id), "-link-" => $value->joinCode, "-initiating-" => ($pone->organization != null) ? $pone->organization : $pone->name], $value->name, $finalFilePath);
                    }
                    if ($value->userPhone != null) {

                        $varjson = ['caseid' => "M" . sprintf("%06d", $id), 'initiating' => ($pone->organization != null) ? $pone->organization : $pone->name];
                        $var = ['-cid-', '-ip-'];
                        $var1 = ["M" . sprintf("%06d", $id), ($pone->organization != null) ? $pone->organization : $pone->name];
                        $content1 = WaTemplate::getcontent('l4_mediation_party2');
                        $content = str_replace($var, $var1, $content1);
                        $dwa1 = [
                            'caseid' => $id,
                            'contact' =>  $value->userPhone,
                            'content' => ['text' => $content],
                            'event' => 'ACPTARB_ADM_RES',
                            'varjson' => $varjson,
                            'haptik_tmp' => 'l4_mediation_party2',

                        ];

                        $access = Whatsapp::sendWamessage($dwa1);

                        $varjson_file = ['caseid' => "M" . sprintf("%06d", $id)];
                        $var_file = ['-caseid-'];
                        $var1_file = ["M" . sprintf("%06d", $id)];
                        $content1_file = WaTemplate::getcontent('mediation_consent_doc');
                        $content_file = str_replace($var_file, $var1_file, $content1_file);
                        $dwa2 = [
                            'caseid' => $id,
                            'contact' =>  $value->userPhone,
                            'content' => ['media' => ['url' => $whatsappSend, 'caption' => $content_file]],
                            'event' => 'ACPTARB_ADM_RES',
                            'varjson' => $varjson_file,
                            'haptik_tmp' => 'mediation_consent_doc',

                        ];
                        $access = Whatsapp::sendWamessage($dwa2);
                    }
                }
            }
            // dd($initiating_phone);
            if ($responding_party != "") {
                // dd($pone);
                if ($pone->userEmail != "") {
                    SendGrid::send($d2, $pone->userEmail, env('L5_INVITATION_TO_INITI_PARTIES_FOR_ONBOARDING', ''), ["-caseid-" => "M" . sprintf("%06d", $id), "-responding-" => $responding_party], $pone->name, $finalFilePath);
                }

                if ($pone->userPhone != "") {

                    $varjson = ['caseid' => "M" . sprintf("%06d", $id), 'responding' => $responding_party];
                    $var = ['-cid-', '-rp-'];
                    $var1 = ["M" . sprintf("%06d", $id), $responding_party];
                    $content1 = WaTemplate::getcontent('l4_mediation_initiating');
                    $content = str_replace($var, $var1, $content1);
                    $dwa1 = [
                        'caseid' => $id,
                        'contact' =>  $pone->userPhone,
                        'content' => ['text' => $content],
                        // 'casetype' => 2,
                        'event' => 'ACPTARB_ADM_INI',
                        'varjson' => $varjson,
                        'haptik_tmp' => 'l4_mediation_initiating',

                    ];

                    $access = Whatsapp::sendWamessage($dwa1);

                    $varjson_file = ['caseid' => "M" . sprintf("%06d", $id)];
                    $var_file = ['-caseid-'];
                    $var1_file = ["M" . sprintf("%06d", $id)];
                    $content1_file = WaTemplate::getcontent('mediation_consent_doc');
                    $content_file = str_replace($var_file, $var1_file, $content1_file);
                    $dwa2 = [
                        'caseid' => $id,
                        'contact' =>  $pone->userPhone,
                        'content' => ['media' => ['url' => $whatsappSend, 'caption' => $content_file]],
                        'event' => 'ACPTARB_ADM_INI',
                        'varjson' => $varjson_file,
                        'haptik_tmp' => 'mediation_consent_doc',

                    ];
                    $access = Whatsapp::sendWamessage($dwa2);
                }
            }

            $InvoledUser = InvoledUser::where(['userPlanId' => $med->id])->get();

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
                    $filename = pathinfo(str_replace(" ", "_", $file->getClientOriginalName()), PATHINFO_FILENAME) . "_date_" . date("Y_m_d_H_i_s_a") . "." . $file->extension();
                    $savePath = 'mediation_documents/mediation/' . $request->caseId . '/settelmentDocument';
                    $finalFilePath = $savePath . '/' . $filename;
                    Storage::disk('s3')->put($finalFilePath, file_get_contents($file));
                    // $path = $file->storeAs('/supporting/' . $request->caseId, pathinfo(str_replace(" ", "_", $file->getClientOriginalName()), PATHINFO_FILENAME) . "_date_" . date("Y_m_d_H_i_s_a") . "." . $file->extension());
                    $insert[$x]['file_path'] = $filename;
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

    public function sned_invitation($id, $invitation, $bulk_flag = 0)
    {
        $involedUser = InvoledUser::select('user_involved_in_agreement.*', 'users.organization')->leftjoin('users', 'users.id', '=', 'user_involved_in_agreement.userId')->where("userPlanId", $id)->get();


        $finalFilePath = 'mediation_documents/mediation/' . $id . '/' . $invitation;
        $whatsappSend = Storage::disk('s3')->url($finalFilePath);

        $initiating_party = "";
        $initiating_phone = [];
        $initiating_email = [];
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
                if ($initiating_party == "") {
                    if ($inv->organization != null) {
                        $initiating_party = $inv->organization;
                    } else {
                        $initiating_party = $inv->name;
                    }
                }
                $ini_userPlanId = $inv->userPlanId;
                $initiating_phone[] = $inv->userPhone;
                $initiating_email[] = $inv->userEmail;
            } else if ($inv->isOnboarded == 0) {
                $code = $inv->joinCode;
                if ($inv->name != "") {
                    if ($responding_party == "") {
                        $responding_party = $inv->name;
                    }
                }
                $responding_phone[] = $inv->userPhone;
                if ($inv->userEmail != "") {
                    SendGrid::send($d2, $inv->userEmail, env('L4_INVITATION_TO_COUNTER_PARTIES_FOR_ONBOARDING', ''), ["-caseid-" => "M" . sprintf("%06d", $id), "-link-" => $inv->joinCode, "-initiating-" => $initiating_party], $inv->name, $finalFilePath);
                }
                // break;
                // SendGrid::send($inv->userEmail, env('L4_INVITATION_TO_COUNTER_PARTIES_FOR_ONBOARDING', ''), ["-caseid-" => "M" . sprintf("%06d", $id), "-link-" => $code, "-initiating-" => $initiating_party], $inv->name, url("/storage/app/public/mediation/" . $id . "/" . $invitation));


                // $dwa2 = [
                //     'caseid' => $inv->userPlanId,
                //     'contact' =>  $inv->userPhone,
                //     'content' => ['media' => ['url' => url("/storage/app/public/mediation/" . $id . "/" . $invitation), 'caption' => 'Invitation to Mediate ' . Common_function::getsixdigitid('sc', $inv->userPlanId)]],
                //     'event' => 'ACPTARB_ADM'
                // ];
                // $access = Whatsapp::sendWamessage($dwa2);
            }
            // continue;

        }
        foreach ($responding_phone as $phone) {
            if ($phone != "") {
                $varjson = ["initiating" => $initiating_party, 'caseid' => "M" . sprintf("%06d", $id)];
                $var = ['-cid-', '-ip-'];
                $var1 = ["M" . sprintf("%06d", $id), $initiating_party];
                $content1 = WaTemplate::getcontent('l4_mediation_party2');
                $content = str_replace($var, $var1, $content1);
                $dwa1 = [
                    'caseid' => $inv->userPlanId,
                    'contact' =>  $phone,
                    'content' => ['text' => $content],
                    'event' => 'ACPTARB_ADM_RES',
                    'varjson' => $varjson,
                    'haptik_tmp' => 'l4_mediation_party2',
                ];

                $access = Whatsapp::sendWamessage($dwa1);

                $varjson_file = ['caseid' => "M" . sprintf("%06d", $id)];
                $var_file = ['-caseid-'];
                $var1_file = ["M" . sprintf("%06d", $id)];
                $content1_file = WaTemplate::getcontent('mediation_consent_doc');
                $content_file = str_replace($var_file, $var1_file, $content1_file);
                $dwa2 = [
                    'caseid' => $ini_userPlanId,
                    'contact' =>  $phone,
                    'content' => ['media' => ['url' => $whatsappSend, 'caption' => $content_file]],
                    'event' => 'ACPTARB_ADM_RES',
                    'varjson' => $varjson_file,
                    'haptik_tmp' => 'mediation_consent_doc',
                ];
                $access = Whatsapp::sendWamessage($dwa2);
            }
        }


        if ($bulk_flag == 0) {
            if ($responding_party != "") {

                foreach ($initiating_email as $ini_email) {
                    SendGrid::send($d1, $ini_email, env('L5_INVITATION_TO_INITI_PARTIES_FOR_ONBOARDING', ''), ["-caseid-" => "M" . sprintf("%06d", $id), "-responding-" => $responding_party], $inv->name, $finalFilePath);
                }


                foreach ($initiating_phone as $ini_phone) {

                    $varjson = ['caseid' => "M" . sprintf("%06d", $id), "responding" => $responding_party];
                    $var = ['-cid-', '-rp-'];
                    $var1 = ["M" . sprintf("%06d", $id), $responding_party];
                    $content1 = WaTemplate::getcontent('l4_mediation_initiating');
                    $content = str_replace($var, $var1, $content1);
                    $dwa1 = [
                        'caseid' => $ini_userPlanId,
                        'contact' =>  $ini_phone,
                        'content' => ['text' => $content],
                        // 'casetype' => 2,
                        'event' => 'ACPTARB_ADM_INI',
                        'varjson' => $varjson,
                        'haptik_tmp' => 'l4_mediation_initiating',
                    ];

                    $access = Whatsapp::sendWamessage($dwa1);

                    $varjson_file = ['caseid' => "M" . sprintf("%06d", $id)];
                    $var_file = ['-caseid-'];
                    $var1_file = ["M" . sprintf("%06d", $id)];
                    $content1_file = WaTemplate::getcontent('mediation_consent_doc');
                    $content_file = str_replace($var_file, $var1_file, $content1_file);
                    $dwa2 = [
                        'caseid' => $ini_userPlanId,
                        'contact' =>  $ini_phone,
                        'content' => ['media' => ['url' => $whatsappSend, 'caption' => $content_file]],
                        'event' => 'ACPTARB_ADM_INI',
                        'varjson' => $varjson_file,
                        'haptik_tmp' => 'mediation_consent_doc',

                    ];
                    $access = Whatsapp::sendWamessage($dwa2);
                }
            }
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

            $varjson = ['sessionDteaTime' => $date, 'caseid' => $mid, 'zoomid' => $url];
            $var = ['-dt-', '-cid-', '-link-'];
            $var1 = [$date, $mid, $url];
            $content1 = WaTemplate::getcontent('l10_session_schedule');
            $content = str_replace($var, $var1, $content1);
            $dwa1 = [
                'caseid' => $id,
                'contact' =>  $userPhone,
                'content' => ['text' => $content],
                'event' => 'SESS_SCHE',
                'varjson' => $varjson,
                'haptik_tmp' => 'l10_session_schedule',

            ];

            // print_r($dwa1);
            // exit;

            $access = Whatsapp::sendWamessage($dwa1);
        }
        return true;
    }

    public function sned_withdrawal($id)
    {
        // $involedUser = InvoledUser::where("userPlanId", $id)->get();
        $involedUser = InvoledUser::select('user_involved_in_agreement.*', 'users.organization')->leftjoin('users', 'users.id', '=', 'user_involved_in_agreement.userId')->where("userPlanId", $id)->get();
        $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->where("mediators_mediation_cases_status.mediation_case_id", "=", $id)
            ->where("mediators_mediation_cases_status.status", "=", 1)
            ->first();
        $mid = "M" . sprintf("%06d", $id);

        $initiating_party = "";
        $initiating_phone = [];
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
                if ($inv->organization != null) {
                    $initiating_party = $inv->organization;
                } else {
                    $initiating_party = $inv->name;
                }
                $initiating_phone[] = $inv->userPhone;
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
                    $varjson = ['caseid' => $mid, 'initiating' => $initiating_party];
                    $var = ['-cid-', '-cl-'];
                    $var1 = [$mid, $initiating_party];
                    $content1 = WaTemplate::getcontent('withdrawal_responding');
                    $content = str_replace($var, $var1, $content1);
                    $dwa1 = [
                        'caseid' => $id,
                        'contact' =>  $phone,
                        'content' => ['text' => $content],
                        'event' => 'WDRN_OTHER_PARTY',
                        'varjson' => $varjson,
                        'haptik_tmp' => 'l14_withdrawal_responding',
                    ];

                    // print_r($dwa1);
                    // exit;
                    $access = Whatsapp::sendWamessage($dwa1);
                }
            }
        }

        if ($responding_party != "") {

            if (isset($initiating_phone)) {
                foreach ($initiating_phone as $ini_phone) {
                    $varjson = ['caseid' => $mid, 'responding' => $responding_party];
                    $var = ['-cid-', '-rp-'];
                    $var1 = [$mid, $responding_party];
                    $content1 = WaTemplate::getcontent('withdrawal_initiating');
                    $content = str_replace($var, $var1, $content1);
                    $dwa1 = [
                        'caseid' => $id,
                        'contact' =>  $ini_phone,
                        'content' => ['text' => $content],
                        // 'casetype' => 2,
                        'event' => 'WDRN_PARTY',
                        'varjson' => $varjson,
                        'haptik_tmp' => 'l13_session_schedule',
                    ];

                    // print_r($dwa1);
                    // exit;

                    $access = Whatsapp::sendWamessage($dwa1);
                }
            }
        }
        if ($mediator) {
            SendGrid::send($d3, $mediator->email, env('L13_WITHDRAWAL_OF_CASE', ''), ["-caseid-" => $mid, "-responding-" => $initiating_party, "-type-" => "Mediator"], $mediator->username);

            $varjson = ['caseid' => $mid];
            $var = ['-cid-'];
            $var1 = [$mid];
            $content1 = WaTemplate::getcontent('withdrawal_mediator');
            $content = str_replace($var, $var1, $content1);
            $dwa1 = [
                'caseid' => $id,
                'contact' =>  $mediator->mobile_number,
                'content' => ['text' => $content],
                // 'casetype' => 2,
                'event' => 'WDRN_MED',
                'varjson' => $varjson,
                'haptik_tmp' => 'l23_withdrawal_mediator',

            ];

            // print_r($dwa1);
            // exit;

            $access = Whatsapp::sendWamessage($dwa1);
        }
        return true;
    }

    public function sned_resolved($id)
    {
        $involedUser = InvoledUser::select('user_involved_in_agreement.*', 'users.organization')->leftjoin('users', 'users.id', '=', 'user_involved_in_agreement.userId')->where("userPlanId", $id)->get();
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
                if ($inv->organization != null) {
                    $initiating_party = $inv->organization;
                } else {
                    $initiating_party = $inv->name;
                }
            }
            if ($inv->userEmail != "") {
                SendGrid::send($d, $inv->userEmail, env('L15_CASE_RESOLVED', ''), ["-caseid-" => $mid, "-responding-" => $initiating_party, "-type-" => "Party"], $inv->name);
            }

            if ($inv->userPhone != "") {
                $varjson = ['caseid' => $mid];
                $var = ['-cid-'];
                $var1 = [$mid];
                $content1 = WaTemplate::getcontent('med_resolved_clamant');
                $content = str_replace($var, $var1, $content1);
                $dwa1 = [
                    'caseid' => $id,
                    'contact' =>  $inv->userPhone,
                    'content' => ['text' => $content],
                    'event' => 'RESO_ADM',
                    'varjson' => $varjson,
                    'haptik_tmp' => 'med_resolved_clamant',

                ];

                $access = Whatsapp::sendWamessage($dwa1);
            }
        }
        if ($mediator) {
            SendGrid::send($d, $mediator->email, env('L15_CASE_RESOLVED', ''), ["-caseid-" => $mid, "-responding-" => $initiating_party, "-type-" => "Mediator"], $mediator->username);
            $varjson = ['caseid' => $mid];
            $var = ['-cid-'];
            $var1 = [$mid];
            $content1 = WaTemplate::getcontent('med_resolved_clamant');
            $content = str_replace($var, $var1, $content1);
            $dwa1 = [
                'caseid' => $id,
                'contact' =>  $mediator->mobile_number,
                'content' => ['text' => $content],
                'event' => 'RESO_ADM',
                'varjson' => $varjson,
                'haptik_tmp' => 'med_resolved_clamant',

            ];

            $access = Whatsapp::sendWamessage($dwa1);
        }
        return true;
    }

    public function sned_unresolved($id)
    {
        $involedUser = InvoledUser::select('user_involved_in_agreement.*', 'users.organization')->leftjoin('users', 'users.id', '=', 'user_involved_in_agreement.userId')->where("userPlanId", $id)->get();
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
                if ($inv->organization != null) {
                    $initiating_party = $inv->organization;
                } else {
                    $initiating_party = $inv->name;
                }
            }
            if ($inv->userEmail != "") {
                SendGrid::send($d, $inv->userEmail, env('L15_CASE_UNRESOLVED', ''), ["-caseid-" => $mid, "-responding-" => $initiating_party, "-type-" => "Party"], $inv->name);
            }
            if ($inv->userPhone != "") {
                $varjson = ['caseid' => $mid];
                $var = ['-cid-'];
                $var1 = [$mid];
                $content1 = WaTemplate::getcontent('med_unresolved_clamant');
                $content = str_replace($var, $var1, $content1);
                $dwa1 = [
                    'caseid' => $id,
                    'contact' =>  $inv->userPhone,
                    'content' => ['text' => $content],
                    'event' => 'UNRESO_ADM',
                    'varjson' => $varjson,
                    'haptik_tmp' => 'med_unresolved_clamant',

                ];

                $access = Whatsapp::sendWamessage($dwa1);
            }
        }
        if ($mediator) {
            SendGrid::send($d, $mediator->email, env('L15_CASE_UNRESOLVED', ''), ["-caseid-" => $id, "-responding-" => $initiating_party, "-type-" => "Mediator"], $mediator->username);
            $varjson = ['caseid' => $mid];
            $var = ['-cid-'];
            $var1 = [$mid];
            $content1 = WaTemplate::getcontent('med_unresolved_clamant');
            $content = str_replace($var, $var1, $content1);
            $dwa1 = [
                'caseid' => $id,
                'contact' =>  $mediator->mobile_number,
                'content' => ['text' => $content],
                'event' => 'UNRESO_ADM',
                'varjson' => $varjson,
                'haptik_tmp' => 'med_unresolved_clamant',

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
        $varjson = ['caseid' => $mid];
        $var = ['-cid-'];
        $var1 = [$mid];
        $content1 = WaTemplate::getcontent('consent_mediator');
        $content = str_replace($var, $var1, $content1);
        $dwa1 = [
            'caseid' => $id,
            'contact' =>  $user->mobile_number,
            'content' => ['text' => $content],
            'event' => 'MEDI_ADD_ADM',
            'varjson' => $varjson,
            'haptik_tmp' => 'l17_consent_mediator',
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
            // $filesE[] = url("storage/app/" . $f["file_name"]);
            $filesE[] = 'mediation_documents/mediation/' . $id . '/supportingDocument/' . $f["file_name"];

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
                        'contact' => $inv->userPhone,
                        'content' => ['text' => $content],
                        'event' => 'SEND_ADDI_DOC',
                        'varjson' => $varjson,
                        'haptik_tmp' => 'l19_additional_doc',
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
                            'haptik_tmp' => 'mediation_consent_doc',
                        ];
                        $accessW = Whatsapp::sendWamessage($dwa2);
                    }
                }
            }
            // $dwa2 = [
            //     'caseid' => $id,
            //     'contact' => $inv->userPhone,
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
                    'haptik_tmp' => 'l20_additional_doc_med',

                ];
                $accessW = Whatsapp::sendWamessage($dwa1);
                foreach ($filesE as $file) {
                    $whatsappSend = Storage::disk('s3')->url($file);

                    $varjson = ['caseid' => $mid];
                    $var_file = ['-caseid-'];
                    $var1_file = [$mid];
                    $content1_file = WaTemplate::getcontent('mediation_consent_doc');
                    $content_file = str_replace($var_file, $var1_file, $content1_file);
                    $dwa2 = [
                        'caseid' => $id,
                        'contact' =>  $mediator->mobile_number,
                        'content' => ['media' => ['url' => $whatsappSend, 'caption' => $content_file]],
                        'event' => 'SEND_ADDI_DOC_MED',
                        'varjson' => $varjson,
                        'haptik_tmp' => 'mediation_consent_doc',
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
            // $filesE[] = url("storage/app/" . $f["file_path"]);
            $filesE[] = 'mediation_documents/mediation/' . $id . '/settelmentDocument/' . $f["file_path"];
        }
        foreach ($involedUser as $inv) {
            if ($inv->userEmail != "") {
                $sendEamils[] = $inv->userEmail;
            }

            if ($inv->userPhone != "") {
                // settlement agreement
                $varjson = ['caseid' => $mid];
                $var = ['-cid-'];
                $var1 = [$mid];
                $content1 = WaTemplate::getcontent('settlement_agreement');
                $content = str_replace($var, $var1, $content1);
                $dwa1 = [
                    'caseid' => $id,
                    'contact' =>  $inv->userPhone,
                    'content' => ['text' => $content],
                    'event' => 'SEND_SETT_AGRE',
                    'varjson' => $varjson,
                    'haptik_tmp' => 'l21_settlement_agreement',

                ];
                $access = Whatsapp::sendWamessage($dwa1);
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
                        'event' => 'SEND_SETT_AGRE',
                        'varjson' => $varjson_file,
                        'haptik_tmp' => 'mediation_consent_doc',

                    ];
                    $access = Whatsapp::sendWamessage($dwa2);
                }
            }
            // $dwa2 = [
            //     'caseid' => $id,
            //     'contact' =>  $inv->userPhone,
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

            $varjson = ['caseid' => $mid];
            $var = ['-cid-'];
            $var1 = [$mid];
            $content1 = WaTemplate::getcontent('settlement_agreement_med');
            $content = str_replace($var, $var1, $content1);
            $dwa1 = [
                'caseid' => $id,
                'contact' =>  $mediator->mobile_number,
                'content' => ['text' => $content],
                'event' => 'SEND_SETT_AGRE_MED',
                'varjson' => $varjson,
                'haptik_tmp' => 'l22_settlement_agreement_med',
            ];
            $access = Whatsapp::sendWamessage($dwa1);

            foreach ($filesE as $file) {
                $whatsappSend = Storage::disk('s3')->url($file);

                $varjson = ['caseid' => $mid];
                $var_file = ['-caseid-'];
                $var1_file = [$mid];
                $content1_file = WaTemplate::getcontent('mediation_consent_doc');
                $content_file = str_replace($var_file, $var1_file, $content1_file);
                $dwa2 = [
                    'caseid' => $id,
                    'contact' =>  $mediator->mobile_number,
                    'content' => ['media' => ['url' => $whatsappSend, 'caption' => $content_file]],
                    'event' => 'SEND_SETT_AGRE_MED',
                    'varjson' => $varjson,
                    'haptik_tmp' => 'mediation_consent_doc',

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
        // dd($request->all());
        $_SESSION['last_uploaded_id'] = '';

        $uploaded_excel = '';
        $claimantid = $request->claimant;
        $cldetails = User::find($claimantid);
        $errormsg = '';

        $selectCsv = $request->file('csv');
        if ($selectCsv == null) {
            $errormsg .= 'Please Select File';
            // return redirect('/admin/case/new-request')->with(['error' => $errormsg]);
            return json_encode(['code' => 200, 'response' => 'error', 'msg' => $errormsg]);
            exit;
        } else {

            $tmpName = $selectCsv->getPathname();

            $ext = pathinfo($selectCsv->getClientOriginalName(), PATHINFO_EXTENSION);
            // dd($ext);



            if ($claimantid == null) {
                $errormsg .= 'Please Select Claimant';
            }

            if ($ext != 'csv') {
                $errormsg .= 'Please upload csv file';
            }
            if ($errormsg == '') {
                $csv = $this->csvToArray($tmpName);
                if (count($csv[0]) != 16) {
                    $errormsg .= "Invalid csv file";
                }
                if ($errormsg != '') {

                    // return redirect('/admin/case/new-request')->with(['error' => $errormsg]);
                    return json_encode(['code' => 200, 'response' => 'error', 'msg' => $errormsg]);

                    exit();
                }
                $errormsg .= '';
                foreach ($csv as $key => $v) {
                    $i = $key + 1;

                    for ($n = 0; $n < 15; $n++) {
                        if ($v[$n] == '') {

                            if ($n != 10 and $n != 11 and $n != 12 and $n != 7 and $n != 3 and $n != 4) {
                                //$errormsg .= "Please fill all the required details to proceed at line no $i";
                            }
                        }
                    }
                    if ($v[4] != "") {
                        if (!filter_var($v[4], FILTER_SANITIZE_NUMBER_INT)) {
                            //$errormsg .= "Invalid mobile number at line no $i ";
                        }

                        if (strlen($v[4]) != 10) {
                            //$errormsg .= "Invalid mobile number at line no $i ";
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
                            //$errormsg .= "Invalid date at line no $i. date format should be dd/mm/YYYY or dd-mm-YYYY";
                        }
                        if (strpos($v[7], '-') or strpos($v[7], '/')) {
                            $dt = explode('/', $v[7]);

                            if (count($dt) != 3 and strlen($dt[0]) != 2 and strlen($dt[1]) != 2 and strlen($dt[0]) != 4) {

                                //$errormsg .= "Invalid date at line no $i. date format should be dd/mm/YYYY or dd-mm-YYYY";
                            }
                        }
                    }

                    if ($v[13] != 'Yes') {
                        //$errormsg .= "Please confirm that the details provided above are true, accurate, current and complete to proceed at line no $i ";
                    }

                    if ($v[14] != 'Yes') {

                        //$errormsg .= "Please accept and agree to abide by Mediation’s Dispute Resolution Rules, Terms & Conditions and Privacy Policy to proceed at line no $i ";
                    }
                }
            }
            if ($errormsg != '') {

                // return redirect('/admin/case/new-request')->with(['error' => $errormsg]);
                return json_encode(['code' => 200, 'response' => 'error', 'msg' => $errormsg]);
                exit();
            }
            // if (1 == 1) {

            //     //save file
            //     $file = $request->file('csv');
            //     $destinationPath = 'public/uploaded';

            //     $extension = $file->getClientOriginalExtension();
            //     $fileName = time() . '.' . $extension;

            //     if ($file->storeAs($destinationPath, $fileName)) {
            //         $uploaded_excel .= $fileName;
            //     }
            // }
            //store in database
            if ($request->batch != null) {
                $batchdata = [
                    'batch_name' => $request->batch,
                ];
                $batch = Batch::where('batch_name', $batchdata['batch_name'])->first();
                if (!$batch) {
                    $batch = Batch::create($batchdata);
                }
            }
            $csv = mb_convert_encoding($csv, 'UTF-8', 'UTF-8');
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
                $data['batch_id'] = isset($batch->id) ? $batch->id : null;
                $data['bulk_flag'] = 1;
                $data['discussion'] = $value[15];

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

            // return redirect('/admin/case/new-request')->with(['success' => 'Success']);
            return json_encode(['code' => 200, 'response' => 'success']);
        }
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
        // Storage::put('public/mediation/' . $data["case"]->id . '/' . $name, $pdf->output());
        $savePath = 'mediation_documents/mediation/' . $data["case"]->id;
        $finalFilePath = $savePath . '/' . $name;
        // Storage::put('public/mediation/' . $data["case"]->id . '/' . $name, $pdf->output());
        $uploadS3 = $this->uploadOnAWSDirect($finalFilePath, $savePath, $pdf);
        return $name;
    }

    public function documentUpload(Request $request)
    {

        $selectDocument = $request->file('fileupload');

        $errormsg = '';

        $med = MedCase::find($request->caseid);

        if ($selectDocument !== null) {

            $ext = pathinfo($selectDocument->getClientOriginalName(), PATHINFO_EXTENSION);

            // dd($ext);

            if ($ext != 'pdf' && $ext != 'zip' && $ext != 'rar') {
                $errormsg .= 'Please upload pdf, rar and zip file';
                return json_encode(['code' => 200, 'response' => 'error', 'msg' => $errormsg]);
                exit;
            } else {
                $filename = 'supporting_document' . $med->id . time() . '.' . $selectDocument->getClientOriginalExtension();
                // dd($filename);
                $savePath = 'mediation_documents/mediation/' . $med->id . '/user/supportingDocument';
                $finalFilePath = $savePath . '/' . $filename;
                // Storage::put('public/mediation/' . $data["case"]->id . '/' . $name, $pdf->output());
                Storage::disk('s3')->put($finalFilePath, file_get_contents($selectDocument));
                // $path = $request->file('document')->storeAs('public/mediation/' . $med->id . '/', $filename);
                $med->documentPath = $filename;
                $med->save();
                // return redirect('/admin/case/new-request')->with(['success' => 'Success']);
                return json_encode(['code' => 200, 'response' => 'success']);
            }
            // $errormsg .= $request->validate([
            //     'document' => 'mimes:pdf,zip,rar|max:20048',
            // ]);


        } else {
            $errormsg .= "Please Select Document";
            return json_encode(['code' => 200, 'response' => 'error', 'msg' => $errormsg]);
            exit;
        }

        // if ($errormsg != '') {

        //     return redirect('/admin/case/new-request')->with(['error' => $errormsg]);

        //     exit();
        // }
    }

    public function track($id)
    {
        $whatsapp = WhatsappTrack::getByCaseIdWh($id);
        // $casedetails = MedCase::getcasebyId($id);
        $email = EmailTrack::getByCaseId($id);
        // $courierCsv = CourierCsv::with('pdf')->where('case_id', $id)->orderBy('status_as_on_date', 'DESC')->get();
        $courierCsv = CourierCsv::select('couriercsv.*', 'courierpdf.file_name')->leftJoin('courierpdf', 'courierpdf.csv_id', '=', 'couriercsv.id')
            ->where('couriercsv.case_id', $id)->orderBy('couriercsv.created_at', 'ASC')->get();


        $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->where("mediators_mediation_cases_status.mediation_case_id", "=", $id)
            ->where("mediators_mediation_cases_status.status", "=", 1)
            ->first();

        $data = [];
        $data['auth'] = "MED360AUTH";
        $data['app'] = "P360MED";
        $data['caseid'] = $id;
        $url = "https://presolv360.com/functions/ivrtrack.php";

        $ivr = json_decode(Curl::getdata($url, $data, 'POST', 'MED360AUTH'), true);
        if ($ivr['code'] != '200') {
            $ivr = [];
        } else {
            $ivr = $ivr['data'];
        }
        // dd($ivr);
        return view('admin.case.track', compact("whatsapp", "id", "mediator", "email", "ivr", "courierCsv"));
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
                    $varjson = ['caseid' => $mid];
                    $var = ['-cid-'];
                    $var1 = [$mid];
                    $content1 = WaTemplate::getcontent('additional_doc_med');
                    $content = str_replace($var, $var1, $content1);
                    $dwa1 = [
                        'caseid' => $request->caseid,
                        'contact' =>  $mediator->mobile_number,
                        'content' => ['text' => $content],
                        'event' => 'SEND_ADDI_DOC_MED',
                        'varjson' => $varjson,
                        'haptik_tmp' => 'l20_additional_doc_med',

                    ];


                    $accessW = Whatsapp::sendWamessage($dwa1);

                    $varjson_file = ['caseid' => $mid];
                    $var_file = ['-caseid-'];
                    $var1_file = [$mid];
                    $content1_file = WaTemplate::getcontent('mediation_consent_doc');
                    $content_file = str_replace($var_file, $var1_file, $content1_file);
                    $dwa2 = [
                        'caseid' => $request->caseid,
                        'contact' =>  $mediator->mobile_number,
                        'content' => ['media' => ['url' => $filesE, 'caption' => $content_file]],
                        'event' => 'SEND_ADDI_DOC_MED',
                        'varjson' => $varjson_file,
                        'haptik_tmp' => 'mediation_consent_doc',

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
                    $varjson = ['caseid' => $mid];
                    $var = ['-cid-'];
                    $var1 = [$mid];
                    $content1 = WaTemplate::getcontent('additional_doc');
                    $content = str_replace($var, $var1, $content1);
                    $dwa1 = [
                        'caseid' => $invUser->userPlanId,
                        'contact' => $invUser->userPhone,
                        'content' => ['text' => $content],
                        'event' => 'SEND_ADDI_DOC',
                        'varjson' => $varjson,
                        'haptik_tmp' => 'l19_additional_doc',

                    ];
                    $accessW = Whatsapp::sendWamessage($dwa1);

                    $varjson_file = ['caseid' => $mid];
                    $var_file = ['-caseid-'];
                    $var1_file = [$mid];
                    $content1_file = WaTemplate::getcontent('mediation_consent_doc');
                    $content_file = str_replace($var_file, $var1_file, $content1_file);
                    $dwa2 = [
                        'caseid' => $invUser->userPlanId,
                        'contact' =>  $invUser->userPhone,
                        'content' => ['media' => ['url' => $filesE, 'caption' => $content_file]],
                        'event' => 'SEND_ADDI_DOC',
                        'varjson' => $varjson_file,
                        'haptik_tmp' => 'mediation_consent_doc',

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

    public function downloadfilebulk(Request $request)
    {
        // dd($request->all());
        $zip_file = 'mediation_Invitation_' . time() . '.zip'; // Name of our archive to download
        $download_path = storage_path() . '/app/public/bulkInvitation/' . $zip_file;
        // Initializing PHP class
        $zip = new ZipArchive();
        $zip->open($download_path, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $caseid = explode(',', $request->allcid);
        $pdf = new PDFMerger();
        $caseid = collect($caseid)->sort();

        // Add all the pages of the PDF to merge
        foreach ($caseid as $value) {
            $invitation = InvitationFiles::where(['case_id' => $value])->orderByDesc('id')->limit(1)->first();
            if ($invitation->file_name != null) {
                $exist_file = storage_path() . '/app/public/mediation/' . $value . '/' . $invitation->file_name;
                if (File::exists($exist_file)) {
                    $save_file =  $invitation->file_name;
                    $zip->addFile($exist_file, $save_file);
                    $pdf->addPDF($exist_file, 'all');
                } else {

                    // --------- First save into local and then add in zip and merge pdf

                    $s3_local = Storage::disk('local')->writeStream('public/mediation/temp/' . $value . '/' . $invitation->file_name, Storage::disk('s3')->readStream('mediation_documents/mediation/' . $value . '/' . $invitation->file_name));

                    $exist_file_local = storage_path() . '/app/public/mediation/temp/' . $value . '/' . $invitation->file_name;
                    $save_file =  $invitation->file_name;

                    $zip->addFile($exist_file_local, $save_file);
                    $pdf->addPDF($exist_file_local, 'all');


                    // unlink($exist_file_local);
                    // --------- Successfully add pdf in zip but merge file error

                    // $s3 = Storage::cloud()->getAdapter()->getClient();

                    // $s3->registerStreamWrapper();
                    // $bucket = env('AWS_BUCKET');

                    // $objects = $s3->ListObjects(array(
                    //     'Bucket' => $bucket,
                    //     'Prefix' => 'mediation_documents/mediation/' . $value . '/' . $invitation->file_name,
                    // ));
                    // if (!empty($objects['Contents']) && is_array($objects['Contents'])) {
                    //     foreach ($objects['Contents'] as $object) {
                    //         if (isset($object['Size']) && $object['Size'] > 0) {
                    //             $contents = file_get_contents("s3://{$bucket}/{$object['Key']}"); // get file
                    //             // echo '<br>';
                    //             // $path = 'cover_letter_' . $value . '.pdf';
                    //             $firstsavelocal =  Storage::disk('local')->put('public/mediation/temp/' . $value . '/' . $invitation->file_name, $contents);
                    //             $exist_file_local = storage_path() . '/app/public/mediation/temp/' . $value . '/' . $invitation->file_name;

                    //             // $zip->addFromString($path, $pdf->output());


                    //             $zip->addFromString($invitation->file_name, $contents); // add file contents in zip

                    //             $pdf->addPDF($exist_file_local, 'all');

                    //         }
                    //     }
                    // }


                    // $filenametostore = 'mediation_documents/mediation/' . $value . '/' . $invitation->file_name;
                    // $save_file =  $invitation->file_name;

                    // $data = Storage::disk('s3')->get($filenametostore);

                    // $pathdata = Storage::disk('s3')->url($filenametostore);

                    // $file_encoded = base64_encode($data);
                    // // dd($file_encoded);
                    // $zip->addFile($file_encoded, $save_file);
                    // // $pdf->addPDF($pathdata, 'all');

                    // $firstsavelocal =  Storage::put('public/mediation/' . $data["case"]->id . '/' . $name, $pdf->output());
                    // $finalFilePath = 'mediation_documents/mediation/' . $value . '/' . $invitation->file_name;
                    // $exist_file1 = Storage::disk('s3')->get($finalFilePath);
                    // $exist_file = Storage::disk('s3')->url($finalFilePath);
                    // $exist_file = storage_path() . '/app/public/mediation/' . $value . '/' . $invitation->file_name;
                    // $save_file =  $invitation->file_name;
                    // $zip->addFile($exist_file1, $save_file);
                    // $pdf->addPDF($exist_file, 'all');
                }
            }
            // $exist_file_local_find = storage_path() . '/app/public/mediation/temp/' . $value . '/' . $invitation->file_name;
            // if(File::exists($exist_file_local_find)) {
            //     unlink($exist_file_local_find);
            // }
        }
        $pathForTheMergedPdf = storage_path() . "/app/public/mergeFiles/allinone_" . time() . ".pdf";
        $pdf->merge('file', $pathForTheMergedPdf);
        // dd($binaryContent);
        $zip->addFile($pathForTheMergedPdf, basename($pathForTheMergedPdf));

        $zip->close();
        foreach ($caseid as $value) {
            // $invitation = InvitationFiles::where(['case_id' => $value])->orderByDesc('id')->limit(1)->first();
            $exist_file_local_find = storage_path() . '/app/public/mediation/temp/' . $value;
            if (File::exists($exist_file_local_find)) {
                // unlink($exist_file_local_find);
                File::deleteDirectory($exist_file_local_find);
            }
        }
        unlink($pathForTheMergedPdf);
        // dd($request_file_name);
        if (File::exists($download_path)) {
            $fileContent = file_get_contents($download_path);

            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $fileType = $finfo->file($download_path);
            unlink($download_path);

            return response($fileContent, 200, [
                'Content-Type' => $fileType,
                'Content-Disposition' => 'attachment; filename="' . basename($download_path) . '"',
            ]);
            // return response()->download($zip_file);
        } else {
            return response("{}", 204, [
                'Content-Type' => "",
                'Content-Disposition' => 'attachment; filename=""',
            ]);
        }
    }

    public function SendforEditSession(Request $request)
    {

        $sessionEditData = DB::table('manage_session')->where('id', $request->id)->first();

        return json_encode($sessionEditData);
    }

    /******************** Update Session : START  ************************************************/
    public function UpdateSession(Request $request)
    {
       // dd($request->all());
        $id =  $request->SessId;

        /********* Zoom Time Format ******************/
        if($request->zoomChoice == "direct") {
            $time_zoom = date("H:i:s", strtotime($request->sessionTime));
            $end_time = date("H:i:s", strtotime($request->sessionTime) + 60*60);
            $date1 = str_replace('/', '-', $request->sessionDate);  

            $date = date('Y-m-d', strtotime($date1));
            $total = $date.' '.$time_zoom;
            $end_total = $date.' '.$end_time;
            $date_format_api =  date("Y-m-d\TH:i:s", strtotime($total));
            $end_date_format_api =  date("Y-m-d\TH:i:s", strtotime($end_total));

            $update_zoom_meeting_response = Zoom::updateZoomMeeting($request->zoomId, $request->CaseId, $date_format_api, $end_date_format_api, $request->note);
            
            $update_zoom_meeting = json_decode($update_zoom_meeting_response, true);

            if($update_zoom_meeting == '') {
                $zoom_invitation_response = Zoom::zoomInvitation($request->zoomId);
                $zoom_invitation = json_decode($zoom_invitation_response, true);
            }

        } else {

        }
        
       /********* Zoom Time Format ******************/

        $time = date("g:i A", strtotime($request->sessionTime));
        $result = ManageSession::find($id);
        $result->session_date = $request->sessionDate . "/" . $time;
        $result->note = $request->note;
        $result->zoom_id = $request->zoomId;
        $result->session_party_ids = json_encode($request->session_party_ids);

        //if($update_zoom_meeting == "") {
        if ($result->save()) {

            foreach ($request->session_party_ids as $party_id) {

                $party = InvoledUser::where("userPlanId", $result->case_id)->where("id", $party_id)->first();

                if($request->zoomChoice == "manual") {
                    $this->sned_session($request->zoomId, $result->case_id, $party->userEmail, $party->name, $request->sessionDate . "/" . $time, $party->userPhone);
                } else {
                    $this->sned_session_invitation($request->zoomId, $result->case_id, $party->userEmail, $party->name, $request->sessionDate . "/" . $time_zoom, $party->userPhone, $request->zoom_link);
                }
                
                
            }
            $d = [
                'event' => 'SESS_SCHE',
                'case_id' => $result->case_id,
            ];
            $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                ->where("mediators_mediation_cases_status.mediation_case_id", "=", $result->case_id)
                ->where("mediators_mediation_cases_status.status", "=", 1)
                ->first();
            if ($mediator) {
                $id = "M" . sprintf("%06d", $result->case_id);
                SendGrid::send($d, $mediator->email, env('L10_SCHEDULING_OF_SESSION', ''), ["-caseid-" => $id, "-insert_date-" => $request->sessionDate . "/" . $time, "-type-" => "Mediator"], $mediator->username);

                $varjson = ['sessionDteaTime' => $request->sessionDate . "/" . $time, 'caseid' => $id, 'zoomid' => $request->zoomId];
                $var = ['-dt-', '-cid-', '-link-'];
                $var1 = [$request->sessionDate . "/" . $time, $id, $request->zoomId];
                $content1 = WaTemplate::getcontent('l10_session_schedule');
                $content = str_replace($var, $var1, $content1);
                $dwa1 = [
                    'caseid' => $result->case_id,
                    'contact' =>  $mediator->mobile_number,
                    'content' => ['text' => $content],
                    'event' => 'SESS_SCHE',
                    'varjson' => $varjson,
                    'haptik_tmp' => 'l10_session_schedule',

                ];
                $access = Whatsapp::sendWamessage($dwa1);
            }
        }
       // }
        return true;
    }
    /******************** Update Session : END  ************************************************/

    public function downloadLogInviation(Request $request)
    {

        // dd($request->all());
        $caseinfo = [];
        $columnHeader = '';
        $setData = '';
        $count = [];
        $caseid = explode(',', trim($request->ids));
        foreach ($caseid as $key => $value) {
            $count[$key] = InvoledUser::where('isClaimant', '!=', 0)->where('userPlanId', $value)->count();
        }
        $forloopcnt = max($count);
        $columnHeader =  "Sr. No." . "\t" . "Case ID" . "\t" . "Reference ID" . "\t" . "Date of Invoking Mediation" . "\t" . "Initiating Organization Name" . "\t" .
            "Initiating Registered Office" . "\t" . "Initiating Full Name" . "\t" . "Initiating Email ID" . "\t" . "Initiating WhatsApp / Mobile Number" . "\t" . "Full name of Primary Respondent" . "\t" .
            "Full Address of Primary Respondent" . "\t" . "Email ID of Primary Respondent" . "\t" . "WhatsApp / Mobile Number of Primary Respondent (10 digit)" . "\t" . "Dispute Category" . "\t" . "Nature of agreement" . "\t" . "Agreement date" . "\t" .  "Disputed amount" . "\t" . "Date of Invitation" . "\t" . "Name of Mediator" . "\t" .
            "Invitation Primary Respondent email transmitted status" . "\t" . "Invitation Primary Respondent email transmitted date" . "\t" . "Invitation Primary Respondent email delivery status" . "\t" . "Invitation Primary Respondent email delivery date" . "\t" . "Invitation Primary Respondent email read status" . "\t" . "Invitation Primary Respondent email read date" . "\t" . "Invitation Primary Respondent whatsapp transmitted status" . "\t" . "Invitation Primary Respondent whatsapp transmitted date" . "\t" . "Invitation Primary Respondent whatsapp delivery status" . "\t" . "Invitation Primary Respondent whatsapp delivery date" . "\t" . "Invitation Primary Respondent whatsapp read status" . "\t" . "Invitation Primary Respondent whatsapp read date" . "\t";

        for ($i = 1; $i < $forloopcnt; $i++) {
            $columnHeader = $columnHeader . "Email ID of Additional Respondent " . $i . "\t" . "WhatsApp / Mobile Number of additional Respondent " . $i . "\t" .
                "Invitation Additional Respondent " . $i . " email transmitted status" . "\t" . "Invitation Additional Respondent " . $i . " email transmitted date" . "\t" . "Invitation Additional Respondent " . $i . " email delivery status" . "\t" . "Invitation Additional Respondent " . $i . " email delivery date" . "\t" . "Invitation Additional Respondent " . $i . " email read status" . "\t" . "Invitation Additional Respondent " . $i . " email read date" . "\t" . "Invitation Additional Respondent " . $i . " whatsapp transmitted status" . "\t" . "Invitation Additional Respondent " . $i . " whatsapp transmitted date" . "\t" . "Invitation Additional Respondent " . $i . " whatsapp delivery status" . "\t" . "Invitation Additional Respondent " . $i . " whatsapp delivery date" . "\t" . "Invitation Additional Respondent " . $i . " whatsapp read status" . "\t" . "Invitation Additional Respondent " . $i . " whatsapp read date" . "\t";
        }

        $columnHeader = $columnHeader . "Ivr log status" . "\t" . "Ivr log Date" . "\t\n";

        // dd($columnHeader);

        foreach ($caseid as $key => $value) {
            $data['case'] = MedCase::find($value);
            // dd($data);
            $data['claimant'] = InvoledUser::select('user_involved_in_agreement.*', 'users.organization')->where('isClaimant', 0)->leftJoin('users', 'users.id', '=', 'user_involved_in_agreement.userId')->where('userPlanId', $value)->first();
            $data['responding'] = InvoledUser::where('isClaimant', '!=', 0)->where('userPlanId', $value)->get();
            $data['inviation_file'] = InvitationFiles::where('case_id', $value)->orderByDesc('id')->limit(1)->first();

            $data['mediator'] = Mediators_mediation_cases_status::select("email", "username", "mobile_number", "first_name", "last_name")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                ->where("mediators_mediation_cases_status.mediation_case_id", "=", $value)
                ->first();
            $caseinfo['srno'] = $key + 1;
            $caseinfo['caseid'] = 'M' . sprintf('%06d', $value);
            $caseinfo['refid'] = $data['case']->ref_id;
            $datearb = new DateTime($data['case']->created_at);
            $caseinfo['datearb'] = $datearb->format('d-m-Y H:i:s');
            $caseinfo['clorg'] = $data['claimant']->organization;
            if ($data['claimant']->fulladdress == null) {
                $caseinfo['cloff'] = $data['claimant']->address1 . ',' . $data['claimant']->address2 . ',' . $data['claimant']->city . ',' . $data['claimant']->pincode . ',' . $data['claimant']->state . ',' . $data['claimant']->country;
            } else {
                $caseinfo['cloff'] = $data['claimant']->fulladdress;
            }
            $caseinfo['clname'] = $data['claimant']->name;
            $caseinfo['clemail'] = $data['claimant']->userEmail;
            $caseinfo['clmob'] = $data['claimant']->userPhone;

            if (isset($data['responding'])) {
                $caseinfo['respname'] = "";
                $caseinfo['respadd'] = "";
                $caseinfo['respemail'] = "";
                $caseinfo['respmob'] = "";

                foreach ($data['responding'] as $k => $v) {

                    if ($k == 0) {

                        $caseinfo['respname'] = $v->name;
                        if ($v->fulladdress == null) {
                            $caseinfo['respadd'] = $v->address1 . ',' . $v->address2 . ',' . $v->city . ',' . $v->pincode . ',' . $v->state . ',' . $v->country;
                        } else {
                            $caseinfo['respadd'] = $v->fulladdress;
                        }
                        $caseinfo['respemail'] = $v->userEmail;
                        $caseinfo['respmob'] = $v->userPhone;
                    }
                }
            }
            $caseinfo['doc'] = $data['case']->disputeCategory;
            $caseinfo['nature'] = $data['case']->natureOfAgreement;
            $caseinfo['adate'] = $data['case']->agreementDate;
            $caseinfo['amt'] = $data['case']->amount;
            if (isset($data['inviation_file'])) {
                $invdate = new DateTime($data['inviation_file']->created_at);
                $caseinfo['invdate'] =  $invdate->format('d-m-Y H:i:s');
            }
            $caseinfo['medname'] = isset($data['mediator']) ? strtoupper($data['mediator']->first_name) . " " . strtoupper($data['mediator']->last_name) : "";

            $data['emailtrck'] = EmailTrack::getByCaseIdAndEvent($value, "ACPTARB_ADM_RES", $caseinfo['respemail']);
            $data['whatsapptrck'] = WhatsappTrack::getByCaseIdWhAndEvent($value, "ACPTARB_ADM_RES",  $caseinfo['respmob']);
            $caseinfo['invets'] = "";
            $caseinfo['invetd'] = "";
            $caseinfo['inveds'] = "";
            $caseinfo['invedd'] = "";
            $caseinfo['invers'] = "";
            $caseinfo['inverd'] = "";
            if (isset($data['emailtrck'])) {
                $time = new DateTime($data['emailtrck']->created_at);
                $time->setTimezone(new DateTimeZone('Asia/Kolkata'));
                $caseinfo['invets'] = "transmitted";
                $caseinfo['invetd'] = $time->format('d-m-Y H:i:s');
                if (isset($data['emailtrck']['track_data'])) {
                    foreach ($data['emailtrck']['track_data'] as $etrck) {
                        if ($etrck->event  == "delivered") {
                            $edate1 = new DateTime($etrck->created_at);
                            $caseinfo['inveds'] = "delivered";
                            $caseinfo['invedd'] = $edate1->format('d-m-Y H:i:s');
                        } else if ($etrck->event  == "open") {
                            $edate1 = new DateTime($etrck->created_at);
                            $caseinfo['invers'] = "read";
                            $caseinfo['inverd'] = $edate1->format('d-m-Y H:i:s');
                        }
                    }
                }
                if ($caseinfo['invers'] != "" && $caseinfo['inveds'] == "") {
                    $caseinfo['inveds'] = "delivered";
                    $caseinfo['invedd'] = $caseinfo['inverd'];
                }
            }
            $caseinfo['invwts'] = "";
            $caseinfo['invwtd'] = "";
            $caseinfo['invwds'] = "";
            $caseinfo['invwdd'] = "";
            $caseinfo['invwrs'] = "";
            $caseinfo['invwrd'] = "";
            if (isset($data['whatsapptrck'])) {
                $time = new DateTime($data['whatsapptrck']->created_at);
                $time->setTimezone(new DateTimeZone('Asia/Kolkata'));
                $caseinfo['invwts'] = "transmitted";
                $caseinfo['invwtd'] = $time->format('d-m-Y H:i:s');
                if (isset($data['whatsapptrck']['whatsapp_log'])) {
                    foreach ($data['whatsapptrck']['whatsapp_log'] as $wtrck) {
                        if (strtolower($wtrck->status)  == "delivered") {
                            $time = new DateTime($wtrck->updated_time, new DateTimeZone('UTC'));
                            $time->setTimezone(new DateTimeZone('Asia/Kolkata'));
                            $caseinfo['invwds'] = "delivered";
                            $caseinfo['invwdd'] = $time->format('d-m-Y H:i:s');
                        } else if (strtolower($wtrck->status)  == "read") {
                            $time = new DateTime($wtrck->updated_time, new DateTimeZone('UTC'));
                            $time->setTimezone(new DateTimeZone('Asia/Kolkata'));
                            $caseinfo['invwrs'] = "read";
                            $caseinfo['invwrd'] = $time->format('d-m-Y H:i:s');
                        }
                    }
                }


                if ($caseinfo['invwrs'] != "" && $caseinfo['invwds'] == "") {
                    $caseinfo['invwds'] = "delivered";
                    $caseinfo['invwdd'] = $caseinfo['invwrd'];
                }
            }
            for ($i = 1; $i < $forloopcnt; $i++) {
                $caseinfo['erespemail' . $i] = "";
                $caseinfo['erespmob' . $i] = "";
                $caseinfo['einvets' . $i] = "";
                $caseinfo['einvetd' . $i] = "";
                $caseinfo['einveds' . $i] = "";
                $caseinfo['einvedd' . $i] = "";
                $caseinfo['einvers' . $i] = "";
                $caseinfo['einverd' . $i] = "";
                $caseinfo['einvwts' . $i] = "";
                $caseinfo['einvwtd' . $i] = "";
                $caseinfo['einvwds' . $i] = "";
                $caseinfo['einvwdd' . $i] = "";
                $caseinfo['einvwrs' . $i] = "";
                $caseinfo['einvwrd' . $i] = "";
            }

            if (isset($data['responding'])) {
                foreach ($data['responding'] as $k => $v) {
                    if ($k != 0) {
                        $caseinfo['erespemail' . $k] = $v->userEmail;
                        $caseinfo['erespmob' . $k] = $v->userPhone;
                        $data['eemailtrck'] = EmailTrack::getByCaseIdAndEvent($value, "ACPTARB_ADM_RES", $v->userEmail);
                        $data['ewhatsapptrck'] = WhatsappTrack::getByCaseIdWhAndEvent($value, "ACPTARB_ADM_RES",  $v->userPhone);
                        if (isset($data['eemailtrck'])) {
                            $time = new DateTime($data['eemailtrck']->created_at);
                            $time->setTimezone(new DateTimeZone('Asia/Kolkata'));
                            $caseinfo['einvets' . $k] = "transmitted";
                            $caseinfo['einvetd' . $k] = $time->format('d-m-Y H:i:s');
                            if (isset($data['eemailtrck']['track_data'])) {
                                foreach ($data['eemailtrck']['track_data'] as $etrck) {
                                    if ($etrck->event  == "delivered") {
                                        $edate1 = new DateTime($etrck->created_at);
                                        $caseinfo['einveds' . $k] = "delivered";
                                        $caseinfo['einvedd' . $k] = $edate1->format('d-m-Y H:i:s');
                                    } else if ($etrck->event  == "open") {
                                        $edate1 = new DateTime($etrck->created_at);
                                        $caseinfo['einvers' . $k] = "read";
                                        $caseinfo['einverd' . $k] = $edate1->format('d-m-Y H:i:s');
                                    }
                                }
                            }
                            if ($caseinfo['einvers' . $k] != "" && $caseinfo['einveds' . $k] == "") {
                                $caseinfo['einveds' . $k] = "delivered";
                                $caseinfo['einvedd' . $k] = $caseinfo['einverd' . $k];
                            }
                        }
                        if (isset($data['ewhatsapptrck'])) {
                            $time = new DateTime($data['ewhatsapptrck']->created_at);
                            $time->setTimezone(new DateTimeZone('Asia/Kolkata'));
                            $caseinfo['einvwts' . $k] = "transmitted";
                            $caseinfo['einvwtd' . $k] = $time->format('d-m-Y H:i:s');
                            if (isset($data['ewhatsapptrck']['whatsapp_log'])) {

                                foreach ($data['ewhatsapptrck']['whatsapp_log'] as $wtrck) {
                                    if (strtolower($wtrck->status)  == "delivered") {
                                        $time = new DateTime($wtrck->updated_time, new DateTimeZone('UTC'));
                                        $time->setTimezone(new DateTimeZone('Asia/Kolkata'));
                                        $caseinfo['einvwds' . $k] = "delivered";
                                        $caseinfo['einvwdd' . $k] = $time->format('d-m-Y H:i:s');
                                    } else if (strtolower($wtrck->status)  == "read") {
                                        $time = new DateTime($wtrck->updated_time, new DateTimeZone('UTC'));
                                        $time->setTimezone(new DateTimeZone('Asia/Kolkata'));
                                        $caseinfo['einvwrs' . $k] = "read";
                                        $caseinfo['einvwrd' . $k] = $time->format('d-m-Y H:i:s');
                                    }
                                }
                            }
                            // foreach ($data['ewhatsapptrck'] as $wtrck) {
                            //     if ($wtrck->status  == "delivered") {
                            //         $time = new DateTime($wtrck->updated_time, new DateTimeZone('UTC'));
                            //         $time->setTimezone(new DateTimeZone('Asia/Kolkata'));
                            //         $caseinfo['einvwds' . $k] = "delivered";
                            //         $caseinfo['einvwdd' . $k] = $time->format('d-m-Y H:i:s');
                            //     } else if ($wtrck->status  == "read") {
                            //         $time = new DateTime($wtrck->updated_time, new DateTimeZone('UTC'));
                            //         $time->setTimezone(new DateTimeZone('Asia/Kolkata'));
                            //         $caseinfo['einvwrs' . $k] = "read";
                            //         $caseinfo['einvwrd' . $k] = $time->format('d-m-Y H:i:s');
                            //     }
                            // }
                            if ($caseinfo['einvwrs' . $k] != "" && $caseinfo['einvwds' . $k] == "") {
                                $caseinfo['einvwds' . $k] = "delivered";
                                $caseinfo['einvwdd' . $k] = $caseinfo['einvwrd' . $k];
                            }
                        }
                    }
                }
            }

            $data = [];
            $data['auth'] = "MED360AUTH";
            $data['app'] = "P360MED";
            $data['caseid'] = $value;
            $url = "https://presolv360.com/functions/ivrtrack.php";

            $ivr = json_decode(Curl::getdata($url, $data, 'POST', 'MED360AUTH'), true);
            if ($ivr['code'] != '200') {
                $ivr = [];
            } else {
                $ivr = $ivr['data'];
            }
            $caseinfo['ivrs'] = "";
            $caseinfo['ivrdate'] = "";
            foreach ($ivr as $key => $value) {
                $time = new DateTime($value['created_at']);
                $caseinfo['ivrs'] = $value['status'];
                $caseinfo['ivrdate'] = $time->format('d-m-Y H:i:s');
            }
            $rowData = '';
            foreach ($caseinfo as $value) {

                $value = '"' . $value . '"' . "\t";

                $rowData .= $value;
            }
            $setData .= trim($rowData) . "\n";
        }

        $content = ucwords($columnHeader) . "\n" . $setData . "\n";
        $file_name = "invitationdeliverdsheet.xls";

        return response($content, 200, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $file_name . '"',
        ]);

        // ---------------------old file log excel download-----------------------------------
        // dd($request->all());
        // $caseinfo = [];

        // $columnHeader = '';
        // $columnHeader =  "Sr. No." . "\t" . "Case ID" . "\t" . "Reference ID" . "\t" . "Date of Invoking Mediation" . "\t" . "Initiating Organization Name" . "\t" .
        //     "Initiating Registered Office" . "\t" . "Initiating Full Name" . "\t" . "Initiating Email ID" . "\t" . "Initiating WhatsApp / Mobile Number" . "\t" . "Full name of Primary Respondent" . "\t" .
        //     "Full Address of Primary Respondent" . "\t" . "Email ID of Primary Respondent" . "\t" . "WhatsApp / Mobile Number of Primary Respondent (10 digit)" . "\t" . "Enter each additional respondent's name, status (eg.: co-borrower, guarantor), address, email ID and mobile number (leave blank if no additional respondent)" . "\t" .
        //     "Dispute Category" . "\t" . "Nature of agreement" . "\t" . "Agreement date" . "\t" .  "Disputed amount" . "\t" . "Date of Invitation" . "\t" . "Name of Mediator" . "\t" .
        //     "Invitation email delivery status" . "\t" . "Invitation email delivery date" . "\t" . "Invitation email read status" . "\t" . "Invitation email read date" . "\t" . "Invitation whatsapp delivery status" . "\t" . "Invitation whatsapp delivery date" . "\t" . "Invitation whatsapp read status" . "\t" . "Invitation whatsapp read date" . "\t" .  "Ivr log status" . "\t" . "Ivr log Date" . "\t\n";
        // $setData = '';
        // $caseid = explode(',', trim($request->ids));

        // foreach ($caseid as $key => $value) {
        //     $data['case'] = MedCase::find($value);
        //     // dd($data);
        //     $data['claimant'] = InvoledUser::select('user_involved_in_agreement.*', 'users.organization')->where('isClaimant', 0)->leftJoin('users', 'users.id', '=', 'user_involved_in_agreement.userId')->where('userPlanId', $value)->first();
        //     $data['responding'] = InvoledUser::where('isClaimant', '!=', 0)->where('userPlanId', $value)->get();
        //     $data['inviation_file'] = InvitationFiles::where('case_id', $value)->orderByDesc('id')->limit(1)->first();
        //     $data['mediator'] = Mediators_mediation_cases_status::select("email", "username", "mobile_number", "first_name", "last_name")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
        //         ->where("mediators_mediation_cases_status.mediation_case_id", "=", $value)
        //         ->first();

        //     $caseinfo['srno'] = $key + 1;
        //     $caseinfo['caseid'] = 'M' . sprintf('%06d', $value);
        //     $caseinfo['refid'] = $data['case']->ref_id;
        //     $datearb = new DateTime($data['case']->created_at);
        //     $caseinfo['datearb'] = $datearb->format('d-m-Y H:i:s');
        //     $caseinfo['clorg'] = $data['claimant']->organization;
        //     if ($data['claimant']->fulladdress == null) {
        //         $caseinfo['cloff'] = $data['claimant']->address1 . ',' . $data['claimant']->address2 . ',' . $data['claimant']->city . ',' . $data['claimant']->pincode . ',' . $data['claimant']->state . ',' . $data['claimant']->country;
        //     } else {
        //         $caseinfo['cloff'] = $data['claimant']->fulladdress;
        //     }
        //     $caseinfo['clname'] = $data['claimant']->name;
        //     $caseinfo['clemail'] = $data['claimant']->userEmail;
        //     $caseinfo['clmob'] = $data['claimant']->userPhone;

        //     if (isset($data['responding'])) {
        //         $caseinfo['respname'] = "";
        //         $caseinfo['respadd'] = "";
        //         $caseinfo['respemail'] = "";
        //         $caseinfo['respmob'] = "";
        //         $caseinfo['otherresp'] = "";

        //         foreach ($data['responding'] as $k => $v) {

        //             if ($k == 0) {

        //                 $caseinfo['respname'] = $v->name;
        //                 if ($v->fulladdress == null) {
        //                     $caseinfo['respadd'] = $v->address1 . ',' . $v->address2 . ',' . $v->city . ',' . $v->pincode . ',' . $v->state . ',' . $v->country;
        //                 } else {
        //                     $caseinfo['respadd'] = $v->fulladdress;
        //                 }
        //                 $caseinfo['respemail'] = $v->userEmail;
        //                 $caseinfo['respmob'] = $v->userPhone;
        //             } else {
        //                 if ($caseinfo['otherresp'] == "") {
        //                     $caseinfo['otherresp'] = $v->userEmail . ',' . $v->userPhone;
        //                 } else {
        //                     $caseinfo['otherresp'] = $caseinfo['otherresp'] . ' | ' . $v->userEmail . ',' . $v->userPhone;
        //                 }
        //             }
        //         }
        //     }
        //     $caseinfo['doc'] = $data['case']->disputeCategory;
        //     $caseinfo['nature'] = $data['case']->natureOfAgreement;
        //     $caseinfo['adate'] = $data['case']->agreementDate;
        //     $caseinfo['amt'] = $data['case']->amount;
        //     if (isset($data['inviation_file'])) {
        //         $invdate = new DateTime($data['inviation_file']->created_at);
        //         $caseinfo['invdate'] =  $invdate->format('d-m-Y H:i:s');
        //     }
        //     $caseinfo['medname'] = strtoupper($data['mediator']->first_name) . " " . strtoupper($data['mediator']->last_name);

        //     $data['emailtrck'] = EmailTrack::getByCaseIdAndEvent($value, "ACPTARB_ADM_RES", $caseinfo['respemail']);
        //     $data['whatsapptrck'] = WhatsappTrack::getByCaseIdWhAndEvent($value, "ACPTARB_ADM_RES",  $caseinfo['respmob']);
        //     $caseinfo['inveds'] = "";
        //     $caseinfo['invedd'] = "";
        //     $caseinfo['invers'] = "";
        //     $caseinfo['inverd'] = "";
        //     if (isset($data['emailtrck'])) {
        //         foreach ($data['emailtrck'] as $etrck) {
        //             if ($etrck->event  == "delivered") {
        //                 $edate1 = new DateTime($etrck->created_at);
        //                 $caseinfo['inveds'] = "delivered";
        //                 $caseinfo['invedd'] = $edate1->format('d-m-Y H:i:s');
        //             } else if ($etrck->event  == "open") {
        //                 $edate1 = new DateTime($etrck->created_at);
        //                 $caseinfo['invers'] = "read";
        //                 $caseinfo['inverd'] = $edate1->format('d-m-Y H:i:s');
        //             }
        //         }
        //     }
        //     $caseinfo['invwds'] = "";
        //     $caseinfo['invwdd'] = "";
        //     $caseinfo['invwrs'] = "";
        //     $caseinfo['invwrd'] = "";
        //     if (isset($data['whatsapptrck'])) {
        //         foreach ($data['whatsapptrck'] as $wtrck) {
        //             if ($wtrck->status  == "delivered") {
        //                 $time = new DateTime($wtrck->updated_time, new DateTimeZone('UTC'));
        //                 $time->setTimezone(new DateTimeZone('Asia/Kolkata'));
        //                 $caseinfo['invwds'] = "delivered";
        //                 $caseinfo['invwdd'] = $time->format('d-m-Y H:i:s');
        //             } else if ($wtrck->status  == "read") {
        //                 $time = new DateTime($wtrck->updated_time, new DateTimeZone('UTC'));
        //                 $time->setTimezone(new DateTimeZone('Asia/Kolkata'));
        //                 $caseinfo['invwrs'] = "read";
        //                 $caseinfo['invwrd'] = $time->format('d-m-Y H:i:s');
        //             }
        //         }
        //     }

        //     $data = [];
        //     $data['auth'] = "MED360AUTH";
        //     $data['app'] = "P360MED";
        //     $data['caseid'] = $value;
        //     $url = "https://presolv360.com/functions/ivrtrack.php";

        //     $ivr = json_decode(Curl::getdata($url, $data, 'POST', 'MED360AUTH'), true);
        //     if ($ivr['code'] != '200') {
        //         $ivr = [];
        //     } else {
        //         $ivr = $ivr['data'];
        //     }
        //     $caseinfo['ivrs'] ="";
        //     $caseinfo['ivrdate'] ="";
        //     foreach($ivr as $key => $value) {
        //         $time = new DateTime($value['created_at']);
        //         $caseinfo['ivrs'] = $value['status'];
        //         $caseinfo['ivrdate'] = $time->format('d-m-Y H:i:s');
        //     }
        //     $rowData = '';
        //     foreach ($caseinfo as $value) {

        //         $value = '"' . $value . '"' . "\t";

        //         $rowData .= $value;
        //     }
        //     $setData .= trim($rowData) . "\n";
        // }

        // $content = ucwords($columnHeader) . "\n" . $setData . "\n";
        // $file_name = "invitationdeliverdsheet.xls";

        // return response($content, 200, [
        //     'Content-Type' => 'application/octet-stream',
        //     'Content-Disposition' => 'attachment; filename="' . $file_name . '"',
        // ]);
    }

    public function BatchWiseApprove(Request $request)
    {
    //  /dd($request->all());

        // return ($request->all());
        // dd($request->mediator);
        // $caseforapprove = MedCase::where('batch_id', $request->batch_id)->where("confirm_status", "=", 0)->take(10)->get();

        // return $caseforapprove;
        // if(count($caseforapprove) != 0) {
        // return response()->json('Data found');
        // foreach($caseforapprove as $value) {

        // dd($logdata);

        $inv = InvoledUser::select('user_involved_in_agreement.*', 'users.address as useraddress', 'users.address1 as useraddress1', 'users.pincode as userpincode', 'users.city as usercity', 'users.state as userstate', 'users.country as usercountry')
            ->leftJoin("users", "users.id", "=", "user_involved_in_agreement.userId")
            ->where(['user_involved_in_agreement.userPlanid' => $request->id])->where('user_involved_in_agreement.isClaimant', 0)->first();

        if ($inv->address1 != null || $inv->useraddress != null) {

            $data = Mediators_mediation_cases_status::where("mediation_case_id", "=", $request->id)
                ->where(function ($q) {
                    $q->where("status", "=", 0)
                        ->orWhere("status", "=", 1);
                })
                ->count();
            if ($data == 0) {
                Mediators_mediation_cases_status::create([
                    'mediator_id' => $request->mediator,
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
                $MedCaseStatus->mediator_id = $request->mediator;
                $MedCaseStatus->status = 0;
                $MedCaseStatus->save();
            }

            // start for re-approve ------------

            $invitation = $this->mediator_appointment($request->id, $request->mediator);

            $invmodel = InvitationFiles::where('case_id', $request->id)->orderByDesc('id')->limit(1)->first();

            if (!isset($invmodel)) {
                $invmodel = new InvitationFiles();
            }
            $invmodel->case_id = $request->id;
            $invmodel->file_name_mediator_appointment = $invitation;
            $invmodel->save();
            //// Common_function::MedNotification($request->id, "MEDI_ADD_ADM", Auth::user()->id);

            // end for re-approve ------------


            $medCas = MedCase::find($request->id);
            $medCas->confirm_status = 1;
            $medCas->case_status = 1;
             /*** Discussion field : START ***/
             if(isset($request->discussion) && $request->discussion != ""){
                $medCas->discussion = $request->discussion;
             }
            
             /*** Discussion field : END ***/
            if ($medCas->save()) {
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

                // start for re-approve ------------

                $this->sned_invitation($request->id, $invitation, $medCas->bulk_flag);
                // end for re-approve ------------



                if ($request->logId != null) {
                    $logdata = BulkLog::find($request->logId);

                    if ($logdata->inserted_row == null) {
                        $logdata->inserted_row = $request->id;
                    } else {
                        $logdata->inserted_row = $logdata->inserted_row . ',' . $request->id;
                    }
                    $logdata->save();
                }
                return json_encode(['code' => 200, 'response' => 'success', 'log_id' => $request->logId, "msg" => "midater Added", "caseid" => $request->id]);

                // return response()->json(["msg" => "midater Added", 'status' => 'success']);


            } else {
                if ($request->logId != null) {
                    $logdata = BulkLog::find($request->logId);

                    if ($logdata->failed_row == null) {
                        $logdata->failed_row = $request->id;
                    } else {
                        $logdata->failed_row = $logdata->failed_row . ',' . $request->id;
                    }
                    $logdata->save();
                }
                return json_encode(['code' => 200, 'response' => 'error', 'log_id' => $request->logId, "caseid" => $request->id]);
            }
        } else {
            return json_encode(['code' => 200, 'response' => 'error', 'log_id' => $request->logId != null ? $request->logId : '', 'caseid' => $request->id, 'msg' => 'Address not-found of Initiating Party']);
        }

        // dd("if");
        // } else {
        //     return response()->json(['msg'=>'Data not found', 'status' => 'error']);
        // }
    }

    public function CountBatchWiseApprove(Request $request)
    {
        $totalcount = MedCase::where('batch_id', $request->batch_id)->where("confirm_status", "=", 0)->count();

        // $caseforapprove = MedCase::where('batch_id', $request->batch_id)->where("confirm_status", "=", 0)->take(10)->get();

        return $totalcount;
    }

    public function GetBatchWiseApprove(Request $request)
    {
        // dd($request->all());
        $caseforapprove = MedCase::where('batch_id', $request->batch_id)->where("confirm_status", "=", 0)->take(env('NO_OF_REQUEST_SEND', 10))->get();
        // dd($caseforapprove);
        if ($request->logId == null) {
            $allcids = array();
            foreach ($caseforapprove as $value) {
                array_push($allcids, $value->id);
            }
            $allcids = implode(',', $allcids);
            $log = BulkLog::create([
                "selected_ids" => $allcids,
                "uploaded_by" => Auth::user()->id,
                "total_row" => count($caseforapprove),
                "log_type" => isset($_POST['log_type']) ? $_POST['log_type'] : "",
                "updated_at" => date('Y-m-d H:i:s'),
            ]);
            $log_id = $log->id;
            if ($request->notiId == null) {
                $noti = [
                    'uploaded_by' => Auth::user()->id,
                    'case_id' => $allcids,
                    'event' => "ACPTARB_ADM",
                    'mediator_id' => $request->mediator,
                    'user_id' => null,
                ];
                $notidata = Notification::create($noti);
                // Common_function::MedNotification($request->id, "ACPTARB_ADM", Auth::user()->id, $request->mediator, null);
            } else {
                $notidata = Notification::find($request->notiId);
                $notidata->case_id = $notidata->case_id . ',' . $allcids;
                $notidata->save();
            }
            return json_encode(['code' => 200, 'response' => 'success', 'log_id' => $log_id, 'data' => $caseforapprove, 'notiId' => $notidata->id]);
        } else {
            $data = BulkLog::find($request->logId);
            $allcids = array();
            foreach ($caseforapprove as $value) {
                array_push($allcids, $value->id);
            }
            $allcids = implode(',', $allcids);
            $data->selected_ids = $data->selected_ids . ',' . $allcids;
            $data->total_row = $data->total_row + count($caseforapprove);

            // dd($data->selected_ids);
            $data->save();
            if ($request->notiId == null) {
                $noti = [
                    'uploaded_by' => Auth::user()->id,
                    'case_id' => $allcids,
                    'event' => "ACPTARB_ADM",
                    'mediator_id' => $request->mediator,
                    'user_id' => null,
                ];
                $notidata = Notification::create($noti);
                // Common_function::MedNotification($request->id, "ACPTARB_ADM", Auth::user()->id, $request->mediator, null);
            } else {
                $notidata = Notification::find($request->notiId);
                $notidata->case_id = $notidata->case_id . ',' . $allcids;
                $notidata->save();
            }
            return json_encode(['code' => 200, 'response' => 'success', 'log_id' => $request->logId, 'data' => $caseforapprove, 'notiId' => $notidata->id]);
        }
    }

    public function confirmStatusWithMidaterAdd(Request $request)
    {
        // dd($request->all());
        if ($request->logId == null) {
            // foreach ($caseforapprove as $value) {
            //     array_push($allcids, $value->id);
            // }
            // $allcids = implode(',', $request->allcids);
            // dd(count($request->allcids));
            $log = BulkLog::create([
                "selected_ids" => $request->allcids,
                "uploaded_by" => Auth::user()->id,
                "total_row" => $request->total_row,
                "log_type" => isset($_POST['log_type']) ? $_POST['log_type'] : "",
                "updated_at" => date('Y-m-d H:i:s'),
                "inserted_row" => $request->insertRow,
                "failed_row" => $request->faildRow,
            ]);
            $log_id = $log->id;
            if ($request->notiId == null) {
                $noti = [
                    'uploaded_by' => Auth::user()->id,
                    'case_id' => $request->insertRow,
                    'event' => "ACPTARB_ADM",
                    'mediator_id' => $request->mediator,
                    'user_id' => null,
                ];
                $notidata = Notification::create($noti);
                // Common_function::MedNotification($request->id, "ACPTARB_ADM", Auth::user()->id, $request->mediator, null);
            } else {
                $notidata = Notification::find($request->notiId);
                $notidata->case_id = $request->insertRow;
                $notidata->save();
            }
            return json_encode(['code' => 200, 'response' => 'success', 'log_id' => $log_id, 'notiId' => $notidata->id]);
        } else {
            $data = BulkLog::find($request->logId);

            $data->inserted_row =  $request->insertRow;
            $data->failed_row =  $request->faildRow;

            $data->save();
            if ($request->notiId == null) {
                $noti = [
                    'uploaded_by' => Auth::user()->id,
                    'case_id' => $request->insertRow,
                    'event' => "ACPTARB_ADM",
                    'mediator_id' => $request->mediator,
                    'user_id' => null,
                ];
                $notidata = Notification::create($noti);
                // Common_function::MedNotification($request->id, "ACPTARB_ADM", Auth::user()->id, $request->mediator, null);
            } else {
                $notidata = Notification::find($request->notiId);
                $notidata->case_id = $request->insertRow;
                $notidata->save();
            }
            return json_encode(['code' => 200, 'response' => 'success', 'log_id' => $request->logId, 'notiId' => $notidata->id]);
        }
    }

    public function CourierCSVUpload(Request $request)
    {
        // dd($request->all());
        $selectCsv = $request->file('csv');
        $tmpName = $selectCsv->getPathname();
        $ext = pathinfo($selectCsv->getClientOriginalName(), PATHINFO_EXTENSION);
        $errormsg = '';
        // dd($ext);

        if ($ext != 'csv') {
            $errormsg .= 'Please upload csv file';
        }

        if ($errormsg != '') {
            return response()->json(["type" => "error", "code" => 200, "message" => $errormsg]);
        } else {
            $csv = $this->csvToArray($tmpName);
            // dd($csv);
            foreach ($csv as $key => $v) {
                $courierCaseId = str_replace("M", "", $v[0]);
                $courierCaseId = sprintf("%0d", $courierCaseId);
                $insertarray['case_id'] = $courierCaseId;
                $insertarray['noticeId'] = $v[0];
                $insertarray['awb_no'] = $v[1];
                $insertarray['status'] = $v[2];
                $insertarray['status_as_on_date'] = $v[3];
                $insertarray['status_at'] = $v[4];
                $insertarray['last_activity'] = $v[5];
                $insertarray['reason'] = $v[6];
                $insertarray['final_status'] = $v[7];
                $insertarray['type'] = $request->type;

                // $insertarray_res = Couriercsv::insertGetId($insertarray);
                CourierCsv::create($insertarray);
            }
            return response()->json(["type" => "success", "code" => 200]);
        }
        return response()->json(["type" => "error", "code" => 200, "message" => "Try Again"]);
    }

    public function CourierZIPUpload(Request $request)
    {
        $selectZip = $request->file('zip');
        $tmpName = $selectZip->getPathname();
        $ext = pathinfo($selectZip->getClientOriginalName(), PATHINFO_EXTENSION);
        $fullname = $selectZip->getClientOriginalName();
        $errormsg = '';
        if ($selectZip) {
            if ($ext != 'zip') {
                $errormsg .= 'Please upload zip file';
            }

            if ($errormsg != '') {
                return response()->json(["type" => "error", "code" => 200, "message" => $errormsg]);
            } else {
                $fileNameArr = explode(".", $_FILES['zip']['name']);

                $zipName = $fileNameArr[0];

                // dd($zipName);
                $zip = new \ZipArchive();
                if ($zip->open($tmpName) === TRUE) {
                    $rand = rand(111111, 9999999999);
                    $target_dir = storage_path() . '/app/public/zipCourier/';

                    $zip->extractTo($target_dir . $rand . '/' . $zipName);
                    $zip->close();

                    $files = scandir($target_dir . $rand . '/' . $zipName);
                    $msg = '';
                    foreach ($files as $list) {
                        if (pathinfo($list, PATHINFO_EXTENSION) == 'pdf' || strlen($list) > 4) {
                            $basefilename = pathinfo($list, PATHINFO_FILENAME);
                            $extension = pathinfo($list, PATHINFO_EXTENSION);
                            // dd($basefilename);
                            $explodeArray = Common_function::getBetween($list, '_', '.');
                            $courierCaseId = str_replace("M", "", $explodeArray[1]);
                            $courierCaseId = sprintf("%0d", $courierCaseId);


                            // $fileparty = substr($explodeArray[0], -1);
                            // $fileparty = preg_replace("/[a-zA-Z]/", "", $explodeArray[0]);

                            if (isset($explodeArray[2])) {
                                $array1['noticeId'] = $explodeArray[1] . "_" . $explodeArray[2];
                            } else {
                                $array1['noticeId'] = $explodeArray[1];
                            }
                            $csvdata = CourierCsv::where('noticeId', $array1['noticeId'])->where('type', $request->type)->where('pdf_uploaded', 0)->first();
                            if (!$csvdata) {
                                $errormsg .= "Please First upload csv file";
                                return response()->json(["type" => "error", "code" => 200, "message" => $errormsg]);
                            } else {
                                $casedetails = MedCase::find($courierCaseId);
                                if ($casedetails) {
                                    $savefilename = $basefilename . '_' . time() . '.' . $extension;
                                    $s3target_dir = 'mediation_documents/mediation/' . $courierCaseId . '/courier_pdf';
                                    $s3target_file = $s3target_dir . '/' . $savefilename;
                                    $uploadS33 = Storage::disk('s3')->put($s3target_file, file_get_contents($target_dir . $rand . '/' . $zipName . '/' . $list));
                                    if ($uploadS33) {
                                        // dd($fileparty);
                                        $array1['case_id'] = $courierCaseId;
                                        $array1['file_name'] = $savefilename;
                                        $array1['type'] = $request->type;
                                        $array1['csv_id'] = $csvdata->id;
                                        if (CourierPdf::create($array1)) {
                                            $csvdata->pdf_uploaded = 1;
                                            $csvdata->save();
                                        }
                                    }
                                }
                            }

                            unlink($target_dir . $rand . '/' . $zipName . '/' . $list);
                        }
                    }
                    rmdir($target_dir . $rand . '/' . $zipName);
                    rmdir($target_dir . $rand);
                }

                return response()->json(["type" => "success", "code" => 200]);
            }
        }
    }



    /******************** Custom Send Session Zoom  **********************/
    public function sned_session_invitation($url, $id, $email_id, $email_name, $date, $userPhone, $invitation)
    {
        $mid = "M" . sprintf("%06d", $id);
        $d = [
            'event' => 'SESS_SCHE',
            'case_id' => $id,
        ];
        if ($email_id != "") {
            SendGrid::send($d, $email_id, $invitation, ["-caseid-" => $mid, "-insert_date-" => $date, "-type-" => "Party"], $email_name);
        }
        if ($userPhone != "") {

            $varjson = ['sessionDteaTime' => $date, 'caseid' => $mid, 'zoomid' => $invitation];
            $var = ['-dt-', '-cid-', '-link-'];
            $var1 = [$date, $mid, $invitation];
            $content1 = WaTemplate::getcontent('l10_session_schedule');
            $content = str_replace($var, $var1, $content1);
            $dwa1 = [
                'caseid' => $id,
                'contact' =>  $userPhone,
                'content' => ['text' => $content],
                'event' => 'SESS_SCHE',
                'varjson' => $varjson,
                'haptik_tmp' => 'l10_session_schedule',

            ];
            

            $access = Whatsapp::sendWaSmessage($dwa1);
        }
        return true;
    }
    /******************** Custom Send Session Zoom  **********************/
}
