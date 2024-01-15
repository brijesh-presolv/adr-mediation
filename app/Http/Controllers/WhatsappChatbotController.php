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
                        $whcasedata = DB::table('whatsapp_tracking')
                                        ->select('whatsapp_tracking.*', 'med_case.id as case_id', 'med_case.payToken', 'med_case.PayLink')
                                        ->leftJoin('mediation_case as med_case', DB::raw('med_case.id'), '=', DB::raw('whatsapp_tracking.caseid'))
                                        ->where('whatsapp_tracking.request_uuid', $reply_message_id)
                                        ->first();

                                        print_r($whcasedata);die();
                                    $mid = "M" . sprintf("%06d", $whcasedata->case_id);

                                    if($data->message=="Pay Now"){

                                        $varjson = ['url' => $whcasedata->PayLink];
                                        $var = ['-url-'];
                                        $var1 = [$whcasedata->PayLink];
                                        $content1 = WaTemplate::getcontent('med_bot_paynow');
                                        $haptik_tmp="med_bot_paynow";
                                        $payresult=PaymentController::WApayNowProcess($whcasedata->case_id, $whcasedata->PayLink);

                                    }
                                    if($data->message=="Why did I get this?"){

                                        $varjson = ['caseid' => $mid];
                                        $var = ['-cid-'];
                                        $var1 = [$mid];
                                        $content1 = WaTemplate::getcontent('med_bot_why');
                                      //  echo "brijesgh"; print_r($content1);die();
                                        $haptik_tmp="med_bot_why";

                                    }
                                    if($data->message=="Explore Alternatives"){

                                        $varjson = ['caseid' => $mid];
                                        $var = ['-cid-'];
                                        $var1 = [$mid];
                                        $content1 = WaTemplate::getcontent('med_bot_Alternatives');
                                        $haptik_tmp="med_bot_Alternatives";

                                    }

                                    $content = str_replace($var, $var1, $content1);

                                    $dwa1 = [
                                        'caseid' => $whcasedata->caseid,
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
                                        exit;

                                    }else{

                                        $result['code']=404;
                                        $result['message']='Que not inserted';//unauthorised
                                        $result['response']='error';
                                        echo json_encode($result);
                                        exit;

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
