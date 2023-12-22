<?php
namespace App\Http\Helpers;
use Illuminate\Http\Request;
use Instamojo\Instamojo;

class Payment
{
    
    public static function initiatePayment2($purpose, $amount, $customer_name, $customer_email){

        //echo "brijesh";die();
        
        /* $api = new Instamojo(
            config('services.instamojo.api_key'),
            config('services.instamojo.auth_token'),
            config('services.instamojo.api_url')
        ); */
        $api = new \Instamojo\Instamojo(
            env('INSTAMOJO_API_KEY'),
            env('INSTAMOJO_AUTH_TOKEN'),
            env('INSTAMOJO_API_URL'),
        );

        $api_key = 'test_9b8475938aff089a4ef92d54c84';
        $api_secret = 'test_26cd070ad5fad603f539df68411';
        // Use the getInstamojo static method to create an instance of Instamojo
      //  $api = Instamojo::getInstamojo($api_key, $api_secret);
     // $api = new Instamojo($api_key, $api_secret, 'https://www.instamojo.com/api/1.1/');
     $api_url = 'https://www.instamojo.com/api/1.1/'; // Make sure to use the correct API version

     // Create an instance of Instamojo using the constructor
    // $api = new Instamojo($api_key, $api_secret, $api_url);
     $api = new \Instamojo\Instamojo(
                    config('test_9b8475938aff089a4ef92d54c84'),
                    config('test_26cd070ad5fad603f539df68411'),
                    config('https://www.instamojo.com/api/1.1/')
        
                );
        
         
        


        

        try {

            $response = $api->paymentRequestCreate([
                'purpose' => 'Order Payment',
                'amount' => 100,
                'buyer_name' => "brijesh",
                'send_email' => true,
                'email' => "brijesh@presolv360.com",
                //'redirect_url' => route('payment.success'),
                'webhook' => route('payment.webhook'), // Optional for capturing payment status
            ]);

            
            //$payment_url = $response['longurl'];
            //return response()->json(['payment_url' => $payment_url]);
            return $response;
            
        } catch (\Exception $e) {
           // return response()->json(['error' => $e->getMessage()], 500);
            return $e->getMessage();

        }
    }

    public static function initiatePayment($purpose, $amount, $customer_name, $customer_email){

        $api_key = 'test_9b8475938aff089a4ef92d54c84';
        $auth_token = 'test_26cd070ad5fad603f539df68411';
         //$redirect_url = base_url("/settlementpaymentdraf");
        // $redirect_url = url('/settlementpaymentdraf');

           // LIVE
      /*  $api_key = '4d8c29684d420ec185fe68a268f0da39';
        $auth_token = '6c4e92388c7430fb5f825f1580014bbd'; */

        $ch = curl_init();
        $testpay_url='https://test.instamojo.com/api/1.1/payment-requests/';

           curl_setopt($ch, CURLOPT_URL, "https://test.instamojo.com/api/1.1/payment-requests/");
           curl_setopt($ch, CURLOPT_HEADER, FALSE);
           curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
           curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
           curl_setopt($ch, CURLOPT_HTTPHEADER,
                       array("X-Api-Key:$api_key",
                             "X-Auth-Token:$auth_token"));
           $payload = Array(
               'purpose' => $purpose,
               'amount' => "100",
               'phone' => '9029289887',
               'buyer_name' => 'Brijesh Prasad',
               //'redirect_url' => $redirect_url,
               'redirect_url' => route('payment.success'),
               'send_email' => FALSE,
               'send_sms' => true,
               'webhook' => route('payment.webhook'),
               'email' => 'brijesh@presolv360.com',
               'allow_repeated_payments' => false
           );
           curl_setopt($ch, CURLOPT_POST, true);
           curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
           $response = curl_exec($ch);
          // print_r($response);die();
           curl_close($ch); 
           $response=json_decode($response, true);

            if($response['success']){

                $payment_request_id = $response['payment_request']['id'];
                $payment_req_created=date('Y-m-d H:i:s');
                $longurl=$response['payment_request']['longurl'];
                $result['message'] = "Payment request created";
                $result['response']='success';
                $result['data'] = ['payment_request_id'=>$payment_request_id, 'payment_req_created'=>$payment_req_created, 'payment_url'=>$longurl];
                $result['code'] = 200;
                return $result;

            }else{

                $result['message'] = "Payment request not created";
                $result['response']='error';
                $result['code'] = 200;
                return $result;
            }
    }

    public static function paymentStatus($payment_request_id){

        $api_key = 'test_9b8475938aff089a4ef92d54c84';
        $auth_token = 'test_26cd070ad5fad603f539df68411';

        //$api_endpoint = "https://api.instamojo.com/v2/payment-requests/{$payment_request_id}/";
        $api_endpoint = "https://test.instamojo.com/v2/payment-requests/{$payment_request_id}/";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://test.instamojo.com/api/1.1/payment-requests/'.$payment_request_id);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-Api-Key: ' . $api_key,
            'X-Auth-Token: ' . $auth_token,
        ]); 
        $response = curl_exec($ch);
        curl_close($ch); 
        
           $response=json_decode($response, true);
          // print_r($response);die();

           if (isset($response['success']) && $response['success'] == true) {

                $payment_completed_at=$response['payment_request']['created_at'];
                $payment_status = $response['payment_request']['status'];
                $amount = $response['payment_request']['amount'];
                $buyer_name = $response['payment_request']['buyer_name'];
                $payment_request_id = $response['payment_request']['id'];
                $payment_id = $response['payment_request']['payments'][0]['payment_id'];
                $result['message'] = "Payment request created";
                $result['response']='success';
                $result['data'] = ['payment_data'=>$response, 'payment_status'=>$payment_status, 'amount'=>$amount, 'buyer_name'=>$buyer_name, 'payment_request_id'=>$payment_request_id, 'payment_completed_at'=>$payment_completed_at, 'payment_id'=>$payment_id];
                $result['code'] = 200;
                return $result;

            }else{

                $result['message'] = $response['message'];
                $result['response']='error';
                $result['code'] = 200;
                return $result;
            }
    }
}
