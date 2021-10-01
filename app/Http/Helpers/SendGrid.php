<?php

namespace App\Http\Helpers;

Class SendGrid {

    public static $apiKey = "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";

    public function __construct() {
        self::$apiKey = env('SENDGRID_API_KEY', '');
    }

    public static function send($to, $templateId, $subs = NULL,
            $toName = NULL, $file = NULL) {


        $response = '';
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom('info@pdm24.pl', 'pdm24.pl');
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
        //dd(self::$apiKey);
        } catch (Exception $e) {
        //dd($e);
            echo 'Caught exception: ' . $e->getMessage() . "\n";
        }

        return $response;
    }

}
