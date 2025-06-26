<?php

namespace App\Http\Helpers;

use App\Models\EmailQue;
use App\Models\EmailTrack;
use Illuminate\Support\Facades\Storage;
use PharIo\Manifest\Email;

class SendGrid
{

    // public static $apiKey = "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";

    // public function __construct()
    // {
    //     self::$apiKey = env('SENDGRID_API_KEY', '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');
    // }

    public static function send(
        $d,
        $to,
        $templateId,
        $subs = NULL,
        $toName = NULL,
        $file = NULL
    ) {


        $all_email = array();

        // if (!empty($o)) {
        //     // $all_email = explode(",", $o);
        //     $all_email[] = $to;
        // } else {
            $all_email[] = $to;
        // }

        for ($e = 0; $e < count($all_email); $e++) {
            $arr_e['to'] = trim($all_email[$e]);
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

            //$arr_e['attachment']=((is_array($a))?json_encode($a):$a);
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

        // dd(basename($file));

        // $response = '';
        // $email = new \SendGrid\Mail\Mail();
        // $email->setFrom('admin@presolv360.com', 'Presolv360');
        // if (is_array($to)) {
        //     foreach ($to as $t) {
        //         $email->addTo($t);
        //     }
        // } else {
        //     $email->addTo($to, $toName);
        // }
        // $email->setTemplateId($templateId);



        // if (!empty($file)) {

        //     //attachment
        //     if (is_array($file)) {
        //         foreach ($file as $ff) {
        //             $data = Storage::disk('s3')->get($ff);
        //             $attachment = new \SendGrid\Mail\Attachment();

        //             $file_encoded = base64_encode($data);
        //             $filename = basename($ff);
        //             $attachment->setType("application/pdf");
        //             $attachment->setContent($file_encoded);
        //             $attachment->setDisposition("attachment");
        //             $attachment->setFilename($filename);
        //             $email->addAttachment(
        //                 $attachment
        //             );
        //         }
        //     } else {
        //         $data = Storage::disk('s3')->get($file);

        //         $attachment = new \SendGrid\Mail\Attachment();

        //         $file_encoded = base64_encode($data);
        //         $filename = basename($file);
        //         $attachment->setType("application/pdf");
        //         $attachment->setContent($file_encoded);
        //         $attachment->setDisposition("attachment");
        //         $attachment->setFilename($filename);



        //         $email->addAttachment(
        //             $attachment
        //         );
        //     }
        // }

        // if ($subs != null) {

        //     foreach ($subs as $key => $value) {
        //         $email->addSubstitution($key, $value);
        //     }
        // }
        // $sendgrid = new \SendGrid(self::$apiKey);

        // try {
        //     $response = $sendgrid->send($email);
        //     self::Etrack($response, $d, $to);
        //     //dd(self::$apiKey);
        // } catch (Exception $e) {
        //     //dd($e);
        //     echo 'Caught exception: ' . $e->getMessage() . "\n";
        //     self::Etrack('', $d, $to, true);
        // }

        // return $response;
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
}
