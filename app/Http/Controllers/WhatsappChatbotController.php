<?php

namespace App\Http\Controllers;

use App\Http\Helpers\SendGrid;
use App\Http\Helpers\Whatsapp;
use App\Models\InvoledUser;
use App\Models\MedCase;
use App\Models\Reminder;
use App\Models\WaTemplate;
use App\Models\WhatsappLog;
use App\Models\WhatsappChatbot;
use App\Models\WhatsappTrack;
use App\Models\UserStopWhatsapp;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\API\PaymentController;


class WhatsappChatbotController extends Controller
{   

    public function whatsappbotReply(){

          
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == "POST") {

            try {
             
                $whatsappbotreply= WhatsappChatbot::where('type', 'message_received')->where('is_send', '0')->get();
    
                if(count($whatsappbotreply) > 0){

                    foreach($whatsappbotreply as $data){

                        $reply_message_id=$data->reply_message_id;

                        $whatsappbotreply= WhatsappChatbot::where('type', 'message_received')->get();
                        $whcasedata = DB::table('whatsapp_tracking')->select('whatsapp_tracking.*')->where('request_uuid', $reply_message_id)->first();

                                if (!empty($whcasedata)) {

                                    $caseData = DB::table('mediation_case')->select('mediation_case.*')->where('id', $whcasedata->caseid)->first();

                                    $mid = "M" . sprintf("%06d", $caseData->id);

                                    if($data->message=="Pay Now"){

                                        $varjson = ['url' => $caseData->PayLink];
                                        $var = ['-url-'];
                                        $var1 = [$caseData->PayLink];
                                        $content1 = WaTemplate::getcontent('med_bot_paynow2');
                                        $haptik_tmp="med_bot_paynow2";
                                       // $payresult=PaymentController::WApayNowProcess($caseData->id, $caseData->PayLink);

                                    }
                                    if($data->message=="Why did I get this?"){

                                        $claimantdata = InvoledUser::select('user_involved_in_agreement.*', 'users.organization')->where('isClaimant', 0)->leftJoin('users', 'users.id', '=', 'user_involved_in_agreement.userId')->where('userPlanId', $caseData->id)->first();
                                        $claimant_name=$claimantdata->name;

                                        $varjson = ['name' => $claimant_name];
                                        $var = ['-name-'];
                                        $var1 = [$claimant_name];
                                        $content1 = WaTemplate::getcontent('med_bot_why');
                                      //  echo "brijesgh"; print_r($content1);die();
                                        $haptik_tmp="med_bot_why";

                                    }
                                    if($data->message=="Explore Alternatives"){

                                        $varjson = ['caseid' => $mid];
                                        $var = ['-cid-'];
                                        $var1 = [$mid];
                                        $content1 = WaTemplate::getcontent('med_bot_Alternatives2');
                                        $haptik_tmp="med_bot_alternatives2";

                                    }
                                    if($data->message=="Restructure"){

                                        $restructure_link="https://mediation.presolv360.com/restructure/".$caseData->id."/".$caseData->payToken;

                                        $varjson = ['url' => $restructure_link];
                                        $var = ['-url-'];
                                        $var1 = [$restructure_link];
                                        $content1 = WaTemplate::getcontent('med_bot_restructure');
                                        $haptik_tmp="med_bot_restructure";

                                    }
                                    if($data->message=="Submit a Reply"){

                                        $reply_link="https://mediation.presolv360.com/replyback/".$caseData->id."/".$caseData->payToken;

                                        $varjson = ['url' => $reply_link];
                                        $var = ['-url-'];
                                        $var1 = [$reply_link];
                                        $content1 = WaTemplate::getcontent('med_bot_submit_reply');
                                        $haptik_tmp="med_bot_submit_reply";

                                    }

                                    $content = str_replace($var, $var1, $content1);

                                    $dwa1 = [
                                        'caseid' => $caseData->id,
                                        'contact' =>  $data->phone_number,
                                        'content' => ['text' => $content],
                                        'event' => "WHATSAPP_CHATBOT_MSG",
                                        'varjson' => $varjson,
                                        'haptik_tmp' => $haptik_tmp,
                
                                    ];

                                    $accessW = Whatsapp::sendWamessage($dwa1);
                                    if($accessW){

                                        $Chatbot=WhatsappChatbot::find($data->id);
                                        $Chatbot->is_send="1";
                                        $Chatbot->updated_at=date('Y-m-d H:s:i');
                                        $Chatbot->save();

                                        $result['code']=200;
                                        $result['message']='success';//unauthorised
                                        $result['response']='success';
                                        echo json_encode($result);

                                    }else{

                                        $result['code']=404;
                                        $result['message']='Que not inserted';//unauthorised
                                        $result['response']='error';
                                        echo json_encode($result);

                                    }
                                }else{

                                    $result['code']=404;
                                    $result['message']='Message not found';
                                    $result['response']='error';
                                    echo json_encode($result);

                                }
                    }
                }
                else{

                    $result['code']=404;
                    $result['message']='No Data Found';//unauthorised
                    $result['response']='error';
                    echo json_encode($result);
                    exit;
                }
            }
            catch (Exception $e) { 

                echo "Caught an exception: " . $e->getMessage();

                $result['code'] = 500;
                $result['message'] = "Something Went Wrong";
                $result['response']='error';
                echo json_encode($result);
                exit;

            }

        }

    }



    // webhook code
    public function medwhatsappbotlog(Request $request){ 
      
        $method = $_SERVER['REQUEST_METHOD'];
        echo "here";
        $reqdata=array('req_type'=>$method,"req_data"=> json_encode($request->all()), "created_at"=>date('Y-m-d_H:i:s'));

        echo "<pre>";print_R($reqdata);
        $insertedId = DB::table('whatsappbot_req_log')->insertGetId($reqdata);

        if($method == "POST") { 
    
            try {

                $message_id=$request->message_id;
                $med_whatsapp_json=$request->med_whatsapp_json;
                $path=$_SERVER['REQUEST_URI']."webhook";
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
        
                    $stop_whatsapp1=UserStopWhatsapp::insert($stop_whatsapp);
                   // $stop_Medwhatsapp=MedUser_stop_whatsapp::insert($stop_whatsapp);

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


    public function medwhatsappConsentReply(){ 
      
        try {

            $url = "https://mediation.presolv360.com/api/whatsappconsentreply";
            $auth =  'MED360WHATSAPPBOT';
            $data = [
                "auth" => "MED360WHATSAPPBOT",
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

    // webhook code

}
