<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\InvoledUser;
use App\Http\Helpers\SendGrid;
use App\Http\Helpers\Whatsapp;
use App\Models\WaTemplate;
use DB;

class DailySession extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'session:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Respectively send an exclusive mail to every party for scheduled session via email.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        \Log::info("Cron is working fine!");
        $date = \Carbon\Carbon::today();
        $date = $date->format('d/m/Y');
        $two_days = \Carbon\Carbon::today()->subDays(2);
        $two_days = $two_days->format('d/m/Y');
       
       // echo "date->".$date; 
        //echo "Two date->".$two_days; 
    //    $date = '02/10/2022';
    //    $two_days = '30/09/2022';
        
        // $getSessionArray = DB::table('manage_session')->select()
        // ->where('session_date', 'LIKE', '%'.$date.'%')
        // ->orWhere('session_date', 'LIKE', '%'.$two_days.'%')
        // ->limit(5)
        // ->get();

        $query1 = "SELECT *
            FROM manage_session

            WHERE is_reminder_sent = 0 AND
            
            (session_date LIKE '%$date%' OR session_date LIKE '%$two_days%')
            
            LIMIT 50";

        $getSessionArray = DB::select($query1);
        
        // /echo "here==><pre>";print_R($getSessionArray);exit;   
        // $getSessionArray = DB::table('manage_session')->select()
        // ->where('is_reminder_sent', '=', 0)
        // ->where('session_date', 'LIKE', '%'.$date.'%')
        // ->orWhere('session_date', 'LIKE', '%'.$two_days.'%')
        // // /->limit(10)
        // ->get();

        // $getSessionArray = DB::table('manage_session')->select()
        // ->where('case_id', '=', '32936')
        // ->orWhere('case_id', '=', '29721')
        // ->get();
       
       
       if(empty($getSessionArray)) {
            echo "No scheduled session found.";
       } else {
        foreach($getSessionArray as $getSessionData) {
           

            $get_time = explode("/",$getSessionData->session_date);
            // /$time = date("g:i A", strtotime($request->sessionTime));
            $time = isset($get_time[3]) ? $get_time[3] : '';

            // $allParty['party']['all'] = InvoledUser::where("userPlanId", $getSessionData->case_id)->get();
            $allParty = InvoledUser::where("userPlanId", $getSessionData->case_id)->get();
             $allParty['zoom'] = $getSessionData->zoom_id;
             $allParty['case'] = $getSessionData->case_id;
             $allParty['time'] = $time;
             $allParty['sdate'] = $getSessionData->session_date;
           
        }
        
        foreach ($allParty as $party) {
            if(isset($party->userEmail) || isset($party->userPhone)) {
                $is_sent = $this->sned_session(($allParty['zoom'] != null) ? $allParty['zoom']  : $getSessionData->fsData['zoomId'], $allParty['case'], $party->userEmail, $party->name, ($allParty['sdate'] != null) ? $allParty['sdate'] : $getSessionData->fsData['sessionDate'] . "/" . $allParty['time'], $party->userPhone);
                
                if($is_sent) {

                    $getSessionArray = DB::table('manage_session')
                     ->where('case_id', $allParty['case'])
                     ->update(array('is_reminder_sent' => 1));
                    // ->limit(5)
                    // ->get();




                    echo "<br/>Reminder sent successfully for caseID - ".$allParty['case'];
                } else {
                    echo "<br/>Some error in caseID - ".$allParty['case'];
                }
            }
        }
        
   
        } 

        

    }



    public function sned_session($url, $id, $email_id, $email_name, $date, $userPhone)
    {
        $mid = "M" . sprintf("%06d", $id);
        $d = [
            'event' => 'SESS_SCHE',
            'case_id' => $id,
        ];
        
        if ($email_id != "") {
            SendGrid::send($d, $email_id, env('L10_SCHEDULING_OF_SESSION', ''), ["-caseid-" => $mid, "-insert_date-" => $date, "-type-" => "Party"], $email_name);
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

            $access = Whatsapp::sendWamessage($dwa1);
        }
        return true;
    }
}
