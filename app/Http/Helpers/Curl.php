<?php

namespace App\Http\Helpers;

use App\Http\Helpers\Token;

class Curl
{
    public static function getdata($url, $formdata = '', $type = "GET", $token = '')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
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
        curl_close($ch);
        return $response;
    }

    public static function request($url, $formdata = '', $type = "", $auth = '')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $formdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Basic ' . $auth,
        ));
        curl_setopt($ch, CURLOPT_TIMEOUT, 500);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public static function NewWhatsappRequest($url, $formdata = '', $type = "", $auth = '')
	{
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_SSL_VERIFYHOST => 2,
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
    public static function NewWhatsappMtalkzRequest($url, $formdata = '', $type = "POST", $auth = '')
	{
        $auth = $auth !== '' ? $auth : config('services.mtalkz.token');
        $url = $url !== '' ? $url : config('services.mtalkz.api_url');

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $type ?: 'POST',
        CURLOPT_POSTFIELDS => $formdata,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authentication: Bearer ' . $auth
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

	}
    // Mtalkz Code
    public static function smsrequest($url, $formdata = '')
	{
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $formdata
		));
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		$output = curl_exec($ch);
		return $output;
	}

    public static function getShortUrl($longLink)
    {
        $post_data = http_build_query([
            'app'    => config('services.odrs.app'),
            'token'  => config('services.odrs.token'),
            'ip'     => '',
            'url'    => $longLink,
            'header' => config('services.odrs.header'),
        ]);

        $ch = curl_init(config('services.odrs.api_url'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);

        $output = json_decode($output)->data;
        return $output->shorturl;
    }
}
