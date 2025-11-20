<?php

namespace App\Http\Helpers;

use App\Models\EmailQue;
use App\Models\EmailDirectSend;
use Illuminate\Support\Facades\Storage;
use PharIo\Manifest\Email;
use Illuminate\Support\Facades\Log;

class SendGrid
{

    public static function send(
        $d,
        $to,
        $templateId,
        $subs = NULL,
        $toName = NULL,
        $file = NULL
    ) {

        if ($server_name === 'apiukmediation.presolv360.com') {

            $new_subs = $subs;

            if (is_array($subs) && count($subs) > 0) {
                $new_subs=[];
                foreach ($subs as $key => $value) {
                    $newKey = str_replace('-', '', $key);
                    $new_subs[$newKey] = $value;
                }
            }

        } else {
            $new_subs = $subs;  // fallback for NULL
        }
       
        $all_email = array();

        $all_email[] = $to;

        for ($e = 0; $e < count($all_email); $e++) {
            $arr_e['to'] = trim($all_email[$e]);
            $arr_e['template_id'] = $templateId;
            $arr_e['subject'] = '';
            $arr_e['email_variables'] = json_encode($new_subs);
            $arr_e['attachment'] = '';

            if (is_array($file)) {
                foreach ($file as $av) {
                    $arr_e['attachment'] .= parse_url($av)['path'] . ',';
                }
            } else {
                $arr_e['attachment'] = parse_url($file)['path'];
            }

            $arr_e['event'] = $d['event'];
            if(isset($d['case_id'])) {
                $arr_e['case_id'] = $d['case_id'];
            } else {
                $arr_e['user_id'] = $d['userid'];
            }
            $arr_e['case_type'] = 2;

            EmailQue::insert($arr_e);
        }

        return true;
    }

    public static function Etrack($response, $d, $to, $ns = false)
    {
        if ($ns == true) {

            if (isset($d['userid'])) {
                $datarr = ['sg_message_id' => 'false', 'event' => 'Not sent', 'userid' => $d['userid'],  'email' => $to, 'status' => '400', 'created_at' => date('Y-m-d H:i:s')];
            } else {
                $datarr = ['sg_message_id' => 'false', 'event' => 'Not sent', 'case_id' => $d['case_id'],  'email' => $to, 'status' => '400', 'created_at' => date('Y-m-d H:i:s')];
            }

            EmailTrack::create($datarr);
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

                if (isset($d['userid'])) {
                    $datarr = ['sg_message_id' => trim($idr[1]), 'event' => $d['event'], 'userid' => $d['userid'],  'email' => $to, 'status' => trim($status), 'created_at' => date('Y-m-d H:i:s')];
                } else {
                    $datarr = ['sg_message_id' => trim($idr[1]), 'event' => $d['event'], 'case_id' => $d['case_id'],  'email' => $to, 'status' => trim($status), 'created_at' => date('Y-m-d H:i:s')];
                }
                EmailTrack::create($datarr);

                return true;
            }
        }
        return false;
    }

    public static function directEmailSend($d, $to, $templateId, $subs = NULL, $toName = NULL, $file = NULL) {


        $apiKey = env('SENDGRID_API_KEY');
        $emailSender= env('SENDGRID_SENDER');
        $emailSenderName= env('SENDGRID_SENDER_NAME');
        $emailReply= env('SENDGRID_SETREPLYTo');
        $all_email = array();
        $all_email[] = $to;


        $arr_e['to'] = trim($to);
        $arr_e['template_id'] = $templateId;
        $arr_e['subject'] = '';
        $arr_e['email_variables'] = json_encode($subs);
        $arr_e['attachment'] = '';

        if (is_array($file)) {

            foreach ($file as $av) {
                $arr_e['attachment'] .= parse_url($av)['path'] . ',';
            }

        } else {
            $arr_e['attachment'] = parse_url($file)['path'];
        }

        $arr_e['event'] = $d['event'];

        if(isset($d['case_id'])) {

            $arr_e['case_id'] = $d['case_id'];
        } else {
            $arr_e['user_id'] = $d['userid'];
        }

        $directsendid=EmailDirectSend::insertGetId($arr_e);


        $email = new \SendGrid\Mail\Mail();
        $email->setFrom($emailSender, $emailSenderName);
        if (is_array($to)) {

           foreach ($to as $t) {
                 $email->addTo($t);
            }
        } else {
            $email->addTo($to, $toName);
        }
        $email->setTemplateId($templateId);

             Log::info('SendGrid: Email data inserted line 2');

        if (!empty($file)) {

             //attachment
            if (is_array($file)) {

                foreach ($file as $ff) {

                    $data = Storage::disk('s3')->get($ff);
                    $attachment = new \SendGrid\Mail\Attachment();

                    $file_encoded = base64_encode($data);
                    $filename = basename($ff);
                    $attachment->setType("application/pdf");
                    $attachment->setContent($file_encoded);
                    $attachment->setDisposition("attachment");
                    $attachment->setFilename($filename);
                    $email->addAttachment($attachment);
                }

            } else {


                 $data = Storage::disk('s3')->get($file);

                 $attachment = new \SendGrid\Mail\Attachment();

                $file_encoded = base64_encode($data);
                $filename = basename($file);
                $attachment->setType("application/pdf");
                $attachment->setContent($file_encoded);
                $attachment->setDisposition("attachment");
                 $attachment->setFilename($filename);



                $email->addAttachment(
                    $attachment
                 );
             }
        }

        if ($subs != null) {

           foreach ($subs as $key => $value) {
                $email->addSubstitution($key, $value);
            }
        }
        $sendgrid = new \SendGrid($apiKey);

        $directemailsend=EmailDirectSend::find($directsendid);

         try {

            $response = $sendgrid->send($email);

            $status = $response->statusCode();
            $headers = $response->headers();
            $body = $response->body();

            Log::info('SendGrid: Email sent', ['status' => $status, 'body' => $body]);

            $sendid = @reset(preg_grep('/^X-Message-Id:\s.*/', $headers));

            if ($sendid) {

                $idr = explode(':', $sendid);

                if (isset($idr[1]) and count($d) > 0) {

                    $sgMessageId=trim($idr[1]);

                    $datarr = ['sg_message_id' => trim($idr[1]), 'event' => $d['event'], 'userid' => $d['userid'],  'email' => $to, 'status' => trim($status), 'created_at' => date('Y-m-d H:i:s')];
                    
                    $directemailsend->sg_message_id = trim($idr[1]);
                    $directemailsend->status = trim($status);
                    $directemailsend->response = $body;
                    $directemailsend->is_sent = 1;
                    $directemailsend->updated_at = date('Y-m-d H:i:s');
                    $directemailsend->save();

                    Log::info('SendGrid: Email sent', ['to' => $to, 'sg_message_id' => $sgMessageId]);

                    return true;
                }
            }

         } catch (Exception $e) {


            Log::error('SendGrid: Email send failed', ['to' => $to, 'message' => $e->getMessage(),'body' => $body ?? null, ]);
            echo 'Caught exception: ' . $e->getMessage() . "\n";

            $directemailsend->response = $body;
            $directemailsend->status = trim($status);
            $directemailsend->is_sent = 0;
            $directemailsend->updated_at = date('Y-m-d H:i:s');
            $directemailsend->save();
            
         }

        return $response;
    }

    public static function directEmailSendBrevo($d, $to, $templateId, $subs = NULL, $toName = NULL, $file = NULL) {


        $apiKey = env('SENDGRID_API_KEY');
        $emailSender= env('SENDGRID_SENDER');
        $emailSenderName= env('SENDGRID_SENDER_NAME');
        $emailReply= env('SENDGRID_SETREPLYTo');
        $all_email = array();
        $all_email[] = $to;


        $arr_e['to'] = trim($to);
        $arr_e['template_id'] = $templateId;
        $arr_e['subject'] = '';
        $arr_e['email_variables'] = json_encode($subs);
        $arr_e['attachment'] = '';

        if (is_array($file)) {

            foreach ($file as $av) {
                $arr_e['attachment'] .= parse_url($av)['path'] . ',';
            }

        } else {
            $arr_e['attachment'] = parse_url($file)['path'];
        }

        $arr_e['event'] = $d['event'];

        if(isset($d['case_id'])) {

            $arr_e['case_id'] = $d['case_id'];
        } else {
            $arr_e['user_id'] = $d['userid'];
        }

        $directsendid=EmailDirectSend::insertGetId($arr_e);
        $directemailsend=EmailDirectSend::find($directsendid);

        $config = Configuration::getDefaultConfiguration()
            ->setApiKey('api-key', env('BREVO_API_KEY'));

        $apiInstance = new TransactionalEmailsApi(new Client(), $config);

        $params = [];

        foreach ($variables as $key => $value) {
            if (!empty($value)) {
                $params[$key] = $value;
            }
        }


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
            'attachment' => $attachments, // PDF optional
        ]);


         try {

            $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
            Log::info('Brevo: Email sent', ['to' => $to, 'sg_message_id' => $result['messageId']]);
            return ['success' => true, 'data' => $result];

         } catch (Exception $e) {

            Log::error('SendGrid: Email send failed', ['to' => $to, 'message' => $e->getMessage(), ]);
            echo 'Caught exception: ' . $e->getMessage() . "\n";
            $directemailsend->is_sent = 0;
            $directemailsend->updated_at = date('Y-m-d H:i:s');
            $directemailsend->save();
            
         }

        return $response;
    }
}
