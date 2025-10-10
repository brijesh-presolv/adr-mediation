<?php

namespace App\Http\Controllers\API\Mediator;

use App\Http\Controllers\Controller;
use App\Models\MedCase;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Helpers\Curl;
use App\Models\ManageSession;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers\Token;
use Illuminate\Support\Facades\Validator;
use App\Http\Helpers\Common_function;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\InvoledUser;
use App\Models\Mediation_status_log;
use App\Models\Mediation_case_comment;
use App\Models\Mediators_mediation_cases_status;
use App\Models\InvitationFiles;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\BulkLog;
use App\Models\EmailTrack;
use App\Models\Notification;
use App\Models\SendWhatsappChoice;
use App\Models\ConsentDisclosures;
use App\Http\Helpers\SendGrid;
use App\Http\Traits\UploadTrait;
use PDF;
use DateTime;
use DateTimeZone;
use Carbon\Carbon;
use App\Http\Helpers\Zoom;


class CaseController extends Controller 
{
    use UploadTrait;

    public function newreq(Request $request){

        $token = $request->cookie('auth_token');
        if (!$token) {

            $result['success'] = false;
            $result['message'] = 'Unauthorized: Missing token';
            $result['error'] = 'Unauthorized: Missing token';
            return response()->json($result, 401);
        }

        $JWT_KEY = env('JWT_KEY');
        $jwtData = JWT::decode($token, new Key(base64_decode($JWT_KEY), 'HS512'));
        $userId = $jwtData->data->userid;
        $role = $jwtData->data->role;

        $start   = $request->input('iDisplayStart', 0);    // offset
        $length  = $request->input('iDisplayLength', 10);  // limit
        $search  = $request->input('search', '');
        $batch_id = $request->input('batch_id', null);
        $sortOrder = $request->input('SortOrder', 'desc'); // asc or desc
        $columnName = $request->input('columnName', ''); 
        
        $bulk = 0;

        $cases = MedCase::getNewReqCaseMediatorApi($role, $bulk, $start, $length, $search, $columnName, $sortOrder, $batch_id, $userId);

        $data = array();
        if(count($cases) > 0) {
        foreach ($cases as $key => $values) {

            $admin_approved_date = date('d-m-Y', strtotime($values->admin_approved_date));
            $id = $values->id;
            $keyInc = $key + 1;

            $data[$key]['id'] = $id;
            $data[$key]['caseid'] ='CID' . sprintf('%06d', $values->id);
            $data[$key]['keyInc'] = $keyInc;
            $data[$key]['batch_id'] = $values->batch_id;
            $data[$key]['ref_id'] = $values->ref_id;
            $data[$key]['confirm_status'] = $values->confirm_status;
            $data[$key]['case_status'] = $values->case_status;
            $data[$key]['created_at'] = $values->created_at;
            $data[$key]['admin_approved_date'] = $admin_approved_date;
            $data[$key]['mediator_name'] = $values->mediator_name;
            $data[$key]['mediator_id'] = $values->mediator_id;
            $data[$key]['mediator_status'] = $values->mediator_status;
            $data[$key]['batch_name'] = $values->batch_name;
            $data[$key]['claimants']  = $values->claimants->values();
            $data[$key]['respondents'] =  $values->respondents->values();
        }
        }

        $casedata['cases']=$data;
        $casedata['pagination']['total_count']=$cases->total();
        $casedata['pagination']['current_page']=$cases->currentPage();
        $casedata['pagination']['per_page']=$cases->perPage();
        $casedata['pagination']['total_page']=$cases->lastPage();

        $result['success'] = true;
        $result['message'] = "New cases fetched successfully.";
        $result['data'] = $casedata;
        return response()->json($result, 200);

    }


    public function ongoing(Request $request){

        $token = $request->cookie('auth_token');
        if (!$token) {

            $result['success'] = false;
            $result['message'] = 'Unauthorized: Missing token';
            $result['error'] = 'Unauthorized: Missing token';
            return response()->json($result, 401);
        }

        $JWT_KEY = env('JWT_KEY');
        $jwtData = JWT::decode($token, new Key(base64_decode($JWT_KEY), 'HS512'));
        $userId = $jwtData->data->userid;
        $role = $jwtData->data->role;

        $start   = $request->input('iDisplayStart', 0);    // offset
        $length  = $request->input('iDisplayLength', 10);  // limit
        $search  = $request->input('search', '');
        $batch_id = $request->input('batch_id', null);
        $sortOrder = $request->input('SortOrder', 'desc'); // asc or desc
        $columnName = $request->input('columnName', ''); 
        
        $bulk = 0;
        $casesData = MedCase::getOgoingCaseMediatorApi($role, $bulk, $start, $length, $search, $columnName, $sortOrder , $batch_id, $userId);
        $data = array();
        if(count($casesData) > 0) {

            foreach ($casesData as $key => $values) {

                $admin_approved_date = date('d-m-Y', strtotime($values->admin_approved_date));
                $id = $values->id;
                $keyInc = $key + 1;

                $data[$key]['id'] = $id;
                $data[$key]['caseid'] ='CID' . sprintf('%06d', $values->id);
                $data[$key]['keyInc'] = $keyInc;
                $data[$key]['batch_id'] = $values->batch_id;
                $data[$key]['ref_id'] = $values->ref_id;
                $data[$key]['confirm_status'] = $values->confirm_status;
                $data[$key]['case_status'] = $values->case_status;
                $data[$key]['created_at'] = $values->created_at;
                $data[$key]['admin_approved_date'] = $admin_approved_date;
                $data[$key]['mediator_name'] = $values->mediator_name;
                $data[$key]['mediator_id'] = $values->mediator_id;
                $data[$key]['mediator_status'] = $values->mediator_status;
                $data[$key]['batch_name'] = $values->batch_name;
                $data[$key]['claimants']  = $values->claimants->values();
                $data[$key]['respondents'] =  $values->respondents->values();

            }
        }


        $casedata['cases']=$data;
        $casedata['pagination']['total_count']=$casesData->total();
        $casedata['pagination']['current_page']=$casesData->currentPage();
        $casedata['pagination']['per_page']=$casesData->perPage();
        $casedata['pagination']['total_page']=$casesData->lastPage();

        $result['success'] = true;
        $result['message'] = "Cases fetched successfully.";
        $result['data'] = $casedata;
        return response()->json($result, 200);
    }

    public function closed(Request $request){

        $token = $request->cookie('auth_token');
        if (!$token) {

            $result['success'] = false;
            $result['message'] = 'Unauthorized: Missing token';
            $result['error'] = 'Unauthorized: Missing token';
            return response()->json($result, 401);
        }

        $JWT_KEY = env('JWT_KEY');
        $jwtData = JWT::decode($token, new Key(base64_decode($JWT_KEY), 'HS512'));
        $userId = $jwtData->data->userid;
        $role = $jwtData->data->role;

        $start   = $request->input('iDisplayStart', 0);    // offset
        $length  = $request->input('iDisplayLength', 10);  // limit
        $search  = $request->input('search', '');
        $batch_id = $request->input('batch_id', null);
        $sortOrder = $request->input('SortOrder', 'desc'); // asc or desc
        $columnName = $request->input('columnName', ''); 
        
        $bulk = 0;
        $casesData = MedCase::getClosedCaseMediatorApi($role, $bulk, $start, $length, $search, $columnName, $sortOrder , $batch_id, $userId);
        $data = array();
        if(count($casesData) > 0) {

            foreach ($casesData as $key => $values) {

                $admin_approved_date = date('d-m-Y', strtotime($values->admin_approved_date));
                $id = $values->id;
                $keyInc = $key + 1;

                $data[$key]['id'] = $id;
                $data[$key]['caseid'] ='CID' . sprintf('%06d', $values->id);
                $data[$key]['keyInc'] = $keyInc;
                $data[$key]['batch_id'] = $values->batch_id;
                $data[$key]['ref_id'] = $values->ref_id;
                $data[$key]['confirm_status'] = $values->confirm_status;
                $data[$key]['case_status'] = $values->case_status;
                $data[$key]['created_at'] = $values->created_at;
                $data[$key]['admin_approved_date'] = $admin_approved_date;
                $data[$key]['mediator_name'] = $values->mediator_name;
                $data[$key]['mediator_id'] = $values->mediator_id;
                $data[$key]['mediator_status'] = $values->mediator_status;
                $data[$key]['batch_name'] = $values->batch_name;
                $data[$key]['claimants']  = $values->claimants->values();
                $data[$key]['respondents'] =  $values->respondents->values();

            }
        }



        $casedata['cases']=$data;
        $casedata['pagination']['total_count']=$casesData->total();
        $casedata['pagination']['current_page']=$casesData->currentPage();
        $casedata['pagination']['per_page']=$casesData->perPage();
        $casedata['pagination']['total_page']=$casesData->lastPage();

        $result['success'] = true;
        $result['message'] = "Cases fetched successfully.";
        $result['data'] = $casedata;
        return response()->json($result, 200);
    }

    public function rejected(Request $request){


        $token = $request->cookie('auth_token');
        if (!$token) {

            $result['success'] = false;
            $result['message'] = 'Unauthorized: Missing token';
            $result['error'] = 'Unauthorized: Missing token';
            return response()->json($result, 401);
        }

        $JWT_KEY = env('JWT_KEY');
        $jwtData = JWT::decode($token, new Key(base64_decode($JWT_KEY), 'HS512'));
        $userId = $jwtData->data->userid;
        $role = $jwtData->data->role;

        $start   = $request->input('iDisplayStart', 0);    // offset
        $length  = $request->input('iDisplayLength', 10);  // limit
        $search  = $request->input('search', '');
        $batch_id = $request->input('batch_id', null);
        $sortOrder = $request->input('SortOrder', 'desc'); // asc or desc
        $columnName = $request->input('columnName', ''); 
        
        $bulk = 0;
        $casesData = MedCase::getClosedCaseMediatorApi($role, $bulk, $start, $length, $search, $columnName, $sortOrder , $batch_id, $userId);

        $data = array();
        if(count($casesData) > 0) {

            foreach ($casesData as $key => $values) {

                $admin_approved_date = date('d-m-Y', strtotime($values->admin_approved_date));
                $id = $values->id;
                $keyInc = $key + 1;

                $data[$key]['id'] = $id;
                $data[$key]['caseid'] ='CID' . sprintf('%06d', $values->id);
                $data[$key]['keyInc'] = $keyInc;
                $data[$key]['batch_id'] = $values->batch_id;
                $data[$key]['ref_id'] = $values->ref_id;
                $data[$key]['confirm_status'] = $values->confirm_status;
                $data[$key]['case_status'] = $values->case_status;
                $data[$key]['created_at'] = $values->created_at;
                 $data[$key]['admin_approved_date'] = $admin_approved_date;
                $data[$key]['mediator_name'] = $values->mediator_name;
                $data[$key]['mediator_id'] = $values->mediator_id;
                $data[$key]['mediator_status'] = $values->mediator_status;
                $data[$key]['batch_name'] = $values->batch_name;
                $data[$key]['claimants']  = $values->claimants->values();
                $data[$key]['respondents'] =  $values->respondents->values();

            }
        }

        $casedata['cases']=$data;
        $casedata['pagination']['total_count']=$casesData->total();
        $casedata['pagination']['current_page']=$casesData->currentPage();
        $casedata['pagination']['per_page']=$casesData->perPage();
        $casedata['pagination']['total_page']=$casesData->lastPage();

        $result['success'] = true;
        $result['message'] = "Cases fetched successfully.";
        $result['data'] = $casedata;
        return response()->json($result, 200);
    }

    public function addSession(Request $request)
    {

        try {

            $token = $request->cookie('auth_token');
            if (!$token) {

                $result['success'] = false;
                $result['message'] = 'Unauthorized: Missing token';
                $result['error'] = 'Unauthorized: Missing token';
                return response()->json($result, 401);
            }

            $JWT_KEY = env('JWT_KEY');
            $jwtData = JWT::decode($token, new Key(base64_decode($JWT_KEY), 'HS512'));
            $userId = $jwtData->data->userid;

            $validator = Validator::make($request->all(), [
                'caseId'             => 'required|integer',
                'sessionDate'        => 'required|date_format:d/m/Y|after_or_equal:today',
                'sessionTime'        => 'required|date_format:H:i',
                'zoom_choice'        => 'required|string|in:directly_zoom,manually_zoom,other',
                'zoomId'             => 'nullable|string|max:255',
                'note'               => 'nullable|string|max:500',
                'session_party_ids'  => 'required|array|min:1',
                'session_party_ids.*'=> 'required|integer'
            ]);

            if ($validator->fails()) {

                $errors = $validator->errors()->all();

                $result['success'] = false;
                $result['message'] = implode(', ', $errors);
                $result['error'] = $validator->errors();
                return response()->json($result, 422);
            }

            $caseId = $request->input('caseId');
            $zoom_choice = $request->input('zoom_choice');
            $sessionTime = $request->input('sessionTime');
            $sessionDate = $request->input('sessionDate');
            $note = $request->input('note');
            $zoomId = $request->input('zoomId');
            $session_party_ids= $request->input('session_party_ids');


            if($request->input('zoom_choice') == "directly_zoom" || $request->input('zoom_choice') == "directly_zoom") {

                $time_zoom = date("H:i:s", strtotime($sessionTime));
                $end_time = date("H:i:s", strtotime($sessionTime) + 60*60);
                $date1 = str_replace('/', '-', $sessionDate);  
                $date = date('Y-m-d', strtotime($date1));
                $total = $date.' '.$time_zoom;
                $end_total = $date.' '.$end_time;
                $date_format_api =  date("Y-m-d\TH:i:s", strtotime($total));
                $end_date_format_api =  date("Y-m-d\TH:i:s", strtotime($end_total));
                
                $create_zoom_meeting_response = Zoom::createZoomMeeting($caseId, $note, $date_format_api, $end_date_format_api);
                $create_zoom_meeting = json_decode($create_zoom_meeting_response, true);
                // Get zoom api invitation : START //
                $zoom_invitation_response = Zoom::zoomInvitation($create_zoom_meeting['id']);
                $zoom_invitation = json_decode($zoom_invitation_response, true);

                /**** Get Zoom URL from invitation ********/
                $zoom_string = $zoom_invitation['invitation'];
                preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $zoom_string, $zoom_match);
                /**** Get Zoom URL from invitation ********/
                
                $created_zoom_link = $zoom_match[0][0];
                $created_zoom_id = $create_zoom_meeting['id'];
                $inserted_zoom_choice = "direct";

            } else {

                $created_zoom_link = ""; 
                $created_zoom_id = $zoomId;
                $inserted_zoom_choice = "manual";
            }

            $time = date("g:i A", strtotime($sessionTime));
            $display_date_time = str_replace('/', '-', $sessionDate) . " " . $time;
            
            $d = [
                'event' => 'SESS_SCHE',
                'case_id' => $caseId,
            ];
            $medcase = MedCase::find($caseId);

            if (isset($session_party_ids)) {

                $dataToInsert = [
                    'case_id' => $caseId,
                    'session_date' => $sessionDate . "/" . $time,
                    'note' => $note,
                    'zoom_id' => $created_zoom_id,
                    'zoom_link' => $created_zoom_link,
                    'zoom_link_choice' => $inserted_zoom_choice,
                    'session_party_ids' => json_encode($session_party_ids),
                    'scheduled_by' => $userId,
                    'participant_whtsapp' => 0
                ];
                $insertData = DB::table('manage_session')->insert($dataToInsert);

                if ($insertData) {

                    $inv_id = "";
                    foreach ($session_party_ids as $party_id) {

                        $party = InvoledUser::where("userPlanId", $caseId)->where("id", $party_id)->first();
                    
                        if ($inv_id == "") {
                            $inv_id = $party->id;
                        } else {
                            $inv_id = $inv_id . "," . $party->id;
                        }

                       

                        if($zoom_choice == "manually_zoom") {

                            if($zoom_choice == "manually_zoom"){
                                $is_send = $this->sned_session($zoomId, $caseId, $party->userEmail, $party->name, $sessionDate . "/" . $time, $party->userPhone, "Party");
                            }
                        
                        } else if($zoom_choice == "directly_zoom") {

                            if($zoom_choice == "directly_zoom" && $party->isClaimant != 0){

                                if($medcase->stop_bulk_session_ip == 1 && $party->isClaimant != 0) {
                                    $is_send = $this->sned_session_invitation($create_zoom_meeting['id'], $caseId, $party->userEmail, $party->name, $sessionDate . "/" . $time_zoom, $party->userPhone, $created_zoom_link, "Party");
                                } else if($medcase->stop_bulk_session_rp == 1 && $party->isClaimant == 0) {
                                    $is_send = $this->sned_session_invitation($create_zoom_meeting['id'], $caseId, $party->userEmail, $party->name, $sessionDate . "/" . $time_zoom, $party->userPhone, $created_zoom_link, "Party");
                                }

                            }elseif($zoom_choice == "directly_zoom"){

                                $is_send = $this->sned_session_invitation($create_zoom_meeting['id'], $caseId, $party->userEmail, $party->name, $request->sessionDate . "/" . $time_zoom, $party->userPhone, $created_zoom_link, "Party");
                            }
                        
                        }
                    }

                    $mediatorNoti = Mediators_mediation_cases_status::select("email", "username", "mobile_number", "users.id")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                        ->where("mediators_mediation_cases_status.mediation_case_id", "=", $caseId)
                        ->where("mediators_mediation_cases_status.status", "=", 1)
                        ->first();
                    Common_function::MedNotification($caseId, "SESS_SCHE_ADMIN", $userId, isset($mediatorNoti) ? $mediatorNoti->id : null, $inv_id);

                    if ($mediatorNoti) {

                        $id = "CID" . sprintf("%06d", $caseId);

                        if($zoom_choice == "manually_zoom") {
                            if($medcase->stop_bulk_session_med == 0) {
                                $is_send = $this->sned_session($zoomId, $caseId, $mediatorNoti->email, $mediatorNoti->username, $display_date_time, $mediatorNoti->mobile_number, "Mediator");
                            }
                        } else if($zoom_choice == "directly_zoom") {
                            if($medcase->stop_bulk_session_med == 0) {
                                $is_send = $this->sned_session_invitation($create_zoom_meeting['id'], $caseId, $mediatorNoti->email, $mediatorNoti->username, $display_date_time, $mediatorNoti->mobile_number, $created_zoom_link, "Mediator");
                            }
                        }
                    }


                    $data['caseid'] = $caseId;

                    $result['success'] = true;
                    $result['message'] = "Zoom Meeting created successfully.";
                    $result['data'] = $data;
                    return response()->json($result, 200);

                } else {

                    $result['success'] = false;
                    $result['message'] = "Zoom Meeting Creataion failed.";
                    $result['error'] = "Zoom Meeting Creataion failed.";
                    return response()->json($result, 200);
                }
            } else {

                $mediatorNoti = Mediators_mediation_cases_status::select("email", "username", "mobile_number", "users.id")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                    ->where("mediators_mediation_cases_status.mediation_case_id", "=", $caseId)
                    ->where("mediators_mediation_cases_status.status", "=", 1)
                    ->first();
                $inv_id = "";
                $inv = InvoledUser::select('id')->where('userPlanId', $caseId)->get();
                foreach ($inv as $v) {
                    if ($inv_id == "") {
                        $inv_id = $v->id;
                    } else {
                        $inv_id = $inv_id . "," . $v->id;
                    }
                }

                Common_function::MedNotification($caseId, "SESS_SCHE_ADMIN", $userId, isset($mediatorNoti) ? $mediatorNoti->id : null, $inv_id);
              
                $allParty = InvoledUser::where("userPlanId", $caseId)->get();
                $party_ids = array();
                $party_ids_bulk = array();
                foreach ($allParty as $party) {

                        $party_ids[] = $party->id; 
                }
            
                $dataToInsert = [
                    'case_id' => $caseId,
                    'session_date' => $sessionDate . "/" . $time,
                    'note' => $note,
                    'zoom_id' => $created_zoom_id,
                    'zoom_link' => $created_zoom_link,
                    'zoom_link_choice' => $inserted_zoom_choice,
                    'session_party_ids' => (!empty($party_ids_bulk)) ? json_encode($party_ids_bulk) : json_encode($party_ids),
                    'scheduled_by' => $userId,
                    'participant_whtsapp' => 0
                ];
                $manage_session = DB::table('manage_session')->insert($dataToInsert);
                if ($manage_session) {
                    $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                        ->where("mediators_mediation_cases_status.mediation_case_id", "=", $caseId)
                        ->where("mediators_mediation_cases_status.status", "=", 1)
                        ->first();
                    if ($mediator) {
                        $id = "CID" . sprintf("%06d", $caseId);

                        if(!isset($request->fsData['zoom_choice'])){

                            if($medcase->stop_bulk_session_med == 0) {

                                SendGrid::send($d, $mediator->email, env('L10_SCHEDULING_OF_SESSION', ''), ["-caseid-" => $id, "-insert_date-" => $sessionDate . "/" . $time, "-type-" => "Mediator"], $mediator->username);
                            
                            }
                        }
                    }
                    foreach ($allParty as $party) {

                        if($zoom_choice == "manually_zoom") {

                            if($medcase->stop_bulk_session_ip == 1 && $party->isClaimant != 0) {
                                $is_send = $this->sned_session($zoomId, $caseId, $party->userEmail, $party->name, $sessionDate, $party->userPhone, "Party");
                            } else if($medcase->stop_bulk_session_rp == 1 && $party->isClaimant == 0) {
                                $is_send = $this->sned_session($zoomId, $caseId, $party->userEmail, $party->name, $sessionDate, $party->userPhone, "Party");
                            }

                        } else if($zoom_choice == "directly_zoom") {

                        if($medcase->stop_bulk_session_ip == 1 && $party->isClaimant != 0) {
                            $is_send = $this->sned_session_invitation($create_zoom_meeting['id'], $caseId, $party->userEmail, $party->name, $display_date_time, $party->userPhone, $created_zoom_link, "Party");
                        } else if($medcase->stop_bulk_session_rp == 1 && $party->isClaimant == 0) {
                            $is_send = $this->sned_session_invitation($create_zoom_meeting['id'], $caseId, $party->userEmail, $party->name, $display_date_time, $party->userPhone, $created_zoom_link, "Party");
                        }
                        
                        }
                    }
                    
                    $data['caseid'] = $caseId;

                    $result['success'] = true;
                    $result['message'] = "Zoom Meeting created successfully.";
                    $result['data'] = $data;
                    return response()->json($result, 200);
        
                } else {

                    $data['caseid'] = $caseId;

                    $result['success'] = false;
                    $result['message'] = "Zoom meeting creation failed. Please try again.";
                    $result['error']   = "Unable to create Zoom meeting.";
                    return response()->json($result, 200);
                }
            }

            return true;

        } catch (Exception $e) {

            $result['success'] = false;
            $result['message'] = "Zoom meeting creation failed. Please try again.";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
        }
    }

    public function sned_session($url, $id, $email_id, $email_name, $date, $userPhone, $userType){

        $mid = "CID" . sprintf("%06d", $id);
        $d = [
            'event' => 'SESS_SCHE',
            'case_id' => $id,
        ];
        if ($email_id != "") {
            SendGrid::send($d, $email_id, env('L10_SCHEDULING_OF_SESSION', ''), ["-caseid-" => $mid, "-insert_date-" => $date, "-type-" => $userType, '-zoom_invitation_link-' => $url], $email_name);
        }
        
        return true;
    }

    public function sned_session_invitation($url, $id, $email_id, $email_name, $date, $userPhone, $invitation, $userType)
    {
        $mid = "CID" . sprintf("%06d", $id);
        $d = [
            'event' => 'SESS_SCHE',
            'case_id' => $id,
        ];
        if ($email_id != "") {
            SendGrid::send($d, $email_id, env('L10_SCHEDULING_OF_SESSION', ''), ["-caseid-" => $mid, "-insert_date-" => $date, "-type-" => $userType, "-zoom_invitation_link-" => $invitation], $email_name);
        }

        return true;
    }

    public function getMeetingSession(Request $request)
    {
        $token = $request->cookie('auth_token');
        if (!$token) {

            $result['success'] = false;
            $result['message'] = 'Unauthorized: Missing token';
            $result['error'] = 'Unauthorized: Missing token';
            return response()->json($result, 401);
        }

        $JWT_KEY = env('JWT_KEY');
        $jwtData = JWT::decode($token, new Key(base64_decode($JWT_KEY), 'HS512'));
        $userId = $jwtData->data->userid;

        $validator = Validator::make($request->all(), [
            'caseId'             => 'required|integer'
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors()->all();

            $result['success'] = false;
            $result['message'] = implode(', ', $errors);
            $result['error'] = $validator->errors();
            return response()->json($result, 422);
        }

        $caseId = $request->input('caseId');

        $sessionData = DB::table('manage_session')->where('case_id', $caseId)->get();
        $sn = 1;
        $dataArray = array();
        $data = array();
        $delete_reason="";
        $zoom_link_choice="";

        foreach ($sessionData as $key => $values) {

            $delete_reason="";

            if (!is_null($values->session_party_ids)) {
                $dataArray = json_decode($values->session_party_ids);
            }

            $user = array();
            foreach ($dataArray as $d) {

                $dd = InvoledUser::where('id', $d)->where('userPlanId', $caseId)->first();
                
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

            if ($values->is_deleted == 0) {

                if ($jwtData->data->role == 2) {
                    
                    $zoom_link_choice= $values->zoom_link_choice;
            
                } else if ($jwtData->data->role == 1) {

                    if ($jwtData->data->id == $values->scheduled_by) {

                        $zoom_link_choice=$values->zoom_link_choice;

                    } else {
                     
                    }
                }
            } else {

               $delete_reason= $values->delete_reason;
            }

            $id = $values->id;
            $data[$key]['id'] = $id;
            $data[$key]['caseid'] ='CID' . sprintf('%06d', $values->case_id);
            $data[$key]['created_at'] = $values->created_at;
            $data[$key]['session_date'] = $values->session_date;
            $data[$key]['zoom_link'] = $values->zoom_link;
            $data[$key]['zoom_id'] = $values->zoom_id;
            $data[$key]['note'] = $values->note;
            $data[$key]['meeting_users'] = implode($user);
            $data[$key]['zoom_link_choice'] = $values->zoom_link_choice;
            $data[$key]['is_deleted'] = $values->is_deleted;
            $data[$key]['delete_reason'] = $delete_reason;
        }

        $result['success'] = true;
        $result['message'] = "Data fetched successfully.";
        $result['data'] = $data;
        return response()->json($result, 200);
    }

    public function UpdateSession(Request $request)
    {
        
        try {

            $token = $request->cookie('auth_token');
            if (!$token) {

                $result['success'] = false;
                $result['message'] = 'Unauthorized: Missing token';
                $result['error'] = 'Unauthorized: Missing token';
                return response()->json($result, 401);
            }

            $JWT_KEY = env('JWT_KEY');
            $jwtData = JWT::decode($token, new Key(base64_decode($JWT_KEY), 'HS512'));
            $userId = $jwtData->data->userid;

            $validator = Validator::make($request->all(), [
                'SessId'             => 'required|integer',
                'caseId'             => 'required|integer',
                'sessionDate'        => 'required|date_format:d/m/Y|after_or_equal:today',
                'sessionTime'        => 'required|date_format:H:i',
                'zoomChoice'        => 'required|string|in:directly_zoom,manually_zoom,other',
                'zoomId'             => 'nullable|string|max:255',
                'note'               => 'nullable|string|max:500',
                'session_party_ids'  => 'required|array|min:1',
                'session_party_ids.*'=> 'required|integer'
            ]);

            if ($validator->fails()) {

                $errors = $validator->errors()->all();

                $result['success'] = false;
                $result['message'] = implode(', ', $errors);
                $result['error'] = $validator->errors();
                return response()->json($result, 422);
            }

            $id = $request->input('SessId');
            $CaseId = $request->input('caseId');
            //print_r($CaseId);die();
            $zoomChoice = $request->input('zoomChoice');
            $sessionTime = $request->input('sessionTime');
            $sessionDate = $request->input('sessionDate');
            $note = $request->input('note');
            $zoomId = $request->input('zoomId');
            $session_party_ids= $request->input('session_party_ids');
            $zoomLink = $request->input('zoomLink');

            /********* Zoom Time Format ******************/
            if($request->input('zoomChoice') == "directly_zoom") {

                $time_zoom = date("H:i:s", strtotime($sessionTime));
                $end_time = date("H:i:s", strtotime($sessionTime) + 60*60);
                $date1 = str_replace('/', '-', $sessionDate);  

                $date = date('Y-m-d', strtotime($date1));
                $total = $date.' '.$time_zoom;
                $end_total = $date.' '.$end_time;
                $date_format_api =  date("Y-m-d\TH:i:s", strtotime($total));
                $end_date_format_api =  date("Y-m-d\TH:i:s", strtotime($end_total));

                $update_zoom_meeting_response = Zoom::updateZoomMeeting($zoomId, $CaseId, $date_format_api, $end_date_format_api, $request->note);
                
                $update_zoom_meeting = json_decode($update_zoom_meeting_response, true);

                if($update_zoom_meeting == '') {

                    $zoom_invitation_response = Zoom::zoomInvitation($zoomId);
                    $zoom_invitation = json_decode($zoom_invitation_response, true);
                }

            } else {

            }

            $time = date("g:i A", strtotime($sessionTime));
            $display_date_time = str_replace('/', '-', $sessionDate) . " " . $time;
            $result = ManageSession::find($id);
            $result->session_date = $sessionDate . "/" . $time;
            $result->note = $note;
            $result->zoom_id = $zoomId;
            $result->session_party_ids = json_encode($session_party_ids);

            if ($result->save()) {

                foreach ($session_party_ids as $party_id) {

                    $party = InvoledUser::where("userPlanId", $result->case_id)->where("id", $party_id)->first();

                    if($zoomChoice == "manually_zoom") {
                        $this->sned_session($zoomId, $result->case_id, $party->userEmail, $party->name, $display_date_time, $party->userPhone, "Party");
                    } else {
                        $this->sned_session_invitation($zoomId, $result->case_id, $party->userEmail, $party->name, $display_date_time, $party->userPhone, $zoomLink, "Party");
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

                    if($zoomChoice == "manually_zoom") {
                        $is_send = $this->sned_session($zoomId, $CaseId, $mediator->email, $mediator->username, $display_date_time, $mediator->mobile_number, "Mediator");
                    } else if($zoomChoice == "directly_zoom") {
                    /**** Zoom Invitation ************/
                        $is_send = $this->sned_session_invitation($zoomId, $CaseId, $mediator->email, $mediator->username, $display_date_time, $mediator->mobile_number, $zoomLink, "Mediator");
                    /**** Zoom Invitation ************/
                    }
                }
            }

            $data['caseid'] = $CaseId;

            $resultdata['success'] = true;
            $resultdata['message'] = "Zoom Session updated successfully.";
            $resultdata['data'] = $data;
            return response()->json($resultdata, 200);

        } catch (Exception $e) {

            $resultdata['success'] = false;
            $resultdata['message'] = "Session updation failed.";
            $resultdata['error'] = $e->getMessage();
            return response()->json($resultdata, 500);
        }
    }

    public function deleteSession(Request $request)
    {

        try {

            $token = $request->cookie('auth_token');
            if (!$token) {

                $result['success'] = false;
                $result['message'] = 'Unauthorized: Missing token';
                $result['error'] = 'Unauthorized: Missing token';
                return response()->json($result, 401);
            }

            $JWT_KEY = env('JWT_KEY');
            $jwtData = JWT::decode($token, new Key(base64_decode($JWT_KEY), 'HS512'));
            $userId = $jwtData->data->userid;

            $validator = Validator::make($request->all(), [
                'SessId'             => 'required|integer',
                'reason'        => 'required'
            ]);

            if ($validator->fails()) {

                $errors = $validator->errors()->all();

                $result['success'] = false;
                $result['message'] = implode(', ', $errors);
                $result['error'] = $validator->errors();
                return response()->json($result, 422);
            }


            $id = $request->input('SessId');
            $reason = $request->input('reason');

            $is_bulk = MedCase::select('bulk_flag')->where('id', $id)->first();

            $deleted = ManageSession::find($id);
            $deleted->is_deleted = 1;
            $deleted->delete_reason = $reason;

            $date_time = explode('/', $deleted->session_date);
            $display_date_time = $date_time[0].'-'.$date_time[1].'-'.$date_time[2].' '.$date_time[3];

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
                $caseid = "CID" . sprintf("%06d", $deleted->case_id);
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
                        } else {
                            $dd = InvoledUser::where('userId', $d)->where('userPlanId', $deleted->case_id)->first();
                            if (isset($dd)) {
                                if ($dd->userEmail != null) {
                                    SendGrid::send($d1, $dd->userEmail, env('L24_CANCELLING_OF_SESSION', ''), ["-cid-" => $caseid, "-date-" => $deleted->session_date, "-type-" => "Party"], $dd->name);
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
                }

                $data['SessId'] = $id;

                $result['success'] = true;
                $result['message'] = "Zoom Meeting deleted successfully.";
                $result['data'] = $data;
                return response()->json($result, 200);

            } else {

                $result['success'] = false;
                $result['message'] = "Session updation failed.";
                $result['error'] = "Session updation failed.";
                return response()->json($result, 500);
            }

        } catch (Exception $e) {

            $result['success'] = false;
            $result['message'] = "Session updation failed.";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
        }
    }

    public function closeCaseStatus(Request $request) {

        try{

            $token = $request->cookie('auth_token');
            if (!$token) {

                $result['success'] = false;
                $result['message'] = 'Unauthorized: Missing token';
                $result['error'] = 'Unauthorized: Missing token';
                return response()->json($result, 401);
            }

            $JWT_KEY = env('JWT_KEY');
            $jwtData = JWT::decode($token, new Key(base64_decode($JWT_KEY), 'HS512'));
            $userId = $jwtData->data->userid;

            $validator = Validator::make($request->all(), [
                'caseid' => 'required|integer',
                'status' => 'required|integer',
                //'comment' => 'required|string'
            ]);

            if(Mediation_status_log::STATUS_WITHDRAWN  != $request->input('status')){

                $validator = Validator::make($request->all(), [
                        'caseid' => 'required|integer',
                        'status' => 'required|integer',
                        'Settelmentfiles' => 'required',
                        'Settelmentfiles.*' => 'mimes:csv,txt,xlx,xls,pdf',
                    ]);
            }

            //Inputs
            $caseid = $request->input('caseid');
            $status = $request->input('status');
            $comment = $request->input('comment');

            if ($validator->fails()) {

                $errors = $validator->errors()->all();

                $result['success'] = false;
                $result['message'] = implode(', ', $errors);
                $result['error'] = $validator->errors();
                return response()->json($result, 422);
            }
          
            if ($status != null) {

                $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number", "users.id")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                    ->where("mediators_mediation_cases_status.mediation_case_id", "=", $caseid)
                    ->where("mediators_mediation_cases_status.status", "=", 1)
                    ->first();

                if(empty($mediator) && $status != Mediation_status_log::STATUS_WITHDRAWN){

                    $result['success'] = false;
                    $result['message'] = "Case not accepted by mediator";
                    $result['error'] = "Case not accepted by mediator";
                    return response()->json($result, 400);
                }

                $inv_id = "";
                $inv = InvoledUser::select('id')->where('userPlanId', $caseid)->get();
                foreach ($inv as $v) {
                    if ($inv_id == "") {
                        $inv_id = $v->id;
                    } else {
                        $inv_id = $inv_id . "," . $v->id;
                    }
                }

                if (Mediation_status_log::STATUS_WITHDRAWN == $status) {

                        Common_function::MedNotification($caseid, "WDRN_BY_ADMIN", $userId, isset($mediator) ? $mediator->id : null, null);
                   
                } else if (Mediation_status_log::STATUS_RESOLVED == $status) {

                        Common_function::MedNotification($caseid, "RES_BY_ADMIN", $userId, isset($mediator) ? $mediator->id : null, null);
                   
                } else if (Mediation_status_log::STATUS_UNRESOLVED == $status) {

                        Common_function::MedNotification($caseid, "UNRES_BY_ADMIN", $userId, isset($mediator) ? $mediator->id : null, null);
                }

                if(Mediation_status_log::STATUS_WITHDRAWN  != $status){

                    if ($request->hasFile('Settelmentfiles')) {

                        $Settelmentfiles = $request->file('Settelmentfiles');
                        $insert = [];

                        foreach ($request->file('Settelmentfiles') as $file) {
                            
                            $filename = pathinfo(str_replace(" ", "_", $file->getClientOriginalName()), PATHINFO_FILENAME)
                                . "_date_" . date("YmdHis") . "." . $file->getClientOriginalExtension();

                            $savePath = "mediation_documents/mediation/{$caseid}/settelmentDocument/{$filename}";

                            Storage::disk('s3')->put($savePath, file_get_contents($file));

                            $insert[] = [
                                'file_path'        => $filename,
                                'uploaded_by'      => $userId,
                                'mediation_case_id'=> $caseid,
                                'created_at'      => now(),
                            ];
                        }

                        DB::table('document_settlements')->insert($insert);
                        
                        $mediatorNoti = Mediators_mediation_cases_status::select("email", "username", "mobile_number", "users.id")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                            ->where("mediators_mediation_cases_status.mediation_case_id", "=", $caseid)
                            ->where("mediators_mediation_cases_status.status", "=", 1)
                            ->first();
                        $inv_id = "";
                        $inv = InvoledUser::select('id')->where('userPlanId', $caseid)->get();
                        foreach ($inv as $v) {
                            if ($inv_id == "") {
                                $inv_id = $v->id;
                            } else {
                                $inv_id = $inv_id . "," . $v->id;
                            }
                        }
                        Common_function::MedNotification($caseid, "SEND_SETT_AGRE_ADMIN", $userId, isset($mediatorNoti) ? $mediatorNoti->id : null, $inv_id);

                        $this->send_settlement_agreement_party($caseid, $insert);

                    } else {

                            $result['success'] = false;
                            $result['message'] = "Settlement file field is required";
                            $result['error'] = "No valid files found";
                            return response()->json($result, 400);
                    }

                }

                $user = MedCase::find($caseid);
                $user->confirm_status = 2;
                $user->case_status = $status;
                $user->withdraw = ($comment != null) ? $comment : "";

                if ($user->save()) {

                    $mediation_status_log = new Mediation_status_log;
                    $mediation_status_log->user_id = $userId;
                    $mediation_status_log->mediation_case_id = $caseid;
                    $mediation_status_log->status = ($status != null) ? $status : "";

                    if (Mediation_status_log::STATUS_WITHDRAWN == $status) {
                        $mediation_status_log->description = "Request Withdrawn";
                            $this->sned_withdrawal($caseid, $user->stop_close_ip, $user->stop_close_rp, $user->stop_close_med);
                    } else if (Mediation_status_log::STATUS_RESOLVED == $status) {
                        $mediation_status_log->description = "Request Resolved";
                            $this->sned_resolved($caseid, $user->stop_close_ip, $user->stop_close_rp, $user->stop_close_med);

                    } else if (Mediation_status_log::STATUS_UNRESOLVED == $status) {
                        $mediation_status_log->description = "Request Unresolved";
                            $this->sned_unresolved($caseid, $user->stop_close_ip, $user->stop_close_rp, $user->stop_close_med);
                    }
                    $mediation_status_log->save();


                    $final['caseid'] = $caseid;
                    $final['status'] = $status;

                    if($status == 5) {
                        $s_text = "Withdrawn";
                    } else if($status == 6) {
                        $s_text = "Resolved";  
                    } else if ($status == 7) {
                        $s_text = "Unresolved"; 
                    }
                    $final['status_text'] = $s_text;
                    $final['comment'] = $comment;


                    $result['success'] = true;
                    $result['message'] = "Case closed successfully.";
                    $result['data'] = $final;
                    return response()->json($result, 200);
                }

            }
        } catch (Exception $e) {

            $result['success'] = false;
            $result['message'] = "Case closing process is failed.";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
        }
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
            $filesE[] = 'mediation_documents/mediation/' . $id . '/settelmentDocument/' . $f["file_path"];
        }
        foreach ($involedUser as $inv) {
            if ($inv->userEmail != "") {
                $sendEamils[] = $inv->userEmail;
            }
        }
        if ($mediator) {
            $d1 = [
                'event' => 'SEND_SETT_AGRE_MED',
                'case_id' => $id,
            ];
            SendGrid::send($d1, $mediator->email, env('L21_SETTLEMENT_AGREEMENT_ALL_PARTIES', ''), ["-caseid-" => $mid], null, $filesE);

        }

        foreach ($sendEamils as $email) {
            SendGrid::send($d, $email, env('L21_SETTLEMENT_AGREEMENT_ALL_PARTIES', ''), ["-caseid-" => $mid], null, $filesE);
        }

        return true;
    }

    public function sessionPdf(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'caseId' => 'required|integer',
            ]);

        if ($validator->fails()) {

            $errors = $validator->errors()->all();

            $result['success'] = false;
            $result['message'] = implode(', ', $errors);
            $result['error'] = $validator->errors();
            return response()->json($result, 422);
        }

        $caseId = $request->input('caseId');

        $data["case"] = MedCase::find($caseId);
        $data["party"] = InvoledUser::where("userPlanId", "=", $caseId)->get();
        $data["mediator"] = Mediators_mediation_cases_status::select("email", "username", "mobile_number", "users.first_name", "users.last_name")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->where("mediators_mediation_cases_status.mediation_case_id", "=", $caseId)
            ->where("mediators_mediation_cases_status.status", "=", 1)
            ->first();
        $data['caseId'] = $caseId;
        $data["sessionData"] = DB::table('manage_session')->where('case_id', $caseId)->get();
        $pdf = PDF::loadView('pdf.view_session', $data);
        return $pdf->download('session_CID' . sprintf('%06d', $caseId) . '.pdf');

    }

    public function caseAccept(Request $request)
    {
        try{

            $token = $request->cookie('auth_token');
            if (!$token) {

                $result['success'] = false;
                $result['message'] = 'Unauthorized: Missing token';
                $result['error'] = 'Unauthorized: Missing token';
                return response()->json($result, 401);
            }

            $JWT_KEY = env('JWT_KEY');
            $jwtData = JWT::decode($token, new Key(base64_decode($JWT_KEY), 'HS512'));
            $userId = $jwtData->data->userid;

            $validator = Validator::make($request->all(), [
                'caseid' => 'required|integer',
                'status' => 'required|integer',
                'consent1' => 'required',
                'consent2' => 'required',
                'consent3' => 'required',
                'consent4' => 'required',
                'consent5' => 'required',
                'particulars1' => 'required',
                'particulars2' => 'required',
                'particulars3' => 'required',
            ], [
                'consent1.required' => 'consent 1 is required.',
                'consent2.required' => 'consent 2 is required.',
                'consent3.required' => 'consent 3 is required.',
                'consent4.required' => 'consent 4 is required.',
                'consent5.required' => 'consent 5 is required.',
                'particulars1.required' => 'Experience is required.',
                'particulars2.required' => 'Disclosur 1 is required.',
                'particulars3.required' => 'Disclosur 2 is required.',
            ]);


            if ($validator->fails()) {

                $errors = $validator->errors()->all();

                $result['success'] = false;
                $result['message'] = implode(', ', $errors);
                $result['error'] = $validator->errors();
                return response()->json($result, 422);
            }

            $caseid = $request->input('caseid');
            $status = $request->input('status');

            $inv_id = "";
            $inv = InvoledUser::select('id')->where('userPlanId', $caseid)->get();
            foreach ($inv as $v) {

                if ($inv_id == "") {
                    $inv_id = $v->id;
                } else {
                    $inv_id = $inv_id . "," . $v->id;
                }
            }

            if ($status == 1) {

                Common_function::MedNotification($caseid, "SEND_APPO_MED", $userId, $userId, $inv_id);
            } else {

                $resultData['caseid']=$caseid;

                $result['success'] = false;
                $result['message'] = "Status value is wrong";
                $result['error'] = "Status value is wrong";
                return response()->json($result, 500);
                
            }

            $consentDisclosures = ConsentDisclosures::where("mediation_case_id", "=", $caseid)->first();

            if (empty($consentDisclosures)) {

                $consentDisclosures = new ConsentDisclosures();
                $consentDisclosures->mediation_case_id = $caseid;
                $consentDisclosures->mediator_id = $userId;
                $consentDisclosures->consent1 = $request->input('consent1');
                $consentDisclosures->consent2 = $request->input('consent2');
                $consentDisclosures->consent3 = $request->input('consent3');
                $consentDisclosures->consent4 = $request->input('consent4');
                $consentDisclosures->consent5 = $request->input('consent5');
                $consentDisclosures->particulars1 = $request->input('particulars1');
                $consentDisclosures->particulars2 = $request->input('particulars2');
                $consentDisclosures->particulars3 = $request->input('particulars3');

            } else {

                $consentDisclosures->mediation_case_id = $caseid;
                $consentDisclosures->mediator_id = $userId;
                $consentDisclosures->consent1 = $request->input('consent1');
                $consentDisclosures->consent2 = $request->input('consent1');
                $consentDisclosures->consent3 = $request->input('consent1');
                $consentDisclosures->consent4 = $request->input('consent1');
                $consentDisclosures->consent5 = $request->input('consent1');
                $consentDisclosures->particulars1 = $request->input('particulars1');
                $consentDisclosures->particulars2 = $request->input('particulars2');
                $consentDisclosures->particulars3 = $request->input('particulars3');
                $consentDisclosures->updated_at = now();
            }
            $consentDisclosures->save();

            $case_type = 0; // individual
            
            $this->send_attechment_party($caseid, $case_type);

            $insertdata= DB::table('mediators_mediation_cases_status')
                ->where('mediator_id', $userId)
                ->where('mediation_case_id', $caseid)
                ->update(['status' => $status, 'updated_at' => now()]);

            if ($insertdata) {

                $resultData['caseid']=$caseid;

                $result['success'] = true;
                $result['message'] = "Case accepted successfully.";
                $result['data'] = $resultData;
                return response()->json($result, 200);
                
            } else {

                $resultData['caseid']=$caseid;

                $result['success'] = false;
                $result['message'] = "Case not accepted, Please try gain";
                $result['error'] = "Something went wrong";
                $result['data'] = $resultData;
                return response()->json($result, 500);
            }
        } catch (Exception $e) {

            $result['success'] = false;
            $result['message'] = "Case not accepted, Please try gain";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
        }
    }

    public function caseReject(Request $request)
    {
        try{

            $token = $request->cookie('auth_token');
            if (!$token) {

                $result['success'] = false;
                $result['message'] = 'Unauthorized: Missing token';
                $result['error'] = 'Unauthorized: Missing token';
                return response()->json($result, 401);
            }

            $JWT_KEY = env('JWT_KEY');
            $jwtData = JWT::decode($token, new Key(base64_decode($JWT_KEY), 'HS512'));
            $userId = $jwtData->data->userid;

            $validator = Validator::make($request->all(), [
                'caseid' => 'required|integer',
                'status' => 'required|integer',
            ]);


            if ($validator->fails()) {

                $errors = $validator->errors()->all();

                $result['success'] = false;
                $result['message'] = implode(', ', $errors);
                $result['error'] = $validator->errors();
                return response()->json($result, 422);
            }

            $caseid = $request->input('caseid');
            $status = $request->input('status');

            $inv_id = "";
            $inv = InvoledUser::select('id')->where('userPlanId', $caseid)->get();
            foreach ($inv as $v) {

                if ($inv_id == "") {
                    $inv_id = $v->id;
                } else {
                    $inv_id = $inv_id . "," . $v->id;
                }
            }

            Common_function::MedNotification($caseid, "REJECTED_MED", $userId, $userId, $inv_id);
        
            $insertdata = DB::table('mediators_mediation_cases_status')
                ->where('mediator_id', $userId)
                ->where('mediation_case_id', $caseid)
                ->update(['status' => $status, 'updated_at' => now()]);

            if ($insertdata) {

                    $resultData['caseid']=$caseid;
                    $result['success'] = true;
                    $result['message'] = "Case rejected successfully.";
                    $result['data'] = $resultData;
                    return response()->json($result, 200);
                
            } else {

                $resultData['caseid']=$caseid;

                $result['success'] = false;
                $result['message'] = "Case not accepted, Please try gain";
                $result['error'] = "Something went wrong";
                return response()->json($result, 500);
            }
        } catch (Exception $e) {

            $result['success'] = false;
            $result['message'] = "Case closing process is failed.";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
        }
    }

    public function send_attechment_party($id, $case_type)
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
        $file_name = "CID" . sprintf("%06d", $id) . "_party.pdf";
        // Storage::put('public/mediation/' . $data["case"]->id . '/' . $file_name, $pdf->output());
        $savePath = 'mediation_documents/mediation/' . $data["case"]->id;
        $finalFilePath = $savePath . '/' . $file_name;
        // Storage::put('public/mediation/' . $data["case"]->id . '/' . $name, $pdf->output());
        $uploadS3 = $this->uploadOnAWSDirect($finalFilePath, $savePath, $pdf);
        $data["consent_disclosures"]->file_name = $file_name;
        $data["consent_disclosures"]->save();
        $involedUser = InvoledUser::where("userPlanId", $id)->get();
        $mid = "CID" . sprintf("%06d", $id);
        $d = [
            'event' => 'SEND_APPO_MED',
            'case_id' => $id,
        ];
        $whatsappSend = Storage::disk('s3')->url($finalFilePath);
        // dd($uploadS3);
        if ($mediator) {
            if($case_type == 0){
                SendGrid::send($d, $mediator->email, env('L18_MEDIATOR_ACCEPTANCE_ALL_PARTIES', ''), ["-caseid-" => $mid], null, $finalFilePath);
            }
        }
        foreach ($involedUser as $inv) {

            if ($inv->userEmail != "") {
                if($case_type == 1 && $inv->isClaimant != 0){
                    SendGrid::send($d, $inv->userEmail, env('L18_MEDIATOR_ACCEPTANCE_ALL_PARTIES', ''), ["-caseid-" => $mid], null, $finalFilePath);
                } elseif($case_type == 0){
                    SendGrid::send($d, $inv->userEmail, env('L18_MEDIATOR_ACCEPTANCE_ALL_PARTIES', ''), ["-caseid-" => $mid], null, $finalFilePath);
                }
                
            }
        }

        return true;
    }

}
