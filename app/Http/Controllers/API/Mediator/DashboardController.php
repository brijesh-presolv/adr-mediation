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
        $userId = 264;
        $view = Notification::where('view_mediator', 0)->where('mediator_id', $userId)->get();

        foreach ($view as $item) {
            $item->view_mediator = 1;
            $item->save();
        }
        $data = Notification::mediatornotificationDataAPI($userId);
        
        $result['success'] = true;
        $result['message'] = "User notifications fetched successfully.";
        $result['data'] = $data;
        return response()->json($result, 200);
    }

    public function viewCaseDetails(Request $request) {
        //Input
        $caseid = $request->input('caseid');

        $case = MedCase::select("mediation_case.*", "users.first_name as mfirstname", "users.last_name as mlastname", "mediators_mediation_cases_status.mediator_id as mediator_id", "mediators_mediation_cases_status.status as mediator_status")
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

        $case->supporting_document = DB::table('manage_files')->select('manage_files.*', 'users.username')
            ->join('users', 'users.id', '=', 'manage_files.uploaded_by')
            ->where('manage_files.case_id', $case->id)
            ->get();

        $case->mom = DB::table('session_mom')->select("file_name")->where('case_id', $case->id)->get();


        $result['success'] = true;
        $result['message'] = "Case details fetched successfully.";
        $result['data'] = $case;
        return response()->json($result, 200);

    }
   
}
