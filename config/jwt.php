<?php

return [

    /*
    |--------------------------------------------------------------------------
    | JWT Signing Key
    |--------------------------------------------------------------------------
    |
    | Base64 encoded secret used to sign and verify the API auth tokens. It is
    | decoded before use, so generate one with:
    |
    |     php -r "echo base64_encode(random_bytes(64)), PHP_EOL;"
    |
    */

    'key' => env('JWT_KEY'),

    'algo' => env('JWT_ALGO', 'HS512'),

    'issuer' => env('JWT_ISSUER', env('APP_URL', 'http://localhost')),

    // Token lifetime in seconds.
    'ttl' => (int) env('JWT_TTL', 60 * 60 * 24),

];
