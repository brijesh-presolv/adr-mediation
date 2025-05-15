<?php

namespace App\Http\Helpers;

use App\Models\sms_queModal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class sms
{
    public static function sendsmsmessage($data)
    {

        $ocarr = [];
        if (!empty($data['contact'])) {
            $ocarr = array_merge($ocarr, explode(',', $data['contact']));
        } 
        
        $i = 1;
        foreach ($ocarr as $key => $value) {

            $c = $value;

            //que table
            $settlelink = 'https://'.$_SERVER['SERVER_NAME'].'/email/reply/' . base64_encode($data['caseid']);
            $sprintf_id = 'M' . sprintf("%06d", $data['caseid']);
            if ($data['casetype'] == 1) {
                $notice_type = 1;
            } else {
                $notice_type = 2;
            }
            $arr_e = array();
            $arr_e['caseid'] = $data['caseid'];
            $arr_e['contact'] = $c;
            $arr_e['content'] = $data['content'];
            $arr_e['casetype'] = $data['casetype'];
            $arr_e['event'] = $data['event'];
            $arr_e['jio_tmp'] = $data['jio_tmp'];
            $arr_e['replylink'] = $settlelink;
            $arr_e['created_at'] = $data['created_at'];
            $arr_e['notice_type'] = $notice_type;
            $arr_e['unq_id_notice'] = json_encode($data['varjson']);

            sms_queModal::insert($arr_e);
            // received_time >= DATE_SUB(NOW(),INTERVAL 1 MINUTE
            // if(!sms_queModal::where('caseid','=',$data['caseid'])->where('contact','=',$c)->where('jio_tmp','=',$data['jio_tmp'])->whereDate('created_at', Carbon::today())->get()){
            if(!sms_queModal::where('caseid','=',$data['caseid'])->where('contact','=',$c)->where('jio_tmp','=',$data['jio_tmp'])->where('created_at','>=', Carbon::now()->subMinutes(1)->toDateTimeString())){
                // sms_queModal::insert($arr_e);            
                Log::info('SMS: this condition is working fine');
                    
            }

            $i++;
        }

        return true;

    }
}
