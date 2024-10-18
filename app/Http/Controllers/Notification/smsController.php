<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Curl;
use App\Models\sms_queModal;
use App\Models\sms_tracking;
use App\Models\sms_template;
use App\Models\sms_statusModal;

class smsController extends Controller
{

    public function send()
    {

        $limit = 100;
        $smsapp = sms_queModal::where(['is_sent' => 0, 'is_processing' => 0])->orderBy('id', 'desc')->limit($limit)->get();

        if (count($smsapp) < 1) {
            exit();
        }

        $smsapppr = [];
        foreach ($smsapp as $key => $value) {

            $smsapppr[] = $value->id;
        }
        $setprocess = sms_queModal::whereIn('id', $smsapppr)->limit($limit)->update(['is_processing' => 1]);
        foreach ($smsapp as $key => $value) {
            $content1 = sms_template::getsmscontent1($value->jio_tmp);
        
            $d = [
                'id' => $value->id,
                'event' => $value->event,
                'jio_tmp' => $value->jio_tmp,
                'content' => $value->content,
                'casetype' => $value->casetype,
                'caseid' => $value->caseid,
                'content1' =>  $content1
            ];

           
            self::NewsmsMessage($d, $value->contact);

        }
       
    }

    public function NewsmsMessage($d, $c)
    {

        $url = "https://control.msg91.com/api/sendhttp.php";
        $authKey = "353508AnLLLst4qR62ce8822P1";
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
        );

        $res = Curl::smsrequest($url, $postData);

        dd($res);
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
                'created_at' => date('Y-m-d H:i:s'),
            ];

            sms_tracking::insert($d1);
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
                'created_at' => date('Y-m-d H:i:s'),
            ];

            sms_tracking::insert($d2);
        }
        $que = sms_queModal::where(['is_sent' => 0, 'is_processing' => 1, 'id' => $d['id']])->update(['is_processing' => 0, 'is_sent' => 1]);
        
        if ($res != " ") {
            $sms_que = sms_queModal::select('is_processing','is_sent')->where('id', '=',$d['id'])->get();
        
            if($sms_que[0]->is_sent == 1 ){
                $status = "sent";
            }
            $data1 = [
                'request_id' => $res,
                'created_time' => date('Y-m-d H:i:s'),
                'sent_time' => date('Y-m-d H:i:s'),
                'delivered_time' => date('Y-m-d H:i:s'),
                'updated_time' => date('Y-m-d H:i:s'),
                'status' => $status,
                'created_at' => date('Y-m-d H:i:s'),

            ];

            sms_statusModal::insert($data1);
        }
    }
}
