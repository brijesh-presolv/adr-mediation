<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Curl;
use App\Models\sms_queModal;
use App\Models\sms_statusModal;
use App\Models\sms_template;
use App\Models\sms_tracking;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class smsController extends Controller
{

    public function send()
    {

        // try {
        $limit = 100;
        // $smsapp = sms_queModal::where(['is_sent' => 0, 'is_processing' => 0])->whereRaw( 'created_at< NOW() - INTERVAL 30 MINUTE')->limit($limit)->get();


        $smsapp = sms_queModal::where(['is_sent' => 0, 'is_processing' => 0])->limit($limit)->get();

        if (count($smsapp) < 1) {
            exit();
        }

        // $smsapppr = [];
        // foreach ($smsapp as $key => $value) {

        //     $smsapppr[] = $value->id;
        // }

        $nowTime = Carbon::now();
        $oneMinuteAgoTime = Carbon::now()->subMinutes(1)->toDateTimeString();


        Log::info("======================SMS INITIAL LOG - Start ======================");
        Log::info("TIME NOW ==>" . $nowTime . " == Time One Minute Ago =>" . $oneMinuteAgoTime . "==> total fetched cases==" . count($smsapp));
        Log::info("======================SMS INITIAL LOG - End ======================");


        foreach ($smsapp as $key => $value) {
            $now = Carbon::now();
            $oneMinuteAgo = Carbon::now()->subMinutes(1)->toDateTimeString();
            // $cacheKey = sms_tracking::where(['contact' => $value->contact, 'event' => $value->event, 'jio_tmp' => $value->jio_tmp])->where('created_at', '>=', 'CURRENT_TIMESTAMP - INTERVAL 1 MINUTE')->get();
            $smsTrackQuery = sms_tracking::where(['contact' => $value->contact, 'jio_tmp' => $value->jio_tmp])
                ->where('caseid', $value->caseid)
                ->whereBetween('created_at', [$oneMinuteAgo, $now]);
            $cacheKey = $smsTrackQuery->get();
            // echo '<pre>';
            // print_r($cacheKey);
            Log::info("======================SMS log 1 - Check if data is already there in tracking or not - Start ======================");
            Log::info("SMS LOG 1==>" . $cacheKey . " == CASE ID =>" . $value->caseid);
            Log::info("======================SMS log 1 - Check if data is already there in tracking or not - End ======================");
            if (!$cacheKey->isEmpty() && $cacheKey->count() > 0) {
                // $cacheKeyQuery = sms_tracking::where(['contact' => $value->contact, 'event' => $value->event, 'jio_tmp' => $value->jio_tmp])->where('created_at', '>=', 'CURRENT_TIMESTAMP - INTERVAL 1 MINUTE');
                // $cacheKeyQuery = sms_tracking::where(['contact' => $value->contact, 'event' => $value->event, 'jio_tmp' => $value->jio_tmp])
                // ->whereBetween('created_at', [$oneMinuteAgo, $now]);
                $query = str_replace(array('?'), array('\'%s\''), $smsTrackQuery->toSql());
                $query = vsprintf($query, $smsTrackQuery->getBindings());
                //     echo '<pre>';
                // print_r($cacheKeyQuery);exit;
                Log::info("======================SMS log 2 print query If Start ======================");
                Log::info("SMS LOG 2 query==>" . $query . " CASE ID =>" . $value->caseid);
                Log::info("======================SMS log 2 print query If End ======================");
            } else {

                $query = str_replace(array('?'), array('\'%s\''), $smsTrackQuery->toSql());
                $query = vsprintf($query, $smsTrackQuery->getBindings());
                //     echo '<pre>';
                // print_r($cacheKeyQuery);exit;
                Log::info("======================SMS log 2 print query ELSE Start ======================");
                Log::info("SMS ELSE query==>" . $query . " CASE ID =>" . $value->caseid);
                Log::info("======================SMS log 2 print query ELSE End ======================");
                //     echo '<pre>';
                // print_r('NAA');exit;
                Log::info("======================SMS Log In If Else Start ======================");
                Log::info("contact NO:" . $value->contact . "-- Event:" . $value->event . "-- Template ID:" . $value->jio_tmp . " Date & Time:" . $now . " CASE ID =>" . $value->caseid);
                Log::info("======================SMS Log In If Else End ======================");
                $content1 = sms_template::getsmscontent1($value->jio_tmp);
                $d = [
                    'id' => $value->id,
                    'event' => $value->event,
                    'jio_tmp' => $value->jio_tmp,
                    'content' => $value->content,
                    'casetype' => $value->casetype,
                    'caseid' => $value->caseid,
                    'content1' => $content1,
                    'datetime' => $now
                ];
                $setprocess = sms_queModal::where('id', $value->id)->update(['is_processing' => 1]);

                if (preg_match('/^[6-9]\d{9}$/', $value->contact)) {
                    $smsSentResponse = self::NewsmsMessage($d, $value->contact);
                    if ($smsSentResponse == true) {
                        Log::info("======================Message Sent Log Start ======================");
                        Log::info("Message Sent ==> contact NO:" . $value->contact . "-- Event:" . $value->event . "-- Template ID:" . $value->jio_tmp . "Date & Time:" . $now . " CASE ID =>" . $value->caseid);
                        Log::info("======================Message Sent Log End ======================");
                        // return;
                    } else {
                        Log::info("======================MSG NOT SENT Start ======================");
                        Log::info("MSG NOT SENT DUE to some issues ==> contact NO:" . $value->contact . "-- Event:" . $value->event . "-- Template ID:" . $value->jio_tmp . "Date & Time:" . $now);
                        Log::info("======================MSG NOT SENT Log End ======================");
                        // return;
                    }
                } else {
                    Log::info("======================Invalid Mobile Number Log Start ======================");
                    Log::info("SMS LOG == Invalid mobile number ==> contact NO:" . $value->contact . "-- Event:" . $value->event . "-- Template ID:" . $value->jio_tmp . "Date & Time:" . $now);
                    Log::info("======================Invalid Mobile Number Log End ======================");
                    // return;
                }
            }
        }
        // } catch (\Exception $e) {

        //     Log::info("Catch Error log => ".$e->getMessage());
        //     // return $e->getMessage();
        // }
    }

    public function NewsmsMessage($d, $c)
    {

        $res = "";
        $url = "https://control.msg91.com/api/sendhttp.php";
        $authKey = env('SMS_AUTH_KEY');
        $mobile_Number = $c;
        // $mobile_Number = '8866822947';
        $senderId = "Prsolv";
        $route = "4";
        $postData = array(
            'authkey' => $authKey,
            'mobiles' => "+91" . $mobile_Number,
            'message' => $d['content'],
            'sender' => $senderId,
            'route' => $route,
            'DLT_TE_ID' => $d['content1']->DLT_TE_ID,
            'unicode' => $d['content1']->is_unicode,
        );

        $res = Curl::smsrequest($url, $postData);
        $caseId = $d["caseid"];
        Log::info("======================SMS LOG Response Log Start ======================");
        Log::info("SMS LOG Response ==>" . $res . " mobile_Number =>" . $mobile_Number . " Case ID =>" . $caseId . " date & Time => " . $d['datetime']);
        Log::info("======================SMS LOG Response Log End ======================");

        // $resjson = json_decode($res);
        if (is_array($d['content']) && array_key_exists('text', $d['content'])) {
            $d1 = [
                'caseid' => $d['caseid'],
                'casetype' => $d['casetype'],
                'event' => $d['event'],
                'contact' => $mobile_Number,
                'content' => $d['content'],
                'jio_tmp' => $d['jio_tmp'],
                'request_uuid' => $res,
                'credits_charged' => '0',
                'full_resp' => $res,
                'created_at' => $d['datetime'],
            ];

            sms_tracking::insert($d1);
            Log::info("======================SMS tracking Log Start ======================");
            Log::info("LOG add tracking D1 ==> Response => " . $res . " mobile_Number =>" . $mobile_Number . " Case ID =>" . $caseId . " Data=>" . implode(",", $d1) . " date & Time => " . $d['datetime']);
            Log::info("======================SMS tracking Log End ======================");
        } else {

            $d2 = [
                'caseid' => $d['caseid'],
                'casetype' => $d['casetype'],
                'event' => $d['event'],
                'contact' => $mobile_Number,
                'content' => $d['content'],
                'jio_tmp' => $d['jio_tmp'],
                'request_uuid' => $res,
                'credits_charged' => '0',
                'full_resp' => $res,
                'created_at' => $d['datetime'],
            ];

            sms_tracking::insert($d2);
            Log::info("======================SMS tracking 2 Log Start ======================");
            Log::info("LOG add tracking D2 ==> Response => " . $res . " mobile_Number =>" . $mobile_Number . " Case ID =>" . $caseId . " Data=>" . implode(",", $d2) . " date & Time => " . $d['datetime']);
            Log::info("======================SMS tracking 2 Log End ======================");
        }
        $que = sms_queModal::where(['is_sent' => 0, 'is_processing' => 1, 'id' => $d['id']])->update(['is_processing' => 0, 'is_sent' => 1]);
        if($que){
            Log::info("====================== SMS update que table - start ======================");
            Log::info("LOG add status  ==> Response => " . $res . " mobile_Number =>" . $mobile_Number . " Case ID =>" . $caseId . " date & Time => " . $d['datetime']);
            Log::info("====================== SMS update que table - End ======================");
            return true;
        }else{
            Log::info("======================SMS Added Statu Log Start ======================");
            Log::info("LOG add status  ==> Response => " . $res . " mobile_Number =>" . $mobile_Number . " Case ID =>" . $caseId . " date & Time => " . $d['datetime']);
            Log::info("======================SMS Added Statu Log End ======================");
            return true;
        }

        /*if ($res != "") {
            $sms_que = sms_queModal::select('is_processing', 'is_sent')->where('id', '=', $d['id'])->get();

            if ($sms_que[0]->is_sent == 1) {
                $status = "sent";
            }
            $data1 = [
                'request_id' => $res,
                'created_time' => $d['datetime'],
                'sent_time' => $d['datetime'],
                'delivered_time' => $d['datetime'],
                'updated_time' => $d['datetime'],
                'status' => $status,
                'created_at' => $d['datetime'],

            ];

            sms_statusModal::insert($data1);
            Log::info("======================SMS Added Statu Log Start ======================");
            Log::info("LOG add status  ==> Response => " . $res . " mobile_Number =>" . $mobile_Number . " Case ID =>" . $caseId . " Data =>" . implode(",", $data1) . " date & Time => " . $d['datetime']);
            Log::info("======================SMS Added Statu Log End ======================");
            return true;
        } else {
            return false;
        }*/
    }
}
