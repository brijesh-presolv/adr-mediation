<?php

namespace App\Http\Helpers;

use App\Http\Helpers\Token;

class Curl
{
    public static function getdata($url, $formdata = '', $type = "GET", $token = '')
    {

        // if ($token == '') {
        // 	//$token_obj = new Token();
        // 	$token = Token::getToken();
        // }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $token,
        ));

        if ($formdata != '') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
            if ($type == "POST") {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $formdata);
            } else {
                $post_field_string = http_build_query($formdata, '', '&');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_field_string);
            }
        }
        curl_setopt($ch, CURLOPT_TIMEOUT, 500);

        $response = curl_exec($ch);
        //echo '<pre>';print_r($response);die;
        curl_close($ch);
        return $response;
    }

    public static function request($url, $formdata = '', $type = "", $auth)
    {


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $formdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Basic ' . $auth,
        ));
        curl_setopt($ch, CURLOPT_TIMEOUT, 500);
        $response = curl_exec($ch);
        //echo '<pre>';print_r($response);die;
        curl_close($ch);
        return $response;
    }

    public static function NewWhatsappRequest($url, $formdata = '', $type = "", $auth)
	{
		$curl = curl_init();
		// dd($formdata);
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => $type,
			CURLOPT_POSTFIELDS => $formdata,
			CURLOPT_HTTPHEADER => array(
				'Authorization: Basic ' . $auth,
				'Content-Type: application/json'
			),
		));
		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}


    // Mtalkz Code 
    public static function NewWhatsappMtalkzRequest_original($url, $formdata = '', $type = "", $auth)
	{
        
        // echo $formdata;
        // exit;
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "'".$url."'",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            //CURLOPT_POSTFIELDS =>"'".$formdata."'",
            CURLOPT_POSTFIELDS => '{
                "message" : {
                    "channel" : "WABA",
                    "content" : {
                        "preview_url" : false,
                        "type" : "TEMPLATE",
                        "template" : {
                            "templateId" : "new_latest",
                            "parameterValues" : {
                                "0" : "DevPresolv"
                                "1" : "M107698"
                                "2" : "http://mediation.localhost.com/"
                            },
                            "headerTitle" : "{{1}}"
                        },
                        "shorten_url" : true
                    },
                    "recipient" : {
                        "to" : "917567043843",
                        "recipient_type" : "individual"
                    },
                    "sender" : {
                        "from" : "918879651360"
                    },
                    "preferences" : {
                        "webHookDNId" : "1001"
                    }
                },
                "metaData" : {
                    "version" : "v1.0.9"
                }
            }',
            CURLOPT_HTTPHEADER => array(
              'Content-Type: application/json',
              'Authentication: Bearer '.$auth
            ),
          ));



        //   echo "<pre>";print_R(array(
        //     CURLOPT_URL => "'".$url."'",
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'POST',
        //     CURLOPT_POSTFIELDS =>"'".$formdata."'",
        //     CURLOPT_HTTPHEADER => array(
        //       'Content-Type: application/json',
        //       'Authentication: Bearer '.$auth
        //     ),
        // ));
        // exit;
       
		// $curl = curl_init();
		
		// curl_setopt_array($curl, array(
		// 	CURLOPT_URL => $url,
		// 	CURLOPT_RETURNTRANSFER => true,
		// 	CURLOPT_ENCODING => '',
		// 	CURLOPT_MAXREDIRS => 10,
		// 	CURLOPT_TIMEOUT => 0,
		// 	CURLOPT_FOLLOWLOCATION => true,
		// 	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		// 	CURLOPT_CUSTOMREQUEST => $type,
		// 	CURLOPT_POSTFIELDS => $formdata,
		// 	CURLOPT_HTTPHEADER => array(
        //         'Content-Type: application/json',
		// 		'Authentication: Bearer ' . $auth
		// 	),
		// ));

		$response = curl_exec($curl);

//         echo "<pre>";print_R(curl_getinfo($curl)) . '<br/>';
// echo "<pre>";print_R(curl_errno($curl)) . '<br/>';
// echo "<pre>";print_R(curl_error($curl)) . '<br/>';
// exit;
       // dd($response);
		curl_close($curl);
		return $response;

    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL, $url);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($ch, CURLOPT_ENCODING, '');
    // curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    // curl_setopt($ch, CURLOPT_TIMEOUT, 0);
    // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    // curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
    // curl_setopt($ch, CURLOPT_POSTFIELDS, $formdata);
    //  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    //     'Content-Type: application/json',
    //     'Authentication: Bearer ' . $auth,
    // ));
    // //curl_setopt($ch, CURLOPT_TIMEOUT, 500);
    // $response = curl_exec($ch);
    // echo '<pre>';print_r($response);die;
    // curl_close($ch);

    // return $response;
    
	}








    public static function NewWhatsappMtalkzRequest($url, $formdata = '', $type = "", $auth)
	{
        /*
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => $type,
          CURLOPT_POSTFIELDS => $formdata,
        
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authentication: Bearer '.$auth
          ),
        ));

       

       
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        
        return $response;
        */

      //  echo $formdata;exit;
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://rcmapi.instaalerts.zone/services/rcm/sendMessage',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $formdata,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authentication: Bearer MVq7GiiTpO4H3ew6P6EOzw=='
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
        return $response;
    
	}
    // Mtalkz Code 
}
