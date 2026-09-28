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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'zeptomail' => [
        'endpoint' => env('ZEPTOMAIL_ENDPOINT', 'https://api.zeptomail.com/v1.1/email'),
        'api_key' => env('ZEPTOMAIL_API_KEY'),
        'from_address' => env('ZEPTOMAIL_FROM_ADDRESS', env('MAIL_FROM_ADDRESS', 'noreply@sahainsurance.co.th')),
        'from_name' => env('ZEPTOMAIL_FROM_NAME', env('MAIL_FROM_NAME', '')),
        'smtp_host' => env('ZEPTOMAIL_SMTP_HOST', 'smtp.zeptomail.com'),
        'smtp_port' => env('ZEPTOMAIL_SMTP_PORT', 587),
        'smtp_username' => env('ZEPTOMAIL_SMTP_USERNAME', 'emailapikey'),
        'smtp_secure' => env('ZEPTOMAIL_SMTP_SECURE', 'tls'),
        'smtp_debug' => env('ZEPTOMAIL_SMTP_DEBUG', 0),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
