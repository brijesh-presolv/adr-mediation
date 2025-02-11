<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

error_reporting(E_ALL);
$projectpath = $_SERVER['DOCUMENT_ROOT'];
//include_once $projectpath . '/mediation/config/constants.php';
include_once $projectpath . '/config/constants.php';
//include_once $projectpath . '/mediation/app/Http/Helpers/Curl.php';
include_once $projectpath . '/app/Http/Helpers/Curl.php';

//echo $_SERVER['DOCUMENT_ROOT'];
//echo $projectpath . '/app/Http/Helpers/Curl.php';
//$json = file_get_contents('php://input');
//$json = '{"version":"1.0","timestamp":"2025-02-06T12:04:09.096009","type":"message_api_sent","data":{"customer":{"id":"3fa92f7d-c41a-49fc-b0af-b740a4075c00","channel_phone_number":"917567043843","phone_number":"7567043843","country_code":"+91","traits":{"name":"","whatsapp_opted_in":true,"source_id":null,"source_url":null}},"message":{"id":"0e09a0a3-c647-4ebe-a84b-d652a3e6d831","chat_message_type":"PublicApiMessage","channel_failure_reason":null,"message_status":"Sent","received_at_utc":"2025-02-06T12:04:08.988554","delivered_at_utc":null,"seen_at_utc":null,"campaign_id":null,"is_template_message":true,"raw_template":"{\"id\": \"e33f311b-c0e9-499e-9611-234453e66930\", \"created_at_utc\": \"2025-01-20T10:46:00.101\", \"modified_at_utc\": \"2025-01-20T10:46:27.586\", \"created_by_user_id\": \"41278f73-503f-4a89-87c9-6fd0cf909d5a\", \"is_deleted\": false, \"name\": \"l19_additional_doc\", \"language\": \"en\", \"category\": \"UTILITY\", \"sub_category\": null, \"template_category_label\": null, \"header_format\": null, \"header\": null, \"header_handle\": null, \"header_handle_file_url\": null, \"header_handle_file_name\": null, \"header_text\": null, \"body\": \"Dear Party,\\n\\nAn Additional Document has been uploaded in the mediation proceedings having Case ID {{1}}. The same has been transmitted on the registered email ID and can also be viewed by logging into your Presolv360 account.\\n\\nThis is a system generated message. Kindly do not respond here.\\n\\nBest regards,\\nPresolv360 Administrator\", \"body_text\": \"[\\n    \\\"M000000\\\"\\n]\", \"footer\": null, \"buttons\": \"{}\", \"button_text\": null, \"allow_category_change\": true, \"limited_time_offer\": null, \"carousel_cards\": \"[]\", \"message_send_ttl_seconds\": null, \"wa_template_ad_id\": null, \"wa_template_ad_account_id\": null, \"autosubmitted_for\": null, \"display_name\": \"l19_additional_doc\", \"organization_id\": \"f4107514-7ab7-47ae-93dd-b89f2e95a14f\", \"approval_status\": \"APPROVED\", \"wa_template_id\": \"2887779794727695\", \"is_archived\": false, \"channel_type\": \"Whatsapp\", \"is_click_tracking_enabled\": false, \"is_conversion_tracking_enabled\": false, \"allow_delete\": true, \"rejection_reason\": null, \"is_mpm\": false, \"is_carousel\": false, \"add_security_recommendation\": false, \"code_expiration_minutes\": null, \"is_ai_recommended\": true, \"order_details\": null, \"is_whatsapp_pay_template\": false}","channel_error_code":null,"message_content_type":"Template","media_url":null,"message":"[{\"type\": \"body\", \"parameters\": [{\"type\": \"text\", \"text\": \"M201344\"}]}]","meta_data":{"source":"PublicInterakt","source_data":{"callback_data":null},"msg_source":"PublicInterakt"}}}}';



 //$myFile = "whatsapp_log_latest/log_new.txt";
 $json = file_get_contents('php://input');

 $myFile = "whatsapp_log_latest/log".date('Y-m-d_H:i:s').'_'.rand(9,9999999).".txt";


 try{

         //file_put_contents($myFile,$json);
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
     // $curl = new Curl();
     
     $fh = file_get_contents($myFile);
     $json=$fh;
     $data = json_decode($fh, true);
      
      if($data){

         if ($data['type'] != "") {

            try{
                  $message_id=$data['data']['message']['id'];
                  $type= $data['type'];
                  $phone_number= $data['data']['customer']['phone_number'];
                  $country_code=$data['data']['customer']['country_code'];
                  $chat_message_type= $data['data']['message']['chat_message_type'];
                  $replymsg= $data['data']['message']['message'];
                  $msgtimestamp= $data['timestamp'];
                  $received_at_utc= $data['data']['message']['received_at_utc'];
                  $message_status= $data['data']['message']['message_status'];
                  $message_content_type=$data['data']['message']['message_content_type'];
                  $med_whatsapp_json=$myFile;

                  if($data['type']=="message_received"){

                      $reply_from=$data['data']['message']['message_context']['from'];
                      $reply_message_id=$data['data']['message']['message_context']['id'];

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
   
                
   
                      $url = $_SERVER['REQUEST_URI']."api/medwhatsappbotlog";

                     
   
                      $auth = base64_encode("1b634896-7d26-4f4d-aa15-8f9313fc9849:4e211b4d-a4d0-477f-98b9-a82ea6fafe89");
   
                      $data = json_encode($formdata);
                      $type = "POST";
                  
                      //$curl = new Curl();
                      $res = Curl::request($url, $data, $type, $auth);
                      
                      $res1 = json_decode($res, true);
   
                     if ($res) {
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

