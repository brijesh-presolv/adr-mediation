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
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Hash;
use App\Models\InvoledUser;
use Carbon\Carbon;

class AdminController extends Controller 
{

    public function dashboard(Request $request){

        $result['success'] = true;
        return response()->json($result, 200);
    }


    public function allCaseCounts(Request $request) {
        try{
            $token = $request->cookie('auth_token');
            if (!$token) {

                $result['success'] = false;
                $result['message'] = 'Unauthorized: Missing token';
                $result['error'] = 'Unauthorized: Missing token';
                return response()->json($result, 401);
            }

            $allCasesCount = 0;
            $allCasesCount = MedCase::count(); 

            $newCount = 0;
            $newCount = MedCase::where("confirm_status", 0)->count();

            $ongoingCount = 0;
            $ongoingCount = MedCase::where("confirm_status", 1)->count();

            $resolvedCount = 0;
            $resolvedCount = MedCase::where("case_status", 6)->where("confirm_status", 2)->count();

            $unresolvedCount = 0;
            $unresolvedCount = MedCase::where("case_status", 7)->where("confirm_status", 2)->count();

            $rejectedCount = 0;
            $rejectedCount = MedCase::where("confirm_status", 3)->count();

            $withdrawnCount = 0;
            $withdrawnCount = MedCase::where("case_status", 5)->where("confirm_status", 2)->count();

            $resultArray['totalCaseCount'] = $allCasesCount;
            $resultArray['newCaseCount'] = $newCount;
            $resultArray['ongoingCaseCount'] = $ongoingCount;
            $resultArray['resolvedCaseCount'] = $resolvedCount;
            $resultArray['unresolvedCaseCount'] = $unresolvedCount;
            $resultArray['withdrawnCaseCount'] = $withdrawnCount;
            $resultArray['rejectedCaseCount'] = $rejectedCount;

            $result['success'] = true;
            $result['message'] = "All case counts are fetched successfully.";
            $result['data'] = $resultArray;
            return response()->json($result, 200);
        } catch (Exception $e) {
            $result['success'] = false;
            $result['message'] = "All case counts loading failed.";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
        }
    }


    public function allUserCounts(Request $request) {
        try{
            $token = $request->cookie('auth_token');
            if (!$token) {

                $result['success'] = false;
                $result['message'] = 'Unauthorized: Missing token';
                $result['error'] = 'Unauthorized: Missing token';
                return response()->json($result, 401);
            }

            $allMediatorCount = 0;
            $allMediatorCount = User::where("role", 1)->count();

            $approveMediatorCount = 0;
            $approveMediatorCount = User::whereIn("role", [1])->where("status", 1)->where("is_deleted", 0)->count();

            $unapproveMediatorCount = 0;
            $unapproveMediatorCount = User::whereIn("role", [1])->where("status", 0)->where("is_deleted", 0)->count();

            $allUserCount = 0;
            $allUserCount = User::where("role", 0)->count();

            $approveUsersCount = 0;
            $approveUsersCount = User::whereIn("role", [0])->where("status", 1)->where("is_deleted", 0)->count();

            $unapproveUsersCount = 0;
            $unapproveUsersCount = User::whereIn("role", [0])->where("status", 0)->where("is_deleted", 0)->count();

            $resultArray['mediatorCount'] = $allMediatorCount;
            $resultArray['approvedMediatorCount'] = $approveMediatorCount;
            $resultArray['unapprovedMediatorCount'] = $unapproveMediatorCount;
            $resultArray['userCount'] = $allUserCount;
            $resultArray['approvedUserCount'] = $approveUsersCount;
            $resultArray['unapprovedUserCount'] = $unapproveUsersCount;

            $result['success'] = true;
            $result['message'] = "All user counts are fetched successfully.";
            $result['data'] = $resultArray;
            return response()->json($result, 200);
        } catch (Exception $e) {
            $result['success'] = false;
            $result['message'] = "All user counts loading failed.";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
        }
    }
    

    public function getLatestNotifications(Request $request) {
        try{
            $token = $request->cookie('auth_token');
            if (!$token) {

                $result['success'] = false;
                $result['message'] = 'Unauthorized: Missing token';
                $result['error'] = 'Unauthorized: Missing token';
                return response()->json($result, 401);
            }
            $data = Notification::notificatioLatestData();
            $userdata = [];
            $casedata = [];

            if(count($data) > 0) {

                foreach ($data as $key => $values) {

                    if(!empty($values->case_id)){

                        $casedata = MedCase::select('id', 'confirm_status', 'case_status')->where('id', $values->case_id)->first();

                    }else if(!empty($values->reg_id)){

                        $userdata = User::select('email', 'isActive', 'role', 'status')->where('id', $values->reg_id)->first();

                    }

                    $data[$key]['casedata'] =  $casedata;
                    $data[$key]['userdata'] =  $userdata;
                    $data[$key]['cid'] =  'CID' . sprintf('%06d', $values->case_id);
                }
            }

            $result['success'] = true;
            $result['message'] = "Latest notifications fetched successfully.";
            $result['data'] = $data;
            return response()->json($result, 200);
        } catch (Exception $e) {
            $result['success'] = false;
            $result['message'] = "Latest notifications loading failed.";
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

            $today_date = Carbon::today();
        
            //$today_date = $today_date->format('d/m/Y');
            $sessionData = DB::table('manage_session')
            ->select('manage_session.*',
            DB::raw("STR_TO_DATE(session_date, '%d/%m/%Y') as date_formatt")
            )
            
            //->orderby('id', 'DESC')->take(15)->get();
            ->orderby('date_formatt', 'ASC')
            ->where('is_deleted', '=', 0)->get();
            $dataArray = array();

            $finalArray = array();

            $sn = 0;
            foreach ($sessionData as $key => $value) {

                if(!is_null($value->date_formatt)){

                    
                    if($value->date_formatt > $today_date){

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

                    

                    

                        // $finalArray[$sn]['caseid'] = $value->case_id;
                        // $finalArray[$sn]['claimant'] = $ip_user;
                        // $finalArray[$sn]['respondant'] = $rp_user;
                        // $finalArray[$sn]['mediator'] = $m_name;

                        $myDate = explode("/", $value->session_date);
                        $finalDate = $myDate[0].'/'.$myDate[1].'/'.$myDate[2];
                        $datee = Carbon::createFromFormat('d/m/Y', $finalDate)->format('d-m-Y');
                        
                        //$finalArray[$sn]['session'] = $datee .' '.$myDate[3];
                        

                        if($value->zoom_link_choice == "manual"){
                            $zoom_id = $value->zoom_id;
                            $zoom_link_final = "";
                            // $finalArray[$sn]['zoom_id'] = $value->zoom_id;
                            // $finalArray[$sn]['zoom_link'] = "";
                        } else {
                            $zoom_id = "";
                            $zoom_link_final = $zoom_link;
                            // $finalArray[$sn]['zoom_id'] = "";
                            // $finalArray[$sn]['zoom_link'] = $zoom_link;
                        }


                        $finalArray[$sn++] = [
                            'id' => $value->case_id,
                            'caseid' => 'CID' . sprintf('%06d', $value->case_id),
                            'claimant' => $ip_user,
                            'respondant' => $rp_user,
                            'mediator' => $m_name,
                            'session' => $datee .' '.substr($myDate[3], 0, -2),
                            'zoom_id' => $zoom_id,
                            'zoom_link' => $zoom_link_final
                        ];
                    
                    
                        //$sn++;
                    }

                    
                }


            }

            $data = $finalArray;
        
            $result['success'] = true;
            $result['message'] = "Upcoming sessions are fetched successfully.";
            $result['data'] = $data;
            return response()->json($result, 200);
        } catch (Exception $e) {
            $result['success'] = false;
            $result['message'] = "Upcoming session fetching failed.";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
        }
    }
}
