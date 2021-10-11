<?php

namespace App\Http\Helpers;

use App\Http\Helpers\Curl;
use App\Models\WhatsappTrack;

class Whatsapp
{
    public static function sendWamessage($d)
    {
        // print_r($d);
        // exit;

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

        // print_r($data);
        // exit;
        $type = "POST";


        $res = Curl::request($url, $data, $type, $auth);
        $res1 = json_decode($res, true);

        // print_r($res1);
        // exit;
        if ($res1) {

            if (array_key_exists('text', $d['content'])) {


                $data1 = [

                    'caseid' => $d['caseid'],
                    'contact' => $d['contact'],
                    'content' => implode(" ", str_replace(['‘', '’'], ['::', ';;'], $d['content'])),
                    'casetype' => $d['casetype'],
                    'event' => $d['event'],
                    'request_uuid' => $res1['meta']['request_uuid'],
                    'credits_charged' => '0',
                    'full_resp' => $res,
                    'created_at' => date('Y-m-d H:i:s')


                ];

                WhatsappTrack::create($data1);
            } else {

                $data2 = [

                    'caseid' => $d['caseid'],
                    'contact' => $d['contact'],
                    'content'=> "",
                    'media' => $d['content']['media']['url'],
                    'casetype' => $d['casetype'],
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
    }
}
