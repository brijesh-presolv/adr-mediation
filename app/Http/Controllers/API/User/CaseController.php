<?php

namespace App\Http\Controllers\API\User;

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

    public function __construct(Request $request)
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
        $user = User::find($userId);

        if (empty($user->address) || empty($user->city) || empty($user->pincode) || empty($user->country)) {

            $data['userid'] = $user->id;
            $data['role'] = $user->role;
            $data['isprofilecompleted'] = 0;

            abort(response()->json([
                'success' => false,
                'message' => 'Please complete your profile to access this feature.',
                'data' => $data
            ], 403));
        }

    }

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

        $start   = $request->input('iDisplayStart', 0);    // offset
        $length  = $request->input('iDisplayLength', 10);  // limit
        $search  = $request->input('search', '');
        $batch_id = $request->input('batch_id', null);
        $sortOrder = $request->input('SortOrder', 'desc'); // asc or desc
        $columnName = $request->input('columnName', ''); 
        

        $role = 0; // user
        $bulk = 0;

        $cases = MedCase::getNewReqCaseUserApi($role, $bulk, $start, $length, $search, $columnName, $sortOrder, $batch_id, $userId);
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

            $data[$key]['claimants']  = $values->user_involed->where('isClaimant', 0)->values();
            $data[$key]['respondents'] =  $values->user_involed->where('isClaimant', 1)->values();
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

        $start   = $request->input('iDisplayStart', 0);    // offset
        $length  = $request->input('iDisplayLength', 10);  // limit
        $search  = $request->input('search', '');
        $batch_id = $request->input('batch_id', null);
        $sortOrder = $request->input('SortOrder', 'desc'); // asc or desc
        $columnName = $request->input('columnName', ''); 
        

        $role = 0; // user
        $bulk = 0;
        $casesData = MedCase::getOgoingCaseUserApi($role, $bulk, $start, $length, $search, $columnName, $sortOrder , $batch_id, $userId);
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

                $data[$key]['claimants']  = $values->user_involed->where('isClaimant', 0)->values();
                $data[$key]['respondents'] =  $values->user_involed->where('isClaimant', 1)->values();

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

        $start   = $request->input('iDisplayStart', 0);    // offset
        $length  = $request->input('iDisplayLength', 10);  // limit
        $search  = $request->input('search', '');
        $batch_id = $request->input('batch_id', null);
        $sortOrder = $request->input('SortOrder', 'desc'); // asc or desc
        $columnName = $request->input('columnName', ''); 
        

        $role = 0; // user
        $bulk = 0;
        $casesData = MedCase::getClosedCaseUserApi($role, $bulk, $start, $length, $search, $columnName, $sortOrder , $batch_id, $userId);
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


            $data[$key]['claimants']  = $values->user_involed->where('isClaimant', 0)->values();
            $data[$key]['respondents'] =  $values->user_involed->where('isClaimant', 1)->values();

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
        $casesData = MedCase::getRejectedCaseUserApi($role, $bulk, $start, $length, $search, $columnName, $sortOrder , $batch_id, $userId);

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
            $data[$key]['caseid'] ='M' . sprintf('%06d', $values->case_id);
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


}
