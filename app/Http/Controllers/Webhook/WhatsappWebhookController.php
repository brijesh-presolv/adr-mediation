<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Curl;
use App\Models\System;
use App\Models\WhatsappLog;
use App\Models\WhatsappLogExt;

use App\Models\WhatsappWebhook;
use App\Models\WhatsappTrack;
use App\Models\MedWhatsappTrack;
use App\Models\MedWhatsappCTrack;

use App\Models\MedWhatsappLog;
use App\Models\MedWhatsappCLog;


use App\Models\MedWhatsappSLog;
use App\Models\MedWhatsappSTrack;

use App\Models\MedWhatsappSebiTrack;
use App\Models\MedWhatsappSebiLog;

use App\Models\ArbWhatsappSLog;
use App\Models\ArbWhatsappSTrack;


use App\Models\ArbWhatsappSebiLog;
use App\Models\ArbWhatsappSebiTrack;


use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class WhatsappWebhookController extends Controller
{
    public function deletelogfile($fullpath){

        if (file_exists($fullpath)) {

                if(unlink($fullpath)){

                    echo 'this';
                } else {

                    echo 'not working';
                }
            }

    }

    public function movelogfile($path,$filename,$fullpath){

        if (file_exists($fullpath)) {

            if(rename($fullpath,$path.'/unmatched/'.$filename)){

                echo 'moved';
            } else {

                echo 'not working';
            }
        }

    }
            
            
    public function whresmovetos3(){
    
        $path='../webhook/whatsapp_log_latest';
    
        $logs=WhatsappLog::whereNotNull('response')->where('response', '<>', '')->limit(1000)->get();
    
        foreach ($logs as $log){

            if(!empty($log->response)){
    
                $s= Storage::disk('s3')->put('public/new_wh_stslog/'.$log->id. '.txt', $log->response);
            
                
                if($s){
                
                $log->response=null;
                
                $log->save();
                
                if($log){
                echo 'success';
                }
                }
            }
       
    
        }
    
    }

    public function WhatsappStatus()
    {

        // $myFile = "wapp_status/testFile".date('Y-m-d_H:i:s').".txt";
        $path='../webhook/whatsapp_log_latest';

        echo $path;exit;

        $files = scandir($path);

        $filescount=count($files);

        $maxfiles=1000;

        if($filescount<$maxfiles){

            $maxfiles=$filescount;
        }


for ($i=0; $i < 1000; $i++) {  

    if(!isset($files[$i])){

        continue;
    }

  $fullpath=$path.'/'.$files[$i];

  $fh = file_get_contents($fullpath);



  $json=$fh;

  $data = json_decode($fh, true);




        if ($data) {


            echo $files[$i];


            if ($data['type'] == 'message_received') {
                
                // echo "203";
                // dd($data);

                // $message='';

                $sender = $data['data']['customer']['channel_phone_number'];
                $profile =  $data['data']['customer']['traits']['name'];
                // if ($data['data']['message']['message_content_type'] == 'Text') {
                //     $message = $data['data']['message']['message'];
                // } elseif ($data['data']['message']['message_content_type'] == 'Media') {
                //     $message = "";
                // }

                $media=$data['data']['message']['media_url'];
                $message = $data['data']['message']['message'];
                $message_content_type = $data['data']['message']['message_content_type'];


                $total_cost = 0;
                if($message_content_type !="Unknown" or $message_content_type !=""){
                    $log = WhatsappWebhook::create([
                        "sender" => "+" . $sender,
                        "sender_profile" => $profile,
                        "message_sent" => $message,
                        "total_cost" => $total_cost,
                        "response" => $json,
                        "media"=>$media,
                    ]);
                    
                    Storage::put('public/new_wh_inlog/'. $log->id.'.txt', $json);

                    
                }

                if ($log) {
                
                         $this->deletelogfile($fullpath);
                    
                    echo '200';
                } else {
                
                    Storage::put('public/new_wh_inlog/testFile_incoming_' . date('Y-m-d_H:i:s') . '.txt', $json);
                }
                
                
                
            } else {

                if(!isset($data['data']['message']['id'])){

                    continue;
                }



                $request_id = $data['data']['message']['id'];
                $created_time = $data['timestamp'];
                $sent_time = $data['data']['message']['received_at_utc'];
                $delivered_time = $data['data']['message']['delivered_at_utc'];
                $updated_time = $data['data']['message']['seen_at_utc'];
                $status = $data['data']['message']['message_status'];
                $json = addcslashes($json, "'");

                $stage=0;


                //mediation

                if($stage==0){

                    $track = WhatsappTrack::where('request_uuid', $data['data']['message']['id'])->get();

                    if (count($track) >0 ) {

                             $stage='med';
                    } 

                }

                //arbitration

                if($stage==0){


                $track = WhatsappTrack::where('request_uuid', $data['data']['message']['id'])->get();


                if (count($track) >0) {

                    $stage='arb';
                } 

            }



                // sahmati arb


                if($stage==0){

                    $track = ArbWhatsappSTrack::where('request_uuid', $data['data']['message']['id'])->get();

                    if (count($track) > 0) {

                             $stage='sarb';
                    } 

                }

                // sebi arb


                if($stage==0){

                    $track = ArbWhatsappSebiTrack::where('request_uuid', $data['data']['message']['id'])->get();

                    if (count($track) > 0) {

                             $stage='sebiarb';
                    } 

                }

                //sahmati mediation

                if($stage==0){

                    $track = MedWhatsappSTrack::where('request_uuid', $data['data']['message']['id'])->get();

                    if (count($track) >0) {

                             $stage='smed';
                    } 

                }

                

                //sebi

                if($stage==0){

                    $track = MedWhatsappSebiTrack::where('request_uuid', $data['data']['message']['id'])->get();

                    if (count($track) > 0) {

                             $stage='sebi';
                    } 

                }

                //court

                if($stage==0){

                    $track = MedWhatsappCTrack::where('request_uuid', $data['data']['message']['id'])->get();

                    if (count($track) > 0) {

                             $stage='court';
                    } 

                }



                switch ($stage) {
                    case 'arb':



                    $log = WhatsappLog::create([
                        'request_id' => $request_id,
                        'created_time' => $created_time,
                        'sent_time' => $sent_time,
                        'delivered_time' => $delivered_time,
                        'updated_time' => ($updated_time == null) ? $delivered_time : $updated_time,
                        'status' => $status,
                        'response' => '',
                        'created_at' => date('Y-m-d H:s:i')
                    ]);

                    if($log){
                    
                      Storage::disk('s3')->put('public/new_wh_stslog/' . $log->id . '.txt', $json);
                    

                      //WhatsappLogExt::create([
                        //'log_id'=>$log->id,
                        //'response'=>$json,
                      //]);  
                    }
                    if ($log) {
                         $this->deletelogfile($fullpath);

                        echo '202';
                    } else {
                    
                    Storage::disk('s3')->put('public/new_wh_stslog/arbitration_sent_' . $request_id . "_" . date('Y-m-d_H:i:s') . '.txt', $json);
                    }

                        
                    break;
                    case 'med':

                    Storage::disk('s3_mediation')->put('public/whatsapp_status/mediation_sent_' . $request_id . "_" . date('Y-m-d_H:i:s') . '.txt', $json);





                    // dd($track);
                    $log = MedWhatsappLog::create([
                        'request_id' => $request_id,
                        'created_time' => $created_time,
                        'sent_time' => $sent_time,
                        'delivered_time' => $delivered_time,
                        'updated_time' => ($updated_time == null) ? $delivered_time : $updated_time,
                        'status' => $status,
                        'response' => $json,
                        'created_at' => date('Y-m-d H:s:i')
                    ]);



                    if ($log) {
  $this->deletelogfile($fullpath);

                        echo '202';
                    }

                        
                    break;
                    case 'sarb':
                    
                    Storage::disk('s3_mediation')->put('public/whatsapp_status/mediation_sent_sarb' . $request_id . "_" . date('Y-m-d_H:i:s') . '.txt', $json);





                    // dd($track);
                    $log = ArbWhatsappSLog::create([
                        'request_id' => $request_id,
                        'created_time' => $created_time,
                        'sent_time' => $sent_time,
                        'delivered_time' => $delivered_time,
                        'updated_time' => ($updated_time == null) ? $delivered_time : $updated_time,
                        'status' => $status,
                        'response' => $json,
                        'created_at' => date('Y-m-d H:s:i')
                    ]);
                    if ($log) {
  $this->deletelogfile($fullpath);

                        echo '202';
                    }

                        
                    break;
                    case 'sebiarb':
                    
                    Storage::disk('s3_mediation')->put('public/whatsapp_status/mediation_sent_sebiarb' . $request_id . "_" . date('Y-m-d_H:i:s') . '.txt', $json);





                    // dd($track);
                    $log = ArbWhatsappSebiLog::create([
                        'request_id' => $request_id,
                        'created_time' => $created_time,
                        'sent_time' => $sent_time,
                        'delivered_time' => $delivered_time,
                        'updated_time' => ($updated_time == null) ? $delivered_time : $updated_time,
                        'status' => $status,
                        'response' => $json,
                        'created_at' => date('Y-m-d H:s:i')
                    ]);
                    if ($log) {
  $this->deletelogfile($fullpath);

                        echo '202';
                    }

                        
                    break;
                    case 'smed':

                    Storage::disk('s3_mediation')->put('public/whatsapp_status/mediation_sent_smed' . $request_id . "_" . date('Y-m-d_H:i:s') . '.txt', $json);





                    // dd($track);
                    $log = MedWhatsappSLog::create([
                        'request_id' => $request_id,
                        'created_time' => $created_time,
                        'sent_time' => $sent_time,
                        'delivered_time' => $delivered_time,
                        'updated_time' => ($updated_time == null) ? $delivered_time : $updated_time,
                        'status' => $status,
                        'response' => $json,
                        'created_at' => date('Y-m-d H:s:i')
                    ]);
                    if ($log) {
  $this->deletelogfile($fullpath);

                        echo '202';
                    }
                        
                    break;

                    case 'sebi';


                    Storage::disk('s3_mediation')->put('public/whatsapp_status_sebi/mediation_sent_' . $request_id . "_" . date('Y-m-d_H:i:s') . '.txt', $json);



                        // dd($track);
                    $log = MedWhatsappSebiLog::create([
                        'request_id' => $request_id,
                        'created_time' => $created_time,
                        'sent_time' => $sent_time,
                        'delivered_time' => $delivered_time,
                        'updated_time' => ($updated_time == null) ? $delivered_time : $updated_time,
                        'status' => $status,
                        'response' => $json,
                        'created_at' => date('Y-m-d H:s:i')
                    ]);
                    if ($log) {
  $this->deletelogfile($fullpath);

                        echo '202';
                    }

                    break;

                    case 'court';


                    Storage::disk('s3_mediation')->put('public/whatsapp_status_c/mediation_sent_' . $request_id . "_" . date('Y-m-d_H:i:s') . '.txt', $json);



                        // dd($track);
                    $log = MedWhatsappCLog::create([
                        'request_id' => $request_id,
                        'created_time' => $created_time,
                        'sent_time' => $sent_time,
                        'delivered_time' => $delivered_time,
                        'updated_time' => ($updated_time == null) ? $delivered_time : $updated_time,
                        'status' => $status,
                        'response' => $json,
                        'created_at' => date('Y-m-d H:s:i')
                    ]);
                    if ($log) {
  $this->deletelogfile($fullpath);

                        echo '202';
                    }

                    break;
                    
                    default:

                    $this->movelogfile($path,$files[$i],$fullpath);

                        
                    break;
                }



                
            }

            




        } else {
            echo "data not found";
        }


    }
        exit;
    }

    public function WhatsappLog()
    {
        $json = file_get_contents('php://input');

        // $json = [
        //     "id"=>"12",
        //     "name"=>"xyz",
        //     "date"=>date(now()),
        // ];

        // $json = json_encode($json);

        try {
            // file_put_contents($myFile, $json);
            $success = Storage::put('public/whatsapp_log/testFile' . date('Y-m-d_H:i:s') . '.txt', $json);
        } catch (Exception $e) {

            echo $e->getMessage();
        }
        $data = json_decode($json);

        exit;
        // dd($data);

        if ($data) {
            //    $json=$db_var->real_escape_string($json);

            // $json = mysqli_real_escape_string($json);

            $sender = $data['data']['source'];
            $profile =  $data['data']['channel_details']['whatsapp']['source_profile']['name'];
            if ($data['data']['content_type'] == 'text') {
                $message = $data['data']['content']['text'];
            } elseif ($data['data']['content_type'] == 'media') {
                $message = $data['data']['content']['media']['url'];
            }
            $total_cost = $data['data']['total_cost'];

            $msg = [
                'contact' => '+917710048834',
                'content' => ['text' => 'You have a new WhatsApp message from ' . $profile . ' - ' . $sender . '. Please login to view the message.'],
            ];


            $message_content_type=$data['data']['content_type'];
            if($message_content_type !="Unknown" or $message_content_type !=""){

                $access = $this->sendWamessage($msg);
                
                $log = WhatsappWebhook::create([
                    "sender" => $sender,
                    "sender_profile" => $profile,
                    "message_sent" => $message,
                    "total_cost" => $total_cost,
                    "response" => $json
                ]);
            }
            if ($log) {
                echo '202';
            }
        }
        exit;
    }

    public function sendWamessage($d)
    {
        // dd("hello", $d);
        $url = System::select('value')->where(['name' => 'WHATSAPP_URL'])->first()->value;
        $auth = base64_encode(System::select('value')->where(['name' => 'WHATSAPP_KEY'])->first()->value);
        $whsource = System::select('value')->where(['name' => 'WHATSAPP_SOURCE'])->first()->value;
        $wheventurl = System::select('value')->where(['name' => 'WHATSAPP_EVENT_URL'])->first()->value;

        // dd($url, $auth, $whsource, $wheventurl);

        $data = [
            "channel" => "whatsapp",
            "source" => $whsource,
            "destination" => [$d['contact']],
            "content" => $d['content'],
            "events_url" => $wheventurl,
        ];

        $data = json_encode($data);
        $type = "POST";
        $res = Curl::request($url, $data, $type, $auth);

        return true;
    }
}
