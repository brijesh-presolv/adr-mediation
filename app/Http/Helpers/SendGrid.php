<?php

namespace App\Http\Helpers;

use App\Models\EmailTrack;
use PharIo\Manifest\Email;

Class SendGrid {

    public static $apiKey = "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";

    public function __construct() {
        self::$apiKey = env('SENDGRID_API_KEY', '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');
    }

    public static function send($d, $to, $templateId, $subs = NULL,
            $toName = NULL, $file = NULL) {

            // dd($to);
        $response = '';
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom('admin@presolv360.com', 'Presolv360');
        if (is_array($to)) {    
        foreach($to as $t){
                $email->addTo($t);
        }
        
        } else {
            $email->addTo($to, $toName);
        }
        $email->setTemplateId($templateId);

    

        if (!empty($file)) {

            //attachment
            if (is_array($file)) {
                foreach ($file as $ff) {
                    $attachment = new \SendGrid\Mail\Attachment();

                    $file_encoded = base64_encode(file_get_contents($ff));
                    $filename = basename($ff);
                    $attachment->setType("application/pdf");
                    $attachment->setContent($file_encoded);
                    $attachment->setDisposition("attachment");
                    $attachment->setFilename($filename);
                    $email->addAttachment(
                            $attachment
                    );
                }
            } else {
                $attachment = new \SendGrid\Mail\Attachment();

                $file_encoded = base64_encode(file_get_contents($file));
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
        $sendgrid = new \SendGrid(self::$apiKey);

        try {
            $response = $sendgrid->send($email);
            self::Etrack($response, $d, $to);
        //dd(self::$apiKey);
        } catch (Exception $e) {
        //dd($e);
            echo 'Caught exception: ' . $e->getMessage() . "\n";
            self::Etrack('', $d, $to, true);
        }

        return $response;
    }

    public static function Etrack($response, $d, $to, $ns = false)
    {
        if ($ns == true) {

            if(isset($d['userid'])) {
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

                if(isset($d['userid'])) {
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

}
