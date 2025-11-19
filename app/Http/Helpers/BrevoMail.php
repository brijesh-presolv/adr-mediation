<?php

namespace App\Http\Helpers;

use App\Models\EmailQue;
use App\Models\EmailDirectSend;
use Illuminate\Support\Facades\Storage;
use PharIo\Manifest\Email;
use Illuminate\Support\Facades\Log;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Model\SendSmtpEmail;
use GuzzleHttp\Client;

class BrevoMail
{

    public static function directEmailSend($d, $to, $templateId, $subs = NULL, $toName = NULL, $file = NULL) {

        $apiKey = env('BREVO_API_KEY');
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


         try {

            $response = $apiInstance->sendTransacEmail($sendSmtpEmail);
            Log::info('Brevo: Email sent', ['to' => $to, 'sg_message_id' => $response->getMessageId()]);
            $directemailsend->is_sent = 1;
            $directemailsend->response = $response;
            $directemailsend->updated_at = date('Y-m-d H:i:s');
            $directemailsend->save();

            return true;

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
