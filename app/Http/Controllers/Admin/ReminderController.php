<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InvoledUser;
use App\Http\Helpers\SendGrid;
use App\Http\Helpers\Whatsapp;
use App\Models\WaTemplate;


use DB;

class ReminderController extends Controller
{

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

    
    public function index()
    {
        $date = \Carbon\Carbon::today();
        $date = $date->format('d/m/Y');
        //$two_days = \Carbon\Carbon::today()->addDays(2);
        $two_days = \Carbon\Carbon::today()->addDays(1);
        $two_days = $two_days->format('d/m/Y');

        $query1 = "SELECT *
            FROM manage_session
            WHERE 
            (is_reminder_sent = 0 OR is_final_reminder = 0)
            AND (session_date LIKE '%$date%' OR session_date LIKE '%$two_days%')   
            AND is_deleted = 0
            LIMIT 50";

        //echo $query1;exit;
        $getSessionArray = DB::select($query1);

        //  /echo "<pre>";print_R($getSessionArray);

        if (empty($getSessionArray)) {
            echo "No scheduled session found.";
        } else {

            foreach ($getSessionArray as $key => $getSessionData) {

                $get_time = explode("/", $getSessionData->session_date);
                // /$time = date("g:i A", strtotime($request->sessionTime));
                $time = isset($get_time[3]) ? $get_time[3] : '';
                $dateToday = $get_time[0].'/'.$get_time[1].'/'.$get_time[2];
                // $toDaydate = Carbon::$getSessionData->session_date->format('d/m/Y');
                //echo $dateToday;exit;
               // echo date('d/m/Y',strtotime($dateToday));exit;
                //$toDaydate =  \Carbon\Carbon::CreateFromFormat('d/m/Y',$getSessionData->session_date);

                // $allParty['party']['all'] = InvoledUser::where("userPlanId", $getSessionData->case_id)->get();

                $party_array = json_decode($getSessionData->session_party_ids);
               
                $allParty[$key] = InvoledUser::where("userPlanId", $getSessionData->case_id)->whereIn("id", $party_array)->get();
                $allParty[$key]['zoom'] = $getSessionData->zoom_id;
                $allParty[$key]['case'] = $getSessionData->case_id;
                $allParty[$key]['zoom_link'] = $getSessionData->zoom_link;
                $allParty[$key]['time'] = $time;
                $allParty[$key]['sdate'] = $getSessionData->session_date;
                $allParty[$key]['finaldate'] = $dateToday;
            }
         //echo "<pre>here=>";print_R($allParty);exit;
            foreach ($allParty as $partyData) {
                foreach ($partyData as $party) {
                    // echo "<pre>";
                    // print_R($partyData);
                    // exit;
                    if (isset($party->userEmail) || isset($party->userPhone)) {
                        //$is_sent = $this->sned_session(($partyData['zoom'] != null) ? $partyData['zoom']  : $getSessionData->fsData['zoomId'], $partyData['case'], $party->userEmail, $party->name, ($partyData['sdate'] != null) ? $partyData['sdate'] : $getSessionData->fsData['sessionDate'] . "/" . $partyData['time'], $party->userPhone);
                        
                        if($partyData['zoom_link'] != "") {
                            $is_sent = $this->sned_session_invitation($partyData['zoom'], $partyData['case'], $party->userEmail, $party->name, $partyData['sdate'], $party->userPhone, $partyData['zoom_link']);
                        } else {
                            $is_sent = $this->sned_session(($partyData['zoom'] != null) ? $partyData['zoom']  : $getSessionData->fsData['zoomId'], $partyData['case'], $party->userEmail, $party->name, ($partyData['sdate'] != null) ? $partyData['sdate'] : $getSessionData->fsData['sessionDate'], $party->userPhone);
                        }
                        if ($is_sent) {
                            $updateReminderData_first = DB::table('manage_session')
                                ->where('case_id', $partyData['case'])
                                ->update(array('is_reminder_sent' => 1));

                            if($partyData['finaldate'] == $date){
                                $updateReminderData_final = DB::table('manage_session')
                                ->where('case_id', $partyData['case'])
                                ->update(array('is_final_reminder' => 1));
                                echo "<br/>Final Reminder sent successfully for caseID - " . $partyData['case'];
                            }

                            echo "<br/>Reminder sent successfully for caseID - " . $partyData['case'];
                        } else {
                            echo "<br/>Some error in caseID - " . $partyData['case'];
                        }
                    }
                }
            }
        }
    }

    public function sned_session_invitation($url, $id, $email_id, $email_name, $date, $userPhone, $invitation)
    {
        $mid = "M" . sprintf("%06d", $id);
        $d = [
            'event' => 'SESS_SCHE',
            'case_id' => $id,
        ];
        if ($email_id != "") {
            SendGrid::send($d, $email_id, env('L10_SCHEDULING_OF_SESSION', ''), ["-caseid-" => $mid, "-insert_date-" => $date, "-type-" => "Party", "-zoom_invitation_link-" => $invitation], $email_name);
            //SendGrid::send($d, $email_id, ["-caseid-" => $mid, "-insert_date-" => $date, "-type-" => "Party", "-zoom" => $invitation], $email_name);
        }
        if ($userPhone != "") {

            $varjson = ['caseid' => $mid, 'sessionDteaTime' => $date, 'zoomid' => $invitation];
            $var = ['-cid-', '-dt-', '-link-'];
            $var1 = [$mid, $date, $invitation];

            $template_name = WaTemplate::getRandomTemplate('L10');

            $content1 = WaTemplate::getcontent($template_name);
            $content = str_replace($var, $var1, $content1);
            $dwa1 = [
                'caseid' => $id,
                'contact' =>  $userPhone,
                'content' => ['text' => $content],
                'event' => 'SESS_SCHE',
                'varjson' => $varjson,
                'haptik_tmp' => $template_name,

            ];
            

            $access = Whatsapp::sendWaSmessage($dwa1);
        }
        return true;
    }


    public function sned_session($url, $id, $email_id, $email_name, $date, $userPhone)
    {
        $mid = "M" . sprintf("%06d", $id);
        $d = [
            'event' => 'SESS_SCHE',
            'case_id' => $id,
        ];
        if ($email_id != "") {
            SendGrid::send($d, $email_id, env('L10_SCHEDULING_OF_SESSION', ''), ["-caseid-" => $mid, "-insert_date-" => $date, "-type-" => "Party", '-zoom_invitation_link-' => $url], $email_name);
        }
        if ($userPhone != "") {

            $varjson = ['caseid' => $mid, 'sessionDteaTime' => $date, 'zoomid' => $url];
            $var = ['-cid-', '-dt-', '-link-'];
            $var1 = [$mid, $date, $url];

            $template_name = WaTemplate::getRandomTemplate('L10');

            $content1 = WaTemplate::getcontent($template_name);
            $content = str_replace($var, $var1, $content1);
            $dwa1 = [
                'caseid' => $id,
                'contact' =>  $userPhone,
                'content' => ['text' => $content],
                'event' => 'SESS_SCHE',
                'varjson' => $varjson,
                'haptik_tmp' => $template_name,

            ];

            // print_r($dwa1);
            // exit;

            $access = Whatsapp::sendWamessage($dwa1);
        }
        return true;
    }
    
}
