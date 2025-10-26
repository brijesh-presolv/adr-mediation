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
use Illuminate\Support\Facades\Hash;
use App\Models\InvoledUser;
use Carbon\Carbon;

class AdminController extends Controller 
{

    public function dashboard(Request $request){

        $result['success'] = true;
        return response()->json($result, 200);
    }

    public function totalCaseCount() {
        $allCasesCount = 0;
        $allCasesCount = MedCase::count();

        $result['success'] = true;
        $result['message'] = "Total case count fetched successfully.";
        $result['data'] = $allCasesCount;
        return response()->json($result, 200);
    }

    public function newCaseCount() {
        $newCount = 0;
        $newCount = MedCase::where("confirm_status", 0)->count();

        $result['success'] = true;
        $result['message'] = "New case count fetched successfully.";
        $result['data'] = $newCount;
        return response()->json($result, 200);
    }

    public function ongoingCaseCount() {
        $ongoingCount = 0;
        $ongoingCount = MedCase::where("confirm_status", 1)->count();

        $result['success'] = true;
        $result['message'] = "Ongoing case count fetched successfully.";
        $result['data'] = $ongoingCount;
        return response()->json($result, 200);
    }

    public function resolvedCaseCount() {
        $resolvedCount = 0;
        $resolvedCount = MedCase::where("case_status", 6)->where("confirm_status", 2)->count();

        $result['success'] = true;
        $result['message'] = "Resolved case count fetched successfully.";
        $result['data'] = $resolvedCount;
        return response()->json($result, 200);
    }

    public function unresolvedCaseCount() {
        $unresolvedCount = 0;
        $unresolvedCount = MedCase::where("case_status", 7)->where("confirm_status", 2)->count();

        $result['success'] = true;
        $result['message'] = "Unresolved case count fetched successfully.";
        $result['data'] = $unresolvedCount;
        return response()->json($result, 200);
    }

    public function rejectedCaseCount() {
        $rejectedCount = 0;
        $rejectedCount = MedCase::where("confirm_status", 3)->count();

        $result['success'] = true;
        $result['message'] = "Rejected case count fetched successfully.";
        $result['data'] = $rejectedCount;
        return response()->json($result, 200);
    }

    public function withdrawnCaseCount() {
        $withdrawnCount = 0;
        $withdrawnCount = MedCase::where("case_status", 5)->where("confirm_status", 2)->count();

        $result['success'] = true;
        $result['message'] = "Withdrawn case count fetched successfully.";
        $result['data'] = $withdrawnCount;
        return response()->json($result, 200);
    }

    public function totalUserCount() {
        $allUserCount = 0;
        $allUserCount = User::where("role", 0)->count();

        $result['success'] = true;
        $result['message'] = "Total user count fetched successfully.";
        $result['data'] = $allUserCount;
        return response()->json($result, 200);
    }

    public function approvedUserCount() {
        $approveUsersCount = 0;
        $approveUsersCount = User::whereIn("role", [0])->where("status", 1)->where("is_deleted", 0)->count();

        $result['success'] = true;
        $result['message'] = "Total approved user count fetched successfully.";
        $result['data'] = $approveUsersCount;
        return response()->json($result, 200);
    }

    public function unapprovedUserCount() {
        $unapproveUsersCount = 0;
        $unapproveUsersCount = User::whereIn("role", [0])->where("status", 0)->where("is_deleted", 0)->count();

        $result['success'] = true;
        $result['message'] = "Total unapproved user count fetched successfully.";
        $result['data'] = $unapproveUsersCount;
        return response()->json($result, 200);
    }

    public function totalMediatorCount() {
        $allMediatorCount = 0;
        $allMediatorCount = User::where("role", 1)->count();

        $result['success'] = true;
        $result['message'] = "Total mediator count fetched successfully.";
        $result['data'] = $allMediatorCount;
        return response()->json($result, 200);
    }

    public function approvedMediatorCount() {
        $approveMediatorCount = 0;
        $approveMediatorCount = User::whereIn("role", [1])->where("status", 1)->where("is_deleted", 0)->count();

        $result['success'] = true;
        $result['message'] = "Total approved mediator count fetched successfully.";
        $result['data'] = $approveMediatorCount;
        return response()->json($result, 200);
    }

    public function unapprovedMediatorCount() {
        $unapproveMediatorCount = 0;
        $unapproveMediatorCount = User::whereIn("role", [1])->where("status", 0)->where("is_deleted", 0)->count();

        $result['success'] = true;
        $result['message'] = "Total unapproved mediator count fetched successfully.";
        $result['data'] = $unapproveMediatorCount;
        return response()->json($result, 200);
    }

    public function getLatestNotifications() {
        $data = Notification::notificatioLatestData();

        $result['success'] = true;
        $result['message'] = "Latest notifications fetched successfully.";
        $result['data'] = $data;
        return response()->json($result, 200);
    }

    public function getUpcomingSessions() {
        $today_date = Carbon::today();
        //echo $today_date;exit;
        //$today_date = $today_date->format('d/m/Y');
        $sessionData = DB::table('manage_session')
        ->select('manage_session.*',DB::raw("STR_TO_DATE(session_date, '%d/%m/%Y') as date_formatt"))
        //->orderby('id', 'DESC')->take(15)->get();
        ->orderby('date_formatt', 'ASC')->get();
        $dataArray = array();

        $finalArray = array();

       // echo "<pre>";print_R($sessionData);exit;

        foreach ($sessionData as $value) {

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

                    $finalArray['caseid'] = $value->case_id;
                    $finalArray['claimant'] = $ip_user;
                    $finalArray['respondant'] = $rp_user;
                    $finalArray['mediator'] = $m_name;
                    $finalArray['session'] = $value->session_date;

                    if($value->zoom_link_choice == "manual"){
                        $finalArray['zoom_id'] = $value->zoom_id;
                        $finalArray['zoom_link'] = "";
                    } else {
                        $finalArray['zoom_id'] = "";
                        $finalArray['zoom_link'] = $zoom_link;
                    }
                
                    // echo "<tr>";
                    // echo "<td>" . $sn . "</td>";
                    // echo "<td>M0" . $value->case_id . "</td>";
                    // echo "<td>" . implode("<br>", $ip_user) ."</td>";
                    // echo "<td>" . implode("<br>", $rp_user) ."</td>";
                    // echo "<td>" . $m_name . "</td>";
                    // echo "<td>" . $value->session_date . "</td>";

                    // if($value->zoom_link_choice == "manual"){
                    //     echo "<td>" . $value->zoom_id . "</td>";
                    // } else {
                    //     echo "<td>" . $zoom_link . "</td>";
                    // }
                    
                    // echo "</tr>";
                    

                   // $sn++;
                }

                
            }
        }

        $result['success'] = true;
        $result['message'] = "Latest notifications fetched successfully.";
        $result['data'] = $finalArray;
        return response()->json($result, 200);
    }
}
