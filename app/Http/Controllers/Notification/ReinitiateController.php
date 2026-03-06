<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Helpers\Curl;
use App\Http\Helpers\Whatsapp;
use App\Http\Helpers\SendGrid;
use App\Models\WhatsappTrack;
use App\Http\Traits\UploadTrait;
use App\Models\System;
use App\Models\WaTemplate;
use App\Models\WhatsAppQue;
use App\Models\InvoledUser;
use App\Models\InvitationFiles;
use App\Models\User;
use App\Models\EmailTrack;
use Illuminate\Support\Facades\Storage;

use App\Http\Helpers\Common_function;

use App\Models\MedCase;
use App\Models\WhatsappLog;

use DB;


use PDF;
use Auth;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\File;
use PDFMerger;

use ZipArchive;

class ReinitiateController extends Controller
{

    use UploadTrait;



    public function __construct()
    {
    }

    public function reinitiate()
    {

        $allData = DB::table('reinitiate_noti_axis_b36')->where('is_whtsapp_sent', 0)->where('is_pdf_sent', 0)->limit(100)->get();

        


        foreach($allData as $data) {
            $varjson = ['initiating' => $data->org, 'caseid' => "M" . sprintf("%06d", $data->caseid)];
            $var = ['-ip-', '-cid-'];
            $var1 = [$data->org, "M" . sprintf("%06d", $data->caseid)];
    
            $template_name = WaTemplate::getRandomTemplate('ITML4');

            $content1 = WaTemplate::getcontent($template_name);
            $content = str_replace($var, $var1, $content1);
            $dwa1 = [
                'caseid' => $data->caseid,
                'contact' =>  $data->phone,
                'content' => ['text' => $content],
                'event' => 'ACPTARB_ADM_RES',
                'varjson' => $varjson,
                'haptik_tmp' => $template_name,
    
            ];

            $access1 = Whatsapp::sendWamessage($dwa1);

            if($access1) {
                $is_update_wa = DB::table('reinitiate_noti_axis_b36')->where('caseid', $data->caseid)->update(['is_whtsapp_sent' => 1]);

                if($is_update_wa) {
                    echo $template_name ." added for case id =" .$data->caseid;
                    echo "<br/>";
                }
                
            }

            // attachment

            $invitation = 'Invitation_mediate_M' . sprintf('%06d', $data->caseid) . '.pdf';

             

            $finalFilePath = 'mediation_documents/mediation/' . $data->caseid . '/' . $invitation;
            $whatsappSend = Storage::disk('s3')->url($finalFilePath);

            $varjson_file = ['caseid' => "M" . sprintf("%06d", $data->caseid)];
            $var_file = ['-caseid-'];
            $var1_file = ["M" . sprintf("%06d", $data->caseid)];

            $pdf_template_name = WaTemplate::getRandomTemplate('PDF');

            $content1_file = WaTemplate::getcontent($pdf_template_name);
            $content_file = str_replace($var_file, $var1_file, $content1_file);
            $dwa2 = [
                'caseid' => $data->caseid,
                'contact' =>  $data->phone,
                'content' => ['media' => ['url' => $whatsappSend, 'caption' => $content_file]],
                'event' => 'ACPTARB_ADM_RES',
                'varjson' => $varjson_file,
                'haptik_tmp' =>  $pdf_template_name,

            ];
            
            $access2 = Whatsapp::sendWamessage($dwa2);


            if($access2) {
                $is_update_pdf = DB::table('reinitiate_noti_axis_b36')->where('caseid', $data->caseid)->update(['is_pdf_sent' => 1]);

                if($is_update_pdf) {
                    echo "attachment template added for case id =" .$data->caseid;
                    echo "<br/>";
                }
               
            }
            
        }
    }



    // whtsapp and email for batch 260
    public function reinitiate_260()
    {

        $allData = DB::table('reinitiate_noti_axis_b36')->where('is_whtsapp_sent', 0)->where('is_pdf_sent', 0)->limit(100)->get();

        


        foreach($allData as $data) {


            $varjson = ['initiating' => $data->org, 'caseid' => "M" . sprintf("%06d", $data->caseid)];
            $var = ['-ip-', '-cid-'];
            $var1 = [$data->org, "M" . sprintf("%06d", $data->caseid)];
    
            $template_name = WaTemplate::getRandomTemplate('ITML4');

            $content1 = WaTemplate::getcontent($template_name);
            $content = str_replace($var, $var1, $content1);
            $dwa1 = [
                'caseid' => $data->caseid,
                'contact' =>  $data->phone,
                'content' => ['text' => $content],
                'event' => 'ACPTARB_ADM_RES',
                'varjson' => $varjson,
                'haptik_tmp' => $template_name,
    
            ];

            $access1 = Whatsapp::sendWamessage($dwa1);

            if($access1) {
                $is_update_wa = DB::table('reinitiate_noti_axis_b36')->where('caseid', $data->caseid)->update(['is_whtsapp_sent' => 1]);

                if($is_update_wa) {
                    echo $template_name ." added for case id =" .$data->caseid;
                    echo "<br/>";
                }
                
            }

            // attachment

            $invitation = 'Invitaton_med_M' . sprintf('%06d', $data->caseid) . '.pdf';

             

            $finalFilePath = 'mediation_documents/mediation/' . $data->caseid . '/' . $invitation;
            $whatsappSend = Storage::disk('s3')->url($finalFilePath);



            $varjson_file = ['caseid' => "M" . sprintf("%06d", $data->caseid)];
            $var_file = ['-caseid-'];
            $var1_file = ["M" . sprintf("%06d", $data->caseid)];

            $pdf_template_name = WaTemplate::getRandomTemplate('PDF');

            $content1_file = WaTemplate::getcontent($pdf_template_name);
            $content_file = str_replace($var_file, $var1_file, $content1_file);
            $dwa2 = [
                'caseid' => $data->caseid,
                'contact' =>  $data->phone,
                'content' => ['media' => ['url' => $whatsappSend, 'caption' => $content_file]],
                'event' => 'ACPTARB_ADM_RES',
                'varjson' => $varjson_file,
                'haptik_tmp' =>  $pdf_template_name,

            ];
            
            $access2 = Whatsapp::sendWamessage($dwa2);


            if($access2) {
                $is_update_pdf = DB::table('reinitiate_noti_axis_b36')->where('caseid', $data->caseid)->update(['is_pdf_sent' => 1]);

                if($is_update_pdf) {
                    echo "attachment template added for case id =" .$data->caseid;
                    echo "<br/>";
                }
               
            }


             // send email function
             $is_email = $this->send_email_batch_260($data->caseid, $finalFilePath);
             
            //$this->send_email_batch_260($data->caseid, $finalFilePath);  // test path

             if($is_email) {
                    echo "email sent for case id =" .$data->caseid;
                    echo "<br/>";
             }
             // send email function
            
        }
    }




    public function send_email_batch_260($id, $finalFilePath){
        $involedUser = InvoledUser::select('user_involved_in_agreement.*', 'users.organization')->leftjoin('users', 'users.id', '=', 'user_involved_in_agreement.userId')->where("userPlanId", $id)->get();


        $initiating_party = "";
        $initiating_phone = [];
        $initiating_email = [];
        $responding_party = "";
        $ini_userPlanId = "";
        $responding_email = [];
        $responding_phone = [];
       
        $d2 = [
            'event' => 'ACPTARB_ADM_RES',
            'case_id' => $id,
        ];
        // $mid = "M" . sprintf("%06d", $id);
        // $responding_phone = "";
        foreach ($involedUser as $inv) {
            if ($inv->isClaimant == 0) {
                if ($initiating_party == "") {
                    if ($inv->organization != null) {
                        $initiating_party = $inv->organization;
                    } else {
                        $initiating_party = $inv->name;
                    }
                }
                $ini_userPlanId = $inv->userPlanId;
                $initiating_phone[] = $inv->userPhone;
                $initiating_email[] = $inv->userEmail;
            } else if ($inv->isOnboarded == 0) {
                $code = $inv->joinCode;
                if ($inv->name != "") {
                    if ($responding_party == "") {
                        $responding_party = $inv->name;
                    }
                }
                $responding_phone[] = $inv->userPhone;
                if ($inv->userEmail != "") {
                    SendGrid::send($d2, $inv->userEmail, env('L4_INVITATION_TO_COUNTER_PARTIES_FOR_ONBOARDING', ''), ["-caseid-" => "M" . sprintf("%06d", $id), "-link-" => $inv->joinCode, "-initiating-" => $initiating_party], $inv->name, $finalFilePath);
                }
                
            }
           

        }
    }
    // whtsapp and email for batch 260




        public function invitation_mediate($id)
    {
        
        //echo "id".$id;
        $data["case"] = MedCase::where("id", "=", $id)->first();
        // $data["party"] = InvoledUser::where("userPlanId", "=", $id)->get();
        $data["party"] = InvoledUser::select('user_involved_in_agreement.*', 'users.address as useraddress', 'users.address1 as useraddress1', 'users.pincode as userpincode', 'users.city as usercity', 'users.state as userstate', 
        'users.country as usercountry', 'mediation_case.poc_name as userpname', 'mediation_case.poc_email as userpemail', 'mediation_case.poc_contact as userpcontact')
            ->leftJoin("users", "users.id", "=", "user_involved_in_agreement.userId")
            ->leftJoin("mediation_case", "mediation_case.id", "=", "user_involved_in_agreement.userPlanId")
            ->where("user_involved_in_agreement.userPlanId", "=", $id)
            ->where("mediation_case.id", "=", $id)->get();

         //dd($data["case"]);

        // Added for icici bank ITM layout //
        // if($data["case"]->batch_id == 63){
        //     $pdf = PDF::loadView('pdf.invitation_mediation_icici', $data);
        // } else {


           // if($data['case']->bulk_flag == 1){
                $pdf = PDF::loadView('pdf.invitation_mediation_all', $data, [], [
                    'title' => 'ITM' . ' ' . $id,
                    'showWatermarkImage' => true, 
                    'wialpha' => 0.1, 
                    'wisize' => 'F', 
                    'wipos' => 'F', 
                    'mode' => 'utf-8',
                    'SetAutoFont' => 'AUTOFONT_THAIVIET',
                    'autoLangToFont' => true,
                    'autoScriptToLang' => true
                ]);
            // } else {
            //     $pdf = PDF::loadView('pdf.invitation_mediation', $data); 
            // }
            
        //}
       // dd($data);
       
        //$name = 'Invitation_mediate_M' . sprintf('%06d', $data["case"]->id) . '.pdf'; /********** file name 30 character */
        $name = 'Invitaton_med_M' . sprintf('%06d', $data["case"]->id) . '.pdf'; /********** file name 30 character */
        
        $savePath = 'mediation_documents/mediation/' . $data["case"]->id;
        $finalFilePath = $savePath . '/' . $name;
        
       //$local_store = Storage::disk('local')->put('public/mediation/' . $data["case"]->id . '/' .  $name, $pdf->output());
       //return $local_store;

        $uploadS3 = $this->uploadOnAWSDirect($finalFilePath, $savePath, $pdf);
        return $name;
    }


    public function regenerate_itm()
    {
        ini_set('memory_limit', -1);
        //$all_cases = MedCase::select('id','batch_id')->where("batch_id", 186)->where('case_status', 1)->get();

       // $query = "CAST(CAST(poc_contact  AS FLOAT) AS bigint)";
      //  $all_cases = DB::table('mediation_case')->where("batch_id", 50)->orderByRaw($query)->get();
        $all_cases = MedCase::select('id','batch_id', 'poc_contact')->where("batch_id", 260)->where('case_status', 1)->get();
     
        //echo "<pre>";print_R($all_cases);

    //$getReportData = DB::table('itm_caseid')->where("batch_id", 186)->get();
    $getReportData = DB::table('itm_caseid')->where("batch_id", 260)->get();


    $insert_data = array();
    foreach($all_cases as $key => $case_data) {

       
        // DB::table('itm_caseid')->insert([
        //     'caseid' => $case_data['id'],
        //     'batch_id' => $case_data['batch_id'],
        //     'is_itm_done'=> 0
        //  ]);
         $insert_data[$key]['caseid'] = $case_data['id']; 
         $insert_data[$key]['batch_id'] = $case_data['batch_id']; 
         $insert_data[$key]['is_itm_done'] = 0; 
    }

    if(sizeof($getReportData) == 0 ) {
        $insert = DB::table('itm_caseid')->insert($insert_data);
    } else {
        $getData = DB::table('itm_caseid')->where('is_itm_done', 0)->orderByDesc('id')->limit(100)->get(); 
    }

    if(isset($insert) && $insert != "") {
        $getData = DB::table('itm_caseid')->where('is_itm_done', 0)->orderByDesc('id')->limit(100)->get();
    }
       foreach($getData as $key => $case_data) {

       
        
      
       // echo "<pre>";print_R($case_data['id']);
            $invitation = $this->invitation_mediate($case_data->caseid);

            // if (!isset($invmodel)) {
            $invmodel = new InvitationFiles();
            // }
            $invmodel->case_id = $case_data->caseid;
            $invmodel->file_name = $invitation;
            $invmodel->save();

            if($invitation) {
                $is_update = DB::table('itm_caseid')->where('caseid', $case_data->caseid)->update(['is_itm_done' => 1]);


                if($is_update) {
                    echo ($key + 1)." ITM generated for case id =".$case_data->caseid;
                    echo "<br/>".$invitation."<br/>";
                }
                
            }

            
       }
    }


    public function change_poc_contact() {
        $getAll = DB::table('change_poc_contact_axis_b36 as cp')->select('cp.caseid', 'cp.poc_contact as cp_contact', 'mediation_case.id', 'mediation_case.poc_contact')
        ->join('mediation_case', 'mediation_case.id', '=', 'cp.caseid')
        ->where('cp.batchid', 260)
        ->get();

        foreach($getAll as $myData) {
            $is_update = DB::table('mediation_case')->where('id', $myData->id)->update(['poc_contact' => $myData->cp_contact]);

            if($is_update) {
                echo "<br/>caseid ".$myData->id. " old contact ".$myData->poc_contact." updated to new ".$myData->cp_contact;
            }
        }
    }



    // delete session notification
    public function delete_session(){
        $allData = DB::table('reini_delete_session')->where('is_email_sent', 0)->where('is_wa_sent', 0)->limit(100)->get();


        
        //dd($allData);
        foreach($allData as $data) {


            $d1 = [
                'event' => 'SESS_CEN_PARTY',
                'case_id' => $data->caseid,
            ];

            $session_date = "27/02/2025/3:00 PM";

           
            if ($data->email != null) {
                $is_email = SendGrid::send($d1, $data->email, env('L24_CANCELLING_OF_SESSION', ''), ["-cid-" => "M" . sprintf("%06d", $data->caseid), "-date-" => $session_date, "-type-" => "Party"], "Party");
            
                if($is_email){



                    $is_update_email = DB::table('reini_delete_session')->where('caseid', $data->caseid)->update(['is_email_sent' => 1]);

                    if($is_update_email) {
                        echo "Email sent for case id " .$data->caseid;
                        echo "<br/>";
                    }

                }
            
            }
            if ($data->phone != null) {

                $varjson = ['party' => 'Party', 'deleteDate' => $session_date, "caseid" => "M" . sprintf("%06d", $data->caseid)];
                $var = ['-party-', '-date-', '-caseid-'];
                $var1 = ["Party", $session_date, "M" . sprintf("%06d", $data->caseid)];
                $content1 = WaTemplate::getcontent('L24_cancel_mediation_session');
                $content = str_replace($var, $var1, $content1);
                $dwa1 = [
                    'caseid' => $data->caseid,
                    'contact' =>  $data->phone,
                    'content' => ['text' => $content],
                    'event' => 'SESS_CEN',
                    'varjson' => $varjson,
                    'haptik_tmp' => 'mediation_cancle_session',

                ];

                $access = Whatsapp::sendWamessage($dwa1);

                if($access) {

                    $is_update_wa = DB::table('reini_delete_session')->where('caseid', $data->caseid)->update(['is_wa_sent' => 1]);

                    if($is_update_wa) {
                        echo "Whatsapp sent for case id" .$data->caseid;
                        echo "<br/>";
                    }
                }
            }

        }
    }
    // delete session notification



    public function insert_wa_log(){
        return view('insertlog');
    }

    public function create_wa_log(Request $request){
        //dd($request->all());
        $selectCsv = $request->file('log_file');
        if ($selectCsv == null) {
            $errormsg .= 'Please Select File';
            // return redirect('/admin/case/new-request')->with(['error' => $errormsg]);
            return json_encode(['code' => 200, 'response' => 'error', 'msg' => $errormsg]);
            exit;
        } else {

            $tmpName = $selectCsv->getPathname();

            $ext = pathinfo($selectCsv->getClientOriginalName(), PATHINFO_EXTENSION);
            // dd($ext);


            if ($ext != 'csv') {
                $errormsg .= 'Please upload csv file';
            } else {
                $csv = $this->csvToArray($tmpName);

                $csv = mb_convert_encoding($csv, 'UTF-8', 'UTF-8');
                foreach ($csv as $k => $value) {
                    $is_track =  DB::table('whatsapp_tracking')->where("request_uuid","=", $value['2'])->first();


                   // echo "<pre>";print_R($value);

                  
                    if(isset($is_track->request_uuid) && $is_track->request_uuid == $value['2']){

                         $data[$k]['request_id'] = $value[2];
                         $data[$k]['created_time'] = "";
                         $data[$k]['sent_time'] = "";
                         $data[$k]['delivered_time'] = $value[11];
                         $data[$k]['updated_time'] = "";
                         $data[$k]['status'] = $value[13];
                         $data[$k]['response'] = "";
                         $data[$k]['created_at'] = date('Y-m-d H:s:i');

                        // date('Y-m-d H:s:i')
                        
                        //  $logs = WhatsappLog::create($data);

                        //  if($logs){
                        //     echo "Log " .$value[2]." added";
                        //  }

                    }

                }

                $logs = WhatsappLog::insert($data);

                if($logs){
                    echo count($data)." added.";
                   //echo "Log " .$value[2]." added";
                }
               // $insert_manage = DB::table('manage_files')->insert($insert);
            }
        }
    }


    public function csvToArray($file)
    {
        $rows = array();
        $headers = array();
        if (file_exists($file) && is_readable($file)) {
            $handle = fopen($file, 'r');
            // dd($handle);
            while (!feof($handle)) {
                $row = fgetcsv($handle, 10240, ',', '"');


                if (empty($headers))
                    $headers = $row;
                else if (is_array($row)) {
                    array_splice($row, count($headers));
                    //$rows[] = array_combine($headers, $row);
                    $rows[] = $row;
                }
            }
            fclose($handle);
        } else {
            throw new Exception($file . ' doesn`t exist or is not readable.');
        }
        return $rows;
    }







    // whtsapp and email session notification
    public function reinitiate_session_notification()
    {

        $allData = DB::table('retrigger_session_axis_b63')->where('is_whtsapp_sent', 0)->where('is_email_sent', 0)->limit(100)->get();

        foreach($allData as $data) {

            $mid = "M" . sprintf("%06d", $data->caseid);
            $d = [
                'event' => 'SESS_SCHE',
                'case_id' => $data->caseid,
            ];

            $userType = "Party";
            $date = "28/03/2025/11:00 AM";
            $invitation = "https://us02web.zoom.us/j/84715690254?pwd=HRYjbXftCpOHZMolWaPBQDzX4Qsd8n.1";
            if ($data->email != "") {
                $is_email = SendGrid::send($d, $data->email, env('L10_SCHEDULING_OF_SESSION', ''), ["-caseid-" => $mid, "-insert_date-" => $date, "-type-" => $userType, "-zoom_invitation_link-" => $invitation], $data->name);
            
            
                if($is_email){
                    $is_update_email = DB::table('retrigger_session_axis_b63')->where('caseid', $data->caseid)->update(['is_email_sent' => 1]);

                    if($is_update_email) {
                        echo "Email sent for case id " .$data->caseid;
                        echo "<br/>";
                    }
                }
            
            
            }
            if ($data->phone != "") {

                $varjson = ['caseid' => $mid, 'sessionDteaTime' => $date, 'zoomid' => $invitation];
                $var = ['-cid-', '-dt-', '-link-'];
                $var1 = [$mid, $date, $invitation];

                $template_name = WaTemplate::getRandomTemplate('L10');

                $content1 = WaTemplate::getcontent($template_name);
                $content = str_replace($var, $var1, $content1);
                $dwa1 = [
                    'caseid' => $data->caseid,
                    'contact' =>  $data->phone,
                    'content' => ['text' => $content],
                    'event' => 'SESS_SCHE',
                    'varjson' => $varjson,
                    'haptik_tmp' => $template_name,

                ];
                

                $access = Whatsapp::sendWaSmessage($dwa1);

                if($access) {
                    $is_update_wa = DB::table('retrigger_session_axis_b63')->where('caseid', $data->caseid)->update(['is_whtsapp_sent' => 1]);
    
                    if($is_update_wa) {
                        echo "Whatsapp sent for case id " .$data->caseid;
                        echo "<br/>";
                    }
                    
                }
            } 
        }
    }



    public function reinitiate_refid() {
        $allData = DB::table('reinitiate_refid')->where('is_whtsapp_sent', 0)->limit(100)->get();

        
        $zoom_date_temp = "31/05/2025/11:00AM-5:00PM";
        $zoom_link_temp = "https://us02web.zoom.us/j/89755436391?pwd=heQqasKUEahTZahiEUJW6o9gX9KWBD.1";

        $ip_name = "Axis Bank LTD.";
        $rp_name = "Responding Party";

        foreach($allData as $data) {
            $varjson = [
                "responding" => $rp_name,
                "initiating" => $ip_name,
                "refid" => $data->refid,
                "sessionDteaTime" => $zoom_date_temp,
                "caseid" => "M" . sprintf("%06d", $data->caseid),
                "zoomid" => $zoom_link_temp
            ];
            $var = ['-rp-','-ip-','-refid-','-dt-','-cid-','-link-'];
            $var1 = [$rp_name,$ip_name,$data->refid,$zoom_date_temp,"M" . sprintf("%06d", $data->caseid),$zoom_link_temp];
                        
    
            $template_name = WaTemplate::getRandomTemplate('L4L10REF');

            $content1 = WaTemplate::getcontent($template_name);
            $content = str_replace($var, $var1, $content1);
            $dwa1 = [
                'caseid' => $data->caseid,
                'contact' =>  $data->phone,
                'content' => ['text' => $content],
                'event' => 'ACPTARB_ADM_RES',
                'varjson' => $varjson,
                'haptik_tmp' => $template_name,
    
            ];

            $access1 = Whatsapp::sendWamessage($dwa1);

            if($access1) {
                $is_update_wa = DB::table('reinitiate_refid')->where('caseid', $data->caseid)->update(['is_whtsapp_sent' => 1]);

                if($is_update_wa) {
                    echo $template_name ." added for case id =" .$data->caseid;
                    echo "<br/>";
                }
                
            }
        }

    }



    // retrigger register case sms //
    public function reinitiate_reg_sms() {
        $allData = DB::table('reinitiate_sms')->where('is_sms_sent', 0)->limit(100)->get();

        
       
        $initiating_party = "Kotak Mahindra Pvt Ltd";

        foreach($allData as $data) {
            $smsvar = ['--caseid--', '--ipname--'];
            $smsvar1 = [Common_function::getsixdigitid('sc', $data->caseid), $initiating_party];
            $varjsonSms = ['caseid' => Common_function::changeidprefix("",$data->caseid), 'ipname' => $initiating_party];
            
            Common_function::sendsmsNotification($data->caseid, $data->phone, $varjsonSms, $smsvar, $smsvar1, 'MEDL4', 'ACPTARB_ADM_RES_SMS', 'L4_Med_case_approve_sms');
             

            //if($access1) {
                $is_update_wa = DB::table('reinitiate_sms')->where('caseid', $data->caseid)->update(['is_sms_sent' => 1]);

                if($is_update_wa) {
                    echo "sms sent for case id =" .$data->caseid;
                    echo "<br/>";
                }
                
            //}
        }

    }
    // retrigger register case sms //





    // retrigger session sms //
    public function reinitiate_session_sms() {
        $allData = DB::table('reinitiate_session_sms')->where('is_sms_sent', 0)->limit(100)->get();

        
       
      

        $zoom_date_temp = "28/06/2025/11:00AM-5:00PM";
        $zoom_link_temp = "https://us02web.zoom.us/j/83703000820?pwd=0Va5xJuV8oAszkSaTUy7F3ylQ4BQGC.1";

        foreach($allData as $data) {
            $smsPresolv360Url = Curl::getShortUrl($zoom_link_temp); // get short url


            $smsvar = ['--datetime--', '--caseid--', '--url--'];
            $smsvar1 = [$zoom_date_temp, Common_function::getsixdigitid('sc', $data->caseid), $smsPresolv360Url];
            $varjsonSms = ['datetime' => $zoom_date_temp, 'caseid' => Common_function::changeidprefix("",$data->caseid), 'url' => $smsPresolv360Url];
            
            Common_function::sendsmsNotification($data->caseid, $data->phone, $varjsonSms, $smsvar, $smsvar1, 'MEDL10', 'SESS_SCHE_SMS', 'L10_med_session_shedule_1');
            //if($access1) {
                $is_update_wa = DB::table('reinitiate_session_sms')->where('caseid', $data->caseid)->update(['is_sms_sent' => 1]);

                if($is_update_wa) {
                    echo "sms sent for case id =" .$data->caseid;
                    echo "<br/>";
                }
                
            //}
        }

    }
    // retrigger session sms //



    // whatsapp and ITM attachment for HDFC B34
    public function reinitiate_hdfc_b34()
    {

        $allData = DB::table('reinitiate_noti_hdfc_b34')->where('is_whtsapp_sent', 0)->where('is_pdf_sent', 0)->limit(100)->get();

        foreach($allData as $data) {


            $varjson = ['initiating' => $data->org, 'caseid' => "M" . sprintf("%06d", $data->caseid)];
            $var = ['-ip-', '-cid-'];
            $var1 = [$data->org, "M" . sprintf("%06d", $data->caseid)];
    
            $template_name = WaTemplate::getRandomTemplate('ITML4');

            $content1 = WaTemplate::getcontent($template_name);
            $content = str_replace($var, $var1, $content1);
            $dwa1 = [
                'caseid' => $data->caseid,
                'contact' =>  $data->phone,
                'content' => ['text' => $content],
                'event' => 'ACPTARB_ADM_RES',
                'varjson' => $varjson,
                'haptik_tmp' => $template_name,
    
            ];

            $access1 = Whatsapp::sendWamessage($dwa1);

            if($access1) {
                $is_update_wa = DB::table('reinitiate_noti_hdfc_b34')->where('caseid', $data->caseid)->update(['is_whtsapp_sent' => 1]);

                if($is_update_wa) {
                    echo $template_name ." added for case id =" .$data->caseid;
                    echo "<br/>";
                }
                
            }

            // attachment

            $invitation = 'Invitaton_med_M' . sprintf('%06d', $data->caseid) . '.pdf';

             

            $finalFilePath = 'mediation_documents/mediation/' . $data->caseid . '/' . $invitation;
            $whatsappSend = Storage::disk('s3')->url($finalFilePath);



            $varjson_file = ['caseid' => "M" . sprintf("%06d", $data->caseid)];
            $var_file = ['-caseid-'];
            $var1_file = ["M" . sprintf("%06d", $data->caseid)];

            $pdf_template_name = WaTemplate::getRandomTemplate('PDF');

            $content1_file = WaTemplate::getcontent($pdf_template_name);
            $content_file = str_replace($var_file, $var1_file, $content1_file);
            $dwa2 = [
                'caseid' => $data->caseid,
                'contact' =>  $data->phone,
                'content' => ['media' => ['url' => $whatsappSend, 'caption' => $content_file]],
                'event' => 'ACPTARB_ADM_RES',
                'varjson' => $varjson_file,
                'haptik_tmp' =>  $pdf_template_name,

            ];
            
            $access2 = Whatsapp::sendWamessage($dwa2);


            if($access2) {
                $is_update_pdf = DB::table('reinitiate_noti_hdfc_b34')->where('caseid', $data->caseid)->update(['is_pdf_sent' => 1]);

                if($is_update_pdf) {
                    echo "attachment template added for case id =" .$data->caseid;
                    echo "<br/>";
                }
               
            }
            
        }
    }

       
}

    

   

