<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Boot Laravel so this endpoint uses the framework's .env-backed DB connection
// instead of credentials embedded in the file.
require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

$json = file_get_contents('php://input');

$dir = __DIR__ . "/sms_log_latest/";
$myFile = $dir . "log" . date('Y-m-d_H-i-s') . '_' . bin2hex(random_bytes(4)) . ".txt";

try {
   if ($json && file_put_contents($myFile, $json)) {

      smsBotlog($myFile);
   } else {
      if (is_dir($dir) && count(glob($dir . "/*")) != 0) {
         if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
               if ($file != "." && $file != "..") {
                  smsBotlog($dir . $file);
               }
            }
            closedir($dh);
         }
      }
   }
} catch (Exception $e) {
   Log::error('SMS webhook failed', ['message' => $e->getMessage()]);
}

echo 'ok';

exit();

function smsBotlog($myFile)
{
   $fh = file_get_contents($myFile);

   $json = str_replace("data=", "", $fh);
   $jsonData = json_decode($json, true);

   if (empty($jsonData)) {
      return;
   }

   $datetime = date('Y-m-d H:i:s');

   foreach ($jsonData as $value) {

      $requestID = $value['requestId'];
      $userId = $value['userId'];
      $senderId = $value['senderId'];

      foreach ($value['report'] as $value1) {

         // Bound parameters — the payload is attacker-controlled and must never
         // be concatenated into the statement.
         DB::table('sms_status')->insert([
            'request_id'         => $requestID,
            'user_id'            => $userId,
            'sender_id'          => $senderId,
            'date'               => $value1['date'],
            'receiver'           => $value1['number'],
            'status'             => $value1['status'],
            'status_description' => $value1['desc'],
            'failedReason'       => $value1['failedReason'],
            'response_data'      => $fh,
            'sent_time'          => $datetime,
            'delivered_time'     => $datetime,
            'updated_time'       => $datetime,
            'created_at'         => $datetime,
         ]);
      }
   }
}
