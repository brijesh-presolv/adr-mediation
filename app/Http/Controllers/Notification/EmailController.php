<?php

namespace App\Http\Controllers\Notification;


use App\Http\Traits\UploadTrait;
use App\Models\EmailQue;
use App\Models\EmailTrack;
use App\Models\System;
use Illuminate\Support\Facades\Log;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Model\SendSmtpEmail;
use GuzzleHttp\Client;

class EmailController
{    
    use UploadTrait;

    public static $apiKey = '';

    public function __construct() 
    {
        $sendgridkey=System::select('value')->where(['name'=>'SENDGRID_AUTH'])->first();
       self::$apiKey = $sendgridkey->value;
    }

    public function send(){

        $platform = DB::table('email_platform')
                    ->where('status', 1)
                    ->first();

        if($platform->name == "Brevo"){

            $this->sendEmailBrevo();

        } else {

    	
            $limit=500;

            $emails=EmailQue::where(['is_sent'=>0,'is_processing'=>0,'is_hold'=> null])->orderBy('updated_at','DESC')->limit($limit)->get();


            if(count($emails)<1){
                exit();
            }


            $emailproccess=[];

            foreach ($emails as $key => $value) {
                
                $emailproccess[]=$value->id;

            }



            $setprocess=EmailQue::whereIn('id', $emailproccess)->limit($limit)->update(['is_processing' => 1]);

            //$emails=Email_que::where(['is_sent'=>0,'is_processing'=>1])->orderBy('updated_at','DESC')->limit($limit)->get();

            foreach ($emails as $key => $value) {
            
                
                $d = [
                        'id'=>$value->id,
                        'event' => $value->event,
                        ($value->case_id != null) ? 'case_id' : 'user_id' => ($value->case_id != null) ? $value->case_id : $value->user_id,
                        'type' => $value->case_type,
                    ];

                    $vars=json_decode($value->email_variables,true);

                    $attachment=$value->attachment;

                    $template=$value->template_id;

                    $r=self::sendmail($d,$value->to,$vars,$template,$attachment);


            }
        }
    	
    }
	
	 public static function sendmail($d, $to, $subsarr, $tempid, $a = '', $o = '')
    {

        $emailSender= env('SENDGRID_SENDER');
        $emailSenderName= env('SENDGRID_SENDER_NAME');

        $to=trim($to);

        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {

            return false;
        }


        // $res=[];

        if ($to != '') {

            $uemail = $to;

             $response = '';
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom($emailSender, $emailSenderName);
        $email->addTo($to, 'User');
         
        $email->setTemplateId($tempid);
         

            if (count($subsarr) > 0) {

                foreach ($subsarr as $key => $value) {
                    //$mail->personalization[0]->addSubstitution("$key", "$value");
                    $email->addSubstitution($key, $value);
                }
            }

            $a=explode(',',rtrim($a,','));

            if (is_array($a)) {

                foreach ($a as $av) {


                    if($av!=''){

                    

                    $filename = basename($av);
                    $d1 = $d;
                    $d1['file'] = $filename;
                    $d1['event_type'] = 3;
                    $d1['event'] = $d['event'] . '_FILE';
                    $fileurl=self::getPreSignedUrl(urldecode(trim($av)),1);
                    $file_encoded = base64_encode(file_get_contents($fileurl));

                    
                    $attachment = new \SendGrid\Mail\Attachment();


                    $ext = pathinfo($av, PATHINFO_EXTENSION);

                    if ($ext == 'pdf') {
                        $attachment->setType("application/pdf");
                    } else if ($ext == 'zip') {
                        $attachment->setType("application/zip");
                    } else if ($ext == 'rar') {

                        $attachment->setType("application/vnd.rar");
                    }


        
                
                $attachment->setContent($file_encoded);
                $attachment->setDisposition("attachment");
                $attachment->setFilename($filename);

                $email->addAttachment($attachment);

                    }
                }
            } 
            else if ($a != '') 
            {
                $filename = basename($a);
                $d1 = $d;
                $d1['file'] =$filename;
                $d1['event_type'] = 3;
                $d1['event'] = $d['event'] . '_FILE';
                $fileurl=self::getPreSignedUrl(urldecode(trim($a)),1);
                $file_encoded = base64_encode(file_get_contents($fileurl));
                 
                $attachment = new \SendGrid\Mail\Attachment();

                $ext = pathinfo($a, PATHINFO_EXTENSION);

                if ($ext == 'pdf') {
                    $attachment->setType("application/pdf");
                } else if ($ext == 'zip') {
                    $attachment->setType("application/zip");
                } else if ($ext == 'rar') {

                    $attachment->setType("application/vnd.rar");
                }

                $attachment->setContent($file_encoded);
                $attachment->setDisposition("attachment");
                $attachment->setFilename($filename);
                $email->addAttachment($attachment);
            }

 
            
            // $sg = new \SendGrid('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');


            // try {

            //     return $response = $sg->client->mail()->send()->post($mail);

            //     self::Etrack($response, $d, $uemail);
            // } catch (Exception $e) {

            //     self::Etrack('', $d, $uemail, true);
            // }

            $sendgrid = new \SendGrid(self::$apiKey);

        try {
          $response = $sendgrid->send($email);

           $et= self::Etrack($response, $d, $uemail);

           if($et==true){
           	$setprocess=EmailQue::where(['id'=>$d['id'],'is_sent'=>0])->limit(1)->update(['is_processing' => 0,'is_sent'=>1]);
           }
        } catch (Exception $e) {
           
            echo 'Caught exception: ' . $e->getMessage() . "\n";
            self::Etrack('', $d, $uemail, true);
        }


         $response;


        }



      


    }

    public static function Etrack($response, $d, $to, $ns = false)
    {
        if ($ns == true) {
            $datarr = ['sg_message_id' => 'false', 'event' => 'Not sent', (isset($d['case_id'])) ? 'case_id' : 'userid' => (isset($d['case_id'])) ? $d['case_id'] : $d['user_id'], 'casetype' => $d['type'], 'email' => $to, 'status' => '400', 'created_at' => date('Y-m-d H:i:s')];

            EmailTrack::insert($datarr);
            return true;
        }
        //sent
        $status = $response->statusCode();
        $headers = $response->headers();
        $body = $response->body();
        $id = @reset(preg_grep('/^X-Message-Id:\s.*/', $headers));

        if ($id) {

            $idr = explode(':', $id);

            if (isset($idr[1]) and count($d) > 0) {

                $datarr = ['sg_message_id' => trim($idr[1]), 'event' => $d['event'],  (isset($d['case_id'])) ? 'case_id' : 'userid' => (isset($d['case_id'])) ? $d['case_id'] : $d['user_id'], 'casetype' => $d['type'], 'email' => $to, 'status' => trim($status), 'created_at' => date('Y-m-d H:i:s')];

                $in=EmailTrack::insert($datarr);

                if($in){
                	return true;
                }
            }
        }
    }

    // Send Email Via Brevo

    public function sendEmailBrevo(){

    	
        $limit=500;

        $emails=EmailQue::where(['is_sent'=>0,'is_processing'=>0,'is_hold'=> null])->orderBy('updated_at','DESC')->limit($limit)->get();

        if(count($emails)<1){
            exit();
        }


        $emailproccess=[];

        foreach ($emails as $key => $value) {
            
            $emailproccess[]=$value->id;

        }

    	$setprocess=EmailQue::whereIn('id', $emailproccess)->limit($limit)->update(['is_processing' => 1]);

    	foreach ($emails as $key => $value) {
    		
    		$d = [
    				'id'=>$value->id,
                    'event' => $value->event,
                    ($value->case_id != null) ? 'case_id' : 'user_id' => ($value->case_id != null) ? $value->case_id : $value->user_id,
                    'type' => $value->case_type,
                ];

                $vars=json_decode($value->email_variables,true);

                $attachment=$value->attachment;

                $template=$value->template_id;

                $r=self::brevosendmail($d, $value->to, $template, $vars, $attachment);


    	}

    }
    
    public static function brevosendmail($d, $to, $templateId, $subs = NULL, $file = NULL) {

        $apiKey = env('BREVO_API_KEY');

        $config = Configuration::getDefaultConfiguration()
            ->setApiKey('api-key', $apiKey);

        $apiInstance = new TransactionalEmailsApi(new Client(), $config);

        $params = $subs ?? [];
        $attachments = [];

        if (!empty($file)) {

            if (is_array($file)) {

                foreach ($file as $attach_file) {

                    if (Storage::disk('s3')->exists($attach_file)) {

                        $rawData = Storage::disk('s3')->get($attach_file);
                        $attachments[] = [
                            'content' => base64_encode($rawData),
                            'name'    => basename($attach_file)
                        ];
                    }
                }

            } else { 

                if (Storage::disk('s3')->exists($file)) {

                    $rawData = Storage::disk('s3')->get($file);
                    $attachments[] = [
                        'content' => base64_encode($rawData),
                        'name'    => basename($file)
                    ];
                }
            }
        }


        $sendSmtpEmail = new SendSmtpEmail([
            'to' => [
                ['email' => $to]
            ],
            'templateId' => $templateId,
            'params' => $params,       // dynamic variables
        ]);

        if (!empty($attachments)) {

            $sendSmtpEmail['attachment'] = $attachments;
        }

        if ($to != '') {

            $uemail = $to;

            try {

                $response = $apiInstance->sendTransacEmail($sendSmtpEmail);

                Log::info('Brevo: Email sent', ['to' => $to, 'sg_message_id' => $response->getMessageId()]);
                $messageId=$response->getMessageId();

            $et= self::etrack_data($response, $d, $uemail);
            if($et==true){
                $setprocess=EmailQue::where(['id'=>$d['id'],'is_sent'=>0])->limit(1)->update(['messageId' => $messageId, 'is_processing' => 0,'is_sent'=>1]);
            }

            return $response;

            } catch (Exception $e) {

                Log::error('SendGrid: Email send failed', ['to' => $to, 'message' => $e->getMessage(), ]);
                echo 'Caught exception: ' . $e->getMessage() . "\n";
                self::etrack_data('', $d, $uemail, true);
            }

            return true;

       }else{

       }
    }

    public static function etrack_data($response, $d, $to, $ns = false)
    {
        if ($ns == true) {
            $datainsert = ['sg_message_id' => 'false', 'event' => 'Not sent', (isset($d['case_id'])) ? 'case_id' : 'userid' => (isset($d['case_id'])) ? $d['case_id'] : $d['user_id'], 'casetype' => $d['type'], 'email' => $to, 'status' => '400', 'created_at' => date('Y-m-d H:i:s')];

            EmailTrack::insert($datainsert);
            return true;
        }
        //sent
        $body = $response->body();
        $messageId = $response->getMessageId() ?? null;

        if ($messageId) {

            // status not proving brevo -- using status 200
            $datainsert = ['sg_message_id' => $messageId, 'event' => $d['event'],  (isset($d['case_id'])) ? 'case_id' : 'userid' => (isset($d['case_id'])) ? $d['case_id'] : $d['user_id'], 'casetype' => $d['type'], 'email' => $to, 'status' => "200", 'created_at' => date('Y-m-d H:i:s')];

            $in=EmailTrack::insert($datainsert);

            if($in){
                return true;
            }
        }
    }

}
