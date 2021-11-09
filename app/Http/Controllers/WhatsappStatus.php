<?php

namespace App\Http\Controllers;

use App\Models\WhatsappLog;
use Exception;
use Illuminate\Http\Request;

class WhatsappStatus extends Controller
{
    public function status()
    {

        $json = file_get_contents('php://input');
        // echo ($json);
        // exit;
        $myFile = "wapp_status/testFile" . date('Y-m-d_H:i:s') . ".txt";
        try {
            file_put_contents($myFile, $json);
        } catch (Exception $e) {

            echo $e->getMessage();
        }
        $data = json_decode($json, true);

        if ($data) {


            $request_id = $data['data']['request_uid'];
            $created_time = $data['data']['created_time'];
            $sent_time = $data['data']['sent_time'];
            $delivered_time = $data['data']['delivered_time'];
            $updated_time = $data['data']['updated_time'];
            $status = $data['data']['status'];
            $json = addcslashes($json, "'");


            $log = WhatsappLog::create([
                'request_id' => $request_id,
                'created_time' => $created_time,
                'sent_time' => $sent_time,
                'delivered_time' => $delivered_time,
                'updated_time' => $updated_time,
                'status' => $status,
                'response' => $json
            ]);




            // $sql = "INSERT INTO whatsapp_log (request_id, created_time,sent_time,delivered_time,updated_time,status,response) VALUES ('$request_id', '$created_time', '$sent_time','$delivered_time','$updated_time','$status','$json')";
            if ($log) {
                echo '202';
            }
        }
        exit;
    }
}
