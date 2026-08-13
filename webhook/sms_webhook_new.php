<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
 error_reporting(E_ALL);
// $projectpath = dirname(dirname(__FILE__));


// include_once $projectpath . '/config/constants.php';
// include($projectpath . '/functions/dbconnect.php');
// include_once $projectpath . '/libs/curl.php';
$projectpath = $_SERVER['DOCUMENT_ROOT'];
include_once $projectpath . '/config/constants.php';
include_once $projectpath . '/app/Http/Helpers/Curl.php';


$json = file_get_contents('php://input');

// echo 'hello';
// echo '<prE>';print_r($json);exit;

// $request = $_REQUEST["data"];
// $json = json_decode($request,true);

$dir = "sms_log_latest/"; 
$myFile = $dir . "log" . date('Y-m-d_H:i:s') . '_' . rand(9, 9999999) . ".txt";


//  $file = fopen($myFile,'w+') or die("File not found");
//     fwrite($file, $json);
//     fclose($file);exit;

try {
   // file_put_contents($myFile,$json);
   if ($json && file_put_contents($myFile, $json)) {

      smsBotLogAsync($myFile, function () {
         echo "sms completed asynchronously-----.<br>";
      });
   } else {
      // echo count(glob($dir."/*"));
      // exit;
      if (is_dir($dir) && count(glob($dir . "/*")) != 0) {
         if ($dh = opendir($dir)) {
            $icnt = 0;
            while (($file = readdir($dh)) !== false) {
               if ($file != "." && $file != "..") {
                  echo $icnt;
                  smsBotLogAsync($dir . $file, function () {

                     echo "sms completed asynchronously.<br>";
                  });
                  $icnt++;
               }
            }
            closedir($dh);
         }
      } else {
         echo "<==folder is empty==! <br>";
      }
   }
} catch (Exception $e) {

   echo $e->getMessage();
}
echo 'ok';

exit();

function smsBotLogAsync($myFile, $callback)
{
   // Simulate asynchronous whatsappbotlog execution
   smsBotlog($myFile);

   $callback();
}

function smsBotlog($myFile)
{

   $db_username = 'meduser';

$db_password = 'vD8gmE3EoBHmHAnjUgk4pj';

$db_name = 'mediation';

$db_host = '13.232.40.128';



$db_var = new mysqli($db_host, $db_username, $db_password,$db_name);

 //  global $db_var;
   $token = '';
   //$curl = new Curl();


   $fh = file_get_contents($myFile);

   // echo $fh;exit;
   $json = $fh;
   $json = str_replace("data=", "", $json);
   $jsonData = json_decode($json, true);

   // echo '<pre>';
   // print_r($jsonData);
   // exit;
   $fh = addcslashes($fh, "'");

   if (!empty($jsonData)) {

      foreach ($jsonData as $key => $value) {
         // request id
         $requestID = $value['requestId'];
         $userId = $value['userId'];
         $senderId = $value['senderId'];
         $datetime = date('Y-m-d H:i:s');
         foreach ($value['report'] as $key1 => $value1) {
            //detail description of report
            $desc = $value1['desc'];
            // status of each number
            $status = $value1['status'];
            // destination number
            $receiver = $value1['number'];
            //delivery report time
            $date = $value1['date'];
            $failedReason = $value1['failedReason'];

            $query = "INSERT INTO sms_status (request_id,user_id,sender_id,date,receiver,status,status_description,failedReason, response_data,sent_time,delivered_time,updated_time,created_at) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
             //echo "queru-->". $query;
            // exit;
             $stmt = $db_var->prepare($query) or die(mysqli_error($db_var));
             $stmt->bind_param("sssssssssssss", $requestID, $userId, $senderId, $date, $receiver, $status, $desc, $failedReason, $fh, $datetime, $datetime, $datetime, $datetime);
             $stmt->execute();
             $result = $stmt;
            // $result = mysqli_query($db_var, $query);

           


                      
                    $curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "https://api.msg91.com/api/v2/logs",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",  
  CURLOPT_HTTPHEADER => [ 'authkey: 446869AcMa6eCNF68063658P1', 'Content-Type: application/json' ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

            if ($result) {
               // unlink($myFile);
               echo "Query run successfully <br>";
            } else {
               echo "Query not run <br>";
            }
         }
      }
   } else {
      echo "data not found <br>";
   }
}