<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Helpers\Curl;
use App\Http\Helpers\Whatsapp;
use App\Models\WhatsappTrack;
use App\Http\Traits\UploadTrait;
use App\Models\System;
use App\Models\WaTemplate;
use App\Models\WhatsAppQue;
use App\Models\InvoledUser;
use App\Models\InvitationFiles;
use Illuminate\Support\Facades\Storage;

use App\Models\MedCase;

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

        $allData = DB::table('reinitiate_noti_axis_b31_latest')->where('is_whtsapp_sent', 0)->where('is_pdf_sent', 0)->limit(100)->get();

        


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
                $is_update_wa = DB::table('reinitiate_noti_axis_b31_latest')->where('caseid', $data->caseid)->update(['is_whtsapp_sent' => 1]);

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
                $is_update_pdf = DB::table('reinitiate_noti_axis_b31_latest')->where('caseid', $data->caseid)->update(['is_pdf_sent' => 1]);

                if($is_update_pdf) {
                    echo "attachment template added for case id =" .$data->caseid;
                    echo "<br/>";
                }
               
            }
            
        }
    }
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
       
        $name = 'Invitation_mediate_M' . sprintf('%06d', $data["case"]->id) . '.pdf'; /********** file name 30 character */
        
        $savePath = 'mediation_documents/mediation/' . $data["case"]->id;
        $finalFilePath = $savePath . '/' . $name;
        
       $local_store = Storage::disk('local')->put('public/mediation/' . $data["case"]->id . '/' .  $name, $pdf->output());
       return $local_store;

        $uploadS3 = $this->uploadOnAWSDirect($finalFilePath, $savePath, $pdf);
        return $name;
    }


    public function regenerate_itm()
    {
        ini_set('memory_limit', -1);
      // $all_cases = MedCase::select('id','batch_id')->whereIn("batch_id", [184, 186])->where('case_status', 1)->get();
       //$all_cases = MedCase::select('id','batch_id')->where("batch_id", 184)->where('case_status', 1)->get();
       $all_cases = MedCase::select('id','batch_id')->where("batch_id", 186)->where('case_status', 1)->get();

        //echo "<pre>";print_R($all_cases);

    $getReportData = DB::table('itm_caseid')->where("batch_id", 186)->get();


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
        
        
}

    

   

