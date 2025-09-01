<?php

namespace App\Http\Controllers\API\Admin;

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
use App\Http\Helpers\SendGrid;
use App\Http\Traits\UploadTrait;
use PDF;
use DateTime;
use DateTimeZone;
use Carbon\Carbon;
use App\Http\Helpers\Zoom;


class CaseController extends Controller 
{

    public function newreq(Request $request){

        $start   = $request->input('iDisplayStart', 0);    // offset
        $length  = $request->input('iDisplayLength', 10);  // limit
        $search  = $request->input('sSearch', '');
        //$batch_id = $request->input('batch_id', null);
        $sortOrder = $request->input('SortOrder', 'desc'); // asc or desc
        $columnName = $request->input('columnName', ''); 
        

        $role = 2; // admin
        $bulk = 0;

        $cases = MedCase::getCaseApi($role, $bulk, $start, $length, $search, $columnName, $sortOrder);

        //dd($cases);

       // $arraydata = array();
        $data = array();
        if(count($cases) > 0) {
        foreach ($cases as $key => $values) {
            
                $id = $values->id;
                $keyInc = $key + 1;
                $data[$key]['id'] = $id;
                $data[$key]['keyInc'] = $keyInc;
                $data[$key]['batch_id'] = $values->batch_id;
                $data[$key]['ref_id'] = $values->ref_id;
                $data[$key]['confirm_status'] = $values->confirm_status;
                $data[$key]['case_status'] = $values->case_status;
                $data[$key]['created_at'] = $values->created_at;
                $data[$key]['mediator_name'] = $values->mediator_name;
                $data[$key]['mediator_id'] = $values->mediator_id;
                $data[$key]['mediator_status'] = $values->mediator_status;
                $data[$key]['batch_name'] = $values->batch_name;

                // Claimants
                $claimants = InvoledUser::select('user_involved_in_agreement.name', 'user_involved_in_agreement.userEmail as email')
                                        ->where('user_involved_in_agreement.isClaimant', 1)
                                        ->where('userPlanid', $id)
                                        ->get();
                // Respondents
                $respondents = InvoledUser::select('user_involved_in_agreement.name', 'user_involved_in_agreement.userEmail as email')
                                            ->where('user_involved_in_agreement.isClaimant', 0)
                                            ->where('userPlanid', $id)
                                            ->get();
                $data[$key]['claimants']  = $claimants;
                $data[$key]['respondents'] = $respondents;
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

        $start   = $request->input('iDisplayStart', 0);    // offset
        $length  = $request->input('iDisplayLength', 10);  // limit
        $search  = $request->input('sSearch', '');
        $batch_id = $request->input('batch_id', null);
        $sortOrder = $request->input('SortOrder', 'desc'); // asc or desc
        $columnName = $request->input('columnName', ''); 
        

        $role = 2; // admin
        $bulk = 0;
        $casesData = MedCase::getOgoingCaseApi($role, $bulk, $start, $length, $search, $columnName, $sortOrder , $batch_id);
        $data = array();
        if(count($casesData) > 0) {

            foreach ($casesData as $key => $values) {

                $id = $values->id;
                $keyInc = $key + 1;
                $data[$key]['id'] = $id;
                $data[$key]['caseid'] ='M' . sprintf('%06d', $values->id);
                $data[$key]['keyInc'] = $keyInc;
                $data[$key]['batch_id'] = $values->batch_id;
                $data[$key]['ref_id'] = $values->ref_id;
                $data[$key]['confirm_status'] = $values->confirm_status;
                $data[$key]['case_status'] = $values->case_status;
                $data[$key]['created_at'] = $values->created_at;
                $data[$key]['mediator_name'] = $values->mediator_name;
                $data[$key]['mediator_id'] = $values->mediator_id;
                $data[$key]['mediator_status'] = $values->mediator_status;
                $data[$key]['batch_name'] = $values->batch_name;

            // Claimants
          /*   $claimants = InvoledUser::select('user_involved_in_agreement.name', 'user_involved_in_agreement.userEmail as email')
                                    ->where('user_involved_in_agreement.isClaimant', 1)
                                    ->where('userPlanid', $id)
                                    ->get();
            // Respondents
            $respondents = InvoledUser::select('user_involved_in_agreement.name', 'user_involved_in_agreement.userEmail as email')
                                        ->where('user_involved_in_agreement.isClaimant', 0)
                                        ->where('userPlanid', $id)
                                        ->get(); */
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

        $start   = $request->input('iDisplayStart', 0);    // offset
        $length  = $request->input('iDisplayLength', 10);  // limit
        $search  = $request->input('sSearch', '');
        $batch_id = $request->input('batch_id', null);
        $sortOrder = $request->input('SortOrder', 'desc'); // asc or desc
        $columnName = $request->input('columnName', ''); 
        

        $role = 2; // admin
        $bulk = 0;
        $casesData = MedCase::getClosedCaseApi($role, $bulk, $start, $length, $search, $columnName, $sortOrder , $batch_id);
        $data = array();
        if(count($casesData) > 0) {

            foreach ($casesData as $key => $values) {

                $id = $values->id;
                $keyInc = $key + 1;
                $data[$key]['id'] = $id;
                $data[$key]['caseid'] ='M' . sprintf('%06d', $values->id);
                $data[$key]['keyInc'] = $keyInc;
                $data[$key]['batch_id'] = $values->batch_id;
                $data[$key]['ref_id'] = $values->ref_id;
                $data[$key]['confirm_status'] = $values->confirm_status;
                $data[$key]['case_status'] = $values->case_status;
                $data[$key]['created_at'] = $values->created_at;
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

        $start   = $request->input('iDisplayStart', 0);    // offset
        $length  = $request->input('iDisplayLength', 10);  // limit
        $search  = $request->input('sSearch', '');
        $batch_id = $request->input('batch_id', null);
        $sortOrder = $request->input('SortOrder', 'desc'); // asc or desc
        $columnName = $request->input('columnName', ''); 
        

        $role = 2; // admin
        $bulk = 0;
        $casesData = MedCase::getRejectedCaseApi($role, $bulk, $start, $length, $search, $columnName, $sortOrder , $batch_id);
        $data = array();
        if(count($casesData) > 0) {

            foreach ($casesData as $key => $values) {

                $id = $values->id;
                $keyInc = $key + 1;
                $data[$key]['id'] = $id;
                $data[$key]['caseid'] ='M' . sprintf('%06d', $values->id);
                $data[$key]['keyInc'] = $keyInc;
                $data[$key]['batch_id'] = $values->batch_id;
                $data[$key]['ref_id'] = $values->ref_id;
                $data[$key]['confirm_status'] = $values->confirm_status;
                $data[$key]['case_status'] = $values->case_status;
                $data[$key]['created_at'] = $values->created_at;
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


    public function mediatorList() {
        $users = User::select('id as mediator_id', DB::raw("CONCAT(users.first_name,' ',users.last_name, ' - ', users.organization) as mediator_name"),)->where("role", "=", 1)->get();
        $result['success'] = true;
        $result['message'] = "Mediators fetched successfully.";
        $result['data'] = $users;
        return response()->json($result, 200);
    }


    public function mediatorAssign(Request $request) {
        if ($request->input('mediator_id') != null) {
            $inv = InvoledUser::select('user_involved_in_agreement.*', 'users.address as useraddress', 'users.address1 as useraddress1', 'users.pincode as userpincode', 'users.city as usercity', 'users.state as userstate', 'users.country as usercountry')
                ->leftJoin("users", "users.id", "=", "user_involved_in_agreement.userId")
                ->where(['user_involved_in_agreement.userPlanid' => $request->input('caseid')])->get();

            $mid = "M" . sprintf("%06d", $request->input('caseid'));




            if ($inv[0]->address1 != null || $inv[0]->useraddress != null) {
                $medcase = MedCase::find($request->input('caseid'));

                $data = Mediators_mediation_cases_status::where("mediation_case_id", "=", $request->input('caseid'))
                    ->where(function ($q) {
                        $q->where("status", "=", 0)
                            ->orWhere("status", "=", 1);
                    })
                    ->count();
                if ($data == 0) {
                    Mediators_mediation_cases_status::create([
                        'mediator_id' => $request->input('mediator_id'),
                        'mediation_case_id' => $request->input('caseid'),
                        'status' => 0,
                        'user_type' => 1,
                    ]);
                } else {
                    $MedCaseStatus = Mediators_mediation_cases_status::where(function ($q) {
                        $q->where("status", "=", 0)
                            ->orWhere("status", "=", 1);
                    })
                        ->where("mediation_case_id", "=", $request->input('caseid'))
                        ->first();
                    $MedCaseStatus->mediator_id = $request->input('mediator_id');
                    $MedCaseStatus->status = 0;
                    $MedCaseStatus->save();
                }

                //generate pdf

                /*
                
                $invitation = $this->mediator_appointment($request->id, $request->midater);
                
                
                $invmodel = InvitationFiles::where('case_id', $request->id)->orderByDesc('id')->limit(1)->first();

                if (!isset($invmodel)) {
                    $invmodel = new InvitationFiles();
                }
                $invmodel->case_id = $request->id;
                $invmodel->file_name_mediator_appointment = $invitation;
                $invmodel->save();

                if ($medcase->bulk_flag == 0 && $medcase->stop_itm_med == 0) {
                    $this->send_mediatorAdd($request->id, $request->midater);
                }

                */

                $finaldata['mediator_id'] = $request->input('mediator_id');
                $result['success'] = true;
                $result['message'] = "Mediators assigned successfully.";
                


                // confirm status for case

                $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number", "users.id")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                    ->where("mediators_mediation_cases_status.mediation_case_id", "=", $request->input('caseid'))
                    
                    ->first();
                    $inv_id = "";
                   
                    $inv = InvoledUser::select('user_involved_in_agreement.*', 'users.address as useraddress', 'users.address1 as useraddress1', 'users.pincode as userpincode', 'users.city as usercity', 'users.state as userstate', 'users.country as usercountry')
                        ->leftJoin("users", "users.id", "=", "user_involved_in_agreement.userId")
                        ->where(['user_involved_in_agreement.userPlanid' => $request->input('caseid')])->get();
                    foreach ($inv as $v) {
                        if ($inv_id == "") {
                            $inv_id = $v->id;
                        } else {
                            $inv_id = $inv_id . "," . $v->id;
                        }
                    }

                    $medCas = MedCase::find($request->input('caseid'));
                    $medCas->confirm_status = 1;
                    $medCas->case_status = 1;
                    /*** Discussion field : START ***/
                    $medCas->discussion = $request->input('discussion_text');
                    /*** Discussion field : END ***/
                    $medCas->save();

                    $mediation_status_log = new Mediation_status_log;
                    $mediation_status_log->user_id = $request->input('userid');
                    $mediation_status_log->mediation_case_id = $request->input('caseid');
                    $mediation_status_log->status = 1;
                    $mediation_status_log->description = "Request Confirm";
                    $mediation_status_log->save();

                    // $reminder = new Reminder;
                    // $reminder->case_Id = $request->id;
                    // $reminder->save();

                    $finaldata['discussion'] = $request->input('discussion_text');
                    $finaldata['caseid'] = $request->input('caseid');
                    $result['data'] = $finaldata;
                    return response()->json($result, 200);

            }
        }
    }


    public function viewCaseDetails(Request $request){

        $case = MedCase::select("mediation_case.*", "users.first_name as mfirstname", "users.last_name as mlastname", "mediators_mediation_cases_status.mediator_id as mediator_id", "mediators_mediation_cases_status.status as mediator_status")
            ->leftJoin("mediators_mediation_cases_status", "mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id")
            ->leftJoin("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->where('mediation_case.id', $request->caseid)
            ->first();
        
        $party_details = InvoledUser::select('user_involved_in_agreement.*', 'users.address as useraddress', 'users.address1 as useraddress1', 'users.pincode as userpincode', 'users.city as usercity', 'users.state as userstate', 'users.country as usercountry')
            ->leftJoin("users", "users.id", "=", "user_involved_in_agreement.userId")
            ->where(['user_involved_in_agreement.userPlanid' => $case->id])->get();

        $claimants = array();
        $respondents = array();
        foreach($party_details as $key => $party) {
            if($party->isClaimant == 0){
                $claimants['name'] = $party->name;
                $claimants['email'] = $party->userEmail;
                $claimants['phone'] = $party->userPhone;

                if($party->address1 != null){
                   $claimants['address'] = $party->address1 . ' ' . $party->address2 . ' ' . $party->city . ', ' . $party->pincode . ', ' . $party->state . ' ' . $party->country;
                } else if($party->fulladdress) {
                    $claimants['address'] = $party->fulladdress;
                } else {
                    $claimants['address'] = $party->useraddress . ' ' . $party->useraddress1 . ' ' . $party->usercity . ', ' . $party->userpincode . ', ' . $party->userstate . ' ' . $party->usercountry;
                }

            }
            
            if($party->isClaimant != 0){
                $respondents[$key]['name'] = $party->name;
                $respondents[$key]['email'] = $party->userEmail;
                $respondents[$key]['phone'] = $party->userPhone;

                 if($party->address1 != null){
                   $respondents[$key]['address'] = $party->address1 . ' ' . $party->address2 . ' ' . $party->city . ', ' . $party->pincode . ', ' . $party->state . ' ' . $party->country;
                } else if($party->fulladdress) {
                    $respondents[$key]['address'] = $party->fulladdress;
                }
            }
        }

        $case->claimants = $claimants;
        $case->respondents = $respondents;

        $case->invitation = InvitationFiles::where(['case_id' => $case->id])->orderByDesc('id')->get();

        $case->appointment = InvitationFiles::where(['case_id' => $case->id])->where('file_name_mediator_appointment', '!=', null)->orderByDesc('id')->limit(1)->first();

        $case->supporting_document = DB::table('manage_files')->select('manage_files.*', 'users.username')
            ->join('users', 'users.id', '=', 'manage_files.uploaded_by')
            ->where('manage_files.case_id', $case->id)
            ->get();
        $case->restructureFile = DB::table('restructure_data')->select('restructure_data.*')
            ->where('restructure_data.caseid', $case->id)
            ->first();


          
        $case->mom = DB::table('session_mom')->select("file_name")->where('case_id', $case->id)->get();


        $result['success'] = true;
        $result['message'] = "Case details fetched successfully.";
        $result['data'] = $case;
        return response()->json($result, 200);
        
    }


    public function fetchCase(Request $request) {
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
                'caseid' => 'required|integer'
            ]);

            //Inputs
            $caseid = $request->input('caseid');
            if ($validator->fails()) {

                $errors = $validator->errors()->all();

                $result['success'] = false;
                $result['message'] = implode(', ', $errors);
                $result['error'] = $validator->errors();
                return response()->json($result, 422);
            }


            $med = MedCase::find($caseid);
            if (!$med) {
                return abort(404);
            }
            //$usr = User::find($med->userid);
            $response = '';

            //fetch all involved users
            $InvoledUser = InvoledUser::where(['userPlanId' => $med->id])->get();
        
            $claimants = array();
            $respondents = array();
            foreach($InvoledUser as $key => $party) {
                if($party->isClaimant == 0){
                    $claimants[$key]['name'] = $party->name;
                    $claimants[$key]['email'] = $party->userEmail;
                    $claimants[$key]['phone'] = $party->userPhone;

                    if($party->address1 != null){
                    $claimants[$key]['address'] = $party->address1 . ' ' . $party->address2 . ' ' . $party->city . ', ' . $party->pincode . ', ' . $party->state . ' ' . $party->country;
                    } else if($party->fulladdress) {
                        $claimants[$key]['address'] = $party->fulladdress;
                    } else {
                        $claimants[$key]['address'] = $party->useraddress . ' ' . $party->useraddress1 . ' ' . $party->usercity . ', ' . $party->userpincode . ', ' . $party->userstate . ' ' . $party->usercountry;
                    }
                }

                if($party->isClaimant != 0){
                    $respondents[$key]['name'] = $party->name;
                    $respondents[$key]['email'] = $party->userEmail;
                    $respondents[$key]['phone'] = $party->userPhone;

                    if($party->address1 != null){
                    $respondents[$key]['address'] = $party->address1 . ' ' . $party->address2 . ' ' . $party->city . ', ' . $party->pincode . ', ' . $party->state . ' ' . $party->country;
                    } else if($party->fulladdress) {
                        $respondents[$key]['address'] = $party->fulladdress;
                    }
                }
            }

            $data['case'] = $med;
            $data['claimants'] = $claimants;
            $data['respondents'] = $respondents;

            $result['success'] = true;
            $result['message'] = "Case details by id fetched successfully.";
            $result['data'] = $data;
            return response()->json($result, 200);
        }catch (Exception $e) {

            $result['success'] = false;
            $result['message'] = "Fetching case data failded";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
        }
    }


    public function caseUpdate(Request $request){
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
                'proposedSolution' => 'string|max:255',
                'issue' => 'string|max:255',
                'application' => 'string|max:255',
                'useraddress' => 'string|max:255',
                'useraddress1' => 'string|max:255',
                'usercity' => 'string|max:255',
                'userpincode' => 'integer',
                'userstate' => 'string|max:255',
                'usercountry' => 'string|max:255',
            ]);

            //Inputs
            $caseid = $request->input('caseid');
            $proposedSolution = $request->input('proposedSolution');
            $issue = $request->input('issue');
            $application = $request->input('application');
            $useraddress = $request->input('useraddress');
            $useraddress1 = $request->input('useraddress1');
            $usecity = $request->input('usercity');
            $userpincode = $request->input('userpincode');
            $userstate = $request->input('userstate');
            $usercountry = $request->input('usercountry');
           


            if ($validator->fails()) {

                $errors = $validator->errors()->all();

                $result['success'] = false;
                $result['message'] = implode(', ', $errors);
                $result['error'] = $validator->errors();
                return response()->json($result, 422);
            }

            //udpate mediation case
            $med = MedCase::find($caseid);
            $med->proposedSolution = $proposedSolution;
            $med->issue = $issue;

            $med->ref_id = isset($application) ? $application : $med->ref_id;
            $med->updated_at = date("Y-m-d H:i:s");
            $med->save();

            //if user profile update
            $usr = User::find($med->userid);
            $usr->address = $useraddress;
            $usr->address1 = $useraddress1;
            $usr->city = $usercity;
            $usr->pincode = $userpincode;
            $usr->state = $userstate;
            $usr->country = $usercountry;
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

            if(isset($request->email) && count($request->email) ) {
            for ($i = 0; $i < count($request->email); $i++) {

                $invid = $request->input('invid')[$i];

                if ($invid != '') {

                    $inv = InvoledUser::find($invid);
                } else {
                    $inv = new InvoledUser();
                }



                //$inv->userId=;
                // if ($inv->userEmail != $r['email'][$i] || $inv->userPhone != $r['phone'][$i]) {

                //     $inv->joinCode = $this->joinCode();
                // }
                if (isset($request->input('selected_party')[$i]) && $request->input('selected_party')[$i] == 0 ) {
                    $inv->isClaimant = 0;
                    $inv->joinCode = null;
                } else {
                    if ($inv->userEmail != $request->input('email')[$i] || $inv->userPhone != $request->input('phone')[$i]) {

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

                if ($inv->userEmail != $request->input('email')[$i]) {

                    $inv->userEmail = $request->input('email')[$i];

                    // $invitation = $this->invitation_mediate($id);

                    // $invmodel = new InvitationFiles();
                    // $invmodel->case_id = $request->id;
                    // $invmodel->file_name = $invitation;
                    // $invmodel->save();


                    // $code = $inv->joinCode;
                    // $s = SendGrid::send($d, $inv->userEmail, env('L4_INVITATION_TO_COUNTER_PARTIES_FOR_ONBOARDING', ''), ["-caseid-" => "M" . sprintf("%06d", $med->id), "-link-" => $inv->joinCode, "-initiating-" => $pone->name], $inv->name, url("/storage/app/public/mediation/" . $med->id . "/" . $invitation));
                }

                if ($inv->userPhone != $request->input('phone')[$i]) {
                    $inv->userPhone = $request->input('phone')[$i];
                }

                $inv->name = $request->input('name')[$i]; 
                if (isset($request->input('add1')[$i])) {
                    $inv->address1 = $request->input('add1')[$i];

                    if ($request->input('add2')[$i] == '') {
                        $request->input('add2')[$i] = 'null';
                    }
                    $inv->address2 = $request->input('add2')[$i];
                    $inv->city = $request->input('city')[$i];
                    $inv->pincode = $request->input('pincode')[$i];
                    $inv->state = $request->input('state')[$i];
                    $inv->country = $request->input('country')[$i];
                } elseif (isset($request->input('fulladdress')[$i])) {
                    $inv->fulladdress = $request->input('fulladdress')[$i];
                }


                $inv->created_at = date('Y-m-d H:s:i');
                $inv->updated_at = date('Y-m-d H:s:i');


                $inv->save();

                // $s = SendGrid::send($d, $inv->userEmail, env('L4_INVITATION_TO_COUNTER_PARTIES_FOR_ONBOARDING', ''), ["-caseid-" => "M" . sprintf("%06d", $med->id), "-link-" => $inv->joinCode, "-initiating-" => $pone->name], $inv->name, url("/storage/app/public/mediation/" . $med->id . "/" . $invitation));
            }
            }
            //remove involed

            // if ($request->input('rminv') != '') {

            //     foreach (explode(',', $request->input('rminv')) as $key => $value) {

            //         InvoledUser::find($value)->delete();
            //     }
            // }

            $result['success'] = true;
            $result['message'] = "Case data updated successfully.";
            $result['data'] = $request->input('caseid');
            return response()->json($result, 200);
        }  catch (Exception $e) {

            $result['success'] = false;
            $result['message'] = "Case updation failed.";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
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


    public function caseReject(Request $request) {
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
                'caseid'             => 'required|integer',
                'userid'=> 'required|integer'
            ]);

            //Inputs
            $caseid = $request->input('caseid');
            $userid = $request->input('userid');

            if ($validator->fails()) {

                $errors = $validator->errors()->all();

                $result['success'] = false;
                $result['message'] = implode(', ', $errors);
                $result['error'] = $validator->errors();
                return response()->json($result, 422);
            }

            $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number", "users.id")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
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
            Common_function::MedNotification($caseid, "REJECTED_ADM", $userid, isset($mediator) ? $mediator->id : null, $inv_id);
        
        
            $user = MedCase::find($caseid);
            $user->confirm_status = 3;
            $user->case_status = 3;

            if ($user->save()) {
                $mediation_status_log = new Mediation_status_log;
                $mediation_status_log->user_id = $request->input('userid');
                $mediation_status_log->mediation_case_id = $request->input('caseid');
                $mediation_status_log->status = 3;
                $mediation_status_log->description = "Request Reject";
                $mediation_status_log->save();

                // Email notification
                //$this->sned_reject($request->input('caseid'));

                $data['caseid'] = $user->id;
                
            }

            $result['success'] = true;
            $result['message'] = "Case rejected successfully.";
            $result['data'] = $data;
            return response()->json($result, 200);
        } catch (Exception $e) {

            $result['success'] = false;
            $result['message'] = "Case rejection failed.";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
        }
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
                'zoom_choice'        => 'required|string|in:directly_zoom,custom_zoom,other',
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
                //print_r($create_zoom_meeting);die();
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

                        $id = "M" . sprintf("%06d", $caseId);

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
                        $id = "M" . sprintf("%06d", $caseId);

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
                    $result['message'] = "Zoom Meeting Creataion failed.";
                    $result['error'] = "Zoom Meeting Creataion failed.";
                    return response()->json($result, 200);
                }
            }

            return true;

        } catch (Exception $e) {

            $result['success'] = false;
            $result['message'] = "Zoom meeting creation failed.";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
        }
    }

    public function sned_session($url, $id, $email_id, $email_name, $date, $userPhone, $userType){

        $mid = "M" . sprintf("%06d", $id);
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
        $mid = "M" . sprintf("%06d", $id);
        $d = [
            'event' => 'SESS_SCHE',
            'case_id' => $id,
        ];
        if ($email_id != "") {
            SendGrid::send($d, $email_id, env('L10_SCHEDULING_OF_SESSION', ''), ["-caseid-" => $mid, "-insert_date-" => $date, "-type-" => $userType, "-zoom_invitation_link-" => $invitation], $email_name);
        }

        return true;
    }

    public function showMomSession(Request $request) {
        
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
            
            $validator = Validator::make($request->all(), [
                'caseid' => 'required|integer',
                'sessionid' => 'integer'
            ]);

            //Inputs
            $caseid = $request->input('caseid');
            $sessionid = $request->input('sessionid');

            if ($validator->fails()) {

                $errors = $validator->errors()->all();

                $result['success'] = false;
                $result['message'] = implode(', ', $errors);
                $result['error'] = $validator->errors();
                return response()->json($result, 422);
            }

            $if_check = DB::table('session_mom')->where('case_id', $caseid)->where('session_id', $sessionid)->first();
            
            $ip_array = array();
            $rp_array = array();
            if(empty($if_check)){
                $partyArray = InvoledUser::where('userPlanId', $caseid)->get();
                
            
                foreach($partyArray as $party){
                    if($party['isClaimant'] === 0){
                        if(!in_array($party['name'], $ip_array)){
                            array_push($ip_array, $party['name']);
                        }
                    } else if($party['isClaimant'] > 0){
                    
                        if(!in_array($party['name'], $rp_array)){
                            array_push($rp_array, $party['name']);
                        }
                    } 

                    
                    
                }
            
                $data['ip_name'] = $ip_array;
                $data['rp_name'] = $rp_array;
                $data['minutes'] = "";
                $data['next'] = "";

                $data['selected_id'] = "";
            
            } else {
                $data['ip_name'] = $if_check->ip_name;
                $data['rp_name'] = $if_check->rp_name;
                $data['minutes'] = $if_check->minutes;
                $data['next'] = $if_check->next_steps;

                $data['selected_id'] = $if_check->share_with_party_ids;
            }

            $mediator = Mediators_mediation_cases_status::select("first_name", "last_name")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->where("mediators_mediation_cases_status.mediation_case_id", "=", $request['caseid'])
            ->first();

            $data['mediator'] = $mediator['first_name'] .' '. $mediator['last_name'];

            $data['party_array'] = InvoledUser::select('id', 'name')->where('userPlanId', $request['caseid'])->get();

            
        
            $result['success'] = true;
            $result['message'] = "Session MOM data fetched successfully.";
            $result['data'] = $data;
            return response()->json($result, 200);
        } catch (Exception $e) {

            $result['success'] = false;
            $result['message'] = "Session MOM data failed.";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
        }
    }


    public function momDataSubmit(Request $request) {
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
                'caseid' => 'integer',
                'sessionid' => 'integer',
                'ip_name' => 'text',
                'rp_name' => 'text',
                'minutes' => 'integer',
                'next_steps' => 'text',
                'mediator' => 'text',
                'session_party_ids'  => 'array',
            ]);

            //Inputs
            $caseid = $request->input('caseid');
            $sessionid = $request->input('sessionid');
            $ip_name = $request->input('ip_name');
            $rp_name = $request->input('rp_name');
            $minutes = $request->input('minutes');
            $next_steps = $request->input('next_steps');
            $mediator = $request->input('mediator');
            $session_party_ids  = $request->input('docs_party_ids');


            if ($validator->fails()) {

                $errors = $validator->errors()->all();

                $result['success'] = false;
                $result['message'] = implode(', ', $errors);
                $result['error'] = $validator->errors();
                return response()->json($result, 422);
            }


            $if_check = DB::table('session_mom')->where('case_id', $caseid)->where('session_id', $sessionid)->first();
            
            if(empty($if_check)){

            
                    $dataToInsert = [
                        'case_id' => $caseid,
                        'session_id' => $sessionid,
                        'ip_name' => $ip_name,
                        'rp_name' => $rp_name,
                        'minutes' => $minutes,
                        'next_steps' => $next_steps,
                        'mediator' => $mediator
                    ];
                    $operationdata = DB::table('session_mom')->insert($dataToInsert);


                    

            } else {
                $dataToUpdate = [
                    'id' => $if_check->id,
                    'case_id' => $caseid,
                    'session_id' => $sessionid,
                    'ip_name' => $ip_name,
                    'rp_name' => $rp_name,
                    'minutes' => $minutes,
                    'next_steps' => $next_steps,
                    'mediator' => $mediator
                ];
                

                $operationdata = DB::table('session_mom')->where('id', $if_check->id)->update($dataToUpdate);
            }

            // generate pdf

            $templateData = array();
           // $templateData['sn'] = $request->MomSn; 
            $templateData['sessid'] = $sessionid; 
            $templateData['caseid'] = $caseid; 
            $templateData['ip'] = $ip_name; 
            $templateData['rp'] = $rp_name; 
            $templateData['minutes'] = $minutes; 
            $templateData['next'] = $next_steps; 
            $templateData['med'] = $mediator; 
            
           // $invitation = $this->session_mom_template($templateData);
           $invitation = "";


            //$preview = $this->tempMOM($templateData);
            $preview = "";
        


            // save file in session mom table 
            DB::table('session_mom')->where('session_id', $sessionid)->update(['file_name' => $invitation]);
            // save file in session mom table 



            
            
            if(isset($operationdata)) {

                // Send notification to party //
            
            if(!empty($session_party_ids)){
            $notification_array = array();
            $notification_array['file_name'] = $invitation;
            $notification_array['access'] = $session_party_ids;
            $notification_array['mediator_access'] = 1;
            $notification_array['uploaded_by'] = $userId;
            $notification_array['case_id'] = $caseid;


                DB::table('session_mom')->where('session_id', $sessionid)->update(['share_with_party_ids' => implode(',',$session_party_ids)]);

                //$this->send_upload_file_party_mom($sessionid, $notification_array);
            }

                $data['caseid'] = $caseid;
                $data['sessionid'] = $sessionid;
                $data['file'] = $invitation;
                $data['preview'] = $preview;
               

                $result['success'] = true;
                $result['message'] = "Minutes of Meeting added successfully.";
                $result['data'] = $data;
                return response()->json($result, 200);

               // return json_encode(['code' => 200, 'response' => 'success', 'file' => $invitation, 'path' => storage_path(), 'preview' => $preview]);
            }
        } catch (Exception $e) {

            $result['success'] = false;
            $result['message'] = "Data submission failded";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
        }
    }


    public function session_mom_template($data)
    {
        $data["case"] = MedCase::select('id', 'discussion', 'otherRespondentDetails')->where("id", "=", $data['caseid'])->first();
        $data["party"] = InvoledUser::select('user_involved_in_agreement.*', 'users.address as useraddress', 'users.address1 as useraddress1', 'users.pincode as userpincode', 'users.city as usercity', 'users.state as userstate', 
        'users.country as usercountry', 'mediation_case.poc_name as userpname', 'mediation_case.poc_email as userpemail', 'mediation_case.poc_contact as userpcontact')
            ->leftJoin("users", "users.id", "=", "user_involved_in_agreement.userId")
            ->leftJoin("mediation_case", "mediation_case.id", "=", "user_involved_in_agreement.userPlanId")
            ->where("user_involved_in_agreement.userPlanId", "=", $data['caseid'])
            ->where("mediation_case.id", "=", $data['caseid'])->get();

        $data['session_mom'] = DB::table('session_mom')->where('session_id', $data['sessid'])->first();
        $session_data = DB::table('manage_session')->select('session_date')->where('id', $data['sessid'])->first();
        //echo "<prE>session==>";print_R($session_data);
        $session_date = explode('/', $session_data->session_date);

        $data['session_date'] = $session_date[0] .'-'.$session_date[1].'-'.$session_date[2];
        $data['session_time'] = $session_date[3];

       
        $data['mediator'] = $data['med'];

        $itm_date = InvitationFiles::select("created_at")->where(['case_id' => $data['caseid']])->orderByDesc('id')->get();

       

         $cdate = new DateTime($itm_date[0]->created_at);
         $data['itm_date'] = $cdate->format('d-m-Y'); 

        
    
        $pdf = PDF::loadView('pdf.session_mom', $data);
        
        $name = 'Minutes_of_the_Meeting_M'. sprintf('%06d', $data['caseid']) . '.pdf';
        
        $savePath = 'mediation_documents/mediation/' . $data['caseid'];
        $finalFilePath = $savePath . '/' . $name;
       // $momSend = Storage::disk('s3')->url($finalFilePath);

       //$s3_local = Storage::disk('local')->writeStream('public/mediation/' . $data['caseid'] . '/' . $name, Storage::disk('s3')->readStream('mediation_documents/mediation/' . $data['caseid'] . '/' . $name));
       $local_store = Storage::disk('local')->put('public/mediation/' . $data['caseid'] . '/' .  $name, $pdf->output());
        // echo $finalFilePath;
        // echo $momSend;
        // exit;
        
       $uploadS3 = $this->uploadOnAWSDirect($finalFilePath, $savePath, $pdf);

      
        return $name;
    }


    public function tempMOM($data){
        $data["case"] = MedCase::select('id', 'discussion', 'otherRespondentDetails')->where("id", "=", $data['caseid'])->first();
        $data["party"] = InvoledUser::select('user_involved_in_agreement.*', 'users.address as useraddress', 'users.address1 as useraddress1', 'users.pincode as userpincode', 'users.city as usercity', 'users.state as userstate', 
        'users.country as usercountry', 'mediation_case.poc_name as userpname', 'mediation_case.poc_email as userpemail', 'mediation_case.poc_contact as userpcontact')
            ->leftJoin("users", "users.id", "=", "user_involved_in_agreement.userId")
            ->leftJoin("mediation_case", "mediation_case.id", "=", "user_involved_in_agreement.userPlanId")
            ->where("user_involved_in_agreement.userPlanId", "=", $data['caseid'])
            ->where("mediation_case.id", "=", $data['caseid'])->get();

        $data['session_mom'] = DB::table('session_mom')->where('session_id', $data['sessid'])->first();
        $session_data = DB::table('manage_session')->select('session_date')->where('id', $data['sessid'])->first();
        //echo "<prE>session==>";print_R($session_data);
        $session_date = explode('/', $session_data->session_date);

        $data['session_date'] = $session_date[0] .'-'.$session_date[1].'-'.$session_date[2];
        $data['session_time'] = $session_date[3];

       
        $data['mediator'] = $data['med'];

        $itm_date = InvitationFiles::select("created_at")->where(['case_id' => $data['caseid']])->orderByDesc('id')->get();

       

         $cdate = new DateTime($itm_date[0]->created_at);
         $data['itm_date'] = $cdate->format('d-m-Y'); 


        return response()->json([
            "html" => view('pdf.session_mom', $data)->render(),
        ]);
    }




    public function send_upload_file_party_mom($id, $files)
    {
        //dd($files);
        $involedUser = InvoledUser::where("userPlanId", $id)->get();
        $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->where("mediators_mediation_cases_status.mediation_case_id", "=", $id)
            ->where("mediators_mediation_cases_status.status", "=", 1)
            ->first();
        $mid = "M" . sprintf("%06d", $id);
        $sendEamils = array();
        //$filesE = array();
        // $access = array();

        $d = [
            'event' => 'SEND_ADDI_DOC',
            'case_id' => $id,
        ];
       // foreach ($files as $f) {
            // $filesE[] = url("storage/app/" . $f["file_name"]);
            $filesE = 'mediation_documents/mediation/' . $id  .'/'. $files["file_name"];

            //$access = explode(',', $files["access"]);
            $access = $files["access"];
            $mediatorAccess = $files["mediator_access"];
        //}
       
        
        if (!empty($sendEamils)) {
            foreach ($sendEamils as $email) {
                SendGrid::send($d, $email, env('L19_ADDITIONAL_DOC_ALL_PARTIES', ''), ["-caseid-" => $mid, "-party_name-" => "Party"], null, $filesE);
            }
        }

        return true;
    }

}
