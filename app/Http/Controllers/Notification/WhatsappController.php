<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Helpers\Curl;
use App\Models\WhatsappTrack;
use App\Http\Traits\UploadTrait;
use App\Models\System;
use App\Models\WaTemplate;
use App\Models\WhatsAppQue;
use App\Models\SendWhatsappChoice;
use Illuminate\Support\Facades\Storage;

class WhatsappController extends Controller
{

    use UploadTrait;



    public function __construct()
    {
    }

    public function que_changes()
    {

        // $que = WhatsAppQue::select('whatsapp_que.*', 'whatsapp_tracking.id as Wtid', 'whatsapp_tracking.request_uuid')->leftJoin('whatsapp_tracking', function($q) {
        //     $q->on('whatsapp_tracking.caseid', '=', 'whatsapp_que.caseid')->on('whatsapp_tracking.event', '=', 'whatsapp_que.event');
        // })->limit(10)->get();

        // dd($que);

        // $que = WhatsAppQue::where(['is_sent' => 0, 'haptik_tmp' => ""])->get();
        // // dd($que);
        // // $que = WhatsAppQue::where('id', 1)->get();
        // foreach ($que as $value) {
        //     if ($value->variable != null) {
        //         $var = json_decode($value->variable, true);


        //         $update = array_reverse($var, true);

        //         $value->variable = json_encode($update);

        //         $value->haptik_tmp = "L4_mediation_party2";

        //         $value->save();
        //     }
        // }
        // $temp = WaTemplate::get();
        // foreach ($temp as $data) {
        //     $que = WhatsAppQue::where(['is_sent' => 0, 'haptik_tmp' => null])->get();
        //     // $per = [];
        //     foreach ($que as $value) {
        //         if ($value->media != 1) {
        //             $check = json_decode($value->content, true);
        //             similar_text($check['text'], $data->content, $percent);
        //             if ($percent > 90) {
        //                 // dd("if");
        //                 // if ($data->haptik_tmp != "") {
        //                     $value->haptik_tmp = $data->haptik_tmp;
        //                     $value->save();
        //                 // }
        //             }
        //         } else {
        //             $value->haptik_tmp = "mediation_consent_doc";
        //             $value->save();
        //         }
        //     }
        //     // dd($per);
        // }
        echo "success";
    }

    public function send()
    {

        // $limit = 500;

        // $whapps = WhatsAppQue::where(['is_sent' => 0, 'is_processing' => 0])->orderBy('created_at', 'DESC')->limit($limit)->get();

        // if (count($whapps) < 1) {
        //     exit();
        // }


        // $whappspr = [];

        // foreach ($whapps as $key => $value) {

        //     $whappspr[] = $value->id;
        // }



        // $setprocess = WhatsAppQue::whereIn('id', $whappspr)->limit($limit)->update(['is_processing' => 1]);


        // foreach ($whapps as $key => $value) {

        //     $content = json_decode($value->content, true);

        //     $oldcontent = '';


        //     if ($value->media == 1) {

        //         $oldcontent = $content;



        //         $contenturl = parse_url($content['media']['url'])["path"];

        //         $content['media']['url'] = $this->getPreSignedUrl(urldecode($contenturl), 1);

        //         if (!file_get_contents($content['media']['url'])) {
        //             continue;
        //         }
        //     }




        //     $d = [
        //         'id' => $value->id,
        //         'event' => $value->event,
        //         'caseid' => $value->caseid,
        //         'type' => $value->casetype,
        //         'content' => $content,
        //         'oldcontent' => $oldcontent,
        //     ];



        //     $r = self::sendwhapp($d, $value->contact);
        // }



        // $contenturl = 'mediation_documents/mediation/23414/Invitation_mediate_M0234141660298541.pdf';
        // $file_name = basename();

        // $content['media']['url'] = $this->getPreSignedUrl(urldecode($contenturl), 15);

        // exit;

        $platform = SendWhatsappChoice::select('platform_name')->where("is_active", "=", 1)->first();

       

        if($platform['platform_name'] == "Mtalkz"){
            $this->sendMtalkz();
        } else {

        $limit = 200;

        $whapps = WhatsAppQue::where(['is_sent' => 0, 'is_processing' => 0, 'is_success' => null,'is_hold'=> null])->whereDate('created_at', '>', '2022-07-31')->orderBy('created_at', 'ASC')->limit($limit)->get();


        // dd($whapps);  


        // echo '<pre>';
        // print_r($whapps);
        if (count($whapps) < 1) {
            exit();
        }
        // exit;


        $whappspr = [];

        foreach ($whapps as $key => $value) {

            $whappspr[] = $value->id;
        }



        $setprocess = WhatsAppQue::whereIn('id', $whappspr)->limit($limit)->update(['is_processing' => 1]);


        foreach ($whapps as $key => $value) {

            $content = json_decode($value->content, true);

            $oldcontent = '';

            // $trimdata = preg_replace("/\r|\n/", "", $content['text']);

            // dd($trimdata);

            // $findname = WaTemplate::get();
            // $tempname = "";
            // if (isset($content['text'])) {
            //     foreach ($findname as $data) {
            //         // $trimdatatable = preg_replace("/\r|\n/", "", $value->content);
            //         // dd($trimdatatable);
            //         similar_text($content['text'], $data->content, $percent);
            //         // dd($percent);
            //         if ($percent > 90) {
            //             $tempname = $data->name;
            //         }
            //     }
            // }

            $vararray = [];
            $vararrayheader = [];
            $convertarray = json_decode($value->variable, true);
            foreach ($convertarray as $var) {
                $vararray[] = $var;
            }

            if ($value->media == 1) {

                $oldcontent = $content;



                $contenturl = parse_url($content['media']['url'])["path"];
                $file_name = basename($content['media']['url']);

                $content['media']['url'] = $this->getPreSignedUrl(urldecode($contenturl), 360);
                

                // $vararrayheader[] = $this->getPreSignedUrl(urldecode($contenturl), 15);

                if (!file_get_contents($content['media']['url'])) {
                    continue;
                } else {
                    $path = 'public/tmp/' . $file_name;
                    Storage::disk('local')->put($path, file_get_contents($content['media']['url']));
                    $vararrayheader[] = url("storage/app/" . $path);
                    // echo url("storage/app/" . $path);
                }
            }
            // exit;

            $d = [
                'id' => $value->id,
                'event' => $value->event,
                'tempname' => $value->haptik_tmp,
                'varbody' => $vararray,
                'varheader' => count($vararrayheader) > 0 ? $vararrayheader : "",
                'file_name' => isset($file_name) ? $file_name : "",
                'content' => $content,
                'oldcontent' => $oldcontent,
                'type' => $value->casetype,
                'caseid' => $value->caseid,
            ];

            // dd($d);


            self::NewWhatsappMessage($d, str_replace('+91', '', $value->contact));
        }
        }
    }

    public function NewWhatsappMessage($d, $c)
    {
        $url = "https://api.interakt.ai/v1/public/message/";


        if ($d['varheader'] != "") {
            // dd("if");

            $data = [
                "countryCode" => "+91",
                "phoneNumber" => $c,
                "type" => "Template",
                "template" => [
                    "name" => $d['tempname'],
                    "languageCode" => "en",
                    "headerValues" => $d['varheader'],
                    "fileName" => $d['file_name'],
                    "bodyValues" => $d['varbody'],
                ]
            ];
        } else {

            $data = [
                "countryCode" => "+91",
                "phoneNumber" => $c,
                "type" => "Template",
                "template" => [
                    "name" => $d['tempname'],
                    "languageCode" => "en",
                    "bodyValues" => $d['varbody']
                ]
            ];
        }
        // dd($data);
        // $data = '{
        //     "countryCode": "+91",
        //     "phoneNumber": "' . $phone . '",
        //     "type": "Template",
        //     "template": {
        //         "name": "' . $temname . '",
        //         "languageCode": "en",
        //         "bodyValues": ' . $varbody . '
        //     }
        // }';
        $type = "POST";
        $auth = env('INTERAKT_KEY');

        $findtrack = WhatsappTrack::where(['que_id' => $d['id']])->orderBy('created_at', 'DESC')->limit(1)->first();

        if (isset($findtrack)) {
            if ($findtrack->request_uuid != "") {
                return true;
            }
        }
        $res = Curl::NewWhatsappRequest($url, json_encode($data), $type, $auth);
        // dd($res);

        $resjson = json_decode($res);


        if ($resjson) {

            if (array_key_exists('text', $d['content'])) {

                $data1 = [

                    'caseid' => $d['caseid'],
                    'que_id' => $d['id'],
                    'contact' => $c,
                    'content' => implode(" ", str_replace(['‘', '’'], ['::', ';;'], $d['content'])),
                    'casetype' => $d['type'],
                    'event' => $d['event'],
                    'request_uuid' => isset($resjson->id) ? $resjson->id : "",
                    'credits_charged' => '0',
                    'full_resp' => $res,
                    'created_at' => date('Y-m-d H:i:s')


                ];

                WhatsappTrack::insert($data1);
            } else {

                $data2 = [

                    'caseid' => $d['caseid'],
                    'que_id' => $d['id'],
                    'contact' => $c,
                    'content' => "",
                    'media' => $d['oldcontent']['media']['url'],
                    'casetype' => $d['type'],
                    'event' => $d['event'],
                    'request_uuid' => isset($resjson->id) ? $resjson->id : "",
                    'credits_charged' => '0',
                    'full_resp' => $res,
                    'created_at' => date('Y-m-d H:i:s')
                ];

                WhatsappTrack::insert($data2);
            }

            $res_decode = json_decode($res, true);
            if ($res_decode['result'] == true && isset($res_decode['id'])) {
                $que = WhatsAppQue::where(['is_sent' => 0, 'is_processing' => 1, 'id' => $d['id']])->update(['is_success' => 1]);
            }
            $que = WhatsAppQue::where(['is_sent' => 0, 'is_processing' => 1, 'id' => $d['id']])->update(['is_processing' => 0, 'is_sent' => 1]);

            return "success";
        } else {

            return "Fail";
        }
    }





    //Mtalkz code //
    public function sendMtalkz()
    {

       
        $limit = 200;

        $whapps = WhatsAppQue::where(['is_sent' => 0, 'is_processing' => 0, 'is_success' => null,'is_hold'=> null])->whereDate('created_at', '>', '2022-07-31')->orderBy('created_at', 'ASC')->limit($limit)->get();

        if (count($whapps) < 1) {
            exit();
        }
        

        $whappspr = [];

        foreach ($whapps as $key => $value) {

            $whappspr[] = $value->id;
        }



        $setprocess = WhatsAppQue::whereIn('id', $whappspr)->limit($limit)->update(['is_processing' => 1]);


        foreach ($whapps as $key => $value) {

            $content = json_decode($value->content, true);

            $oldcontent = '';


            $vararray = [];
            $vararrayheader = [];
            $convertarray = json_decode($value->variable, true);
            foreach ($convertarray as $var) {
                $vararray[] = $var;
            }

            if ($value->media == 1) {

                $oldcontent = $content;



                $contenturl = parse_url($content['media']['url'])["path"];
                $file_name = basename($content['media']['url']);

                $content['media']['url'] = $this->getPreSignedUrl(urldecode($contenturl), 360);
                //$content['media']['url'] = "https://www.antennahouse.com/XSLsample/pdf/sample-link_1.pdf";
                

                

                if (!file_get_contents($content['media']['url'])) {
                    continue;
                } else {
                    $path = 'public/tmp/' . $file_name;
                    Storage::disk('local')->put($path, file_get_contents($content['media']['url']));
                    $vararrayheader[] = url("storage/app/" . $path);
                    
                }
            }
            // exit;

            $d = [
                'id' => $value->id,
                'event' => $value->event,
                'tempname' => $value->haptik_tmp,
                'varbody' => $vararray,
                'varheader' => count($vararrayheader) > 0 ? $vararrayheader : "",
                'file_name' => isset($file_name) ? $file_name : "",
                'content' => $content,
                'oldcontent' => $oldcontent,
                'type' => $value->casetype,
                'caseid' => $value->caseid,
            ];

             


            self::NewWhatsappMtalkzMessage($d, str_replace('+91', '', $value->contact));
        }
    }
   


    public function NewWhatsappMtalkzMessage($d, $c)
    {
       
        $url = "https://rcmapi.instaalerts.zone/services/rcm/sendMessage";

         
        if ($d['varheader'] != "") {
            $finalMediabody = "";
            $array = $d['varbody'];
            $last_key = count($d['varbody']);

            foreach($d['varbody'] as $k => $body) {
                $last = $k+1;
                if ($last == $last_key) { // last element
                    $finalMediabody .= '"'.$k.'" : "'.$body.'"';
                } else {
                    $finalMediabody .= '"'.$k.'" : "'.$body.'",';  // not last element
                }
            
            }

           // echo $finalMediabody;exit;
            // $data = [
            //     "message" => [
            //         "channel" => "WABA",
            //         "content" => [
            //             "preview_url" => false,
            //             "type" => "MEDIA_TEMPLATE",
            //             "mediaTemplate" => [
            //                 "templateId" => $d['tempname'],
            //                 "media" => [
            //                     "type" => "document",
            //                     "url" => $d['varheader'][0],
            //                     "fileName" => $d['file_name']
            //                 ],
            //                 "bodyParameterValues" => $d['varbody']
            //             ],
            //             "shorten_url" => true
            //         ],
            //         "recipient" => [
            //             "to" => "91".$c,
            //             "recipient_type" => "individual"
            //         ],
            //         "sender" => [
            //             "from" => "918879651360"
            //         ],
            //         "preferences" => [
            //             "webHookDNId" => "1001"
            //         ]
            //     ],
            //     "metaData" => [
            //         "version" => "v1.0.9"
            //     ]
            // ];

           // "url" : "'.$d['varheader'][0].'",

           // "url" : "https://www.antennahouse.com/XSLsample/pdf/sample-link_1.pdf",

            $data = '{
                "message" : {
                    "channel" : "WABA",
                    "content" : {
                        "preview_url" : false,
                        "type" : "MEDIA_TEMPLATE",
                        "mediaTemplate" : {
                            "templateId" : "'.$d['tempname'].'",
                            "media" : {
                                "type" : "document",
                                "url" : "'.$d['varheader'][0].'",
                                "fileName" : "'.$d['file_name'].'"
                            },
                            "bodyParameterValues" : {
                                '.$finalMediabody.'
                            }
                        },
                        "shorten_url" : true
                    },
                    "recipient" : {
                        "to" : "91'.$c.'",
                        "recipient_type" : "individual"
                    },
                    "sender" : {
                        "from" : "'.env('MTALKZ_KEY').'"
                    },
                    "preferences" : {
                        "webHookDNId" : "<webHookDNId>"
                    }
                },
                "metaData" : {
                    "version" : "v1.0.9"
                }
            }';

          
        } else {

            $finalbody = "";
            $array = $d['varbody'];
            $last_key = count($d['varbody']);

            foreach($d['varbody'] as $k => $body) {
                $last = $k+1;
                if ($last == $last_key) { // last element
                    $finalbody .= '"'.$k.'" : "'.$body.'"';
                } else {
                    $finalbody .= '"'.$k.'" : "'.$body.'",';  // not last element
                }
            
            }

            // $data = [
            //     "message" => [
            //         "channel" => "WABA",
            //         "content" => [
            //             "preview_url" => false,
            //             "type" => "TEMPLATE",
            //             "template" => [
            //                 "templateId" => $d['tempname'],
            //                 "parameterValues" => $d['varbody'],
            //                 "headerTitle" => "{{1}}"
            //             ],
            //             "shorten_url" => true
            //         ],
            //         "recipient" => [
            //             "to" => "91".$c,
            //             "recipient_type" => "individual"
            //         ],
            //         "sender" => [
            //             "from" => "918879651360"
            //         ],
            //         "preferences" => [
            //             "webHookDNId" => "1001"
            //         ]
            //     ],
            //     "metaData" => [
            //         "version" => "v1.0.9"
            //     ]
            // ];
            $data = '{
                "message" : {
                    "channel" : "WABA",
                    "content" : {
                        "preview_url" : false,
                        "type" : "TEMPLATE",
                        "template" : {
                            "templateId" : "'.$d['tempname'].'",
                            "parameterValues" : {
                                '.$finalbody.'
                            }
                        },
                        "shorten_url" : true
                    },
                    "recipient" : {
                        "to" : "91'.$c.'",
                        "recipient_type" : "individual"
                    },
                    "sender" : {
                        "from" : "'.env('MTALKZ_KEY').'"
                    },
                    "preferences" : {
                        "webHookDNId" : "<webHookDNId>"
                    }
                },
                "metaData" : {
                    "version" : "v1.0.9"
                }
            }';
        }

    
       
        $type = "POST";
        $auth = env('MTALKZ_KEY');

        $findtrack = WhatsappTrack::where(['que_id' => $d['id']])->orderBy('created_at', 'DESC')->limit(1)->first();

        if (isset($findtrack)) {
            if ($findtrack->request_uuid != "") {
                return true;
            }
        }

       
       // $res = Curl::NewWhatsappMtalkzRequest($url, json_encode($data), $type, $auth);
        $res = Curl::NewWhatsappMtalkzRequest($url, $data, $type, $auth);

       // echo "<pre>";print_R($res);
        
        $resjson = json_decode($res);


        // echo "<pre>";print_R($resjson);
        // exit;
        
        if ($resjson) {

            if (array_key_exists('text', $d['content'])) {

                $data1 = [

                    'caseid' => $d['caseid'],
                    'que_id' => $d['id'],
                    'contact' => $c,
                    'content' => implode(" ", str_replace(['‘', '’'], ['::', ';;'], $d['content'])),
                    'casetype' => $d['type'],
                    'event' => $d['event'],
                    'request_uuid' => isset($resjson->mid) ? $resjson->mid : "",
                    'credits_charged' => '0',
                    'full_resp' => $res,
                    'created_at' => date('Y-m-d H:i:s')


                ];

                WhatsappTrack::insert($data1);
            } else {

                $data2 = [

                    'caseid' => $d['caseid'],
                    'que_id' => $d['id'],
                    'contact' => $c,
                    'content' => "",
                    'media' => $d['oldcontent']['media']['url'],
                    'casetype' => $d['type'],
                    'event' => $d['event'],
                    'request_uuid' => isset($resjson->mid) ? $resjson->mid : "",
                    'credits_charged' => '0',
                    'full_resp' => $res,
                    'created_at' => date('Y-m-d H:i:s')
                ];

                WhatsappTrack::insert($data2);
            }

            $res_decode = json_decode($res, true);
            //dd($res_decode);
            //if ($res_decode['result'] == true && isset($res_decode['id'])) {
            if ($res_decode['statusCode'] == 200 && isset($res_decode['mid'])) {
                $que = WhatsAppQue::where(['is_sent' => 0, 'is_processing' => 1, 'id' => $d['id']])->update(['is_success' => 1]);
            }
            $que = WhatsAppQue::where(['is_sent' => 0, 'is_processing' => 1, 'id' => $d['id']])->update(['is_processing' => 0, 'is_sent' => 1]);

            return "success";
        } else {

            return "Fail";
        }
    }

    //Mtalkz code //






    public function sendwhapp($d, $c)
    {



        $url = System::select('value')->where(['name' => 'WHATSAPP_URL'])->first()->value;

        //sandbox testing
        $auth = base64_encode(System::select('value')->where(['name' => 'WHATSAPP_KEY'])->first()->value);

        //for live number
        //$auth = base64_encode(WAUTH);

        $whsource = System::select('value')->where(['name' => 'WHATSAPP_SOURCE'])->first()->value;

        $wheventurl = System::select('value')->where(['name' => 'WHATSAPP_EVENT_URL'])->first()->value;


        $data = [
            "channel" => "whatsapp",
            "source" => $whsource,
            "destination" => [$c],
            "content" => $d['content'],
            "events_url" => $wheventurl,
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
                    'contact' => $c,
                    'content' => implode(" ", str_replace(['‘', '’'], ['::', ';;'], $d['content'])),
                    'casetype' => $d['type'],
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
                    'contact' => $c,
                    'content' => "",
                    'media' => $d['oldcontent']['media']['url'],
                    'casetype' => $d['type'],
                    'event' => $d['event'],
                    'request_uuid' => $res1['meta']['request_uuid'],
                    'credits_charged' => '0',
                    'full_resp' => $res,
                    'created_at' => date('Y-m-d H:i:s')


                ];

                WhatsappTrack::insert($data2);
            }

            $que = WhatsAppQue::where(['is_sent' => 0, 'is_processing' => 1, 'id' => $d['id']])->update(['is_processing' => 0, 'is_sent' => 1]);;



            return true;
        } else {

            return false;
        }
    }
}
