<?php

namespace App\Http\Helpers;

use Firebase\JWT\JWT;
use RuntimeException;

class Token
{
	public static function createToken($d)
	{
		$secret = config('jwt.key');

		if (empty($secret)) {
			throw new RuntimeException('JWT_KEY is not configured.');
		}

		$currentTimestamp = time();
		$expire = $currentTimestamp + config('jwt.ttl');

		$data = array(
			'iat' => $currentTimestamp,
			'jti' => base64_encode(random_bytes(32)),
			'iss' => config('jwt.issuer'),
			'nbf' => $currentTimestamp,
			'exp' => $expire,
			'data' => $d
		);

		return JWT::encode($data, base64_decode($secret), config('jwt.algo'));
	}
}
