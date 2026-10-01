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

    'whatsapp_otp' => [
        'driver' => env('WHATSAPP_OTP_DRIVER', 'log'),
        'graph_version' => env('WHATSAPP_GRAPH_VERSION', 'v25.0'),
        'access_token' => env('WHATSAPP_ACCESS_TOKEN'),
        'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
        'template_name' => env('WHATSAPP_OTP_TEMPLATE_NAME', 'registration_otp'),
        'template_language' => env('WHATSAPP_OTP_TEMPLATE_LANGUAGE', 'en_US'),
    ],

];
