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

}
