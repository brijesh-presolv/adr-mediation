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
}
