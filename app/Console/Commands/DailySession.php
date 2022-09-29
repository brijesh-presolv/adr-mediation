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
        
        // $date = \Carbon\Carbon::today();
        // $date = $date->format('d/m/Y');
        // $two_days = \Carbon\Carbon::today()->subDays(2);
        // $two_days = $two_days->format('d/m/Y');
       
        //echo "date-><pre>";print_R($date); 
       //$date = '06/03/2022';
       //$two_days = '04/03/2022';
        
        // $getSessionArray = DB::table('manage_session')->select()
        // ->where('session_date', 'LIKE', '%'.$date.'%')
        // ->orWhere('session_date', 'LIKE', '%'.$two_days.'%')
        // ->get();

        $getSessionArray = DB::table('manage_session')->select()
        ->where('case_id', '=', '32936')
        ->orWhere('case_id', '=', '29721')
        ->orWhere('case_id', '=', '11278')
        ->get();
       
        //echo "<pre>";print_R($getSessionArray);
       
       if($getSessionArray->isEmpty()) {
            echo "No scheduled session found.";

       } else {
        foreach($getSessionArray as $getSessionData) {
            //echo "<pre>";print_R($getSessionData);

            $allParty = InvoledUser::where("userPlanId", $getSessionData->case_id)->get();
            //echo "<pre>allParty==>";print_R($allParty);

            $get_time = explode("/",$getSessionData->session_date);
            // /$time = date("g:i A", strtotime($request->sessionTime));
            $time = $get_time[3];

            foreach ($allParty as $party) {
                //echo "<prE>";print_R($party);
            // $this->sned_session($request->zoomId, $request->caseId, $party->userEmail, $party->name, $request->sessionDate . "/" . $time, $party->userPhone);
                $is_sent = $this->sned_session(($getSessionData->zoom_id != null) ? $getSessionData->zoom_id  : $getSessionData->fsData['zoomId'], $getSessionData->case_id, $party->userEmail, $party->name, ($getSessionData->session_date != null) ? $getSessionData->session_date : $getSessionData->fsData['sessionDate'] . "/" . $time, $party->userPhone);
                
                if($is_sent) {
                    echo "<br/>Reminder sent successfully for caseID - ".$getSessionData->case_id;
                } else {
                    echo "<br/>Some error in caseID - ".$getSessionData->case_id;
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

            $varjson = ['sessionDteaTime' => $date, 'caseid' => $mid, 'zoomid' => $url];
            $var = ['-dt-', '-cid-', '-link-'];
            $var1 = [$date, $mid, $url];
            $content1 = WaTemplate::getcontent('l10_session_schedule');
            $content = str_replace($var, $var1, $content1);
            $dwa1 = [
                'caseid' => $id,
                'contact' =>  $userPhone,
                'content' => ['text' => $content],
                'event' => 'SESS_SCHE',
                'varjson' => $varjson,
                'haptik_tmp' => 'l10_session_schedule',

            ];

            $access = Whatsapp::sendWamessage($dwa1);
        }
        return true;
    }
}
