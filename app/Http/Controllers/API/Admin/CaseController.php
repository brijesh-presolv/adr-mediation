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

        $start   = $request->input('iDisplayStart', 0);    // offset
        $length  = $request->input('iDisplayLength', 10);  // limit
        $search  = $request->input('search', '');
        //$batch_id = $request->input('batch_id', null);
        $sortOrder = $request->input('SortOrder', 'desc'); // asc or desc
        $columnName = $request->input('columnName', ''); 
        

        $role = 2; // admin
        $bulk = 0;

        $cases = MedCase::getCaseApi($role, $bulk, $start, $length, $search, $columnName, $sortOrder, $batch_id);

        //dd($cases);

       // $arraydata = array();
        $data = array();
        if(count($cases) > 0) {
        foreach ($cases as $key => $values) {
            
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

        $start   = $request->input('iDisplayStart', 0);    // offset
        $length  = $request->input('iDisplayLength', 10);  // limit
        $search  = $request->input('search', '');
        $batch_id = $request->input('batch_id', null);
        $sortOrder = $request->input('SortOrder', 'desc'); // asc or desc
        $columnName = $request->input('columnName', ''); 
        

        $role = 2; // admin
        $bulk = 0;
        $casesData = MedCase::getOgoingCaseApi($role, $bulk, $start, $length, $search, $columnName, $sortOrder , $batch_id);
      // print_r($casesData);die();
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
                $data[$key]['claimants']  = $values->claimants;
                $data[$key]['respondents'] =  $values->respondents;

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
        $search  = $request->input('search', '');
        $batch_id = $request->input('batch_id', null);
        $sortOrder = $request->input('SortOrder', 'desc'); // asc or desc
        $columnName = $request->input('columnName', ''); 
        

        $role = 2; // admin
        $bulk = 0;
        $casesData = MedCase::getClosedCaseApi($role, $bulk, $start, $length, $search, $columnName, $sortOrder, $batch_id);

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

        $start   = $request->input('iDisplayStart', 0);    // offset
        $length  = $request->input('iDisplayLength', 10);  // limit
        $search  = $request->input('search', '');
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


    public function mediatorList() {
        $users = User::select('id as mediator_id', DB::raw("CONCAT(users.first_name,' ',users.last_name, ' - ', users.organization) as mediator_name"),)->where("role", "=", 1)->get();
        $result['success'] = true;
        $result['message'] = "Mediators fetched successfully.";
        $result['data'] = $users;
        return response()->json($result, 200);
    }


    public function mediatorAssign(Request $request) {
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
                'mediator_id' => 'required|integer',
                'discussion_text' => 'string|max:255'
            ]);

            //Inputs
            $caseid = $request->input('caseid');
            $mediator_id = $request->input('mediator_id');
            $discussion_text = $request->input('discussion_text');

            if ($validator->fails()) {

                $errors = $validator->errors()->all();

                $result['success'] = false;
                $result['message'] = implode(', ', $errors);
                $result['error'] = $validator->errors();
                return response()->json($result, 422);
            }




            if ($mediator_id != null) {
                $inv = InvoledUser::select('user_involved_in_agreement.*', 'users.address as useraddress', 'users.address1 as useraddress1', 'users.pincode as userpincode', 'users.city as usercity', 'users.state as userstate', 'users.country as usercountry')
                    ->leftJoin("users", "users.id", "=", "user_involved_in_agreement.userId")
                    ->where(['user_involved_in_agreement.userPlanid' => $caseid])->get();

                $mid = "M" . sprintf("%06d", $caseid);




                if ($inv[0]->address1 != null || $inv[0]->useraddress != null) {
                    $medcase = MedCase::find($caseid);

                    $data = Mediators_mediation_cases_status::where("mediation_case_id", "=", $caseid)
                        ->where(function ($q) {
                            $q->where("status", "=", 0)
                                ->orWhere("status", "=", 1);
                        })
                        ->count();
                    if ($data == 0) {
                        Mediators_mediation_cases_status::create([
                            'mediator_id' => $mediator_id,
                            'mediation_case_id' => $caseid,
                            'status' => 0,
                            'user_type' => 1,
                        ]);
                    } else {
                        $MedCaseStatus = Mediators_mediation_cases_status::where(function ($q) {
                            $q->where("status", "=", 0)
                                ->orWhere("status", "=", 1);
                        })
                            ->where("mediation_case_id", "=", $caseid)
                            ->first();
                        $MedCaseStatus->mediator_id = $mediator_id;
                        $MedCaseStatus->status = 0;
                        $MedCaseStatus->save();
                    }

                    // generate pdf : start //

                    
                    
                    $invitation = $this->mediator_appointment($caseid, $mediator_id);
                    
                    
                    $invmodel = InvitationFiles::where('case_id', $caseid)->orderByDesc('id')->limit(1)->first();

                    if (!isset($invmodel)) {
                        $invmodel = new InvitationFiles();
                    }
                    $invmodel->case_id = $caseid;
                    $invmodel->file_name_mediator_appointment = $invitation;
                    $invmodel->save();

                    if ($medcase->bulk_flag == 0 && $medcase->stop_itm_med == 0) {
                        $this->send_mediatorAdd($caseid, $mediator_id);
                    }
                    
                    // generate pdf : end //

                    $finaldata['mediator_id'] = $mediator_id;
                    $result['success'] = true;
                    $result['message'] = "Mediators assigned successfully.";
                    


                    // confirm status for case

                    $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number", "users.id")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                        ->where("mediators_mediation_cases_status.mediation_case_id", "=", $caseid)
                        
                        ->first();
                        $inv_id = "";
                    
                        $inv = InvoledUser::select('user_involved_in_agreement.*', 'users.address as useraddress', 'users.address1 as useraddress1', 'users.pincode as userpincode', 'users.city as usercity', 'users.state as userstate', 'users.country as usercountry')
                            ->leftJoin("users", "users.id", "=", "user_involved_in_agreement.userId")
                            ->where(['user_involved_in_agreement.userPlanid' => $caseid])->get();
                        foreach ($inv as $v) {
                            if ($inv_id == "") {
                                $inv_id = $v->id;
                            } else {
                                $inv_id = $inv_id . "," . $v->id;
                            }
                        }

                        $medCas = MedCase::find($caseid);
                        $medCas->confirm_status = 1;
                        $medCas->case_status = 1;
                        /*** Discussion field : START ***/
                        $medCas->discussion = $discussion_text;
                        /*** Discussion field : END ***/
                        $medCas->save();

                        $mediation_status_log = new Mediation_status_log;
                        $mediation_status_log->user_id = $userId;
                        $mediation_status_log->mediation_case_id = $caseid;
                        $mediation_status_log->status = 1;
                        $mediation_status_log->description = "Request Confirm";
                        $mediation_status_log->save();

                        // $reminder = new Reminder;
                        // $reminder->case_Id = $request->id;
                        // $reminder->save();

                        $finaldata['discussion'] = $discussion_text;
                        $finaldata['caseid'] = $caseid;
                        $result['data'] = $finaldata;
                        return response()->json($result, 200);

                }
            }
        } catch (Exception $e) {

            $result['success'] = false;
            $result['message'] = "Case approval failed.";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
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

        $ckey = 1;
        $rkey = 1;

        foreach($party_details as $key => $party) {

            if($party->isClaimant == 0){

                $claimants[$ckey++] = [
                    "name"=> $party->name,
                    "email"=> $party->userEmail,
                    "phone"=> $party->userPhone,
                    "address"=> $party->address1,
                    "address2"=> $party->address2,
                    "city"=> $party->city,
                    "pincode"=> $party->pincode,
                    "state"=> $party->state,
                    "country"=> $party->country,
                ];

            }

            
            
            if($party->isClaimant != 0){

                $respondents[$rkey++] = [
                    "name"=> $party->name,
                    "email"=> $party->userEmail,
                    "phone"=> $party->userPhone,
                    "address"=> $party->address1,
                    "address2"=> $party->address2,
                    "city"=> $party->city,
                    "pincode"=> $party->pincode,
                    "state"=> $party->state,
                    "country"=> $party->country,
                ];
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


    public function caseUpdateOriginal(Request $request){
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
                $this->sned_reject($request->input('caseid'));

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

    public function getConsentDisclosures(Request $request)
    {

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

        $casesData = ConsentDisclosures::select('consent_disclosures.*', 'users.first_name', 'users.last_name', 'users.email', 'users.id as medId')->join("users", "consent_disclosures.mediator_id", "=", "users.id")
            ->where("mediation_case_id", "=", $caseId)
            ->get();

        $data = array();
        if(count($casesData) > 0) {

            foreach ($casesData as $key => $values) {

                if ($values->file_name != null) {
                    $dis_file_name = $values->file_name;
                    $exist_file = storage_path() . '/app/public/mediation/' . $caseId . '/' . $dis_file_name;
                } else {
                    $dis_file_name = "M" . sprintf("%06d", $caseId) . "_party.pdf";
                    $exist_file = storage_path() . '/app/public/mediation/' . $caseId . '/' . $dis_file_name;
                }

                $id = $values->id;
                $data[$key]['id'] = $id;
                $data[$key]['caseid'] = $values->mediation_case_id;
                $data[$key]['file_name'] = $dis_file_name;
                $data[$key]['name'] = $values->first_name." ".$values->last_name;
                $data[$key]['created_at'] = $values->created_at;

            }

            $result['success'] = true;
            $result['message'] = "Data fetched successfully.";
            $result['data'] = $data;
             return response()->json($result, 200);

        } else {
            
            $result['success'] = false;
            $result['message'] = "File Not available.";
            $result['error'] = "File Not available.";
             return response()->json($result, 500);
        }
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

    public function downloadDisclosures($id)
    {

        $validator = Validator::make($request->all(), [
            'id'             => 'required|integer',
            'caseId'             => 'required|integer'
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors()->all();

            $result['success'] = false;
            $result['message'] = implode(', ', $errors);
            $result['error'] = $validator->errors();
            return response()->json($result, 422);
        }

        $id = $request->input('id');
        $caseId = $request->input('caseId');

        $data = ConsentDisclosures::select('consent_disclosures.*', 'users.first_name', 'users.last_name', 'users.email', 'users.username', 'users.mobile_number', 'users.organization', 'users.signature_photo', 'users.id as medId')->join("users", "consent_disclosures.mediator_id", "=", "users.id")
            ->where("id", "=", $id)
            ->first();
        if (isset($data)) {
            if ($data->file_name != null) {
                $dis_file_name = $data->file_name;
                $exist_file = storage_path() . '/app/public/mediation/' . $caseId . '/' . $dis_file_name;
            } else {
                $dis_file_name = "M" . sprintf("%06d", $id) . "_party.pdf";
                $exist_file = storage_path() . '/app/public/mediation/' . $caseId . '/' . $dis_file_name;
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

            $result['success'] = false;
            $result['message'] = "File Not available.";
            $result['error'] = "File Not available.";
            return response()->json($result, 500);
        }
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
            $data[$key]['zoom_id'] = $values->zoom_id;
            $data[$key]['note'] = $values->note;
            $data[$key]['meeting_users'] = implode($user);
            $data[$key]['zoom_link_choice'] = $zoom_link_choice;
            $data[$key]['delete_reason'] = $delete_reason;
        }

        $result['success'] = true;
        $result['message'] = "Data fetched successfully.";
        $result['data'] = $data;
        return response()->json($result, 200);
    }

    public function SendforEditSession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'             => 'required|integer'
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors()->all();

            $result['success'] = false;
            $result['message'] = implode(', ', $errors);
            $result['error'] = $validator->errors();
            return response()->json($result, 422);
        }

        $id = $request->input('id');

        $sessionEditData = DB::table('manage_session')->where('id', $id)->first();

        $result['success'] = true;
        $result['message'] = "Data fetched successfully.";
        $result['data'] = $sessionEditData;
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
             //$userId = 2;

            $validator = Validator::make($request->all(), [
                'caseid' => 'integer',
                'sessionid' => 'integer',
                'ip_name' => 'string',
                'rp_name' => 'string',
                'minutes' => 'integer',
                'next_steps' => 'string',
                'mediator' => 'string|max:255',
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
            $session_party_ids  = $request->input('session_party_ids');


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
            
            $invitation = $this->session_mom_template($templateData);
           //$invitation = "";


            $preview = $this->tempMOM($templateData);
           // $preview = "";
        


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

                $this->send_upload_file_party_mom($sessionid, $notification_array);
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

       
        //xecho "<pre>";print_R($itm_date);exit;

        if(!empty($itm_date) && count($itm_date) > 0) {
            $cdate = new DateTime($itm_date[0]->created_at);
            $data['itm_date'] = $cdate->format('d-m-Y'); 
        } else {
            $data['itm_date'] = "";
        }

        

        
    
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
        //echo "<prE>session==>";print_R($session_data);exit;
        $session_date = explode('/', $session_data->session_date);

        $data['session_date'] = $session_date[0] .'-'.$session_date[1].'-'.$session_date[2];
        $data['session_time'] = $session_date[3];

       
        $data['mediator'] = $data['med'];

        $itm_date = InvitationFiles::select("created_at")->where(['case_id' => $data['caseid']])->orderByDesc('id')->get();

        if(!empty($itm_date) && count($itm_date) > 0) {
            $cdate = new DateTime($itm_date[0]->created_at);
            $data['itm_date'] = $cdate->format('d-m-Y'); 
        } else {
            $data['itm_date'] = 0;     
        }


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


    public function caseTrack(Request $request){
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
            //$userId = $jwtData->data->userid;

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

            $caseid = $request->input('caseid');

            $email = EmailTrack::getByCaseId($caseid);
            

            $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                ->where("mediators_mediation_cases_status.mediation_case_id", "=", $caseid)
                ->where("mediators_mediation_cases_status.status", "=", 1)
                ->first();

            // if(Auth::user()->role == 2) {
            //     return view('admin.case.track', compact("whatsapp", "id", "mediator", "email", "courierCsv", "sms"));
            // } else if(Auth::user()->role == 3) {
            //     return view('user.mtrack', compact("whatsapp", "id", "mediator", "email", "courierCsv", "sms"));
            // } else if(Auth::user()->role == 0) {
            //     return view('user.track', compact("whatsapp", "id", "mediator", "email", "courierCsv", "sms"));
            // }

            $data['caseid'] = $caseid;
            $data['email'] = $email;
            $data['mediator'] = $mediator;

            $result['success'] = true;
            $result['message'] = "Track loaded successfully.";
            $result['data'] = $data;
            return response()->json($result, 200);
        } catch (Exception $e) {

            $result['success'] = false;
            $result['message'] = "Case track not loaded.";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
        }
    }

    public function mediatorEdit(Request $request) {
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
            //$userId = $jwtData->data->userid;

            $validator = Validator::make($request->all(), [
                'caseid' => 'required|integer',
                'mediator_id' => 'required|integer'
            ]);

            //Inputs
            $caseid = $request->input('caseid');
            $mediator_id = $request->input('mediator_id');
            


            if ($validator->fails()) {

                $errors = $validator->errors()->all();

                $result['success'] = false;
                $result['message'] = implode(', ', $errors);
                $result['error'] = $validator->errors();
                return response()->json($result, 422);
            }




            if ($mediator_id != null) {
                $inv = InvoledUser::select('user_involved_in_agreement.*', 'users.address as useraddress', 'users.address1 as useraddress1', 'users.pincode as userpincode', 'users.city as usercity', 'users.state as userstate', 'users.country as usercountry')
                    ->leftJoin("users", "users.id", "=", "user_involved_in_agreement.userId")
                    ->where(['user_involved_in_agreement.userPlanid' => $caseid])->get();

                $mid = "M" . sprintf("%06d", $caseid);

                if ($inv[0]->address1 != null || $inv[0]->useraddress != null) {
                    $medcase = MedCase::find($caseid);

                    $data = Mediators_mediation_cases_status::where("mediation_case_id", "=", $caseid)
                        ->where(function ($q) {
                            $q->where("status", "=", 0)
                                ->orWhere("status", "=", 1);
                        })
                        ->count();
                    if ($data == 0) {
                        Mediators_mediation_cases_status::create([
                            'mediator_id' => $mediator_id,
                            'mediation_case_id' => $caseid,
                            'status' => 0,
                            'user_type' => 1,
                        ]);
                    } else {
                        $MedCaseStatus = Mediators_mediation_cases_status::where(function ($q) {
                            $q->where("status", "=", 0)
                                ->orWhere("status", "=", 1);
                        })
                            ->where("mediation_case_id", "=", $caseid)
                            ->first();
                        $MedCaseStatus->mediator_id = $mediator_id;
                        $MedCaseStatus->status = 0;
                        $MedCaseStatus->save();
                    }

                    //generate pdf : start//

                    
                    
                    $invitation = $this->mediator_appointment($request->id, $mediator_id);
                    
                    
                    $invmodel = InvitationFiles::where('case_id', $caseid)->orderByDesc('id')->limit(1)->first();

                    if (!isset($invmodel)) {
                        $invmodel = new InvitationFiles();
                    }
                    $invmodel->case_id = $caseid;
                    $invmodel->file_name_mediator_appointment = $invitation;
                    $invmodel->save();

                    if ($medcase->bulk_flag == 0 && $medcase->stop_itm_med == 0) {
                        $this->send_mediatorAdd($caseid, $mediator_id);
                    }

                    
                    //generate pdf : end//

                    $final['mediator_id'] = $mediator_id;
                    $result['success'] = true;
                    $result['message'] = "Mediator changed successfully.";
                    $result['data'] = $final;
                    
                     return response()->json($result, 200);

                }
            }
        } catch (Exception $e) {

            $result['success'] = false;
            $result['message'] = "Mediator change failed.";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
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
        $name = Common_function::changeidprefix("",$id, "S","_assignment.pdf");
        // Storage::put('public/mediation/' . $data["case"]->id . '/' . $name, $pdf->output());
        $savePath = 'mediation_documents/mediation/' . $data["case"]->id;
        $finalFilePath = $savePath . '/' . $name;
        // Storage::put('public/mediation/' . $data["case"]->id . '/' . $name, $pdf->output());
        $uploadS3 = $this->uploadOnAWSDirect($finalFilePath, $savePath, $pdf);
        return $name;
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


                
                /**** SMS Notification ****/
                // $smsvar = ['--caseid--'];
                // $smsvar1 = [Common_function::getsixdigitid('sc', $id)];
                // $varjsonSms = ['caseid' => Common_function::changeidprefix("",$id)];
                
                // Common_function::sendsmsNotification($id, $inv->userPhone, $varjsonSms, $smsvar, $smsvar1, 'SM8', 'ADMIN_CASE_REJ', 'SM8_admin_rejecting_case');
                
                /**** SMS Notification ****/
                
                
                
                $party_name = $inv->name;
                $id = Common_function::changeidprefix("",$id);
                SendGrid::send($d, $inv->userEmail, env('L8_CASE_REJECTED', ''), ["-caseid-" => $id, "-responding-" => $party_name], $inv->name);
            
            }
        }
    }

    public function viewSettelment(Request $request)
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

        $sessionData = DB::table('document_settlements')
            ->join('users', 'users.id', '=', 'document_settlements.uploaded_by')
            ->where('document_settlements.mediation_case_id', $caseId)
            ->get();

        $data = array();
        if(count($sessionData) > 0) {

            foreach ($sessionData as $key => $values) {
                
                    $id = $values->id;
                    $keyInc = $key + 1;
                    $data[$key]['id'] = $id;
                    $data[$key]['file_path'] = $values->file_path;
                    $data[$key]['caseId'] = $caseId;
                    $data[$key]['username'] = $values->username;
                    $data[$key]['userId'] = $userId;
            }
        }

        $result['success'] = true;
        $result['message'] = "Data fetched successfully.";
        $result['data'] = $data;
        return response()->json($result, 200);
    }

    public function settlementUpload(Request $request)
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
                    'caseId' => 'required|integer',
                    'Settelmentfiles' => 'required',
                    'Settelmentfiles.*' => 'mimes:csv,txt,xlx,xls,pdf',
                ]);

            if ($validator->fails()) {

                $errors = $validator->errors()->all();

                $result['success'] = false;
                $result['message'] = implode(', ', $errors);
                $result['error'] = $validator->errors();
                return response()->json($result, 422);
            }

            $caseId = $request->input('caseId');
            $Settelmentfiles = $request->file('Settelmentfiles');

            $insert = [];

            if ($request->hasFile('Settelmentfiles')) {

                foreach ($request->file('Settelmentfiles') as $file) {
                    
                    $filename = pathinfo(str_replace(" ", "_", $file->getClientOriginalName()), PATHINFO_FILENAME)
                        . "_date_" . date("YmdHis") . "." . $file->extension();

                    $savePath = "mediation_documents/mediation/{$caseId}/settelmentDocument";
                    $finalFilePath = $savePath . '/' . $filename;

                    Storage::disk('s3')->put($finalFilePath, file_get_contents($file));

                    $insert[] = [
                        'file_path'        => $filename,
                        'uploaded_by'      => $userId,
                        'mediation_case_id'=> $caseId,
                        'created_at'      => now(),
                    ];
                }

                DB::table('document_settlements')->insert($insert);
                
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
                Common_function::MedNotification($caseId, "SEND_SETT_AGRE_ADMIN", $userId, isset($mediatorNoti) ? $mediatorNoti->id : null, $inv_id);

                $this->send_settlement_agreement_party($caseId, $insert);

                $resultData['caseid']=$caseId;

                $result['success'] = true;
                $result['message'] = "Files uploaded successfully.";
                $result['data'] = $resultData;
                return response()->json($result, 200);

            } else {

                    $result['success'] = false;
                    $result['message'] = "Files not uploaded";
                    $result['error'] = "No valid files found";
                    return response()->json($result, 400);
            }

        }  catch (\Exception $e) {

            $result['success'] = false;
            $result['message'] = "Files not uploaded";
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


    public function send_mediatorAdd($id, $mediator_id)
    {
        $user = User::where("id", $mediator_id)->first();
        $mid = "M" . sprintf("%06d", $id);

        $d = [
            'event' => 'MEDI_ADD_ADM',
            'case_id' => $id,
        ];
        SendGrid::send($d, $user->email, env('L17_WHEN_ADMIN_SELECTS_MEDIATOR', ''), ["-caseid-" => $mid], $user->name);

        return true;
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
                'comment' => 'required|string'
            ]);

            //Inputs
            $caseid = $request->input('caseid');
            $status = $request->input('status');
            $comment = $request->input('comment');
            //$userId = 2;

            if ($validator->fails()) {

                $errors = $validator->errors()->all();

                $result['success'] = false;
                $result['message'] = implode(', ', $errors);
                $result['error'] = $validator->errors();
                return response()->json($result, 422);
            }


            if ($status != null && $comment != null) {
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


                if (Mediation_status_log::STATUS_WITHDRAWN == $status) {
                    // if (Auth::user()->role == 1) {
                    //     Common_function::MedNotification($request->case_id, "WDRN_BY_MED", Auth::user()->id, Auth::user()->id, null);
                    // } else {
                        Common_function::MedNotification($caseid, "WDRN_BY_ADMIN", $userId, isset($mediator) ? $mediator->id : null, null);
                    //}
                } else if (Mediation_status_log::STATUS_RESOLVED == $status) {
                    // if (Auth::user()->role == 1) {
                    //     Common_function::MedNotification($request->case_id, "RES_BY_MED", Auth::user()->id, Auth::user()->id, null);
                    // } else {
                        Common_function::MedNotification($caseid, "RES_BY_ADMIN", $userId, isset($mediator) ? $mediator->id : null, null);
                    //}
                } else if (Mediation_status_log::STATUS_UNRESOLVED == $status) {
                    // if (Auth::user()->role == 1) {
                    //     Common_function::MedNotification($request->case_id, "UNRES_BY_MED", Auth::user()->id, Auth::user()->id, null);
                    // } else {
                        Common_function::MedNotification($caseid, "UNRES_BY_ADMIN", $userId, isset($mediator) ? $mediator->id : null, null);
                    //}
                }


                $user = MedCase::find($caseid);
                $user->confirm_status = 2;
                $user->case_status = $status;
                $user->withdraw = ($comment != null) ? $comment : "";

                if ($user->save()) {

                    // dd($user->bulk_flag);
                    $mediation_status_log = new Mediation_status_log;
                    $mediation_status_log->user_id = $userId;
                    $mediation_status_log->mediation_case_id = $caseid;
                    $mediation_status_log->status = ($status != null) ? $status : "";


                    if (Mediation_status_log::STATUS_WITHDRAWN == $status) {
                        $mediation_status_log->description = "Request Withdrawn";
                        //if ($user->bulk_flag != 1) {
                            $this->sned_withdrawal($caseid, $user->stop_close_ip, $user->stop_close_rp, $user->stop_close_med);
                        //}
                    } else if (Mediation_status_log::STATUS_RESOLVED == $status) {
                        $mediation_status_log->description = "Request Resolved";
                        //if ($user->bulk_flag != 1) {
                            $this->sned_resolved($caseid, $user->stop_close_ip, $user->stop_close_rp, $user->stop_close_med);
                        //}
                    } else if (Mediation_status_log::STATUS_UNRESOLVED == $status) {
                        $mediation_status_log->description = "Request Unresolved";
                        //if ($user->bulk_flag != 1) {
                            $this->sned_unresolved($caseid, $user->stop_close_ip, $user->stop_close_rp, $user->stop_close_med);
                        //}
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


    public function sned_withdrawal($id, $stop_close_ip = 0, $stop_close_rp = 0, $stop_close_med = 0)
    {

        $is_bulk = MedCase::select('bulk_flag')->where('id', $id)->first();

        
        //echo "<pre>";print_R($is_bulk);exit;
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

                if($stop_close_ip == 0){

                    SendGrid::send($d1, $inv->userEmail, env('L13_WITHDRAWAL_OF_CASE', ''), ["-caseid-" => $mid, "-type-" => "Party"], $inv->name);
                }
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
                    if($stop_close_rp == 0){

                        SendGrid::send($d2, $email, env('L14_COMMUNICATION_OF_WITHDRAWAL_TO_OTHER_PARTIES', ''), ["-caseid-" => $mid, "-partyname-" => $initiating_party, "-type-" => "Party"], $inv->name);
                    }
                }
            }
        }

        
        if ($mediator) {

            if($stop_close_med == 0){

                SendGrid::send($d3, $mediator->email, env('L13_WITHDRAWAL_OF_CASE', ''), ["-caseid-" => $mid, "-responding-" => $initiating_party, "-type-" => "Mediator"], $mediator->username);
            }

            
        }
        return true;
    }


    public function sned_resolved($id, $stop_close_ip = 0, $stop_close_rp = 0, $stop_close_med = 0)
    {
        $is_bulk = MedCase::select('bulk_flag')->where('id', $id)->first();
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
                if($stop_close_ip == 0) {

                    SendGrid::send($d, $inv->userEmail, env('L15_CASE_RESOLVED', ''), ["-caseid-" => $mid, "-responding-" => $initiating_party, "-type-" => "Party"], $inv->name);
                }
            }
        }
        if ($mediator) {
            if($stop_close_med == 0){
                
                SendGrid::send($d, $mediator->email, env('L15_CASE_RESOLVED', ''), ["-caseid-" => $mid, "-responding-" => $initiating_party, "-type-" => "Mediator"], $mediator->username);
            }
        }
        return true;
    }



    public function sned_unresolved($id, $stop_close_ip = 0, $stop_close_rp = 0, $stop_close_med = 0)
    {
        $is_bulk = MedCase::select('bulk_flag')->where('id', $id)->first();
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
                if($stop_close_ip == 0) {

                    SendGrid::send($d, $inv->userEmail, env('L15_CASE_UNRESOLVED', ''), ["-caseid-" => $mid, "-responding-" => $initiating_party, "-type-" => "Party"], $inv->name);
                }
            }
        }
        if ($mediator) {
            if($stop_close_med == 0){

                SendGrid::send($d, $mediator->email, env('L15_CASE_UNRESOLVED', ''), ["-caseid-" => $id, "-responding-" => $initiating_party, "-type-" => "Mediator"], $mediator->username);
            }
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
        return $pdf->download('session_M' . sprintf('%06d', $caseId) . '.pdf');

    }


    public function caseUpdate(Request $request) {
        $caseid = $request->input('caseid');
        $proposedSolution = $request->input('proposedSolution');
        $issue = $request->input('issue');
        $application = $request->input('application');

    

        // Claimants
        $claimants_array['cnames'] = $request->input('cnames');
        $claimants_array['cemails'] = $request->input('cemails');
        $claimants_array['cphones'] = $request->input('cphones');
        $claimants_array['caddress1'] = $request->input('caddress1');
        $claimants_array['caddress2'] = $request->input('caddress2');
        $claimants_array['ccity'] = $request->input('ccity');
        $claimants_array['cpincode'] = $request->input('cpincode');
        $claimants_array['cstate'] = $request->input('cstate');
        $claimants_array['ccountry'] = $request->input('ccountry');

        // Respondants
        $resp_array['rnames'] = $request->input('rnames');
        $resp_array['remails'] = $request->input('remails');
        $resp_array['rphones'] = $request->input('rphones');
        $resp_array['raddress1'] = $request->input('raddress1');
        $resp_array['raddress2'] = $request->input('raddress2');
        $resp_array['rcity'] = $request->input('rcity');
        $resp_array['rpincode'] = $request->input('rpincode');
        $resp_array['rstate'] = $request->input('rstate');
        $resp_array['rcountry'] = $request->input('rcountry');


        //udpate mediation case
        $med = MedCase::find($caseid);
        $med->proposedSolution = $proposedSolution;
        $med->issue = $issue;

        $med->ref_id = isset($application) ? $application : $med->ref_id;
        $med->updated_at = date("Y-m-d H:i:s");
        $med->save();

         //echo "<pre>";print_R($claimants_array);

        foreach($claimants_array['cemails'] as $ckey => $claimant_data) {
            
            $involedUser = InvoledUser::where(['userPlanId' => $med->id, 'userEmail' => $claimant_data])->get()->toArray();
            
          // echo "<pre>";print_R($involedUser['id']);exit;
            if(!empty($involedUser)) {
                $dataToInsert = [
                    'name' => $claimants_array['cnames'][$ckey],
                    'userEmail' => $claimant_data,
                    'userPhone' => $claimants_array['cphones'][$ckey],
                    'userPlanId' => $med->id,
                    'address1' => $claimants_array['caddress1'][$ckey],
                    'address2' => $claimants_array['caddress2'][$ckey],
                    'city' => $claimants_array['ccity'][$ckey],
                    'pincode' => $claimants_array['cpincode'][$ckey],
                    'state' => $claimants_array['cstate'][$ckey],
                    'country' => $claimants_array['ccountry'][$ckey]
                ];
                $add_claimant = DB::table('user_involved_in_agreement')->where('userEmail', $claimant_data)->update($dataToInsert);
                // /$involedUser->save($dataToInsert);
                // $involedUser->userEmail = $involedUser[0]['userEmail'];
                // $involedUser->userPhone = $claimants_array['cphones'][$ckey];
                // $involedUser->userPlanId = $med->id;
                // $involedUser->address1 = $claimants_array['caddress1'][$ckey];
                // $involedUser->address2 = $claimants_array['caddress2'][$ckey];
                // $involedUser->city = $claimants_array['ccity'][$ckey];
                // $involedUser->pincode = $claimants_array['cpincode'][$ckey];
                // $involedUser->state = $claimants_array['cstate'][$ckey];
                // $involedUser->country = $claimants_array['ccountry'][$ckey];
                // $involedUser->save();
            } else {

                $add_claimant = new InvoledUser();
                $add_claimant->userEmail = $claimant_data;
                $add_claimant->name = $claimants_array['cnames'][$ckey];
                $add_claimant->userPhone = $claimants_array['cphones'][$ckey];
                $add_claimant->userPlanId = $med->id;
                $add_claimant->address1 = $claimants_array['caddress1'][$ckey];
                $add_claimant->address2 = $claimants_array['caddress2'][$ckey];
                $add_claimant->city = $claimants_array['ccity'][$ckey];
                $add_claimant->pincode = $claimants_array['cpincode'][$ckey];
                $add_claimant->state = $claimants_array['cstate'][$ckey];
                $add_claimant->country = $claimants_array['ccountry'][$ckey];
                // $dataToInsert = [
                //     'userEmail' => $claimant_data,
                //     'userPhone' => $claimants_array['cphones'][$ckey] ,
                //     'userPlanId' => $med->id,
                //     'address1' => $claimants_array['caddress1'][$ckey],
                //     'address2' => $claimants_array['caddress2'][$ckey],
                //     'city' => $claimants_array['ccity'][$ckey],
                //     'pincode' => $claimants_array['cpincode'][$ckey],
                //     'state' => $claimants_array['cstate'][$ckey],
                //     'country' => $claimants_array['ccountry'][$ckey]
                // ];
                $add_claimant->save();
            }
            
            
        }


//echo "<pre>";print_r($resp_array);
         foreach($resp_array['remails'] as $rkey => $resp_data) {
            $respUser = InvoledUser::where(['userPlanId' => $med->id, 'userEmail' => $resp_data])->orderByDesc('id')->limit(1)->first();
             //echo "<pre>";print_r($respUser);

             $isClaimant_count = InvoledUser::select('isClaimant')->where('isClaimant', '!=', 0)->orderBy('isClaimant', 'desc')->first()->toArray();
             //echo "<pre>";print_r($isClaimant);exit;
            
            if(!empty($respUser)) {
                $dataToRespInsert = [
                    'name' => $resp_array['rnames'][$rkey],
                    'userEmail' => $resp_data,
                    'userPhone' => $resp_array['rphones'][$rkey],
                    'userPlanId' => $med->id,
                    'address1' => $resp_array['raddress1'][$rkey],
                    'address2' => $resp_array['raddress2'][$rkey],
                    'city' => $resp_array['rcity'][$rkey],
                    'pincode' => $resp_array['rpincode'][$rkey],
                    'state' => $resp_array['rstate'][$rkey],
                    'country' => $resp_array['rcountry'][$rkey],
                    'isClaimant' => $respUser['isClaimant']
                ];
                $add_resp = DB::table('user_involved_in_agreement')->where('userEmail', $resp_data)->update($dataToRespInsert);
               
            } else {
                $add_resp = new InvoledUser();
                $add_resp->name = $resp_array['rnames'][$rkey];
                $add_resp->userEmail = $resp_data;
                $add_resp->userPhone = $resp_array['rphones'][$rkey];
                $add_resp->userPlanId = $med->id;
                $add_resp->address1 = $resp_array['raddress1'][$rkey];
                $add_resp->address2 = $resp_array['raddress2'][$rkey];
                $add_resp->city = $resp_array['rcity'][$rkey];
                $add_resp->pincode = $resp_array['rpincode'][$rkey];
                $add_resp->state = $resp_array['rstate'][$rkey];
                $add_resp->country = $resp_array['rcountry'][$rkey];
                $add_resp->isClaimant = $isClaimant_count['isClaimant'] + 1;
                $add_resp->joinCode = $this->joinCode();
                // $dataToInsert = [
                //     'userEmail' => $claimant_data,
                //     'userPhone' => $claimants_array['cphones'][$ckey] ,
                //     'userPlanId' => $med->id,
                //     'address1' => $claimants_array['caddress1'][$ckey],
                //     'address2' => $claimants_array['caddress2'][$ckey],
                //     'city' => $claimants_array['ccity'][$ckey],
                //     'pincode' => $claimants_array['cpincode'][$ckey],
                //     'state' => $claimants_array['cstate'][$ckey],
                //     'country' => $claimants_array['ccountry'][$ckey]
                // ];
                $add_resp->save();
            }
        }


        $result['success'] = true;
        $result['message'] = "Case data updated successfully.";
        $result['data'] = $caseid;
        return response()->json($result, 200);



    }

}
