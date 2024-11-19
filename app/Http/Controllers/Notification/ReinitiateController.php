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

        $allData = DB::table('reinitiate_noti')->get();

        


        foreach($allData as $data) {
            $varjson = ['caseid' => "M" . sprintf("%06d", $data->caseid), 'initiating' => $data->org];
            $var = ['-cid-', '-ip-'];
            $var1 = ["M" . sprintf("%06d", $data->caseid), $data->org];
    
    
            $content1 = WaTemplate::getcontent('l4_mediation_party2');
            $content = str_replace($var, $var1, $content1);
            $dwa1 = [
                'caseid' => $data->caseid,
                'contact' =>  $data->phone,
                'content' => ['text' => $content],
                'event' => 'ACPTARB_ADM_RES',
                'varjson' => $varjson,
                'haptik_tmp' => 'l4_mediation_party2',
    
            ];

            $access1 = Whatsapp::sendWamessage($dwa1);

            if($access1) {
                echo "l4_mediation_party2 added for case id =" .$data->caseid;
                echo "<br/>";
            }

            // attachment

            $invitation = $this->invitation_mediate($data->caseid);

            // if (!isset($invmodel)) {
            $invmodel = new InvitationFiles();
            // }
            $invmodel->case_id = $data->caseid;
            $invmodel->file_name = $invitation;
            $invmodel->save();

            $responding_party = "";
            $finalFilePath = 'mediation_documents/mediation/' . $data->caseid . '/' . $invitation;
            $whatsappSend = Storage::disk('s3')->url($finalFilePath);

            $varjson_file = ['caseid' => "M" . sprintf("%06d", $data->caseid)];
            $var_file = ['-caseid-'];
            $var1_file = ["M" . sprintf("%06d", $data->caseid)];
            $content1_file = WaTemplate::getcontent('mediation_consent_doc');
            $content_file = str_replace($var_file, $var1_file, $content1_file);
            $dwa2 = [
                'caseid' => $data->caseid,
                'contact' =>  $data->phone,
                'content' => ['media' => ['url' => $whatsappSend, 'caption' => $content_file]],
                'event' => 'ACPTARB_ADM_RES',
                'varjson' => $varjson_file,
                'haptik_tmp' => 'mediation_consent_doc',

            ];
            
            $access2 = Whatsapp::sendWamessage($dwa2);


            if($access2) {
                echo "attachment added for case id =" .$data->caseid;
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
        
        
    }

    

   

