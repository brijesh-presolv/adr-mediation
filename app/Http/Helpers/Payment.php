<?php
namespace App\Http\Helpers;

use Illuminate\Http\Request;
use Instamojo\Instamojo;

class Payment
{
    /**
     * Base URL of the Instamojo API, without a trailing slash.
     */
    private static function apiUrl()
    {
        return rtrim(config('services.instamojo.api_url'), '/');
    }

    /**
     * Auth headers every Instamojo call needs.
     */
    private static function authHeaders()
    {
        return [
            'X-Api-Key: ' . config('services.instamojo.api_key'),
            'X-Auth-Token: ' . config('services.instamojo.auth_token'),
        ];
    }

    public static function initiatePayment2($purpose, $amount, $customer_name, $customer_email){

        $api = new \Instamojo\Instamojo(
            config('services.instamojo.api_key'),
            config('services.instamojo.auth_token'),
            config('services.instamojo.api_url')
        );

        try {

            $response = $api->paymentRequestCreate([
                'purpose' => $purpose,
                'amount' => $amount,
                'buyer_name' => $customer_name,
                'send_email' => true,
                'email' => $customer_email,
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

    public static function initiatePayment($purpose, $amount, $customer_name, $customer_email, $customer_phone = ''){

        $ch = curl_init();

           curl_setopt($ch, CURLOPT_URL, self::apiUrl() . "/payment-requests/");
           curl_setopt($ch, CURLOPT_HEADER, FALSE);
           curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
           curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
           curl_setopt($ch, CURLOPT_HTTPHEADER, self::authHeaders());
           $payload = Array(
               'purpose' => $purpose,
               'amount' => $amount,
               'phone' => $customer_phone,
               'buyer_name' => $customer_name,
               'redirect_url' => route('payment.success'),
               'send_email' => FALSE,
               'send_sms' => true,
               'webhook' => route('payment.webhook'),
               'email' => $customer_email,
               'allow_repeated_payments' => false
           );
           curl_setopt($ch, CURLOPT_POST, true);
           curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
           $response = curl_exec($ch);
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

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::apiUrl() . '/payment-requests/' . $payment_request_id);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, self::authHeaders());
        $response = curl_exec($ch);
        curl_close($ch);

           $response=json_decode($response, true);

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
