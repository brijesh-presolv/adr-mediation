<?php
namespace App\Http\Helpers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;

class Token {

	public static function createToken($d) {
				$DOMAIN='http://360_new.com';
				$ADJWTSECRET='MXc0KG5eaTFmb2g3JXNuZ2wxdzQobl5pMWZvaDclc25nbGUxIyY2Zmg0a2R0KTQpc2tjZ21lMSMmNmZoNGtkdCk0KXNrY2dtaGxmKHh1c2Rycm1fLWI=';
		$currentTimestamp = time();
		$expire = $currentTimestamp + 60;
		$data = array(
			'iat' => $currentTimestamp,
			'jti' => base64_encode(openssl_random_pseudo_bytes(32)),
			'iss' => $DOMAIN,
			'nbf' => $currentTimestamp,
			'exp' => $expire,
			'data' =>$d
		);
		$token = JWT::encode($data, base64_decode($ADJWTSECRET), 'HS512'); 
		return $token;
	}
}

?>