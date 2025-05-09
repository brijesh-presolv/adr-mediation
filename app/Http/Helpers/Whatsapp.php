<?php

namespace App\Http\Helpers;

use App\Http\Helpers\Curl;
use App\Models\WhatsAppQue;
use App\Models\WhatsappTrack;
use App\Models\SendWhatsappChoice;

use App\Http\Helpers\Common_function;

use DB;

class Whatsapp
{
    public static function sendWamessage($d)
    {
        $platform = SendWhatsappChoice::select('platform_name')->where("is_active", "=", 1)->first();

       

        if($platform['platform_name'] == "Mtalkz"){
            self::sendWaMtalkzmessage($d);
        } else {
                $ocarr = [];

                $ocarr[] = $d['contact'];

                // if ($oc != '') {

                //     $ocarr = array_merge($ocarr, explode(',', $oc));
                // }

                // $i = 1;

                // Check if user stopped the whtsapp notification //
                $check_phone = Common_function::checkIfPhoneExist($d['contact']);
                // Check if user stopped the whtsapp notification //

       
                if($check_phone == 0){

                    foreach ($ocarr as $key => $value) {

                        if ($value == '') {
                            continue;
                        }
                        // dd(strlen($value));
                        if (strlen($value) == 10) {
                            $value = '+91' . $value;
                        } else {
                            $value = $value;
                        }
            
                        $arr_e = array();
                        $arr_e['caseid'] = $d['caseid'];
                        $arr_e['contact'] = trim($value);
                        $arr_e['content'] = json_encode($d['content']);
                        $arr_e['casetype'] = 2;
                        $arr_e['event'] = $d['event'];
                        $arr_e['variable'] = json_encode($d['varjson']);
                        $arr_e['haptik_tmp'] = $d['haptik_tmp'];
            
                        if (array_key_exists('media', $d['content'])) {
                            $arr_e['media'] = 1;
                        }
            
            
                        WhatsAppQue::create($arr_e);
                    }

                }

        

                return true;
        }
        // dd(date('Y-m-d H:i:s'));

        $url = "https://api.karix.io/message/";

        //sandbox testing
        $auth = base64_encode("7f88f3bf-478a-45d9-aa82-08ca650a3838:1f244c14-7ee9-4cf8-90d1-96c49e9a38bd");

        //for live number
        //$auth = base64_encode(WAUTH);

        $data = [
            "channel" => "whatsapp",
            "source" => "+13253077759",
            "destination" => [$d['contact']],
            "content" => $d['content'],
            "events_url" => route('whatsapp_status'),
        ];
        // $eventUrl = route('whatsapp_status');

        // $auth = base64_encode("1b634896-7d26-4f4d-aa15-8f9313fc9849:4e211b4d-a4d0-477f-98b9-a82ea6fafe89");

        // $data = [
        //     "channel" => "whatsapp",
        //     "source" => "+918591275735",
        //     "destination" => [$d['contact']],
        //     // "destination" => $d['contact'],
        //     "content" => $d['content'],
        //     "events_url" => route('whatsapp_status'),
        // ];

        //+918591275735 - live no.
        //+13253077759 - sandbox no.

        $data = json_encode($data);


        $type = "POST";


        $res = Curl::request($url, $data, $type, $auth);
        $res1 = json_decode($res, true);


        if ($res1) {

            if (array_key_exists('text', $d['content'])) {


                $data1 = [

                    'caseid' => isset($d['caseid']) ? $d['caseid'] : null,
                    'contact' => $d['contact'],
                    'content' => implode(" ", str_replace(['‘', '’'], ['::', ';;'], $d['content'])),
                    'event' => $d['event'],
                    'request_uuid' => $res1['meta']['request_uuid'],
                    'credits_charged' => '0',
                    'full_resp' => $res,
                    'created_at' => date('Y-m-d H:i:s')


                ];

                WhatsappTrack::create($data1);
            } else {

                $data2 = [

                    'caseid' => isset($d['caseid']) ? $d['caseid'] : null,
                    'contact' => $d['contact'],
                    'content' => "",
                    'media' => $d['content']['media']['url'],
                    'event' => $d['event'],
                    'request_uuid' => $res1['meta']['request_uuid'],
                    'credits_charged' => '0',
                    'full_resp' => $res,
                    'created_at' => date('Y-m-d H:i:s')


                ];

                WhatsappTrack::create($data2);
            }

            //     return true;
            // } else {

            //     return false;
            // }
            return true;
            // }
        } else {
            return false;
        }
    }


    public static function sendWaSmessage($d, $oc = '')
    {

        $platform = SendWhatsappChoice::select('platform_name')->where("is_active", "=", 1)->first();;

        if($platform['platform_name'] == "Mtalkz"){

            self::sendWaMtalkzmessage($d);
        } else {
       
            $ocarr = [];

            $ocarr[] = $d['contact'];

            if ($oc != '') {

                $ocarr = array_merge($ocarr, explode(',', $oc));
            }

            $i = 1;

            // Check if user stopped the whtsapp notification //
            $check_phone = Common_function::checkIfPhoneExist($d['contact']);
            // Check if user stopped the whtsapp notification //

            if($check_phone == 0){
                foreach ($ocarr as $key => $value) {

                    if ($value == '') {
                        continue;
                    }

                    if ($i == 1) {
                        $c = $value;
                    } else {

                        $c = '+91' . $value;
                    }

                    if($c=='+919414784873'){

                        continue;
                    }



                    //que table
                    $arr_e = array();
                    $arr_e['caseid'] = $d['caseid'];
                    $arr_e['contact'] = trim($c);
                    $arr_e['content'] = json_encode($d['content']);
                    $arr_e['casetype'] = 2;
                    $arr_e['event'] = $d['event'];
                    $arr_e['variable'] = json_encode($d['varjson']);
                    $arr_e['haptik_tmp'] = isset($d['haptik_tmp']) ? $d['haptik_tmp'] : null;

                    if (array_key_exists('media', $d['content'])) {

                        $arr_e['media'] = 1;
                    }

                    WhatsAppQue::insert($arr_e);

                    $i++;
                }
            }
            return true;
        }

        $url = "https://api.karix.io/message/";

        //sandbox testing
        $auth = base64_encode("7f88f3bf-478a-45d9-aa82-08ca650a3838:1f244c14-7ee9-4cf8-90d1-96c49e9a38bd");

        //for live number
        //$auth = base64_encode(WAUTH);

        $data = [
            "channel" => "whatsapp",
            "source" => "+13253077759",
            "destination" => [$d['contact']],
            "content" => $d['content'],
            "events_url" => "https://presolv360.com/functions/whatsapp_status.php",
        ];

        //+918591275735 - live no.
        //+13253077759 - sandbox no.

        $data = json_encode($data);

        $type = "POST";


        $res = Curl::request($url, $data, $type, $auth);
        $res1 = json_decode($res, true);


        if ($res1) {

            if (array_key_exists('text', $d['content'])) {


                $data1 = [

                    'caseid' => $d['caseid'],
                    'contact' => $d['contact'],
                    'content' => implode(" ", str_replace(['‘', '’'], ['::', ';;'], $d['content'])),
                    'casetype' => 2,
                    'event' => $d['event'],
                    'request_uuid' => $res1['meta']['request_uuid'],
                    'credits_charged' => '0',
                    'full_resp' => $res,
                    'created_at' => date('Y-m-d H:i:s')


                ];

                WhatsappTrack::insert($data1);
            } else {

                $data2 = [

                    'caseid' => $d['caseid'],
                    'contact' => $d['contact'],
                    'media' => $d['content']['media']['url'],
                    'casetype' => $d['casetype'],
                    'event' => $d['event'],
                    'request_uuid' => $res1['meta']['request_uuid'],
                    'credits_charged' => '0',
                    'full_resp' => $res,
                    'created_at' => date('Y-m-d H:i:s')


                ];

                WhatsappTrack::insert($data2);
            }

            return true;
        } else {

            return false;
        }
    }


    // For stop whtsapp message //
    
    public static function sendWaStopmessage($d)
    {
        $platform = SendWhatsappChoice::select('platform_name')->where("is_active", "=", 1)->first();;

        if($platform['platform_name'] == "Mtalkz"){
            self::sendWaMtalkzmessage($d);
        } else {
            
        
        
            $ocarr = [];

            $ocarr[] = $d['contact'];


                foreach ($ocarr as $key => $value) {

                    if ($value == '') {
                        continue;
                    }
                    // dd(strlen($value));
                    if (strlen($value) == 10) {
                        $value = '+91' . $value;
                    } else {
                        $value = $value;
                    }
        
                    $arr_e = array();
                    $arr_e['caseid'] = $d['caseid'];
                    $arr_e['contact'] = trim($value);
                    $arr_e['content'] = json_encode($d['content']);
                    $arr_e['casetype'] = 2;
                    $arr_e['event'] = $d['event'];
                    $arr_e['variable'] = json_encode($d['varjson']);
                    $arr_e['haptik_tmp'] = $d['haptik_tmp'];
        
                    if (array_key_exists('media', $d['content'])) {
                        $arr_e['media'] = 1;
                    }
        
        
                    WhatsAppQue::create($arr_e);
                }

        

            return true;
       
        } 
    }
    // For stop whtsapp message //


    // Mtalkz Code //
    public static function sendWaMtalkzmessage($d)
    {
       // dd($d);
        $ocarr = [];

        $ocarr[] = $d['contact'];

        
            foreach ($ocarr as $key => $value) {

                if ($value == '') {
                    continue;
                }
                // dd(strlen($value));
                if (strlen($value) == 10) {
                    $value = '+91' . $value;
                } else {
                    $value = $value;
                }
    
                $arr_e = array();
                $arr_e['caseid'] = $d['caseid'];
                $arr_e['contact'] = trim($value);
                $arr_e['content'] = json_encode($d['content']);
                $arr_e['casetype'] = 2;
                $arr_e['event'] = $d['event'];
                $arr_e['variable'] = json_encode($d['varjson']);
                $arr_e['haptik_tmp'] = $d['haptik_tmp'];
    
                if (array_key_exists('media', $d['content'])) {
                    $arr_e['media'] = 1;
                }
    
    
                WhatsAppQue::create($arr_e);
            }

        
        return true;
       
    }
    // Mtalkz Code //

}
