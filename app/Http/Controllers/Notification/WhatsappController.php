<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Helpers\Curl;
use App\Models\WhatsappTrack;
use App\Http\Traits\UploadTrait;
use App\Models\System;
use App\Models\WhatsAppQue;

class WhatsappController extends Controller
{ 

    use UploadTrait;



      public function __construct()
    {


        
    }


    public function send(){

        $limit=500;

        $whapps=WhatsAppQue::where(['is_sent'=>0,'is_processing'=>0])->orderBy('created_at','DESC')->limit($limit)->get();

        if(count($whapps)<1){
            exit();
        }


        $whappspr=[];

        foreach ($whapps as $key => $value) {
            
            $whappspr[]=$value->id;

        }



     $setprocess=WhatsAppQue::whereIn('id', $whappspr)->limit($limit)->update(['is_processing' => 1]);


      foreach ($whapps as $key => $value) {

        $content=json_decode($value->content,true);

        $oldcontent='';


            if($value->media==1){

                $oldcontent=$content;

                  

                  $contenturl=parse_url($content['media']['url'])["path"];

                  $content['media']['url']=$this->getPreSignedUrl(urldecode($contenturl),1);

                  if(!file_get_contents($content['media']['url'])){
                    continue;
                  }


            }


            
            
            $d = [
                        'id'=>$value->id,
                    'event' => $value->event,
                    'caseid' => $value->caseid,
                    'type' => $value->casetype,
                    'content'=>$content,
                    'oldcontent'=>$oldcontent,
                ];

                

                $r=self::sendwhapp($d,$value->contact);

      }
    }


    public function sendwhapp($d,$c){



        $url = System::select('value')->where(['name'=>'WHATSAPP_URL'])->first()->value;

        //sandbox testing
        $auth=base64_encode(System::select('value')->where(['name'=>'WHATSAPP_KEY'])->first()->value);

        //for live number
        //$auth = base64_encode(WAUTH);

        $whsource=System::select('value')->where(['name'=>'WHATSAPP_SOURCE'])->first()->value;

        $wheventurl=System::select('value')->where(['name'=>'WHATSAPP_EVENT_URL'])->first()->value;


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

            $que=WhatsAppQue::where(['is_sent'=>0,'is_processing'=>1,'id'=>$d['id']])->update(['is_processing' => 0,'is_sent'=>1]);;

        

            return true;
        } else {
            
            return false;
        }



    }
}