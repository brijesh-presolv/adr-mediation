<?php

namespace App\Http\Helpers;



Class SendGrid

{


public static $apiKey='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';

public static function send($to, $templateId, $subs=NULL, $senderName = NULL, 
$toName = NULL, $file = NULL)
{

    
    $response = '';
    $email = new \SendGrid\Mail\Mail();
    $email->setFrom('prashant@bombayblokes.com','pdmo24.pl');
    $email->addTo($to , $toName);
    $email->setTemplateId($templateId);



    if(!empty($file))
    {

        //attachment
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

    if($subs!=null){

        foreach ($subs as $key => $value) {
            $email->addSubstitution($key,$value);
        }
    }
    $sendgrid = new \SendGrid(self::$apiKey);

    try {
        $response = $sendgrid->send($email);

    } catch (Exception $e) {
        echo 'Caught exception: '.  $e->getMessage(). "\n";
    }

    return $response;
}

}