<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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
use App\Http\Helpers\Curl;
use App\Models\WhatsAppQue;
use App\Models\WhatsappTrack;
use App\Models\WhatsappBotQue;
use App\Models\WhatsappBotReport;
use App\Models\SettlementPayment;
use App\Models\ManageSession;
use App\Models\UserStopWhatsapp;

use App\Http\Controllers\API\PaymentController;

class WhatsappChatbotController extends Controller
{   

    public function whatsappbotReply(){

          
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == "POST") {

            try {
             
                $whatsappbotreply= WhatsappChatbot::where('type', 'message_received')->where('bot_type', '1')->where('is_send', '0')->get();
               

    
                if(count($whatsappbotreply) > 0){

                    foreach($whatsappbotreply as $data){

                        $reply_message_id=$data->reply_message_id;
                       


                        //$whatsappbotreply= WhatsappChatbot::where('type', 'message_received')->get();
                        $whcasedata = DB::table('whatsapp_tracking')->select('whatsapp_tracking.*')->where('request_uuid', $reply_message_id)->first();

                        // For the check User Reply Message

                        $bot_data = DB::table('whatsapp_bot_data')->select('whatsapp_bot_data.*')->where('bot_type', "1")->where('reply_message', $data->message)->first();

                        if (!empty($bot_data)) {

                                if (!empty($whcasedata)) {

                                    $caseData = DB::table('mediation_case')->select('mediation_case.*')->where('id', $whcasedata->caseid)->first();

                                    $mid = "M" . sprintf("%06d", $caseData->id);

                                    if($data->message=="Pay Now"){

                                        $varjson = ['url' => $caseData->PayLink];
                                        $var = ['-url-'];
                                        $var1 = [$caseData->PayLink];
                                        $content1 = WaTemplate::getcontent('med_bot_paynow2');
                                        $haptik_tmp="med_bot_paynow2";
                                        $eventname="WHATSAPP_BOT_PAY";
                                       // $payresult=PaymentController::WApayNowProcess($caseData->id, $caseData->PayLink);
                                       

                                    }
                                    if($data->message=="Why did I get this?"){

                                        $claimantdata = InvoledUser::select('user_involved_in_agreement.*', 'users.organization')->where('isClaimant', 0)->leftJoin('users', 'users.id', '=', 'user_involved_in_agreement.userId')->where('userPlanId', $caseData->id)->first();
                                        $claimant_name=$claimantdata->name;

                                        $varjson = ['name' => $claimant_name];
                                        $var = ['-name-'];
                                        $var1 = [$claimant_name];
                                        $content1 = WaTemplate::getcontent('med_bot_why');
                                        $haptik_tmp="med_bot_why";
                                        $eventname="Whatsapp_Bot_Why";

                                    }
                                    if($data->message=="Explore Alternatives"){

                                        $varjson = ['caseid' => $mid];
                                        $var = ['-cid-'];
                                        $var1 = [$mid];
                                        $content1 = WaTemplate::getcontent('med_bot_Alternatives2');
                                        $haptik_tmp="med_bot_alternatives2";
                                        $eventname="Bot_Explore_Alternatives";

                                    }
                                    if($data->message=="Restructure"){

                                        $restructure_link="https://mediation.presolv360.com/restructure/".$caseData->id."/".$caseData->payToken;

                                        $varjson = ['url' => $restructure_link];
                                        $var = ['-url-'];
                                        $var1 = [$restructure_link];
                                        $content1 = WaTemplate::getcontent('med_bot_restructure');
                                        $haptik_tmp="med_bot_restructure";
                                        $eventname="WHATSAPP_BOT_RESTR";

                                    }
                                    if($data->message=="Submit a Reply"){

                                        $reply_link="https://mediation.presolv360.com/replyback/".$caseData->id."/".$caseData->payToken;

                                        $varjson = ['url' => $reply_link];
                                        $var = ['-url-'];
                                        $var1 = [$reply_link];
                                        $content1 = WaTemplate::getcontent('med_bot_submit_reply');
                                        $haptik_tmp="med_bot_submit_reply";
                                        $eventname="WHATSAPP_BOT_REPLY";
                                    }

                                    $content = str_replace($var, $var1, $content1);
                                              
                                    $dwa1 = [
                                        'caseid' => $caseData->id,
                                        'bot_type' => "1",
                                        'contact' =>  $data->phone_number,
                                        'content' => ['text' => $content],
                                        'event' => "WHATSAPP_CHATBOT_MSG",
                                        'varjson' => $varjson,
                                        'haptik_tmp' => $haptik_tmp,
                                        'bot_id' => $data->id,
                
                                    ];

                                    $claimantdata2 = InvoledUser::select('user_involved_in_agreement.*', 'users.organization')->where('isClaimant', 0)->leftJoin('users', 'users.id', '=', 'user_involved_in_agreement.userId')->where('userPlanId', $caseData->id)->first();
                                    $claimant_name2=$claimantdata2->name;
                                    $claimant_email=$claimantdata2->userEmail;
                                    $respondentdata=SettlementPayment::getrespondent($caseData->id);
                                    $respondent_name=$respondentdata->name;
                                    $respondent_email=$respondentdata->userEmail;

                                    $botlogdata=[
                                        'caseid' => $caseData->id,
                                        'respondent_name' =>  $respondent_name,
                                        'respondent_email' => $respondent_email,
                                        'claimant_name' => $claimant_name2,
                                        'claimant_email' => $claimant_email,
                                        'event' => $eventname,
                                        'created_at' => date('Y-m-d H:i:s')
                                    ];
                                    WhatsappBotReport::insert($botlogdata);

                                    //$accessW = Whatsapp::sendWamessage($dwa1);
                                    //$accessW=self::whatsappsend($d, str_replace('+91', '', $value->contact));
                                    //$accessW = self::whatsappsend($dwa1);
                                    $accessW = self::sendWamessage($dwa1);
                                   // $accessW2=json_decode($accessW, true);

                                   if($accessW==true){

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
                            }else{

                                $result['code']=404;
                                $result['message']='Data not inserted';//unauthorised
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

    public function whatsappconsentreply(){

          
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == "POST") {

            try {
             
                $whatsappbotreply= WhatsappChatbot::where('type', 'message_received')->where('bot_type', '2')->where('is_send', '0')->get();
                //print_r($whatsappbotreply);die();

    
                if(count($whatsappbotreply) > 0){

                    foreach($whatsappbotreply as $data){

                        $reply_message_id=$data->reply_message_id;
                        $whcasedata = DB::table('whatsapp_tracking')->select('whatsapp_tracking.*')->where('request_uuid', $reply_message_id)->first();

                        //print_r($whcasedata);die();

                        // For the check User Reply Message
                        $consent_bot_data = DB::table('whatsapp_bot_data')->select('whatsapp_bot_data.*')->where('bot_type', "2")->where('reply_message', $data->message)->first();
                         //print_r($data);die();
                            if (!empty($consent_bot_data)) {

                                if (!empty($whcasedata)) {

                                    $caseData = DB::table('mediation_case')->select('mediation_case.*')->where('id', $whcasedata->caseid)->first();

                                    $mid = "M" . sprintf("%06d", $caseData->id);

                                    if($data->message=="Yes"){

                                        $varjson = ['caseid' => $mid];
                                        $var = ['-cid-'];
                                        $var1 = [$mid];
                                        $content1 = WaTemplate::getcontent('lmed_wa_consent_accept_yes');
                                        $haptik_tmp="lmed_wa_consent_accept_yes";
                                        $eventname="WHATSAPP_CONSENT_YES";
                                    }
                                    if($data->message=="No"){

                                        $varjson = ['caseid' => $mid];
                                        $var = ['-cid-'];
                                        $var1 = [$mid];
                                        $content1 = WaTemplate::getcontent('lmed_wa_consent_accept_no');
                                        $haptik_tmp="lmed_wa_consent_accept_no";
                                        $eventname="WHATSAPP_CONSENT_NO";
                                    }    
                                    
                                    $wa_consent_manage = DB::table('wa_consent_manage')->select('wa_consent_manage.*')->where('wa_que_id', $whcasedata->que_id)->first();

                                    if (!empty($wa_consent_manage)) {

                                        $replydata1=ManageSession::where('id', $wa_consent_manage->manage_session_id)->first();
                                        
                                        if(!empty($replydata1)){

                                            $replydata = json_decode($replydata1->wa_session_consent_data, true);

                                        }

                                        $replydata[]=[
                                            'message' => $data->message,
                                            'mobile' =>  $data->phone_number,
                                            'created_at' => date('Y-m-d H:i:s'),
                                        ];

                                        $messagedata=json_encode($replydata);
                                        $manage_session = ManageSession::find($wa_consent_manage->manage_session_id);
                                        $manage_session->wa_session_consent_data=$messagedata;
                                        $manage_session->save();

                                        $botlogdata=[
                                            'caseid' => $caseData->id,
                                            'chatbot_id' =>  $data->id,
                                            'message_req_id' => $data->message_id,
                                            'message_context_id' => $data->reply_message_id,
                                            'mobile' => $data->phone_number,
                                            'event' => $eventname,
                                            'manage_session_id' => $wa_consent_manage->manage_session_id,
                                            'wa_consent_text' => $data->message,
                                            'created_at' => date('Y-m-d H:i:s'),
                                        ];
                                        $wa_bot_consent = DB::table('wa_bot_consent_details')->insert($botlogdata);
                                    }

                                    //$content = str_replace($var, $var1, $content1);
                                    $content=$content1;

                                    $dwa = [
                                        'caseid' => $caseData->id,
                                        'contact' =>  $data->phone_number,
                                        'content' => ['text' => $content],
                                        'event' => $eventname,
                                        'varjson' => $varjson,
                                        'haptik_tmp' => $haptik_tmp,
                                    ];
                                    $access = Whatsapp::sendWamessage($dwa);
                                            
                                    /* $dwa1 = [
                                        'caseid' => $caseData->id,
                                        'bot_type' => "2",
                                        'contact' =>  $data->phone_number,
                                        'content' => ['text' => $content],
                                        'event' => $eventname,
                                        'varjson' => $varjson,
                                        'haptik_tmp' => $haptik_tmp,
                                        'bot_id' => $data->id,
                                    ];
                                    $accessW = self::sendWamessage($dwa1); */

                                    if($access==true){

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

    public static function sendWamessage($d)
    {

        $ocarr = [];

        $ocarr[] = $d['contact'];

        foreach ($ocarr as $key => $value) {

            if ($value == '') {
                continue;
            }
            if (strlen($value) == 10) {
                $value = '+91' . $value;
            } else {
                $value = $value;
            }

            $arr_e = array();
            $arr_e['caseid'] = $d['caseid'];
            $arr_e['bot_type'] = $d['bot_type'];
            $arr_e['contact'] = trim($value);
            $arr_e['content'] = json_encode($d['content']);
            $arr_e['casetype'] = 2;
            $arr_e['event'] = $d['event'];
            $arr_e['variable'] = json_encode($d['varjson']);
            $arr_e['haptik_tmp'] = $d['haptik_tmp'];
            $arr_e['bot_id'] = $d['bot_id'];
            

            if (array_key_exists('media', $d['content'])) {
                $arr_e['media'] = 1;
            }

            $BotQue =WhatsappBotQue::create($arr_e);
        }

        self::send();

        return true;
    }

    public function send()
    {

        $limit = 200;

        $whapps = WhatsappBotQue::where(['is_sent' => 0, 'is_processing' => 0, 'is_success' => null,'is_hold'=> null])->whereDate('created_at', '>', '2022-07-31')->orderBy('created_at', 'ASC')->limit($limit)->get();

        if (count($whapps) < 1) {
            exit();
        }
        $whappspr = [];

        foreach ($whapps as $key => $value) {

            $whappspr[] = $value->id;
        }

        $setprocess = WhatsappBotQue::whereIn('id', $whappspr)->limit($limit)->update(['is_processing' => 1]);

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

                $content['media']['url'] = $this->getPreSignedUrl(urldecode($contenturl), 15);

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

            $access=self::WhatsappMessage($d, str_replace('+91', '', $value->contact));
        }
    }

    public function whatsappsend($whdata)
    {
       
        $accessW = self::sendWamessage($whdata);
        
        if (array_key_exists('media', $whdata['content'])) {

            $whmedia= 1;
        }else{
            $whmedia= 0;
        }

        //$content = json_decode($whdata['content'], true);
        $content = $whdata['content'];
        $oldcontent = '';
        $vararray = [];
        $vararrayheader = [];
        //$convertarray = json_decode($whdata['varjson'], true);
        $convertarray = $whdata['varjson'];
        foreach ($convertarray as $var) {
            $vararray[] = $var;
        }

        if ($whmedia == 1) {

            $oldcontent = $content;

            $contenturl = parse_url($content['media']['url'])["path"];
            $file_name = basename($content['media']['url']);

            $content['media']['url'] = $this->getPreSignedUrl(urldecode($contenturl), 15);

            if (!file_get_contents($content['media']['url'])) {

            } else {
                $path = 'public/tmp/' . $file_name;
                Storage::disk('local')->put($path, file_get_contents($content['media']['url']));
                $vararrayheader[] = url("storage/app/" . $path);
                // echo url("storage/app/" . $path);
            }
        }

        $d = [
            'id'=> $accessW->id,
            'event' => $whdata['event'],
            'tempname' => $whdata['haptik_tmp'],
            'varbody' => $vararray,
            'varheader' => count($vararrayheader) > 0 ? $vararrayheader : "",
            'file_name' => isset($file_name) ? $file_name : "",
            'content' => $content,
            'oldcontent' => $oldcontent,
            'type' => 2,
            'caseid' => $whdata['caseid'],
        ];


        $access=self::WhatsappMessage($d, str_replace('+91', '', $whdata['contact']));
        return $access;
    }

    public function WhatsappMessage($d, $c)
    {

        $url = "https://api.interakt.ai/v1/public/message/";


        if ($d['varheader'] != "") {

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

        $type = "POST";
        $auth = env('INTERAKT_KEY');
        $findtrack = WhatsappTrack::where(['que_id' => $d['id']])->orderBy('created_at', 'DESC')->limit(1)->first();

        if (isset($findtrack)) {
            if ($findtrack->request_uuid != "") {
                return true;
            }
        }

        $res = Curl::NewWhatsappRequest($url, json_encode($data), $type, $auth);
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

                $que = WhatsappBotQue::where(['is_sent' => 0, 'is_processing' => 1, 'id' => $d['id']])->update(['is_sent' => 1, 'is_success' => 1]);
            }
            $que = WhatsappBotQue::where(['is_sent' => 0, 'is_processing' => 1, 'id' => $d['id']])->update(['is_processing' => 0, 'is_sent' => 1]);

            
            $result2['message']='Success';//unauthorised
            $result2['response']='success';
            return json_encode($result2);

        } else {

            $result2['message']='Fail';//unauthorised
            $result2['response']='error';
            return json_encode($result2);
        }
    }
    
    public function createbot_report($data){

        $data1 = [
            'caseid' => $data['caseid'],
            'respondent_name' => $data['respondent_name'],
            'respondent_email' => $data['respondent_email'],
            'claimant_name' => $data['claimant_name'],
            'claimant_email' => $data['claimant_email'],
            'event' => $data['event'],
            'reply' => $data['reply'],
            'restructure_option' => $data['restructure_option'],
            'created_at' => date('Y-m-d H:i:s')
        ];

        WhatsappBotReport::insert($data1);
    }

    public function botmisreport(Request $request){

        $from=$request->from_caseid;
        $to=$request->to_caseid;
        $botdata=WhatsappBotReport::getmisreport($from, $to);
        print_r($botdata);die();
        $MIS_arr=array();
       if(count($botdata)>0){

            foreach($botdata as  $key2 => $botdata){

                $keyvalue=0;
                
                $MIS_arr[$key2]['caseid'] = $botdata->caseid;
                $keyvalue++;
            }

            $result['code']=200;
            $result['message']='success';//unauthorised
            $result['response']='success';
            echo json_encode($result);
            exit;

           //return Excel::download(new ExportCodyDataSheet($botdata), 'chat360_data.xlsx');

       }else{

        $result['code']=404;
        $result['message']='Data not found';//unauthorised
        $result['response']='error';
        echo json_encode($result);
        exit;

       }

    }




    // webhook code
    public function medwhatsappbotlog(Request $request){ 
      
        $method = $_SERVER['REQUEST_METHOD'];

        $reqdata=array('req_type'=>$method,"req_data"=> json_encode($request->all()), "created_at"=>date('Y-m-d_H:i:s'));
        //echo "<pre>";print_R($reqdata);
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

                echo "this";

                if (count($medtrack) > 0 && $request->type=="message_received")
                {

                    echo "in this"; exit;

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

}
