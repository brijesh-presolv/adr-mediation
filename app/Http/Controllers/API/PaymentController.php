<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Session;
use App\Http\Helpers\SendGrid;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Models\MedCase;
use App\Models\PaymentGatewayData;
use App\Models\SettlementPayment;
use Illuminate\Http\Response;
use App\Http\Helpers\Payment;
use DB;
use App\Models\RestructureData;
use PDF;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\File;
use PDFMerger;
use App\Http\Traits\UploadTrait;
use App\Http\Helpers\Common_function;
use Illuminate\Support\Facades\Storage;
use App\Models\CaseReply;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\OtpCode;
use App\Models\InvoledUser;
use App\Http\Helpers\Whatsapp;
use App\Models\Reminder;
use App\Models\WaTemplate;
use App\Models\WhatsappLog;
use App\Models\WhatsappChatbot;
use App\Models\WhatsappBotReport;





class PaymentController extends Controller
{
    use UploadTrait;

      public function __construct()
      {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
        header('Access-Control-Allow-Headers: Content-Type');
  
      }

    public function index()
    {

        return view('welcome');
    }

    public function downloads3file(Request $request)
    {
        

        $finalFilePath = 'mediation_documents/mediation/93425/Invitation_mediate_M093425.pdf' ;
        $localFilePath = 'pdf/filename.pdf';
        $s3Client = Storage::cloud()->getAdapter()->getClient();

        $fileContent = $s3Client->getObject([
            'Bucket' => env('AWS_BUCKET'),
            'Key'    => $finalFilePath,
            'SaveAs' => storage_path('app/' . $localFilePath),
        ])['Body']->getContents();

            // Return the file as a response
           /*  return response($fileContent, 200, [
              'Content-Type' => 'application/pdf',
              'Content-Disposition' => 'inline; filename="filename.pdf"',
          ]); */
      $fileContent = storage_path('app/' . $localFilePath);
      $result['code'] = 200;
      $result['response']='success';
      $result['responseData']=['filename'=>$fileContent];
      echo json_encode($result);
      exit;

    }

    public function checkPaymentAvl(){ 
      

       // echo "brijesh";die();
   
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == "POST") {
    
          $notice_id= $this->post['notice_id'];
          if(isset($notice_id)){   
            
              $payToken= $this->post['token'];
    
                try {
    
                $tableeName ='';
                $notice_result = $this->BaseModel->getRowByField('adminNotice', 'notice_id', $notice_id);
                $notice_tableName = "notice_" . $notice_result[0]->notice_type;
                $notice_tableName = str_replace(" ", "_", $notice_tableName);
    
                $noticeData=$this->db->query("select * from ".$notice_tableName." where payToken ='".$payToken."'")->result();
    
                  if(!empty($noticeData)){
  
                    $payData=$this->db->query("select id, notice_unq_id, total_amt from settlement_payments where payToken ='".$payToken."' and pay_status ='success'")->result();
  
                      $today_for = new DateTime();
                      $PayLinkExpire = $noticeData[0]->PayLinkExpire; // Replace with your expiry date
                      $currentDate=$today_for->format('Y-m-d');
                      if (strtotime($PayLinkExpire) > strtotime($currentDate) && count($payData)=="0") {
  
                        $result['code']=200;
                        $result['message']='success';//unauthorised
                        //$result['responseData']=$laent_offers_data;
                        $result['responseData']="1";
                        echo json_encode($result);
                        exit;
  
                      
                     }else{
  
                      $result['code']=404;
                      $result['message']='No Data Found';//unauthorised
                      $result['response']='error';
                      echo json_encode($result);
                      exit;
                     }
                  }
                  else
                  {
                    $result['code']=404;
                    $result['message']='No Data Found';//unauthorised
                    $result['response']='error';
                    echo json_encode($result);
                    exit;
                  }
    
                  
                }
                catch (Exception $e) 
                { 
                  $result['code'] = 500;
                  $result['message'] = "Something Went Wrong";
                  $result['response']='error';
                  echo json_encode($result);
                  exit;
                }
              
          }
          else 
          {
                $result['code']=404;//unauthorised
                $result['message']='Notice id not found';//unauthorised
                $result['response']='error';
                echo json_encode($result);
                exit;
          }
        }else{
  
          $output['code']=404;//unauthorised
          $output['message']='Method Not Found';//unauthorised
          $output['response']='error';
          echo json_encode($output);
          exit;
    
        }
      
      }

    public function getcasedetails(Request $request){

      $method = $_SERVER['REQUEST_METHOD'];
      if($method == "POST") {

          $caseid= $request->input('caseid');
          $payToken=  $request->input('payToken');
          if(empty($caseid)) {

              $result['code'] = 500;
              $result['message'] = "Invalid Request";
              $result['response'] = 'error';
              echo json_encode($result);
              exit;
          }
          if (isset($payToken)) {
              try { 
                    $caseData = MedCase::select("*")->where("id", $caseid)->where("payToken", $payToken)->first();

                    if(!empty($caseData)){

                       $invit_file = DB::table('invitation_files')->select('*')->where('case_id', $caseid)->orderBy('id', 'desc')->first();

                      if(!empty($invit_file)){

                        $name = $invit_file->file_name;
                        $savePath = 'mediation_documents/mediation/' . $caseid;
                        $finalFilePath = $savePath . '/' . $name;
                       // $invit_file_name = Storage::disk('s3')->url($finalFilePath);
                        
                       // $finalFilePath = 'mediation_documents/mediation/93425/Invitation_mediate_M093425.pdf';
                        $invit_file_name=$this->getPreSignedUrl($finalFilePath, 15, [
                          'ResponseContentDisposition' => 'inline; filename="file.pdf"', // Set filename for inline display
                          'ContentType' => 'application/pdf', // Set the content type
                      ]);
                        $localFilePath = 'pdf/filename.pdf';

                        /* $s3Client = Storage::cloud()->getAdapter()->getClient();

                        $stream = $s3Client->getObject([
                            'Bucket' => env('AWS_BUCKET'),
                            'Key'    => $finalFilePath,
                            'SaveAs' => storage_path('app/' . $localFilePath),
                        ]);

                        $fileContent = storage_path('app/' . $localFilePath); */


                        $result['code'] = 200;
                        $result['response']='success';
                        $result['responseData']=['caseid'=> sprintf('%06d', $caseid), 'casedataData'=>$caseData, 'filename'=>$invit_file_name];
                        echo json_encode($result);
                        exit;

                      }else{

                        $result['code'] = 404;
                        $result['message'] = "Data Not Found";
                        $result['response']='error';
                        echo json_encode($result);
                        exit;

                      }

                    }else{
                      $result['code'] = 404;
                      $result['message'] = "Data Not Found";
                      $result['response']='error';
                      echo json_encode($result);
                      exit;

                    }
              }
              catch (Exception $e)  { // Also tried JwtException
                  $result['code'] = 500;
                  $result['message'] = "Something Went Wrong";
                  $result['response']='error';
                  echo json_encode($result);
                  exit;
              }
          }else {
              $result['code'] = 500;
              $result['message'] = "Invalid Request";
              $result['response']='error';
              echo json_encode($result);
              exit;
          }

      }else {
          $output['code']=404;//unauthorised
          $output['message']='Method Not Found';//unauthorised
          $output['response']='error';
          echo json_encode($output);
          exit;
      }
    }

    public function casereplydetails(Request $request){

      $method = $_SERVER['REQUEST_METHOD'];
      if($method == "POST") {

          $caseid= $request->input('caseid');
          $payToken=  $request->input('payToken');
          if(empty($caseid)) {

              $result['code'] = 500;
              $result['message'] = "Invalid Request";
              $result['response'] = 'error';
              echo json_encode($result);
              exit;
          }
          if (isset($payToken)) {
              try { 
                    $caseData = MedCase::select("*")->where("id", $caseid)->where("payToken", $payToken)->first();

                   // print_r($caseData);die();

                    if(!empty($caseData)){

                       $invit_file = DB::table('invitation_files')->select('*')->where('case_id', $caseid)->orderBy('id', 'desc')->first();
                      // print_r($invit_file);die();
                      $respondentdata=SettlementPayment::getrespondent($caseid);

                      if(!empty($invit_file)){

                        $name = $invit_file->file_name;
                        $savePath = 'mediation_documents/mediation/' . $caseid;
                        $finalFilePath = $savePath . '/' . $name;
                        $invit_file_name = Storage::disk('s3')->url($finalFilePath);
                        //$invit_file_name = $this->getPreSignedUrl(urldecode($finalFilePath), 15);
                        //$contenturl = parse_url($finalFilePath);
                        //$invit_file_name = $this->getPreSignedUrl($finalFilePath, 15);
                        //print_r($invit_file_name);die();
                        $case_id="M".sprintf('%06d', $caseid);

                        $result['code'] = 200;
                        $result['response']='success';
                        $result['responseData']=['caseid'=>$case_id , 'casedataData'=>$caseData, 'filename'=>$invit_file_name, 'party_name'=>$respondentdata->name];
                        echo json_encode($result);
                        exit;

                      }else{

                        $result['code'] = 404;
                        $result['message'] = "Data Not Found";
                        $result['response']='error';
                        echo json_encode($result);
                        exit;

                      }

                    }else{
                      $result['code'] = 404;
                      $result['message'] = "Data Not Found";
                      $result['response']='error';
                      echo json_encode($result);
                      exit;

                    }
              }
              catch (Exception $e)  { // Also tried JwtException
                  $result['code'] = 500;
                  $result['message'] = "Something Went Wrong";
                  $result['response']='error';
                  echo json_encode($result);
                  exit;
              }
          }else {
              $result['code'] = 500;
              $result['message'] = "Invalid Request";
              $result['response']='error';
              echo json_encode($result);
              exit;
          }

      }else {
          $output['code']=404;//unauthorised
          $output['message']='Method Not Found';//unauthorised
          $output['response']='error';
          echo json_encode($output);
          exit;
      }
    }
  
  
    public function payNowProcessed(Request $request){  
      

          $method = $_SERVER['REQUEST_METHOD'];

         // print_r($request['caseid']);die();
         if($method == "POST") {
  
              //$caseid= $request->input('caseid');
             // $payToken=  $request->input('payToken');

              $caseid= $request['caseid'];
              $payToken=  $request['payToken'];
  
              if (empty($caseid)){

                $result['code'] = 200;
                $result['message'] = "Invalid Request";
                $result['response']='error';
                echo json_encode($result);
                exit;
              }
             if(empty($payToken)) {

                 $result['code'] = 500;
                 $result['message'] = "Invalid Request";
                 $result['response'] = 'error';
                 echo json_encode($result);
                 exit;
             }
             if (isset($payToken)) {

                 try { 

                     $check_payment_gateway_data = PaymentGatewayData::select("*")->where("userid", "=", "78")->where("is_active", "=", 1)->first();

                    if(empty($check_payment_gateway_data)) {
                      $result['code'] = 500;
                      $result['message'] = "Payment Gateway not available";
                      $result['response'] = 'error';
                      echo json_encode($result);
                      exit;
                    }
                    $caseData = MedCase::select("*")->where("id", "=", $caseid)->where("payToken", "=", $payToken)->first();
                    if(empty($caseData)) {

                      $result['code'] = 500;
                      $result['message'] = "Case not found";
                      $result['response'] = 'error';
                      echo json_encode($result);
                      exit;
                    }

                    $restructuredata=RestructureData::select('*')->where('caseid', $caseid)->first();

                if(!empty($restructuredata)) {

                  $result['code'] = 500;
                  $result['message'] = "Already requested for the case restructure";
                  $result['response'] = 'error';
                  echo json_encode($result);
                  exit;

                }
                
                    $checkpayment = SettlementPayment::select("*")->where("caseid", "=", $caseid)->where("payToken", "=", $payToken)->where("pay_status", "=", "success")->get();
                    $PayLinkExpire = $caseData->PayLinkExpire; 
                    $currentDate=date('Y-m-d H:i:s');
                    if (strtotime($PayLinkExpire) > strtotime($currentDate)) {

                      if(count($checkpayment) == 0){
                        $respondentdata=SettlementPayment::getrespondent($caseData->id);
                        $customer_name=$respondentdata->name;
                        $customer_email=$respondentdata->userEmail;
                        $discount="";
                        $length_of_string=16;
                        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                        $str_token=substr(str_shuffle($str_result),  0, $length_of_string);
                        $payment_req_token=$str_token."_".$caseData->id;
                        $payment_security_token=$caseData->id.$str_token;

                        $total_amt=$caseData->amount;

                        $original_price = $caseData->amount; // set original price
                        $payArr['caseid']=$caseData->id;
                        $payArr['customer_name']=$customer_name;
                        $payArr['customer_email']=$customer_email;
                        $payArr['actual_amt']=$original_price;
                        $payArr['discount_amt']=$discount;
                        $payArr['total_amt']=$total_amt;
                        $payArr['pay_status']="process";
                        //$payArr['created_at']=date('Y-m-d H:i:s');
                        $payArr['payToken']=$payToken;
                        $payArr['payment_req_token']=$payment_req_token;
                        $payArr['payment_req_created']=date('Y-m-d H:i:s');
                        $payArr['payment_security_token']=$payment_security_token;
                        $insert_data=SettlementPayment::create($payArr);
                        if($insert_data){
                          $_SESSION['payment_req_token'] = $payment_req_token; 
                          $_SESSION['payment_security_token'] = $payment_security_token; 
                          $_SESSION['pay_caseid'] = $caseData->id;
                          $purpose="SEBI Case Payment Case Id";

                                                    $claimantdata2 = InvoledUser::select('user_involved_in_agreement.*', 'users.organization')->where('isClaimant', 0)->leftJoin('users', 'users.id', '=', 'user_involved_in_agreement.userId')->where('userPlanId', $caseid)->first();
                          $claimant_name2=$claimantdata2->name;
                          $claimant_email=$claimantdata2->userEmail;
                          $respondentdata=SettlementPayment::getrespondent($caseid);
                          $respondent_name=$respondentdata->name;
                          $respondent_email=$respondentdata->userEmail;

                          $botlogdata=[
                              'caseid' => $caseData->id,
                              'respondent_name' =>  $respondent_name,
                              'respondent_email' => $respondent_email,
                              'claimant_name' => $claimant_name2,
                              'claimant_email' => $claimant_email,
                              'event' => "PRESS_PAY_NOW",
                              'created_at' => date('Y-m-d H:i:s')
                          ];

                         $botlogsave= WhatsappBotReport::insert($botlogdata);
                         // $payresult=Payment::initiatePayment($purpose, $total_amt, $customer_name, $customer_email);
                         //$payresult['response']=='success'
                          if($botlogsave){

                         /*    $SettlementPayment=SettlementPayment::find($insert_data->id);
                            $SettlementPayment->payment_request_id=$payresult['data']['payment_request_id'];
                            $SettlementPayment->payment_req_created=$payresult['data']['payment_req_created'];
                            $SettlementPayment->payment_url=$payresult['data']['payment_url'];
                            $SettlementPayment->save();
                            $payment_url=$payresult['data']['payment_url']; */
                            $created_apy_link=$caseData->PayLink;

                            $result['message'] = "Payment request created";
                            $result['response']='success';
                            $result['data'] = ['caseid'=> $caseData->id, 'total_amt'=>$total_amt, 'payment_req_token'=> $payment_req_token, 'created_apy_link'=>$created_apy_link];
                            $result['code'] = 200;
                            echo json_encode($result);
                            exit;

                          }else{

                            $result['message'] = "Payment not creating";
                            $result['response']='error';
                            $result['code'] = 200;
                            echo json_encode($result);
                            exit;
                            

                          }
                          
                        }else{
                            $result['message'] = "Something Went Wrong";
                            $result['response']='error';
                            echo json_encode($result);
                            exit;
                        }
    
                      }else{
    
                        $result['message'] = "Payment already received";
                        $result['response']='error';
                        echo json_encode($result);
                        exit;
    
                      }
                    }else{

                        $result['message'] = "Payment Link Expired.";
                        $result['response']='error';
                        echo json_encode($result);
                        exit;

                    }
                 }
                 catch (Exception $e)  { // Also tried JwtException
                     $result['code'] = 500;
                     $result['message'] = "Something Went Wrong";
                     $result['response']='error';
                     echo json_encode($result);
                     exit;
                 }
             }else {
                 $result['code'] = 500;
                 $result['message'] = "Invalid Request";
                 $result['response']='error';
                 echo json_encode($result);
                 exit;
             }
  
         }else {
             $output['code']=404;//unauthorised
             $output['message']='Method Not Found';//unauthorised
             $output['response']='error';
             echo json_encode($output);
             exit;
         }   
    }

    public function paymentSuccess(Request $request)
    {
      
        $paymentId = $request->input('payment_id');
        $buyerName = $request->input('buyer_name');
        $buyerEmail = $request->input('buyer_email');
        $payment_status = $request->input('payment_status');
        $payment_request_id = $request->input('payment_request_id');

        $paystatus=Payment::paymentStatus($payment_request_id);
        $paymentdata=SettlementPayment::select('*')->where('payment_request_id', $payment_request_id)->first();
        if(!empty($paymentdata)){

          if($paystatus['response']=='success'){

            $dateString = $paystatus['data']['payment_completed_at'];
            $carbonDate = Carbon::parse($dateString);
              // Convert to a different format
              $formattedDate = $carbonDate->format('Y-m-d H:i:s');
            $payment_request_id = $paystatus['data']['payment_request_id'];
            $amount = $paystatus['data']['amount'];
            $buyer_name = $paystatus['data']['buyer_name'];
            $payment_status = $paystatus['data']['payment_status'];
            $payment_id=$paystatus['data']['payment_id'];
            $payment_data=json_encode($paystatus['data']['payment_data']);
            $payment_completed_at = $formattedDate;
            $SettlementPayment=SettlementPayment::where('payment_request_id', $payment_request_id)->first();
            $SettlementPayment->pay_status=$payment_status;
            $SettlementPayment->payment_id=$payment_id;
            $SettlementPayment->success_at=$payment_completed_at;
            $SettlementPayment->payment_data=$payment_data;
            $result=$SettlementPayment->save();

            if($result){

              $data['caseid']=sprintf('%06d', $paymentdata->caseid);
              $data['payment_id']=$payment_id;
              $data['paymentdata']=$paymentdata;

              $pdf = PDF::loadView('payment.settlement_letter_pdf', $data);
              $name = 'settlement_letter_M' . sprintf('%06d', $paymentdata->caseid) . time() . '.pdf';
              $savePath = 'mediation_documents/mediation/settlementletter' . $paymentdata->caseid;
              $finalFilePath = $savePath . '/' . $name;
              $uploadS3 = $this->uploadOnAWSDirect($finalFilePath, $savePath, $pdf);
              $settlementletterpdf = Storage::disk('s3')->url($finalFilePath);

              $localFilePath = 'pdf'.$name;
              $s3Client = Storage::cloud()->getAdapter()->getClient();
              $stream = $s3Client->getObject([
                  'Bucket' => env('AWS_BUCKET'),
                  'Key'    => $finalFilePath,
                  'SaveAs' => storage_path('app/' . $localFilePath),
              ]);

            }

          }

          
         $amount = $request->input('amount');
          return view('payment.success', [
              'paymentId' => $paymentId,
              'buyerName' => $buyerName,
              'buyerEmail' => $buyerEmail,
              'amount' => $amount,
          ]);


      }else{

              $result['code'] = 500;
              $result['message'] = "Invalid Request";
              $result['response']='error';
              echo json_encode($result);
              exit;
      }

    }

    public function paymentWebhook(Request $request)
    {
        // Verify Instamojo signature to ensure the request is legitimate
        $instamojoSignature = $request->header('X-Instamojo-Signature');
        $isValidSignature = $this->validateInstamojoWebhookSignature($request, $instamojoSignature);

        if (!$isValidSignature) {
            Log::warning('Invalid Instamojo webhook signature');
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Process the webhook payload
        $payload = json_decode($request->getContent(), true);

        // Log the payload for debugging (you may want to log to a file or database)
        Log::info('Instamojo Webhook Payload:', $payload);

        // Handle different event types as needed
        $eventType = $payload['type'];

        switch ($eventType) {
            case 'payment.authorized':
                // Payment authorized event
                $paymentId = $payload['payment_id'];
                Log::info('Payment Authorized: ' . $paymentId);
                // Process authorized payment, update status, etc.
                break;

            case 'payment.failed':
                // Payment failed event
                $paymentId = $payload['payment_id'];
                Log::info('Payment Failed: ' . $paymentId);
                // Handle failed payment, update status, etc.
                break;

            // Add more cases for other events as needed...

            default:
                Log::info('Unhandled Instamojo webhook event type: ' . $eventType);
                break;
        }

        // Return a response to Instamojo
        return response()->json(['status' => 'success']);
    }

    // Helper method to validate Instamojo webhook signature
    private function validateInstamojoWebhookSignature(Request $request, $expectedSignature)
    {
        // Obtain your Instamojo API key and auth token from your config
        $instamojoApiKey = config('services.instamojo.api_key');
        $instamojoAuthToken = config('services.instamojo.auth_token');

        // Concatenate API key, auth token, and request body
        $data = $instamojoApiKey . $instamojoAuthToken . $request->getContent();

        // Calculate the expected signature using HMAC SHA-1
        $calculatedSignature = hash_hmac('sha1', $data, $instamojoAuthToken);

        // Compare calculated signature with the expected signature
        return hash_equals($calculatedSignature, $expectedSignature);
    }
    
    public function payDirect(Request $request){  
      

      $method = $_SERVER['REQUEST_METHOD'];

     // print_r($method);die();
     if($method == "POST") {

          $caseid= $request->input('caseid');
          $payToken=  $request->input('payToken');

          if (empty($caseid)){

            $result['code'] = 200;
            $result['message'] = "Invalid Request";
            $result['response']='error';
            echo json_encode($result);
            exit;
          }
         if(empty($payToken)) {

             $result['code'] = 500;
             $result['message'] = "Invalid Request";
             $result['response'] = 'error';
             echo json_encode($result);
             exit;
         }
         if (isset($payToken)) {

             try { 

                 $check_payment_gateway_data = PaymentGatewayData::select("*")->where("userid", "=", "78")->where("is_active", "=", 1)->first();

                if(empty($check_payment_gateway_data)) {
                  $result['code'] = 500;
                  $result['message'] = "Payment Gateway not available";
                  $result['response'] = 'error';
                  echo json_encode($result);
                  exit;
                }
                $caseData = MedCase::select("*")->where("id", "=", $caseid)->where("payToken", "=", $payToken)->first();

               // print_r($caseData);die();
                if(empty($caseData)) {

                  $result['code'] = 500;
                  $result['message'] = "Case not found";
                  $result['response'] = 'error';
                  echo json_encode($result);
                  exit;
                }

                $restructuredata=RestructureData::select('*')->where('caseid', $caseid)->first();

                if(!empty($restructuredata)) {

                  $result['code'] = 500;
                  $result['message'] = "Already requested for the case restructure";
                  $result['response'] = 'error';
                  echo json_encode($result);
                  exit;

                }

                $checkpayment = SettlementPayment::select("*")->where("caseid", "=", $caseid)->where("payToken", "=", $payToken)->where("pay_status", "=", "success")->get();
                $PayLinkExpire = $caseData->PayLinkExpire; 
                $currentDate=date('Y-m-d H:i:s');
                if (strtotime($PayLinkExpire) > strtotime($currentDate)) {

                  if(count($checkpayment) == 0){
                    $respondentdata=SettlementPayment::getrespondent($caseData->id);
                    $customer_name=$respondentdata->name;
                    $customer_email=$respondentdata->userEmail;
                    $discount="";
                    $length_of_string=16;
                    $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                    $str_token=substr(str_shuffle($str_result),  0, $length_of_string);
                    $payment_req_token=$str_token."_".$caseData->id;
                    $payment_security_token=$caseData->id.$str_token;

                    $total_amt=$caseData->amount;

                    $original_price = $caseData->amount; // set original price
                    $payArr['caseid']=$caseData->id;
                    $payArr['actual_amt']=$original_price;
                    $payArr['discount_amt']=$discount;
                    $payArr['total_amt']=$total_amt;
                    $payArr['pay_status']="process";
                    //$payArr['created_at']=date('Y-m-d H:i:s');
                    $payArr['payToken']=$payToken;
                    $payArr['payment_req_token']=$payment_req_token;
                    $payArr['payment_req_created']=date('Y-m-d H:i:s');
                    $payArr['payment_security_token']=$payment_security_token;
                    $insert_data=SettlementPayment::create($payArr);
                    if($insert_data){
                      $_SESSION['payment_req_token'] = $payment_req_token; 
                      $_SESSION['payment_security_token'] = $payment_security_token; 
                      $_SESSION['pay_caseid'] = $caseData->id;
                      $purpose="SEBI Case Payment Case Id";

                      $payresult=Payment::initiatePayment($purpose, $total_amt, $customer_name, $customer_email);

                      if($payresult['response']=='success'){

                        $SettlementPayment=SettlementPayment::find($insert_data->id);
                        $SettlementPayment->payment_request_id=$payresult['data']['payment_request_id'];
                        $SettlementPayment->payment_req_created=$payresult['data']['payment_req_created'];
                        $SettlementPayment->payment_url=$payresult['data']['payment_url'];
                        $SettlementPayment->save();
                        $payment_url=$payresult['data']['payment_url'];

                        $result['message'] = "Payment request created";
                        $result['response']='success';
                        $result['data'] = ['caseid'=> $caseData->id, 'total_amt'=>$total_amt, 'payment_req_token'=> $payment_req_token, 'payment_url'=>$payment_url];
                        $result['code'] = 200;
                        echo json_encode($result);
                        exit;

                      }else{

                        $result['message'] = "Payment not creating";
                        $result['response']='error';
                        $result['code'] = 200;
                        echo json_encode($result);
                        exit;
                        

                      }



                    }else{
                        $result['message'] = "Something Went Wrong";
                        $result['response']='error';
                        echo json_encode($result);
                        exit;
                    }

                  }else{

                    $result['message'] = "Payment already received";
                    $result['response']='error';
                    echo json_encode($result);
                    exit;

                  }
                }else{

                    $result['message'] = "Payment Link Expired.";
                    $result['response']='error';
                    echo json_encode($result);
                    exit;

                }
             }
             catch (Exception $e)  { // Also tried JwtException
                 $result['code'] = 500;
                 $result['message'] = "Something Went Wrong";
                 $result['response']='error';
                 echo json_encode($result);
                 exit;
             }
         }else {
             $result['code'] = 500;
             $result['message'] = "Invalid Request";
             $result['response']='error';
             echo json_encode($result);
             exit;
         }

     }else {
         $output['code']=404;//unauthorised
         $output['message']='Method Not Found';//unauthorised
         $output['response']='error';
         echo json_encode($output);
         exit;
     }   
}

    public function offerCheck(Request $request){   

   
      $method = $_SERVER['REQUEST_METHOD'];

      if($method == "POST") {
  
        $caseid= $request->input('caseid');
        $payToken=  $request->input('payToken');
        if(isset($caseid)){   
  
              try {

                $caseData = MedCase::select("*")->where("id", "=", $caseid)->where("payToken", "=", $payToken)->first();
                if(!empty($caseData)){

                  $claimantdata2 = InvoledUser::select('user_involved_in_agreement.*', 'users.organization')->where('isClaimant', 0)->leftJoin('users', 'users.id', '=', 'user_involved_in_agreement.userId')->where('userPlanId', $caseid)->first();
                  $claimant_name2=$claimantdata2->name;
                  $claimant_email=$claimantdata2->userEmail;
                  $respondentdata=SettlementPayment::getrespondent($caseid);
                  $respondent_name=$respondentdata->name;
                  $respondent_email=$respondentdata->userEmail;

                  $botlogdata=[
                      'caseid' => $caseid,
                      'respondent_name' =>  $respondent_name,
                      'respondent_email' => $respondent_email,
                      'claimant_name' => $claimant_name2,
                      'claimant_email' => $claimant_email,
                      'event' => "WHATSAPP_BOT_RESTR_LINK",
                      'restructure_option' => "",
                      'created_at' => date('Y-m-d H:i:s')
                  ];
                  WhatsappBotReport::insert($botlogdata);
  
                  $offer_list=array();
                  if(isset($caseData->restructure_offer_1) && $caseData->restructure_offer_1 !=""){

                    $offer_val=count($offer_list)+1;
  
                      array_push($offer_list,  array('offer_no'=>count($offer_list)+1, 'value'=>"Offer - ".$offer_val, 'name' => $caseData->restructure_offer_1));
                  }
                  if(isset($caseData->restructure_offer_2) && $caseData->restructure_offer_2 !=""){
  
                    $offer_val=count($offer_list)+1;
  
                      array_push($offer_list,  array('offer_no'=>count($offer_list)+1, 'value'=>"Offer - ".$offer_val, 'name' => $caseData->restructure_offer_2));
                  }
                  if(isset($caseData->restructure_offer_3) && $caseData->restructure_offer_3 !=""){
  
                    $offer_val=count($offer_list)+1;
  
                      array_push($offer_list,  array('offer_no'=>count($offer_list)+1, 'value'=>"Offer - ".$offer_val, 'name' => $caseData->restructure_offer_3));
                  }

                 // print_r($offer_list);die();


                  if(!empty($offer_list)){
  
                        $result['code']=200;
                        $result['message']='success';//unauthorised
                        $result['responseData']=$offer_list;
                        echo json_encode($result);
                        exit;

                  }else{

                        $result['code']=404;
                        $result['message']='No Data Found';//unauthorised
                        $result['response']='error';
                        echo json_encode($result);
                        exit;
                  }
                }
                else
                {
                  $result['code']=404;
                  $result['message']='No Data Found';//unauthorised
                  $result['response']='error';
                  echo json_encode($result);
                  exit;
                }
  
                
              }
              catch (Exception $e) 
              { 
                $result['code'] = 500;
                $result['message'] = "Something Went Wrong";
                $result['response']='error';
                echo json_encode($result);
                exit;
              }
            
        }
        else 
        {
              $result['code']=404;//unauthorised
              $result['message']='Notice id not found';//unauthorised
              $result['response']='error';
              echo json_encode($result);
              exit;
        }
      }else{
  
      }
    
    }

    public function storeRestructure(Request $request){


      $method = $_SERVER['REQUEST_METHOD'];
      if($method == "POST") {

          $caseid= $request->input('caseid');
          $payToken=  $request->input('token');
          $offer_name=  $request->input('offer_name');
  
          if(empty($offer_name)) {
            
              $result['code'] = 404;
              $result['message'] = "Offer ID not found";
              $result['response'] = 'error';
              echo json_encode($result);
              exit;
          }
          if (isset($offer_name)) {
              try { 
  
                   $payData=SettlementPayment::where('caseid', $caseid)->where('payToken', $payToken)->where('pay_status', "success")->first();

                   if(empty($payData)){
                    
                    $caseData = MedCase::select("*")->where("id", $caseid)->where("payToken", $payToken)->get();
                  //  print_r($caseData);die(); 
                    if(count($caseData)>0){

                      $caseid = $caseData[0]->id;
                      $restructure_data = DB::table('restructure_data')->select('*')->where('caseid', $caseid)->get();

                      //print_r($restructure_data);die();

                      if(count($restructure_data)==0){

                        $respondentdata=SettlementPayment::getrespondent($caseid);

                        //print_r($respondentdata->userId);die();
  
                        $restructureFile=$name = 'restructure_' . sprintf('%06d', $caseid) . '.pdf';

                        $offerArr['caseid']=$caseid;
                        $offerArr['res_user_id']=11;
                        $offerArr['offer_name']=$offer_name;
                        $offerArr['cl_user_id']="1";
                        $offerArr['restructureFile']=$restructureFile;
                        $offerArr['created_at']=date('Y-m-d H:i:s');
                        $insert_data=RestructureData::create($offerArr);
  
                        if($insert_data){

                          $claimantdata2 = InvoledUser::select('user_involved_in_agreement.*', 'users.organization')->where('isClaimant', 0)->leftJoin('users', 'users.id', '=', 'user_involved_in_agreement.userId')->where('userPlanId', $caseid)->first();
                          $claimant_name2=$claimantdata2->name;
                          $claimant_email=$claimantdata2->userEmail;
                          $respondentdata=SettlementPayment::getrespondent($caseid);
                          $respondent_name=$respondentdata->name;
                          $respondent_email=$respondentdata->userEmail;

                          $botlogdata=[
                              'caseid' => $caseid,
                              'respondent_name' =>  $respondent_name,
                              'respondent_email' => $respondent_email,
                              'claimant_name' => $claimant_name2,
                              'claimant_email' => $claimant_email,
                              'event' => "RESTR_OFFER_SUBMIT",
                              'restructure_option' => $offer_name,
                              'created_at' => date('Y-m-d H:i:s')
                          ];
                          WhatsappBotReport::insert($botlogdata);
  
                          $inserted_id=$insert_data->id;
                          
                          $restructuredata=RestructureData::select('*')->where('id', $inserted_id)->first();
                          $data["case_id"] = sprintf('%06d', $caseid);
                          $data["respondent_name"] = $restructuredata->name;
                          $data["restructuredata"]=$restructuredata;
                          $pdf = PDF::loadView('pdf.restructurepdf', $data);
                          $savePath = 'mediation_documents/medrestructure/' . $caseData[0]->id;
                          $finalFilePath = $savePath . '/' . $restructureFile;
                          //$uploadS3 = $this->uploadOnAWSDirect($finalFilePath, $savePath, $pdf);
                          $uploadS3 = $this->uploadOnAWSDirect($finalFilePath, $savePath, $pdf);
                          //$uploadS3=true;

                         if($uploadS3){

                          $s3filepath = Storage::disk('s3')->url($finalFilePath);
                          $varjson = ['caseid' => "M" . sprintf("%06d", $caseid)];
                          $var = ['-caseid-'];
                          $var1 = ["M" . sprintf("%06d", $caseid)];
                          $content1 = WaTemplate::getcontent('restructure_settlement_doc');
                          $haptik_tmp="restructure_settlement_doc_56";
                          $content = str_replace($var, $var1, $content1);

                          if($respondentdata->userPhone !=""){

                            $dwa1 = [
                              'caseid' => $caseid,
                              'contact' => $respondentdata->userPhone,
                              'content' =>  ['media' => ['url' => $s3filepath, 'caption' => $content]],
                              'event' => "RESTRUCTURE_BOT_MSG",
                              'varjson' => $varjson,
                              'haptik_tmp' => $haptik_tmp,
                          ];

                            $accessW = Whatsapp::sendWamessage($dwa1);

                          }
                            //$restructureFile_path = S3getUrl($restructureFile, 'restructure');
                           //$restructureFile_path=base_url('assets/upload/restructure/'.$restructureFile);
                            $caption = "Please see the restructure document";
                                
                            $result['message'] = "Offer Selected Successfully";
                            $result['response']='success';
                            $result['responseData']=$offer_name;
                            echo json_encode($result);
                            exit;
  
  
                         }else{
  
                          $result['message'] = "Restructure file not uploaded.";
                          $result['response']='error';
                          echo json_encode($result);
                          exit;
  
                         }
                        }else{
                          $result['message'] = "Something Went Wrong";
                          $result['response']='error';
                          echo json_encode($result);
                          exit;
                        } 
                        
                      }else{
  
                        $result['message'] = "Offer already selected.";
                        $result['response']='error';
                        echo json_encode($result);
                        exit;
                        
                      }
  
                    }else{
                      $result['message'] = "Data Not Found.";
                      $result['response']='error';
                      echo json_encode($result);
                      exit;
  
                    }
                  }else{
  
                    $result['message'] = "This case id not available for restructuring";
                    $result['response']='error';
                    echo json_encode($result);
                    exit;
  
                  }
  
  
              }
              catch (Exception $e)  { // Also tried JwtException
                  $result['code'] = 500;
                  $result['message'] = "Something Went Wrong";
                  $result['response']='error';
                  echo json_encode($result);
                  exit;
              }
          }else {
              $result['code'] = 500;
              $result['message'] = "Invalid Request";
              $result['response']='error';
              echo json_encode($result);
              exit;
          }
  
      }else {
          $output['code']=404;//unauthorised
          $output['message']='Method Not Found';//unauthorised
          $output['response']='error';
          echo json_encode($output);
          exit;
      }
    }

    public function addDisputeReply(Request $request){  

      //if(isset($this->post['token']))
      $method = $_SERVER['REQUEST_METHOD'];
    
      if($method == "POST") {  

        $caseid= $request->input('caseid');
        $payToken=  $request->input('payToken');
        $your_reply=  $request->input('your_reply');
        $party_name=  $request->input('party_name');
        //$files=  $request->file('files');
       // print_r($files['originalName']);die();

          if(empty($your_reply)) {
            $result['code'] = 500;
            $result['message'] = "Reply not found";
            $result['response'] = 'error';
            echo json_encode($result);
            exit;
          }
          if(empty($party_name)) {  
            $result['code'] = 500;
            $result['message'] = "Party Name not found";
            $result['response'] = 'error';
            echo json_encode($result);
            exit;
          }
           try {

            $payData=SettlementPayment::where('caseid', $caseid)->where('payToken', $payToken)->where('pay_status', "success")->first();
  
            if(empty($payData)){
  
              $caseData = MedCase::select("*")->where("id", $caseid)->where("payToken", $payToken)->first();

              if ($request->hasFile('files')) {

                  $image = $request->file('files');
                  $fileName = 'replyfile_'.time().'.'.$image->getClientOriginalExtension();
                 // $path = $image->storeAs('replyfile', $fileName, 'public');
                 $savePath = 'mediation_documents/medreplyfile/';
                 $finalFilePath = $savePath . '/' . $fileName;
                // $uploadS3 = $this->uploadOnAWSDirect($finalFilePath, $savePath, $image);
                 $uploadS3 = Storage::disk('s3')->put($finalFilePath , fopen($request->file('files'), 'r+'));
                 $file_path = Storage::disk('s3')->url($finalFilePath);
              }else{

                $fileName="";

              }

               if(!empty($caseData)){

                     $sub_array1['caseid']=$caseid;
                     $sub_array1['attachment_file']=$fileName;
                     $sub_array1['organization_name']="";
                     $sub_array1['party_name']=$party_name;
                     $sub_array1['your_reply']=$your_reply;
                     $sub_array1['case_pdf']="";
                     $sub_array1['reply_on']=date('Y-m-d H:i:s');
                     $main_array[]=$sub_array1;
                     $sub_array1['reply_data']=json_encode($main_array);

                    $insert_data=CaseReply::create($sub_array1);


                      if($insert_data){

                        $claimantdata2 = InvoledUser::select('user_involved_in_agreement.*', 'users.organization')->where('isClaimant', 0)->leftJoin('users', 'users.id', '=', 'user_involved_in_agreement.userId')->where('userPlanId', $caseid)->first();
                        $claimant_name2=$claimantdata2->name;
                        $claimant_email=$claimantdata2->userEmail;
                        $respondentdata=SettlementPayment::getrespondent($caseid);
                        $respondent_name=$respondentdata->name;
                        $respondent_email=$respondentdata->userEmail;

                        $botlogdata=[
                            'caseid' => $caseid,
                            'respondent_name' =>  $respondent_name,
                            'respondent_email' => $respondent_email,
                            'claimant_name' => $claimant_name2,
                            'claimant_email' => $claimant_email,
                            'event' => "SUBMIT_REPLY",
                            'reply' => $your_reply,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        WhatsappBotReport::insert($botlogdata);

                        $result['code']=200;
                        $result['message']='Your reply has been submitted.';//unauthorised
                        $result['response']='success';
                        $result['responseData']='Your reply has been submitted.';
                        echo json_encode($result);
                        exit;
                      }
                      else
                      {
                         $result['code']=500;
                         $result['message']='Sorry, Unable to submit reply';//unauthorised
                         $result['response']='error';
                         $result['responseData']='Sorry, Unable to submit reply.';
                         echo json_encode($result);
                         exit;
                      }
               }
               else
               {
                  $result['code']=404;
                  $result['message']='No Data Found';//unauthorised
                  $result['response']='error';
                  $result['responseData']='No Data Found';
                  echo json_encode($result);
                  exit;
               }
  
            }else{
  
              $result['code'] = 500;
              $result['message'] = "Your Payment has been already complted, You can not reply.";
              $result['response']='error';
              echo json_encode($result);
              exit;
  
  
            }
           }
           catch (Exception $e) 
           { 
              $result['code'] = 500;
              $result['message'] = "Something Went Wrong";
              $result['response']='error';
              echo json_encode($result);
              exit;
           }
          
      }
      else {
  
            $output['code']=404;//unauthorised
            $output['message']='Notice id not found';//unauthorised
            $output['response']='error';
            echo json_encode($output);
            exit;
      }
    }

    public function otpGenerate(Request $request){  

      //if(isset($this->post['token']))
      $method = $_SERVER['REQUEST_METHOD'];
    
      if($method == "POST") {  

        $caseid= $request->input('caseid');
        $payToken=  $request->input('token');

          if(empty($caseid)) {
            $result['code'] = 500;
            $result['message'] = "Case not found";
            $result['response'] = 'error';
            echo json_encode($result);
            exit;
          }
          if(empty($payToken)) {  

            $result['code'] = 500;
            $result['message'] = "Invalid Request";
            $result['response'] = 'error';
            echo json_encode($result);
            exit;
          }
           try {

              $restructure_data = DB::table('restructure_data')->select('*')->where('caseid', $caseid)->get();
              if(count($restructure_data) > 0){

                  $result['code'] = 200;
                  $result['message'] = "Offer already selected.";
                  $result['response'] = 'error';
                  echo json_encode($result);
                  exit;

              }

              $caseData = MedCase::select("*")->where("id", $caseid)->where("payToken", $payToken)->first();

               if(!empty($caseData)){

                    $respondentdata=SettlementPayment::getrespondent($caseid);
                    $otp = rand(100000, 999999);
                    $insertArr['otp']=$otp;
                    $insertArr['caseid']=$caseData->id;
                    $insertArr['mobileNo']=$respondentdata->userPhone;
                    $insertArr['email']=$respondentdata->userEmail;
                    $insertArr['status']="1";
                    $insertArr['is_verified']="0";
                    $insertArr['otp_event']="RESTROTP";
                    $insertArr['Expire_date']=date('Y-m-d H:i:s');
                    $insertArr['created_at']=date('Y-m-d H:i:s');
                    $insert_data=OtpCode::create($insertArr);
            
                  
                      $otpsend=true;

                      $d = [
                        'event' => 'RES_OTPGEN',
                        'case_id' => $caseData->id,
                    ];
                    
                    if($respondentdata->userEmail !=""){
                      
                      SendGrid::send($d, $respondentdata->userEmail, 'ee4e13df-fc58-441a-9843-b3e26d606f72', ["-otp-" => $otp], $respondentdata->name);

                    }

                  /* if($mobile!=""){
                        
                    $authKey = "353508AnLLLst4qR62ce8822P1";
                    $mobileNumber = "+91" . $mobile;
                    $senderId = "Prsolv";
                    $otp = "Your Presolv360 OTP is: ".$otp;
                    $message = urlencode($otp);
                    $route = "4";
                    $postData = array(
                        'authkey' => $authKey,
                        'mobiles' => $mobileNumber,
                        'message' => $message,
                        'sender' => $senderId,
                        'route' => $route,
                        'DLT_TE_ID'=>'1207161665402749813'
                    );
                    $url = "https://control.msg91.com/api/sendhttp.php";
                    $ch = curl_init();
                    curl_setopt_array($ch, array(
                        CURLOPT_URL => $url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => $postData
                    ));
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    $output = curl_exec($ch);
        
        
                    if (curl_errno($ch)) {
      
                      echo 'error:' . curl_error($ch);
                    }
        
                    curl_close($ch);
  
                  } */

                  if($respondentdata->userPhone !=""){

                    $authKey = env('SMS_AUTH_KEY', '');
                    $flowId = env('SMS_FLOW_KEY', '');
                    $url = env('SMS_FLOW_API', '');
                    $senderId = "Prsolv";
                    $mobileNumber = "+91" . $respondentdata->userPhone;
            
                    $ch = curl_init();
                    curl_setopt_array($ch, [
                        CURLOPT_URL => $url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => "{\n  \"flow_id\": \"$flowId\",\n  \"sender\": \"$senderId\",\n  \"mobiles\": \"$mobileNumber\",\n  \"otp\": \"$otp\"\n  }",
                        CURLOPT_HTTPHEADER => [
                            "authkey: {$authKey}",
                            "content-type: application/JSON"
                        ],
                    ]);
            
                    $response = curl_exec($ch);
            
            
                    // dd($response);
                    //Print error if any
                    if (curl_errno($ch)) {
                        echo 'error:' . curl_error($ch);
                    }
            
                    curl_close($ch);

                  }

                  if($insert_data){

                    $result['code']=200;
                    $result['message']='OTP Send Mobile ';
                    $result['response']='success';
                    echo json_encode($result);
                    exit;
                  }
                  else
                  {
                    $result['code']=500;
                    $result['message']='Something Went Wrong';//unauthorised
                    $result['response']='error';
                    $result['responseData']='Something Went Wrong';
                    echo json_encode($result);
                    exit;
                  }
               }
               else
               {
                  $result['code']=404;
                  $result['message']='No Data Found';//unauthorised
                  $result['response']='error';
                  $result['responseData']='No Data Found';
                  echo json_encode($result);
                  exit;
               }
           }
           catch (Exception $e) 
           { 
              $result['code'] = 500;
              $result['message'] = "Something Went Wrong";
              $result['response']='error';
              echo json_encode($result);
              exit;
           }
          
      }
      else {
            $output['code']=404;//unauthorised
            $output['message']='Notice id not found';//unauthorised
            $output['response']='error';
            echo json_encode($output);
            exit;
      }
    }

    public function otpVerify(Request $request){  

      $method = $_SERVER['REQUEST_METHOD'];
    
      if($method == "POST") {  

        $caseid= $request->input('caseid');
        $payToken=  $request->input('token');
        $otpcode=  $request->input('otp');

          if(empty($caseid)) {
            $result['code'] = 500;
            $result['message'] = "Case not found";
            $result['response'] = 'error';
            echo json_encode($result);
            exit;
          }
          if(empty($payToken)) {  

            $result['code'] = 500;
            $result['message'] = "Invalid Request";
            $result['response'] = 'error';
            echo json_encode($result);
            exit;
          }
           try {

              $caseData = MedCase::select("*")->where("id", $caseid)->where("payToken", $payToken)->first();
              $otpdata=OtpCode::select("*")->where("caseid", $caseid)
                                           ->where('is_verified', '0')
                                           ->where('otp_event', 'RESTROTP')
                                           ->orderBy('id', 'DESC')
                                           ->first();

                if(!empty($otpdata)){

                    if($otpdata->otp==$otpcode){

                        $otpcode=OtpCode::find($otpdata->id);
                        $otpcode->is_verified="1";
                        $otpcode->save();
                        
                        $result['code']=200;
                        $result['message']='submitted.';
                        $result['response']='success';
                        echo json_encode($result);
                        exit;

                    } else{

                      $result['code']=500;
                      $result['message']='Invalid OTP. Please try again';
                      $result['response']='error';
                      echo json_encode($result);
                      exit;
                      
                    }
                }
                else {

                    $result['code']=404;
                    $result['message']='Invalid OTP';//unauthorised
                    $result['response']='error';
                    echo json_encode($result);
                    exit;
                }
           }
           catch (Exception $e) 
           { 
              $result['code'] = 500;
              $result['message'] = "Something Went Wrong";
              $result['response']='error';
              echo json_encode($result);
              exit;
           }
          
      }
      else {
            $output['code']=404;//unauthorised
            $output['message']='Notice id not found';//unauthorised
            $output['response']='error';
            echo json_encode($output);
            exit;
      }
    }

    public function WApayNowProcess($caseid, $PayLink){  

        //echo "brijesh"; die();
      
        $method = $_SERVER['REQUEST_METHOD'];
       //print_r($PayLink);die();
     if($method == "POST") {

         // $caseid= $request->input('caseid');
         // $payToken=  $request->input('payToken');

          if (empty($caseid)){

            $result['code'] = 200;
            $result['message'] = "Invalid Request";
            $result['response']='error';
            echo json_encode($result);
            exit;
          }
        /*  if(empty($payToken)) {

             $result['code'] = 500;
             $result['message'] = "Invalid Request";
             $result['response'] = 'error';
             echo json_encode($result);
             exit;
         } */
         if (isset($caseid)) {

             try { 

                 /* $check_payment_gateway_data = PaymentGatewayData::select("*")->where("userid", "=", "78")->where("is_active", "=", 1)->first();

                if(empty($check_payment_gateway_data)) {
                  $result['code'] = 500;
                  $result['message'] = "Payment Gateway not available";
                  $result['response'] = 'error';
                  echo json_encode($result);
                  exit;
                } */
                $caseData = MedCase::select("*")->where("id", "=", $caseid)->first();

                if(empty($caseData)) {

                  $result['code'] = 500;
                  $result['message'] = "Case not found";
                  $result['response'] = 'error';
                  echo json_encode($result);
                  exit;
                }

                $restructuredata=RestructureData::select('*')->where('caseid', $caseid)->first();

            if(!empty($restructuredata)) {

              $result['code'] = 500;
              $result['message'] = "Already requested for the case restructure";
              $result['response'] = 'error';
              echo json_encode($result);
              exit;

            }
            
                $checkpayment = SettlementPayment::select("*")->where("caseid", "=", $caseid)->where("pay_status", "=", "success")->get();
                //print_r($checkpayment);die();
                $PayLinkExpire = $caseData->PayLinkExpire; 
                $currentDate=date('Y-m-d H:i:s');
                if (strtotime($PayLinkExpire) > strtotime($currentDate)) {

                  if(count($checkpayment) == 0){
                    
                    $respondentdata=SettlementPayment::getrespondent($caseData->id);
                   // print_r($respondentdata->userEmail);die();
                    $customer_name=$respondentdata->name;
                    $customer_email=$respondentdata->userEmail;
                    $discount="";
                    $length_of_string=16;
                    $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                    $str_token=substr(str_shuffle($str_result),  0, $length_of_string);
                    $payment_req_token=$str_token."_".$caseData->id;
                    $payment_security_token=$caseData->id.$str_token;

                    $total_amt=$caseData->amount;

                    $original_price = $caseData->amount; // set original price
                    $payArr['caseid']=$caseData->id;
                    $payArr['customer_name']=$customer_name;
                    $payArr['customer_email']=$customer_email;
                    $payArr['actual_amt']=$original_price;
                    $payArr['discount_amt']=$discount;
                    $payArr['total_amt']=$total_amt;
                    $payArr['pay_status']="process";
                    //$payArr['created_at']=date('Y-m-d H:i:s');
                    $payArr['payToken']=$caseData->payToken;
                    $payArr['payment_req_token']=$payment_req_token;
                    $payArr['payment_url']=$PayLink;
                    $payArr['payment_req_created']=date('Y-m-d H:i:s');
                    $payArr['payment_security_token']=$payment_security_token;
                    $insert_data=SettlementPayment::create($payArr);
                    if($insert_data){

                      $_SESSION['payment_req_token'] = $payment_req_token; 
                      $_SESSION['payment_security_token'] = $payment_security_token; 
                      $_SESSION['pay_caseid'] = $caseData->id;
                      $purpose="SEBI Case Payment Case Id";

                      //$payresult=Payment::initiatePayment($purpose, $total_amt, $customer_name, $customer_email);

                        $SettlementPayment=SettlementPayment::find($insert_data->id);
                        $payment_url=$SettlementPayment->payment_url;

                        $result['message'] = "Payment request created";
                        $result['response']='success';
                        $result['data'] = ['caseid'=> $caseData->id, 'total_amt'=>$total_amt, 'payment_req_token'=> $payment_req_token, 'payment_url'=>$payment_url];
                        $result['code'] = 200;
                       /*  echo json_encode($result);
                        exit; */

                       return json_encode($result);

                    }else{
                        $result['message'] = "Something Went Wrong";
                        $result['response']='error';
                        echo json_encode($result);
                        exit;
                    }

                  }else{

                    $result['message'] = "Payment already received";
                    $result['response']='error';
                    echo json_encode($result);
                    exit;

                  }
                }else{

                    $result['message'] = "Payment Link Expired.";
                    $result['response']='error';
                    echo json_encode($result);
                    exit;

                }
             }
             catch (Exception $e)  { 
              // Also tried JwtException
              echo "Caught an exception: " . $e->getMessage();
                 $result['code'] = 500;
                 $result['message'] = "Something Went Wrong";
                 $result['response']='error';
                 echo json_encode($result);
                 exit;
             }
         }else {
             $result['code'] = 500;
             $result['message'] = "Invalid Request";
             $result['response']='error';
             echo json_encode($result);
             exit;
         }

     }else {
         $output['code']=404;//unauthorised
         $output['message']='Method Not Found';//unauthorised
         $output['response']='error';
         echo json_encode($output);
         exit;
     } 

    }
}
