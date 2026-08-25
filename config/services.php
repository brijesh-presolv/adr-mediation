<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'brevo' => [
        'api_key' => env('BREVO_API_KEY'),
        'webhook_token' => env('BREVO_WEB_TOKEN'),
    ],

    'instamojo' => [
        'api_key' => env('INSTAMOJO_API_KEY'),
        'auth_token' => env('INSTAMOJO_AUTH_TOKEN'),
        // Defaults to the TEST endpoint on purpose: this preserves the behaviour
        // the hardcoded code had, and an unset variable must never silently
        // charge real money. Set INSTAMOJO_API_URL explicitly to go live.
        'api_url' => env('INSTAMOJO_API_URL', 'https://test.instamojo.com/api/1.1/'),
    ],

    'msg91' => [
        'auth_key' => env('SMS_AUTH_KEY'),
        'sender_id' => env('SMS_SENDER_ID', 'Prsolv'),
        'logs_url' => env('MSG91_LOGS_URL', 'https://api.msg91.com/api/v2/logs'),
    ],

    'karix' => [
        // "<api-key>:<api-secret>" pair, base64 encoded at call time.
        'auth' => env('KARIX_AUTH'),
        'source' => env('KARIX_SOURCE'),
        'api_url' => env('KARIX_API_URL', 'https://api.karix.io/message/'),
    ],

    'mtalkz' => [
        'token' => env('MTALKZ_KEY'),
        'api_url' => env('MTALKZ_API_URL', 'https://rcmapi.instaalerts.zone/services/rcm/sendMessage'),
    ],

    'odrs' => [
        'token' => env('ODRS_TOKEN'),
        'app' => env('ODRS_APP', 'P360MED'),
        'header' => env('ODRS_HEADER', 'PRSOLV'),
        'api_url' => env('ODRS_SHORTURL_URL', 'https://odrs.in/api/getshort'),
    ],

    'ivr' => [
        'auth' => env('IVR_AUTH'),
        'api_url' => env('IVR_API_URL'),
        'track_url' => env('IVR_TRACK_URL'),
        'respondent_id' => env('IVR_RESPONDENT_ID'),
        'claimant_id' => env('IVR_CLAIMANT_ID'),
    ],

    'interakt' => [
        'key' => env('INTERAKT_KEY'),
        'api_url' => env('INTERAKT_API_URL', 'https://api.interakt.ai/v1/public/message/'),
    ],

    'whatsapp_bot' => [
        'auth' => env('WHATSAPP_BOT_AUTH'),
        'reply_url' => env('WHATSAPP_BOT_REPLY_URL'),
        'consent_reply_url' => env('WHATSAPP_BOT_CONSENT_REPLY_URL'),
        'log_url' => env('WHATSAPP_BOT_LOG_URL'),
        'log_url_mtalkz' => env('WHATSAPP_BOT_LOG_URL_MTALKZ'),
    ],

    'zoom' => [
        'client_id' => env('ZOOM_API_KEY'),
        'client_secret' => env('ZOOM_API_SECRET'),
        'account_id' => env('ZOOM_OAUTH_ACCOUNT_ID'),
        'meeting_password' => env('ZOOM_MEETING_PASSWORD'),
    ],

];
