<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

error_reporting(E_ALL);
$projectpath = $_SERVER['DOCUMENT_ROOT'];
include_once $projectpath . '/config/constants.php';
include_once $projectpath . '/app/Http/Helpers/Curl.php';

echo $projectpath;exit;
 //$myFile = "whatsapp_log_latest/log_new.txt";
 $json = file_get_contents('php://input');

 //$json = '{"channel":"WABA","appDetails":{"type":"LIVE","id":""},"recipient":{"to":"917567043843","recipient_type":"individual"},"batchId":"","campaignId":"","templateId":"new_latest","templateCategory":"UTILITY","sender":{"from":"918879651360"},"events":{"eventType":"DELIVERY EVENTS","timestamp":"1743144770000","date":"2025-March-28","mid":"410135450328122240866789"},"notificationAttributes":{"status":"delivered","reason":"Delivered to User","code":"101"},"convDetails":{"conversationType":"utility","isBillable":"true","waConvId":"46868470b4d1e2108065294233df534b","kxBillable":"N","uim":"","bim":""}}';

 $myFile = "whatsapp_log_latest_m/log".date('Y-m-d_H:i:s').'_'.rand(9,9999999).".txt";

 //$myFile = "whatsapp_log_latest/log2025-03-28_121.txt";

 try{

         if (file_put_contents($myFile,$json)){ 
                  whatsappbotlogAsync($myFile, function () {
                  
                  echo "whatsappbotlog completed asynchronously.";
              });

         }

     } catch(Exception $e){
      

        echo $e->getMessage();
     }
     echo 'ok';

    // exit();

     function whatsappbotlogAsync($myFile, $callback) {
            // Simulate asynchronous whatsappbotlog execution
            whatsapbotlog($myFile);

            $callback();
      }

   function whatsapbotlog($myFile){

     
      $token = '';
      //$curl = new Curl();
     
     $fh = file_get_contents($myFile);
     $json=$fh;
     $data = json_decode($fh, true);
      
     echo "<pre>";print_R($data);exit;
      if($data){

         if ($data['notificationAttributes']['status'] != "") {

            try{
                  // $message_id=$data['data']['message']['id'];
                  // $type= $data['type'];
                  // $phone_number= $data['data']['customer']['phone_number'];
                  // $country_code=$data['data']['customer']['country_code'];
                  // $chat_message_type= $data['data']['message']['chat_message_type'];
                  // $replymsg= $data['data']['message']['message'];
                  // $msgtimestamp= $data['timestamp'];
                  // $received_at_utc= $data['data']['message']['received_at_utc'];
                  // $message_status= $data['data']['message']['message_status'];
                  // $message_content_type=$data['data']['message']['message_content_type'];
                  // $med_whatsapp_json=$myFile;

                  $final_date = date("Y-m-d", $data['events']['date']);

                  $ms = $data['events']['timestamp'];
                  $s = floor($ms/1000);
                  date_default_timezone_set('Asia/Kolkata');
                  //$timestamp = date("Y-m-d H:i:s");
                  $temp = date('Y-m-d H:i:s', $s);
                  $final_time = date("Y-m-d\TH:i:s.000", strtotime($temp));
                  
                 // echo date('m/d/Y H:i:s', 1128297600);
                  //echo date('Y-m-d H:i:s', strtotime($data['events']['timestamp']));

                 // echo $datetime;
                  //exit;

                  $message_id=$data['events']['mid'];
                  $type= $data['notificationAttributes']['status'];
                  $phone_number= substr($data['recipient']['to'], 2, -1);
                  $country_code=substr($data['recipient']['to'], 0, 2);
                  $chat_message_type= $data['convDetails']['conversationType'];
                  //$replymsg= $data['data']['message']['message'];
                  $replymsg= "";
                  $msgtimestamp= $final_time;
                  //$received_at_utc= date('Y-m-d', $data['events']['date']);
                  $received_at_utc= $final_time;
                  $message_status= $data['notificationAttributes']['status'];
                  $message_content_type=$data['templateCategory'];
                  $med_whatsapp_json=$myFile;

                  if($data['notificationAttributes']['status']=="delivered"){

                      $reply_from=substr($data['sender']['from'], 2, -1);
                      $reply_message_id=$data['events']['mid'];

                      $formdata = [];
                      $formdata['message_id'] = $message_id;
                      $formdata['type'] = $type;
                      $formdata['phone_number'] = $phone_number;
                      $formdata['country_code'] = $country_code;
                      $formdata['chat_message_type'] = $chat_message_type;
                      $formdata['message_content_type'] = $message_content_type;
                      $formdata['replymsg'] = $replymsg;
                      $formdata['msgtimestamp'] = $msgtimestamp;
                      $formdata['received_at_utc'] = $received_at_utc;
                      $formdata['message_status'] = $message_status;
                      $formdata['reply_from'] = $reply_from;
                      $formdata['reply_message_id'] = $reply_message_id;
                      $formdata['med_whatsapp_json'] = $med_whatsapp_json;
   
                
   
                      //$url = "https://mediation.presolv360.com/api/medwhatsappbotlog";
                      $url = "https://mediation.presolv360.com/api/medwhatsappbotlogmtalkz";

                     
                     
                      $auth = base64_encode("1b634896-7d26-4f4d-aa15-8f9313fc9849:4e211b4d-a4d0-477f-98b9-a82ea6fafe89");
   
                      $data = json_encode($formdata);
                      $type = "POST";
                  



                      
                     $ch = curl_init();
                     curl_setopt($ch, CURLOPT_URL, $url);
                     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
                     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
                     curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                     curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                     curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                     'Content-Type: application/json',
                     'Authorization: Basic ' . $auth,
                     ));
                     curl_setopt($ch, CURLOPT_TIMEOUT, 500);
                     $response = curl_exec($ch);
                     //echo '<pre>';print_r($response);die;
                     curl_close($ch);
                    // return $response;








                     //  $curl = new Curl();
                     //  //$res = Curl::request($url, $data, $type, $auth);
                     //  $res = $curl->request($url, $data, $type, $auth);
                      
                     //  $res1 = json_decode($res, true);
   
                     if ($response) {
                        echo "success";
                     }else{
                        echo "fail";
                     }

                  }

            } catch(Exception $e){

               echo $e->getMessage();
            }    
         }
      }else{
         echo "data not found"; 
      }
   }

