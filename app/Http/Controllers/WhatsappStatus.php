<?php

namespace App\Http\Controllers;

use App\Http\Helpers\SendGrid;
use App\Http\Helpers\Whatsapp;
use App\Models\InvoledUser;
use App\Models\MedCase;
use App\Models\Reminder;
use App\Models\WaTemplate;
use App\Models\WhatsappLog;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WhatsappStatus extends Controller
{
    public function status()
    {

        // date_default_timezone_set('Asia/Kolkata');


        $json = file_get_contents('php://input');
        // $s = storage_path();
        // print_r($s);
        // exit;
        // $myFile = storage_path()."/whatsapp_status/testFile" . date('Y-m-d_H:i:s') . ".txt";
        try {
            // file_put_contents($myFile, $json);
            Storage::put('public/whatsapp_status/testFile' . date('Y-m-d_H:i:s') . '.txt', $json);
        } catch (Exception $e) {

            echo $e->getMessage();
        }

        $data = json_decode($json, true);

        if ($data) {


            $request_id = $data['data']['request_uid'];
            $created_time = $data['data']['created_time'];
            $sent_time = $data['data']['sent_time'];
            $delivered_time = $data['data']['delivered_time'];
            $updated_time = $data['data']['updated_time'];
            $status = $data['data']['status'];
            $json = addcslashes($json, "'");


            $log = WhatsappLog::create([
                'request_id' => $request_id,
                'created_time' => $created_time,
                'sent_time' => $sent_time,
                'delivered_time' => $delivered_time,
                'updated_time' => $updated_time,
                'status' => $status,
                'response' => $json,
                'created_at' => date('Y-m-d H:s:i')
            ]);


            // $sql = "INSERT INTO whatsapp_log (request_id, created_time,sent_time,delivered_time,updated_time,status,response) VALUES ('$request_id', '$created_time', '$sent_time','$delivered_time','$updated_time','$status','$json')";
            if ($log) {
                echo '202';
            }
        }
        exit;
    }

    public function SendInvitation()
    {

        $date = \Carbon\Carbon::today()->subDays(2);
        $date = $date->format('Y-m-d');
        $cases = MedCase::select('mediation_case.*', 'iu.*', 'if.file_name', 'remainder.send_reminder')
            ->leftJoin('remainder', DB::raw('remainder.case_Id'), '=', DB::raw('mediation_case.id'))
            ->rightJoin('user_involved_in_agreement as iu', DB::raw('iu.userPlanId'), '=', DB::raw('mediation_case.id'))
            ->leftJoin('invitation_files as if', DB::raw('if.case_id'), '=', DB::raw('mediation_case.id'))
            ->where('iu.isOnboarded', 0)
            ->where('mediation_case.confirm_status', 1)
            ->where('if.file_name', '!=', null)
            ->where('remainder.send_reminder', 0)
            ->whereDate('remainder.created_at', $date)->get();

        foreach ($cases as $value) {
            //    $initiating_party = InvoledUser::where('userPlanId', $value->userPlanId)->where('isClaimant', 0)->first();
            $initiating_party = InvoledUser::select('user_involved_in_agreement.*', 'users.organization')->leftjoin('users', 'users.id', '=', 'user_involved_in_agreement.userId')->where('userPlanId', $value->userPlanId)->where('isClaimant', 0)->first();

            // dd($initiating_party);
            $d = [
                'event' => 'REM_ACPTARB_ADM_RES',
                'case_id' => $value->userPlanId,
            ];
            if ($value->userEmail != null) {
                $s = SendGrid::send($d, $value->userEmail, env('L4_INVITATION_TO_COUNTER_PARTIES_FOR_ONBOARDING', ''), ["-caseid-" => "M" . sprintf("%06d", $value->userPlanId), "-link-" => $value->joinCode, "-initiating-" => ($initiating_party->organization != null) ? $initiating_party->organization : $initiating_party->name], $value->name, url("/storage/app/public/mediation/" . $value->userPlanId . "/" . $value->file_name));
            }
            if ($value->userPhone != null) {
                $varjson = ['caseid' => "M" . sprintf("%06d", $value->userPlanId), 'initiating' => ($initiating_party->organization != null) ? $initiating_party->organization : $initiating_party->name];
                $var = ['-cid-', '-ip-'];
                $var1 = ["M" . sprintf("%06d", $value->userPlanId), ($initiating_party->organization != null) ? $initiating_party->organization : $initiating_party->name];
                $content1 = WaTemplate::getcontent('l4_mediation_party2');
                $content = str_replace($var, $var1, $content1);
                $dwa1 = [
                    'caseid' => $value->userPlanId,
                    'contact' => "+91" . $value->userPhone,
                    'content' => ['text' => $content],
                    'event' => 'REM_ACPTARB_ADM_RES',
                    'varjson' => $varjson,
                ];

                $access = Whatsapp::sendWamessage($dwa1);

                $varjson_file = ['caseid' => "M" . sprintf("%06d", $value->userPlanId)];
                $var_file = ['-caseid-'];
                $var1_file = ["M" . sprintf("%06d", $value->userPlanId)];
                $content1_file = WaTemplate::getcontent('mediation_consent_doc');
                $content_file = str_replace($var_file, $var1_file, $content1_file);
                $dwa2 = [
                    'caseid' =>  $value->userPlanId,
                    'contact' => "+91" . $value->userPhone,
                    'content' => ['media' => ['url' => url("/storage/app/public/mediation/" . $value->userPlanId . "/" . $value->file_name), 'caption' => $content_file]],
                    'event' => 'REM_ACPTARB_ADM_RES',
                    'varjson' => $varjson_file,
                ];
                $access = Whatsapp::sendWamessage($dwa2);
            }

            $remainder = Reminder::where('case_Id', $value->userPlanId)->first();
            $remainder->send_reminder = 1;
            $remainder->save();
        }

        echo "Success";
        exit;
    }
}
