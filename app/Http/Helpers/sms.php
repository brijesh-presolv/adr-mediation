<?php

namespace App\Http\Helpers;

use App\Http\Helpers\Curl;
use App\Models\sms_queModal;
use App\Models\sms_statusModal;
use App\Models\sms_tracking;

class sms
{
    public static function sendsmsmessage($data)
    {

        $ocarr = [];

        $ocarr[] = $data['contact'];
        $i = 1;
        foreach ($ocarr as $key => $value) {

            if ($value == '') {
                continue;
            }

            if ($i == 1) {
                $c = $value;
            } else {

                $c = '+91' . $value;
            }

            if ($c == '+919414784873') {

                continue;
            }

            //que table
          //  $settlelink = request()->getSchemeAndHttpHost() . '/email/reply/' . base64_encode($data['caseid']);
            $settlelink = "";
            $sprintf_id = 'M' . sprintf("%06d", $data['caseid']);
            if($data['casetype'] == 1){
             $notice_type = 1 ;
            }else{
                $notice_type = 2;
            }
            $arr_e = array();
            $arr_e['caseid'] = $data['caseid'];
            $arr_e['contact'] = trim($c);
            $arr_e['content'] = $data['content'];
            $arr_e['jio_tmp'] = $data['jio_tmp'];
            $arr_e['casetype'] = $data['casetype'];
            $arr_e['event'] = $data['event'];
            $arr_e['replylink'] = $settlelink;
            $arr_e['created_on'] = $data['created_at'];
            $arr_e['notice_type'] = $notice_type ;
            $arr_e['unq_id_notice'] = $sprintf_id;

            sms_queModal::insert($arr_e);

            $i++;
        }
        $authKey = "353508AnLLLst4qR62ce8822P1";
        $mobile_Number = $data['contact'];
        // $mobile_Number = '8866822947';
        $senderId = "Prsolv";
        $route = "4";
        $postData = array(
            'authkey' => $authKey,
            'mobiles' => "+91" . $mobile_Number,
            'message' => $data['content'],
            'sender' => $senderId,
            'route' => $route,
            'DLT_TE_ID' => $data['content1']->DLT_TE_ID,
        );
        $url = "https://control.msg91.com/api/sendhttp.php";
        $res = Curl::smsrequest($url, $postData);
        if (is_array($data['content']) && array_key_exists('text', $data['content'])) {
            $data1 = [
                'caseid' => $data['caseid'],
                'casetype' => $data['casetype'],
                'event' => $data['event'],
                'contact' => $data['contact'],
                'content' => $data['content'],
                'jio_tmp' => $data['jio_tmp'],
                'request_uuid' => $res,
                'credits_charged' => '0',
                'full_resp' => $res,
                'created_at' => $data['created_at'],
            ];

            sms_tracking::insert($data1);
        } else {

            $data2 = [
                'caseid' => $data['caseid'],
                'casetype' => $data['casetype'],
                'event' => $data['event'],
                'contact' => $data['contact'],
                'content' => $data['content'],
                'jio_tmp' => $data['jio_tmp'],
                'request_uuid' => $res,
                'credits_charged' => '0',
                'full_resp' => $res,
                'created_at' => $data['created_at'],
            ];

            sms_tracking::insert($data2);
        }
      
        if ($res != " ")  {
            $data1 = [
                'request_id' => $res,
                'created_time' => date('Y-m-d H:i:s'),
                'sent_time' => date('Y-m-d H:i:s'),
                'delivered_time' => date('Y-m-d H:i:s'),
                'updated_time' => date('Y-m-d H:i:s'),
                'status' => 0,
                'created_at' => $data['created_at'],

            ];

            sms_statusModal::insert($data1);
        }

    }
}
