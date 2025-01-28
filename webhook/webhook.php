<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

error_reporting(E_ALL);
$projectpath = $_SERVER['DOCUMENT_ROOT'];
//include_once $projectpath . '/mediation/config/constants.php';
include_once $projectpath . '/config/constants.php';
//include_once $projectpath . '/mediation/app/Http/helpers/Curl.php';
include_once $projectpath . '/app/Http/helpers/Curl.php';
//echo $_SERVER['DOCUMENT_ROOT'];
$json = file_get_contents('php://input');

 $myFile = "whatsapp_log_latest/log".date('Y-m-d_H:i:s').'_'.rand(9,9999999).".txt";


 try{
        // file_put_contents($myFile,$json);
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
      $curl = new Curl();

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
                  
                      $curl = new Curl();
                      $res = $curl->request($url, $data, $type, $token);
                      
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

