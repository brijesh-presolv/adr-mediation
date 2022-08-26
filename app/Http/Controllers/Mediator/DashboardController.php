<?php

namespace App\Http\Controllers\Mediator;

use Auth;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Common_function;
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
use App\Http\Traits\UploadTrait;
use App\Models\BulkLog;
use App\Models\Mediators_mediation_cases_status;
use App\Models\Notification;
use App\Models\WaTemplate;
use DB;
use Illuminate\Support\Facades\File;
use PDF;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
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

    public function Notification()
    {
        $view = Notification::where('view_mediator', 0)->where('mediator_id', Auth::user()->id)->get();
        foreach ($view as $item) {
            $item->view_mediator = 1;
            $item->save();
        }
        $data = Notification::mediatornotificationData();
        // dd($data);
        return view('mediator.notification', compact('data'));
    }

    public function newrequest()
    {
        $mediationDetails = Mediation_Details::select('mediation_details.*', 'users.first_name', 'users.last_name')->leftJoin('users', 'users.id', '=', 'mediation_details.user_id')->where("mediation_details.user_id", "=", Auth::user()->id)->first();
        return view('mediator.newrequest', compact('mediationDetails'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function newjson()
    {

        $draw = $_POST['sEcho'];
        $row = $_POST['iDisplayStart'];
        $rowperpage = $_POST['iDisplayLength']; // Rows display per page
        $indexColumn = $_POST['iSortCol_0'];
        $columnName = $_POST['mDataProp_' . $indexColumn]; // Column name
        $columnSortOrder = $_POST['sSortDir_0']; // asc or desc

        $searchValue = $_POST['sSearch'];
        $loginUser = Auth::user()->id;
        $newrequestData = MedCase::newrequestDataMediator($searchValue, $columnName, $columnSortOrder, $draw, $row, $rowperpage, $loginUser);
        $newrequestDataCount = MedCase::newrequestDataMediatorCount($searchValue, $loginUser);
        $arraydata = array();


        foreach ($newrequestData as $key => $d) {
            $arraydata[] = [
                "key" => $key + 1,
                "id" => $d->mediation_case_id,
                "party" => InvoledUser::select('user_involved_in_agreement.id', 'user_involved_in_agreement.userPhone', 'user_involved_in_agreement.address1', 'user_involved_in_agreement.address2', 'user_involved_in_agreement.fulladdress', 'user_involved_in_agreement.userEmail', 'user_involved_in_agreement.name', 'user_involved_in_agreement.isOnboarded', 'user_involved_in_agreement.isClaimant', "user_involved_in_agreement.userId", "users.organization")->leftjoin('users', 'users.id', '=', 'user_involved_in_agreement.userId')->where(['userPlanid' => $d->mediation_case_id])->get(),
                "comments" => "tesr",
                "caseId" => $d->mediation_case_id,
                "case_issue" => $d->issue,
                "mediator_id" => $d->mediator_id,
                "date" => date('d-m-Y', strtotime($d->created_at)),
                "private_count" => Mediation_case_comment::where("type", "=", 1)->where('mediation_case_id', $d->mediation_case_id)->count(),
                "private_view_count" => Mediation_case_comment::where("type", "=", 1)->where('mediation_case_id', $d->mediation_case_id)->where('view_mediator', 0)->count(),
                "share_count" => Mediation_case_comment::where("type", "=", 0)->where('mediation_case_id', $d->mediation_case_id)->count(),
                "share_view_count" => Mediation_case_comment::where("type", "=", 0)->where('mediation_case_id', $d->mediation_case_id)->where('view_mediator', 0)->count(),

            ];
        }

        return response()->json(["sEcho" => intval($draw), "iTotalRecords" => $newrequestDataCount, "iTotalDisplayRecords" => $newrequestDataCount, "aaData" => $arraydata]);
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
        // $profileData = User::find($loginUser);
        $profileData = User::select('users.*', 'mediation_details.experience')
            ->leftJoin("mediation_details", "mediation_details.user_id", "=", "users.id")
            ->where('users.id', $loginUser)->first();
        return view('mediator.profile', compact('profileData'));
    }

    /**
     * Status change for accept and reject the new case.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function statusChange(Request $request)
    {
        $inv_id = "";
        $inv = InvoledUser::select('id')->where('userPlanId', $request->mediation_case_id)->get();
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
                if ($request->fsData['status'] == 1 || $request->status == 1) {
                    Common_function::MedNotification($_POST['allcids'], "SEND_APPO_MED", Auth::user()->id, Auth::user()->id, null);
                } else {
                    Common_function::MedNotification($_POST['allcids'], "REJECTED_MED", Auth::user()->id, Auth::user()->id, null);
                }
            }
        } else {
            if ($request->fsData['status'] == 1 || $request->status == 1) {
                Common_function::MedNotification($request->mediation_case_id, "SEND_APPO_MED", Auth::user()->id, Auth::user()->id, $inv_id);
            } else {
                Common_function::MedNotification($request->mediation_case_id, "REJECTED_MED", Auth::user()->id, Auth::user()->id, $inv_id);
            }
        }
        if ($request->fsData['status'] == 1 || $request->status == 1) {
            $caseid = $request->mediation_case_id;
            $consentDisclosures = ConsentDisclosures::where("mediation_case_id", "=", $caseid)->first();
            if (empty($consentDisclosures)) {
                $consentDisclosures = new ConsentDisclosures();
                $consentDisclosures->mediation_case_id = $caseid;
                $consentDisclosures->mediator_id = Auth::user()->id;
                $consentDisclosures->consent1 = ($request->consent1 != null) ? $request->consent1 : $request->fsData['consent1'];
                $consentDisclosures->consent2 = ($request->consent2 != null) ? $request->consent2 : $request->fsData['consent2'];
                $consentDisclosures->consent3 = ($request->consent3 != null) ? $request->consent3 : $request->fsData['consent3'];
                $consentDisclosures->consent4 = ($request->consent4 != null) ? $request->consent4 : $request->fsData['consent4'];
                $consentDisclosures->consent5 = ($request->consent5 != null) ? $request->consent5 : $request->fsData['consent5'];
                $consentDisclosures->particulars1 = ($request->particulars1 != null) ? $request->particulars1 : $request->fsData['particulars1'];
                $consentDisclosures->particulars2 = ($request->particulars2 != null) ? $request->particulars2 : $request->fsData['particulars2'];
                $consentDisclosures->particulars3 = ($request->particulars3 != null) ? $request->particulars3 : $request->fsData['particulars3'];
                $consentDisclosures->particulars4 = ($request->particulars4 != null) ? $request->particulars4 : $request->fsData['particulars4'];
            } else {
                $consentDisclosures->mediation_case_id = $caseid;
                $consentDisclosures->mediator_id = Auth::user()->id;
                $consentDisclosures->consent1 = ($request->consent1 != null) ? $request->consent1 : $request->fsData['consent1'];
                $consentDisclosures->consent2 = ($request->consent2 != null) ? $request->consent2 : $request->fsData['consent2'];
                $consentDisclosures->consent3 = ($request->consent3 != null) ? $request->consent3 : $request->fsData['consent3'];
                $consentDisclosures->consent4 = ($request->consent4 != null) ? $request->consent4 : $request->fsData['consent4'];
                $consentDisclosures->consent5 = ($request->consent5 != null) ? $request->consent5 : $request->fsData['consent5'];
                $consentDisclosures->particulars1 = ($request->particulars1 != null) ? $request->particulars1 : $request->fsData['particulars1'];
                $consentDisclosures->particulars2 = ($request->particulars2 != null) ? $request->particulars2 : $request->fsData['particulars2'];
                $consentDisclosures->particulars3 = ($request->particulars3 != null) ? $request->particulars3 : $request->fsData['particulars3'];
                $consentDisclosures->particulars4 = ($request->particulars4 != null) ? $request->particulars4 : $request->fsData['particulars4'];
            }
            $consentDisclosures->save();
            $this->send_attechment_party($caseid);
        } else {
            $caseid = $request->mediation_case_id;
        }
        $insertbulk = DB::table('mediators_mediation_cases_status')
            ->where('mediator_id', Auth::user()->id)
            ->where('mediation_case_id', $caseid)
            ->update(['status' => ($request->status != null) ?  $request->status : $request->fsData['status'], 'updated_at' => now()]);
        if ($insertbulk) {
            if (isset($_POST['log_id']) && $_POST['log_id'] != "") {
                $success_log = BulkLog::find($_POST['log_id']);


                if ($success_log->inserted_row == null) {
                    $success_log->inserted_row = $request->mediation_case_id;
                    $success_log->save();
                } else {
                    if (isset($_POST['insertRow'])) {

                        $insert_row = $_POST['insertRow'] . "," . $request->mediation_case_id;
                        // dd(json_encode(explode(',', $insert_row)));
                        $success_log->inserted_row = json_encode(explode(',', $insert_row));
                        $success_log->save();
                    }
                }

                return json_encode(['code' => 200, 'response' => 'success', 'log_id' => $_POST['log_id'], 'caseid' => $request->mediation_case_id]);
            } else if (isset($log_id)) {
                $success_log = BulkLog::find($log_id);
                // dd($success_log);

                if ($success_log->inserted_row == null) {
                    $success_log->inserted_row = $request->mediation_case_id;
                    $success_log->save();
                } else {
                    if (isset($_POST['insertRow'])) {

                        $insert_row = $_POST['insertRow'] . "," . $request->mediation_case_id;
                        // dd(json_encode(explode(',', $insert_row)));
                        $success_log->inserted_row = json_encode(explode(',', $insert_row));
                        $success_log->save();
                    }
                }
                return json_encode(['code' => 200, 'response' => 'success', 'log_id' => $log_id, 'caseid' => $request->mediation_case_id]);
            } else {
                return json_encode(['code' => 200, 'response' => 'success', 'caseid' => $request->mediation_case_id]);
            }
        } else {
            if (isset($_POST['log_id']) && $_POST['log_id'] != "") {
                $faild_log = BulkLog::find($_POST['log_id']);
                if ($faild_log->failed_row == null) {
                    $faild_log->failed_row = $request->mediation_case_id;
                    $faild_log->save();
                } else {
                    if (isset($_POST['faildRow'])) {

                        $faild_row = $_POST['faildRow'] . "," . $request->mediation_case_id;
                        // $faild_log->failed_row = $faild_log->failed_row + "," + $request->id;
                        $faild_log->failed_row = json_encode(explode(',', $faild_row));
                        $faild_log->save();
                    }
                }
                return json_encode(['code' => 200, 'response' => 'error', 'log_id' => $_POST['log_id'], 'caseid' => $request->mediation_case_id]);
            } else if (isset($log_id)) {
                $faild_log = BulkLog::find($log_id);
                if ($faild_log->failed_row == null) {
                    $faild_log->failed_row = $request->mediation_case_id;
                    $faild_log->save();
                } else {
                    if (isset($_POST['faildRow'])) {

                        $faild_row = $_POST['faildRow'] . "," . $request->mediation_case_id;
                        // $faild_log->failed_row = $faild_log->failed_row + "," + $request->id;
                        $faild_log->failed_row = json_encode(explode(',', $faild_row));
                        $faild_log->save();
                    }
                }
                return json_encode(['code' => 200, 'response' => 'error', 'log_id' => $log_id, 'caseid' => $request->mediation_case_id]);
            } else {
                return json_encode(['code' => 200, 'response' => 'error', 'caseid' => $request->mediation_case_id]);
            }
        }
    }

    /**
     * create new meeting add session.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function addSession(Request $request)
    {
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
            DB::table('manage_session')->insert($dataToInsert);
            $inv_id = "";
            foreach ($request->session_party_ids as $party_id) {
                $party = InvoledUser::where("userPlanId", $request->caseId)->where("id", $party_id)->first();
                if ($inv_id == "") {
                    $inv_id = $party->id;
                } else {
                    $inv_id = $inv_id . "," . $party->id;
                }
                $this->sned_session($request->zoomId, $request->caseId, $party->userEmail, $party->name, $request->sessionDate . "/" . $time, $party->userPhone);
            }
            $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                ->where("mediators_mediation_cases_status.mediation_case_id", "=", $request->caseId)
                ->where("mediators_mediation_cases_status.status", "=", 1)
                ->first();
            if ($mediator) {
                $id = "M" . sprintf("%06d", $request->caseId);
                SendGrid::send($d, $mediator->email, env('L10_SCHEDULING_OF_SESSION', ''), ["-caseid-" => $id, "-insert_date-" => $request->sessionDate . "/" . $time, "-type-" => "Mediator"], $mediator->username);

                $varjson = ['sessionDteaTime' => $request->sessionDate . "/" . $time, 'caseid' => $id, 'zoomid' => $request->zoomId];
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

                // print_r($dwa1);
                // exit;
                $access = Whatsapp::sendWamessage($dwa1);
            }
            Common_function::MedNotification($request->caseId, "SESS_SCHE_MED", Auth::user()->id, Auth::user()->id, $inv_id);
            return json_encode(['code' => 200, 'response' => 'success']);
        } else {
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
                    Common_function::MedNotification($_POST['allcids'], "SESS_SCHE_MED", Auth::user()->id, Auth::user()->id, null);
                }
            }

            $allParty = InvoledUser::where("userPlanId", $request->caseId)->get();
            $party_ids = array();
            foreach ($allParty as $party) {
                $party_ids[] = $party->userId;
                $this->sned_session(($request->zoomId != null) ? $request->zoomId : $request->fsData['zoomId'], $request->caseId, $party->userEmail, $party->name, ($request->sessionDate != null) ? $request->sessionDate : $request->fsData['sessionDate'] . "/" . $time, $party->userPhone);
            }
            // dd($party_ids);
            $dataToInsert = [
                'case_id' => $request->caseId,
                'session_date' => ($request->sessionDate != null) ? $request->sessionDate : $request->fsData['sessionDate'] . "/" . $time,
                'note' => ($request->note != null) ? $request->note : $request->fsData['note'],
                'zoom_id' => ($request->zoomId != null) ? $request->zoomId : $request->fsData['zoomId'],
                'session_party_ids' => json_encode($party_ids),
                'scheduled_by' => Auth::user()->id,
            ];
            $manage_session = DB::table('manage_session')->insert($dataToInsert);
            $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                ->where("mediators_mediation_cases_status.mediation_case_id", "=", $request->caseId)
                ->where("mediators_mediation_cases_status.status", "=", 1)
                ->first();
            if ($mediator) {
                $id = "M" . sprintf("%06d", $request->caseId);
                SendGrid::send($d, $mediator->email, env('L10_SCHEDULING_OF_SESSION', ''), ["-caseid-" => $id, "-insert_date-" => $request->sessionDate . "/" . $time, "-type-" => "Mediator"], $mediator->username);

                $varjson = ['sessionDteaTime' => $request->sessionDate . "/" . $time, 'caseid' => $id, 'zoomid' => $request->zoomId];
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

                // print_r($dwa1);
                // exit;
                $access = Whatsapp::sendWamessage($dwa1);
            }
            if ($manage_session) {
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

    /**
     * get added session data to view on ongoing.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getAddedSesion(Request $request)
    {

        $sessionData = DB::table('manage_session')->where('scheduled_by', $request->mediator_id)->get();
        $sn = 1;
        foreach ($sessionData as $value) {
            $dataArray = array();
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
            // if(Auth::user()->id == $value->scheduled_by){

            echo "<td>
                <button id='UpdateSession' data-id='" . $value->id . "' data-toggle='modal' data-target='#Session-edit' class='btn btn-sm btn-success px-2'><i class='far fa-edit'></i></button>
                <button id='DeleteSession' data-id='" . $value->id . "' class='btn btn-sm btn-danger mt-1 px-2'><i class='far fa-trash-alt' style='padding: 0px 2px'></i></button>
                </td>";
            // }else{
            //     echo "<td>--</td>";
            // }
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
            ->where('mediator_access', 1)
            ->get();
        $sn = 1;
        foreach ($sessionData as $value) {

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
        $draw = $_POST['sEcho'];
        $row = $_POST['iDisplayStart'];
        $rowperpage = $_POST['iDisplayLength']; // Rows display per page
        $indexColumn = $_POST['iSortCol_0'];
        $columnName = $_POST['mDataProp_' . $indexColumn]; // Column name
        $columnSortOrder = $_POST['sSortDir_0']; // asc or desc

        $searchValue = $_POST['sSearch'];

        $cases = MedCase::getClosedMediator($searchValue, $columnName, $columnSortOrder, $draw, $row, $rowperpage, $role);
        $casescount = MedCase::getClosedMediatorCount($searchValue, $role);
        $arraydata = array();
        foreach ($cases as $d) {
            $arraydata[] = [
                "date" => date('d-m-Y', strtotime($d->created_at)),
                "case" => $d,
                "party" => InvoledUser::select('user_involved_in_agreement.id', 'user_involved_in_agreement.name', 'user_involved_in_agreement.isOnboarded', 'user_involved_in_agreement.isClaimant', "user_involved_in_agreement.userId", "users.organization")->leftjoin('users', 'users.id', '=', 'user_involved_in_agreement.userId')->where(['userPlanid' => $d->id])->get(),
                "status_log" => Mediation_status_log::select("status", "description", DB::raw("DATE_FORMAT(created_at,'%d-%c-%y %h:%i %p') as created"))->where(['mediation_case_id' => $d->id])->orderByDesc('id')->limit(1)->get(),
                "private_count" => Mediation_case_comment::where("type", "=", 1)->where('mediation_case_id', $d->id)->count(),
                "private_view_count" => Mediation_case_comment::where("type", "=", 1)->where('mediation_case_id', $d->id)->where('view_mediator', 0)->count(),
                "share_count" => Mediation_case_comment::where("type", "=", 0)->where('mediation_case_id', $d->id)->count(),
                "share_view_count" => Mediation_case_comment::where("type", "=", 0)->where('mediation_case_id', $d->id)->where('view_mediator', 0)->count(),

            ];
        }
        return response()->json(["sEcho" => intval($draw), "iTotalRecords" => $casescount, "iTotalDisplayRecords" => $casescount, "aaData" => $arraydata]);

        // return response()->json(["data" => $arraydata]);
    }

    public function casedetails($id)
    {


        $case = MedCase::select("mediation_case.*", "users.first_name as mfirstname", "users.last_name as mlastname", "mediators_mediation_cases_status.mediator_id as mediator_id", "mediators_mediation_cases_status.status as mediator_status")
            ->leftJoin("mediators_mediation_cases_status", "mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id")
            ->leftJoin("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->where('mediation_case.id', '=', $id)
            ->first();


        $case->party = InvoledUser::where(['userPlanid' => $case->id])->get();

        // $case->invitation = InvitationFiles::where(['case_id' => $case->id])->orderByDesc('id')->limit(1)->first();
        $case->invitation = InvitationFiles::where(['case_id' => $case->id])->orderByDesc('id')->get();

        $case->appointment = InvitationFiles::where(['case_id' => $case->id])->where('file_name_mediator_appointment', '!=', null)->orderByDesc('id')->limit(1)->first();

        $case->supporting_document = DB::table('manage_files')->select('manage_files.*', 'users.username')
            ->join('users', 'users.id', '=', 'manage_files.uploaded_by')
            ->where('manage_files.case_id', $case->id)
            ->get();


        return view('mediator.casedetails', compact("case"));
    }

    public function jsonOngoing($role = 0)
    {
        $draw = $_POST['sEcho'];
        $row = $_POST['iDisplayStart'];
        $rowperpage = $_POST['iDisplayLength']; // Rows display per page
        $indexColumn = $_POST['iSortCol_0'];
        $columnName = $_POST['mDataProp_' . $indexColumn]; // Column name
        $columnSortOrder = $_POST['sSortDir_0']; // asc or desc

        $searchValue = $_POST['sSearch'];

        $cases = MedCase::getOngoingMediator($searchValue, $columnName, $columnSortOrder, $draw, $row, $rowperpage);
        $casescount = MedCase::getOngoingMediatorCount($searchValue);
        $arraydata = array();
        foreach ($cases as $d) {
            $arraydata[] = [
                "date" => date('d-m-Y', strtotime($d->created_at)),
                "case" => $d,
                "party" => InvoledUser::select('user_involved_in_agreement.id', 'user_involved_in_agreement.name', 'user_involved_in_agreement.isOnboarded', 'user_involved_in_agreement.isClaimant', "user_involved_in_agreement.userId", "users.organization")->leftjoin('users', 'users.id', '=', 'user_involved_in_agreement.userId')->where(['userPlanid' => $d->id])->get(),
                "private_count" => Mediation_case_comment::where("type", "=", 1)->where('mediation_case_id', $d->id)->count(),
                "private_view_count" => Mediation_case_comment::where("type", "=", 1)->where('mediation_case_id', $d->id)->where('view_mediator', 0)->count(),
                "share_count" => Mediation_case_comment::where("type", "=", 0)->where('mediation_case_id', $d->id)->count(),
                "share_view_count" => Mediation_case_comment::where("type", "=", 0)->where('mediation_case_id', $d->id)->where('view_mediator', 0)->count(),
            ];
        }
        return response()->json(["sEcho" => intval($draw), "iTotalRecords" => $casescount, "iTotalDisplayRecords" => $casescount, "aaData" => $arraydata]);
    }

    /**
     * upload supporting Documents.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function storeMultiFile(Request $request)
    {

        $validatedData = $request->validate([
            'files0' => 'required',
            'files.*' => 'mimes:csv,txt,xlx,xls,pdf,rar,zip',
            // 'docs_party_ids' => 'required',
        ],
        [
            'files0.required' => 'You have to choose the file!',
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
                Common_function::MedNotification($_POST['allcids'], "SEND_ADDI_DOC_MED", Auth::user()->id, Auth::user()->id, null);
            }
        } else {
            Common_function::MedNotification($request->caseId, "SEND_ADDI_DOC_MED", Auth::user()->id, Auth::user()->id, $inv_id);
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
                    $insert[$x]['access'] = $request->docs_party_ids;
                    $insert[$x]['mediator_access'] = 1;
                    $insert[$x]['uploaded_by'] = Auth::user()->id;
                    $insert[$x]['case_id'] = $request->caseId;
                    // $insert[$x]['path'] = $path;
                }
            }
            // dd($insert);
            // die();
            // File::insert($insert);
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
            $inv_id = "";
            $inv = InvoledUser::select('id')->where('userPlanId', $request->caseId)->get();
            foreach ($inv as $v) {
                if ($inv_id == "") {
                    $inv_id = $v->id;
                } else {
                    $inv_id = $inv_id . "," . $v->id;
                }
            }
            DB::table('document_settlements')->insert($insert);
            Common_function::MedNotification($request->caseId, "SEND_SETT_AGRE_MED", Auth::user()->id, Auth::user()->id, $inv_id);
            $this->send_settlement_agreement_party($request->caseId, $insert);
            return response()->json(["message" => 'Ajax Multiple fIle has been uploaded']);
        } else {
            return response()->json(["message" => "Please try again."]);
        }
    }

    public function sned_session($url, $id, $email_id, $email_name, $date, $userPhone)
    {
        $d = [
            'event' => 'SESS_SCHE',
            'case_id' => $id,
        ];
        $mid = "M" . sprintf("%06d", $id);
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

            $access = Whatsapp::sendWamessage($dwa1);
        }
        return true;
    }

    public function send_attechment_party($id)
    {
        $data["case"] = MedCase::where("id", "=", $id)->first();
        $data["party"] = InvoledUser::where("userPlanId", "=", $id)->get();
        $data["consent_disclosures"] = ConsentDisclosures::select('consent_disclosures.*', 'users.first_name', 'users.last_name', 'users.email', 'users.username', 'users.mobile_number', 'users.organization', 'users.signature_photo', 'users.id as medId')->join("users", "consent_disclosures.mediator_id", "=", "users.id")
            ->where("mediation_case_id", "=", $id)
            ->first();
        $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->where("mediators_mediation_cases_status.mediation_case_id", "=", $id)
            // ->where("mediators_mediation_cases_status.status", "=", 1)
            ->first();
        if (empty($data["case"]) || empty($data["party"]) || empty($data["consent_disclosures"])) {
            return abort(404);
        }
        $pdf = PDF::loadView('pdf.consent_and_disclosures', $data);
        $file_name = "M" . sprintf("%06d", $id) . "_party.pdf";
        // Storage::put('public/mediation/' . $data["case"]->id . '/' . $file_name, $pdf->output());
        $savePath = 'mediation_documents/mediation/' . $data["case"]->id;
        $finalFilePath = $savePath . '/' . $file_name;
        // Storage::put('public/mediation/' . $data["case"]->id . '/' . $name, $pdf->output());
        $uploadS3 = $this->uploadOnAWSDirect($finalFilePath, $savePath, $pdf);
        $data["consent_disclosures"]->file_name = $file_name;
        $data["consent_disclosures"]->save();
        $involedUser = InvoledUser::where("userPlanId", $id)->get();
        $mid = "M" . sprintf("%06d", $id);
        $d = [
            'event' => 'SEND_APPO_MED',
            'case_id' => $id,
        ];
        $whatsappSend = Storage::disk('s3')->url($finalFilePath);
        // dd($uploadS3);
        if ($mediator) {
            SendGrid::send($d, $mediator->email, env('L18_MEDIATOR_ACCEPTANCE_ALL_PARTIES', ''), ["-caseid-" => $mid], null, $finalFilePath);
        }
        foreach ($involedUser as $inv) {
            if ($inv->userEmail != "") {
                SendGrid::send($d, $inv->userEmail, env('L18_MEDIATOR_ACCEPTANCE_ALL_PARTIES', ''), ["-caseid-" => $mid], null, $finalFilePath);
            }
            if ($inv->userPhone != "") {
                $varjson = ['caseid' => $mid];
                $var = ['-cid-'];
                $var1 = [$mid];
                $content1 = WaTemplate::getcontent('mediator_appointment');
                $content = str_replace($var, $var1, $content1);
                $dwa1 = [
                    'caseid' => $id,
                    'contact' =>   $inv->userPhone,
                    'content' => ['text' => $content],
                    'event' => 'SEND_APPO_MED',
                    'varjson' => $varjson,
                    'haptik_tmp' => 'l18_mediator_appointment'
                ];
                $access = Whatsapp::sendWamessage($dwa1);
                $varjson_file = ['caseid' => $mid];
                $var_file = ['-caseid-'];
                $var1_file = [$mid];
                $content1_file = WaTemplate::getcontent('mediation_consent_doc');
                $content_file = str_replace($var_file, $var1_file, $content1_file);
                $dwa2 = [
                    'caseid' => $id,
                    'contact' =>  $inv->userPhone,
                    'content' => ['media' => ['url' => $whatsappSend, 'caption' => $content_file]],
                    'event' => 'SEND_APPO_MED',
                    'varjson' => $varjson_file,
                    'haptik_tmp' => 'mediation_consent_doc'

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
            // $dwa2 = [
            //     'caseid' => $id,
            //     'contact' =>   $inv->userPhone,
            //     'content' => ['media' => ['url' => $filesE, 'caption' => 'Additional Document ' . $mid]],
            //     'event' => 'SEND_ADDI_DOC_ADM'
            // ];
            // $access = Whatsapp::sendWamessage($dwa2);
        }
        // echo ("error");
        // exit;
        if ($mediator) {
            // $sendEamils[] = $mediator->email;
            if ($mediatorAccess == 1) {

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
        // dd($sendEamils);
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
            // settlement agreement
            if ($inv->userPhone != "") {
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
                    'haptik_tmp' => 'l21_settlement_agreement'

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
                    'haptik_tmp' => 'mediation_consent_doc'

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
                'haptik_tmp' => 'l22_settlement_agreement_med'

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
                    'contact' =>  $mediator->mobile_number,
                    'content' => ['media' => ['url' => $whatsappSend, 'caption' => $content_file]],
                    'event' => 'SEND_SETT_AGRE_MED',
                    'varjson' => $varjson_file,
                'haptik_tmp' => 'mediation_consent_doc'

                ];
                $access = Whatsapp::sendWamessage($dwa2);
            }
        }
        foreach ($sendEamils as $email) {

            SendGrid::send($d, $email, env('L21_SETTLEMENT_AGREEMENT_ALL_PARTIES', ''), ["-caseid-" => $mid], null, $filesE);
        }
        return true;
    }
}
