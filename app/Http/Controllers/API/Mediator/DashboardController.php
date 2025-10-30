<?php

namespace App\Http\Controllers\API\Mediator;

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

use App\Http\Helpers\Zoom;

use Carbon\Carbon;

class DashboardController extends Controller
{

    use UploadTrait;

    
    public function getNotifications()
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
        $category = $request->input('category', ''); 

        $view = Notification::where('view_mediator', 0)->where('mediator_id', $userId)->get();

        foreach ($view as $item) {
            $item->view_mediator = 1;
            $item->save();
        }
        $noficationdata = Notification::mediatornotificationbyctgry($userId, $category);

        $data = array();
        $casedata=array();
        $userdata=array();
        if(count($noficationdata) > 0) {

            foreach ($noficationdata as $key => $values) {

                $caseid="";
                if(!empty($values->case_id)){

                    $caseid=$values->case_id;

                    $casedata = MedCase::select('id', 'confirm_status', 'case_status')->where('id', $values->case_id)->first();

                }else if(!empty($values->reg_id)){

                    $userdata = User::select('email', 'isActive', 'role', 'status')->where('id', $values->reg_id)->first();

                }
                    $data[$key]['id'] = $values->id;
                    $data[$key]['case_id'] ='CID' . sprintf('%06d', $caseid);
                    $data[$key]['reg_id'] = $values->reg_id;
                    $data[$key]['event'] = $values->event;
                    $data[$key]['created_at'] = $values->created_at;
                    $data[$key]['updated_at'] = $values->updated_at;
                    $data[$key]['mediator_id'] = $values->mediator_id;
                    $data[$key]['user_id'] = $values->user_id;
                    $data[$key]['view_mediator'] = $values->view_mediator;
                    $data[$key]['view_user'] = $values->view_user;
                    $data[$key]['category'] = $values->category;
                    $data[$key]['isRead'] = $values->isRead;
                    $data[$key]['ititle'] = $values->ititle;
                    $data[$key]['idescription'] = $values->idescription;
                    $data[$key]['casedata'] =  $casedata;
                    $data[$key]['userdata'] =  $userdata;

            }
        }
        
        $result['success'] = true;
        $result['message'] = "User notifications fetched successfully.";
        $result['data'] = $data;
        return response()->json($result, 200);
    }

    public function getNotificationsCounts(Request $request)
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

            $view = Notification::where('view', 0)->get();
            foreach ($view as $item) {
                $item->view = 1;
                $item->save();
            }
            $notificationAll = Notification::mediatornotificationData($userId);
            $noficationCaseUpdates = Notification::notificationDatabyctgry($userId, 1);
            $noficationDocsUpdates = Notification::notificationDatabyctgry($userId, 2);
            $noficationSessionUpdates = Notification::notificationDatabyctgry($userId, 3);
            $noficationAccountUpdates = Notification::notificationDatabyctgry($userId, 4);

            $notificationAllUnread = Notification::select('id')->where('mediator_id', "=", $userId)->where('isRead', "=", 0)->get();
            $noficationCaseUpdatesUnread = Notification::select('id')->where('mediator_id', "=", $userId)->where('category', "=", 1)->where('isRead', "=", 0)->get();
            $noficationDocsUpdatesUnread = Notification::select('id')->where('mediator_id', "=", $userId)->where('category', "=", 2)->where('isRead', "=", 0)->get();
            $noficationSessionUpdatesUnread = Notification::select('id')->where('mediator_id', "=", $userId)->where('category', "=", 3)->where('isRead', "=", 0)->get();
            $noficationAccountUpdatesUnread = Notification::select('id')->where('mediator_id', "=", $userId)->where('category', "=", 4)->where('isRead', "=", 0)->get();

            $resultData['notifications']['all']=count($notificationAll);
            $resultData['notifications']['caseUpdates']=count($noficationCaseUpdates);
            $resultData['notifications']['docsUpdates']=count($noficationSessionUpdates);
            $resultData['notifications']['sessionUpdates']=count($noficationSessionUpdates);
            $resultData['notifications']['accountUpdates']=count($noficationSessionUpdates);

            $resultData['notifications']['allUnread']=count($notificationAllUnread);
            $resultData['notifications']['caseUpdatesUnread']=count($noficationCaseUpdatesUnread);
            $resultData['notifications']['docsUpdatesUnread']=count($noficationDocsUpdatesUnread);
            $resultData['notifications']['sessionUpdatesUnread']=count($noficationSessionUpdatesUnread);
            $resultData['notifications']['accountUpdatesUnread']=count($noficationAccountUpdatesUnread);

            $result['success'] = true;
            $result['message'] = "Notifications fetched successfully.";
            $result['data'] = $resultData;
            return response()->json($result, 200);
        } catch (Exception $e) {
            $result['success'] = false;
            $result['message'] = "Notifications loading failed.";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
        }
    }

    public function viewCaseDetails(Request $request) {
        //Input
        $caseid = $request->input('caseid');

        $case = MedCase::select("mediation_case.*", "users.first_name as mfirstname", "users.last_name as mlastname", "mediators_mediation_cases_status.mediator_id as mediator_id", "mediators_mediation_cases_status.status as mediator_status", DB::raw("CONCAT(users.first_name,' ', users.last_name) as mfullname"))
            ->leftJoin("mediators_mediation_cases_status", "mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id")
            ->leftJoin("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
            ->where('mediation_case.id', '=', $caseid)
            ->first();

        $party_details = InvoledUser::select('user_involved_in_agreement.*', 'users.address as useraddress', 'users.address1 as useraddress1', 'users.pincode as userpincode', 'users.city as usercity', 'users.state as userstate', 'users.country as usercountry')
            ->leftJoin("users", "users.id", "=", "user_involved_in_agreement.userId")
            ->where(['user_involved_in_agreement.userPlanid' => $case->id])->get();

            // Party details //
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
        // Party details //

        $case->invitation = InvitationFiles::where(['case_id' => $case->id])->orderByDesc('id')->get();

        $case->appointment = InvitationFiles::where(['case_id' => $case->id])->where('file_name_mediator_appointment', '!=', null)->orderByDesc('id')->limit(1)->first();

        $case->supporting_document = DB::table('manage_files')->select('manage_files.*', DB::raw("CONCAT(users.first_name,' ',users.last_name) as fullname"))
            ->join('users', 'users.id', '=', 'manage_files.uploaded_by')
            ->where('manage_files.case_id', $case->id)
            ->get();

        $case->settlement_document = DB::table('document_settlements')->select('document_settlements.*', DB::raw("CONCAT(users.first_name,' ',users.last_name) as fullname"))
            ->join('users', 'users.id', '=', 'document_settlements.uploaded_by')
            ->where('document_settlements.mediation_case_id', $case->id)
            ->get();

        //$case->mom = DB::table('session_mom')->select("file_name")->where('case_id', $case->id)->get();

        $case->mom = DB::table('session_mom')->select('file_name', DB::raw("CONCAT(users.first_name,' ',users.last_name) as fullname"))
            ->join('users', 'users.id', '=', 'session_mom.uploaded_by')
            ->where('case_id', $case->id)
            ->get();


        $result['success'] = true;
        $result['message'] = "Case details fetched successfully.";
        $result['data'] = $case;
        return response()->json($result, 200);

    }


    // Dashboard functions : start //
    public function allCaseCounts(Request $request) {
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

            $allCasesCount = 0;
            $allCasesCount = Mediators_mediation_cases_status::where('mediator_id', $userId)->count(); 

            $allActiveCasesCount = 0;
            $allActiveCasesCount = Mediators_mediation_cases_status::where('mediator_id', $userId)->where('status', 1)->count();
            
            $successRate = 0;
            $resolvedCases = Mediators_mediation_cases_status::where('mediator_id', $userId)
                ->leftJoin("mediation_case", "mediation_case.id", "=", "Mediators_mediation_cases_status.mediation_case_id")
                    ->whereIn('mediation_case.case_status', [6, 8])->count();
            $totalCases = Mediators_mediation_cases_status::where('mediator_id', $userId)
                ->leftJoin("mediation_case", "mediation_case.id", "=", "Mediators_mediation_cases_status.mediation_case_id")
                ->whereIn('mediation_case.case_status', [5, 6, 7, 8])->count();

            $successRate = ($resolvedCases /$totalCases ) * 100;

            $closedCasesCount = 0;
            $closedCasesCount = $totalCases;

            $resultArray['totalCaseCount'] = $allCasesCount;
            $resultArray['activeCaseCount'] = $allActiveCasesCount;
            $resultArray['successRate'] = $successRate;
            $resultArray['closedCasesCount'] = $closedCasesCount;

            $result['success'] = true;
            $result['message'] = "All case counts are fetched successfully.";
            $result['data'] = $resultArray;
            return response()->json($result, 200);
        } catch (Exception $e) {

            $result['success'] = false;
            $result['message'] = "Case counts process failed.";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
        }
    }


    public function getUpcomingSessions(Request $request) {
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
        
            $today_date = Carbon::today();
            $sessionData = DB::table('manage_session')
            ->select('manage_session.*','mediators_mediation_cases_status.mediator_id', DB::raw("STR_TO_DATE(manage_session.session_date, '%d/%m/%Y') as date_format"))
            ->join('mediators_mediation_cases_status', 'mediators_mediation_cases_status.mediation_case_id', '=', 'manage_session.case_id')
            ->where('mediators_mediation_cases_status.mediator_id', $userId)
            ->orderby('date_format', 'ASC')->get();

        

            $dataArray = array();
            $finalArray = array();
            $sn = 1;

            foreach ($sessionData as $value) {
                if( $value->date_format > $today_date ){
                    
                
                    if (!is_null($value->session_party_ids)) {
                        $dataArray = json_decode($value->session_party_ids);
                    }
                    $ip_user = array();
                    $rp_user = array();
                    $dd_data = InvoledUser::where('userPlanId', $value->case_id)->get();
                        foreach($dd_data as $dd){
                            if (isset($dd)) {
                                if ($dd->name != null) {

                                    if($dd->isClaimant == 0){
                                        $ip_user[] = $dd->name;
                                    } else {
                                        $rp_user[] = $dd->name;
                                    }
                                    
                                }
                            }
                        }
                    

                    $mediator = DB::table('mediators_mediation_cases_status')->select('mediators_mediation_cases_status.mediator_id', 'users.first_name', 'users.last_name')
                    ->join('users', 'users.id', '=', 'mediators_mediation_cases_status.mediator_id')
                    ->where('mediators_mediation_cases_status.mediation_case_id', $value->case_id)
                    ->first();

                    if($mediator){
                        $m_name = $mediator->first_name .' '.$mediator->last_name;
                    }else{
                        $m_name = "-";
                    }
        
                    if(isset($value->zoom_link) && $value->zoom_link != null){
                        $zoom_link = $value->zoom_link;
                    } else {
                        $zoom_link = "-";
                    }

                    $myDate = explode("/", $value->session_date);
                    $finalDate = $myDate[0].'/'.$myDate[1].'/'.$myDate[2];
                    $datee = Carbon::createFromFormat('d/m/Y', $finalDate)->format('d-M-Y');

                    if($value->zoom_link_choice == "manual"){
                        $zoom_id = $value->zoom_id;
                        $zoom_link_final = "";
                    } else {
                        $zoom_id = "";
                        $zoom_link_final = $zoom_link;
                    }

                    $finalArray[$sn++] = [
                        'caseid' => $value->case_id,
                        'claimant' => $ip_user,
                        'respondant' => $rp_user,
                        'mediator' => $m_name,
                        'session' => $datee .' '.$myDate[3],
                        'zoom_id' => $zoom_id,
                        'zoom_link' => $zoom_link_final
                    ];
                }
            }

            $data = $finalArray;
       
            $result['success'] = true;
            $result['message'] = "Upcoming sessions are fetched successfully.";
            $result['data'] = $data;
            return response()->json($result, 200);





        } catch (Exception $e) {

            $result['success'] = false;
            $result['message'] = "Case counts process failed.";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
        }
    }
    // Dashboard functions : end //
   
}
