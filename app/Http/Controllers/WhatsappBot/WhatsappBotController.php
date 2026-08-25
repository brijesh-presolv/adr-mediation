<?php

namespace App\Http\Controllers\WhatsappBot;


use App\Http\Helpers\SendGrid;
use App\Http\Helpers\Whatsapp;

use App\Models\WhatsappLog;
use App\Models\WhatsappChatbot;
use App\Models\WhatsappTrack;
use App\Models\UserStopWhatsapp;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


// use App\Http\Traits\UploadTrait;
class WhatsappBotController extends Controller
{
    use UploadTrait;

    public function medwhatsappbotlog(Request $request){ 

        echo "in this function==>";
      
        $method = $_SERVER['REQUEST_METHOD'];

        $reqdata=array('req_type'=>$method,"req_data"=> json_encode($request->all()), "created_at"=>date('Y-m-d_H:i:s'));
        $insertedId = DB::table('whatsappbot_req_log')->insertGetId($reqdata);

        if($method == "POST") { 
    
            try {

                $message_id=$request->message_id;
                $med_whatsapp_json=$request->med_whatsapp_json;
                $path=$_SERVER['DOCUMENT_ROOT']."whatsapp_log_latest";
                echo $path;exit;
                $med_whatsapp_log_path="public/medwhatsapp_bot/mediation_wha_".$message_id."_". date('Y-m-d_H:i:s').".txt";
                $json_full_path=$path.'/'.$med_whatsapp_json;

                $updated = DB::table('whatsappbot_req_log')
                            ->where('id', $insertedId)
                            ->update([
                                'platform' => 'Mediation',
                                'whatsapp_log_path' => $json_full_path
                            ]);

                if($request->replymsg =="STOP"){

                    $stop_whatsapp = array();
                    $stop_whatsapp['message_id'] = $request->message_id;
                    $stop_whatsapp['phone_number'] = $request->phone_number;
                    $stop_whatsapp['reply_message'] = $request->replymsg;
                    $stop_whatsapp['msgtimestamp'] = $request->msgtimestamp;
                    $stop_whatsapp['received_at_utc'] = $request->received_at_utc;
        
                    //$stop_whatsapp1=User_stop_whatsapp::insert($stop_whatsapp);
                    //$stop_whatsapp1=User_stop_whatsapp::insert($stop_whatsapp);
                    $stop_Medwhatsapp=UserStopWhatsapp::insert($stop_whatsapp);

                    $response['success']=true;
                    $response['message']="Stop whatsapp Message";
                    return response()->json($response, 200);

                }

               
                if($request->type=="message_received"){

                    $request_message_id=$request->reply_message_id;

                }else{

                    $request_message_id=$request->message_id;
                }

                $medtrack = WhatsappTrack::where('request_uuid', $request_message_id)->get();

                if (count($medtrack) > 0 && $request->type=="message_received")
                {

                    $phone_number= $request->phone_number;
                    $type= $request->type;
                    $country_code=$request->country_code;
                    $chat_message_type=$request->chat_message_type;
                    $replymsg= $request->replymsg;
                    $msgtimestamp=$request->msgtimestamp;
                    $received_at_utc=$request->received_at_utc;
                    $message_status=$request->message_status;
                    $message_content_type=$request->message_content_type;
                    $reply_from=$request->reply_from;
                    $med_whatsapp_json=$request->med_whatsapp_json;
                    $reply_message_id=$request->reply_message_id;

                    $fh = file_get_contents($json_full_path);
                    $json=$fh;
                     //Storage::disk('s3_mediation')->put($med_whatsapp_log_path, $json);

                     $medtrackdata = WhatsappTrack::where('request_uuid', $request_message_id)->first();

                     if($medtrackdata->event=="WA_Session_Consent"){

                        $bot_type="2";

                        $botlog = WhatsappChatbot::create([
                            'bot_type' => $bot_type,
                            'phone_number' => $phone_number,
                            'message_id' => $message_id,
                            'type' => $type,
                            'chat_message_type' => $chat_message_type,
                            'message_status' => $message_status,
                            'received_at_utc' => $received_at_utc,
                            'message_content_type' => $message_content_type,
                            'message' => $replymsg,
                            'reply_from' => $reply_from,
                            'reply_message_id' => $reply_message_id,
                            'reponse_file_path' => $med_whatsapp_log_path,
                            'timestamp' => $msgtimestamp,
                            'is_send'=> '0',
                            'created_at' => date('Y-m-d H:s:i')
                        ]);
    
                            $botresponse = $this->medwhatsappConsentReply();
                            $response['success']=true;
                            $response['message']="Whatsapp chat log created successfully";
                            return response()->json($response, 200);

                     }else{

                        $bot_type="1";

                        $botlog = WhatsappChatbot::create([
                            'bot_type' => $bot_type,
                            'phone_number' => $phone_number,
                            'message_id' => $message_id,
                            'type' => $type,
                            'chat_message_type' => $chat_message_type,
                            'message_status' => $message_status,
                            'received_at_utc' => $received_at_utc,
                            'message_content_type' => $message_content_type,
                            'message' => $replymsg,
                            'reply_from' => $reply_from,
                            'reply_message_id' => $reply_message_id,
                            'reponse_file_path' => $med_whatsapp_log_path,
                            'timestamp' => $msgtimestamp,
                            'is_send'=> '0',
                            'created_at' => date('Y-m-d H:s:i')
                        ]);

                            $botresponse = $this->medwhatsappbotReply();
                            
                            $response['success']=true;
                            $response['message']="Whatsapp chat log created successfully";
                            return response()->json($response, 200);

                     }
                                

                }else{

                    $response['success']=false;
                    $response['message']="Whatsapp track not found";
                    return response()->json($response, 200);
                }

            }
            catch (Exception $e) 
            { 
            $response['success']=false;
            $response['message']=$e->getMessage();
           // $response['message']="Something went wromg";
            return response()->json($response, 200);
            }
        }else{
  
          $response['success']=false;
          $response['message']="Method Not Found";
           return response()->json($response, 200);
    
        }
      
    }

    public function medwhatsappbotReply(){ 
      
        try {

            $url = config('services.whatsapp_bot.reply_url');
            $auth = config('services.whatsapp_bot.auth');

            $data = [
                "auth" => config('services.whatsapp_bot.auth'),
            ];
    
            $data = json_encode($data);
            $type = "POST";
            $res = Curl::request($url, $data, $type, $auth);

            return true;

        }
        catch (Exception $e) 
        { 
            $response['success']=false;
            $response['message']=$e->getMessage();
         // $response['message']="Something went wromg";
            return response()->json($response, 200);
        }
    }

    public function medwhatsappConsentReply(){ 
      
        try {

            $url = config('services.whatsapp_bot.consent_reply_url');
            $auth = config('services.whatsapp_bot.auth');
            $data = [
                "auth" => config('services.whatsapp_bot.auth'),
            ];
            $data = json_encode($data);
            $type = "POST";
            $res = Curl::request($url, $data, $type, $auth);

            return true;

        }
        catch (Exception $e) 
        { 
            $response['success']=false;
            $response['message']=$e->getMessage();
         // $response['message']="Something went wromg";
            return response()->json($response, 200);
        }
    }

}
