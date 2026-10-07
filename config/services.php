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
        'endpoint' => env(
            'ZEPTOMAIL_ENDPOINT',
            'https://api.zeptomail.com/v1.1/email'
        ),
        'api_key' => env('ZEPTOMAIL_API_KEY'),
        'from_address' => env('MAIL_FROM_ADDRESS'),
        'from_name' => env('MAIL_FROM_NAME', 'PA Online'),

        // For your sendEmailSmtp() method
        'smtp_host' => env('ZEPTOMAIL_SMTP_HOST', 'smtp.zeptomail.com'),
        'smtp_port' => env('ZEPTOMAIL_SMTP_PORT', 587),
        'smtp_username' => env('ZEPTOMAIL_SMTP_USERNAME', 'emailapikey'),
        'smtp_secure' => env('ZEPTOMAIL_SMTP_SECURE', 'tls'),
        'smtp_debug' => 0,
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

    'kbank' => [
        'public_key' => env('KBANK_PUBLIC_KEY', 'pkey_test_22500dOmwrzgdQXT7BZghVMHnHijHyJB9JN2J'),
        'private_key' => env('KBANK_PRIVATE_KEY', 'skey_test_22500QnBdlhrUgSfdxbM08IOByagApQIXWNKI'),
        'merchant_name' => env('KBANK_MASTER_MERCHANT_ID', 'SAHAMONGKHON INSURANCE'),
        'master_merchant_id' => env('KBANK_MASTER_MERCHANT_ID', '401926120298001'),
        'create_qr_url' => env('KBANK_CREATE_QR_URL', 'https://dev-kpaymentgateway-services.kasikornbank.com/qr/v2/order'),
        'create_link_url' => env('KBANK_CREATE_LINK_URL', 'https://dev-kpaymentgateway-services.kasikornbank.com/KPGW-Payment-Webapi/public/api/payment-link/create'),
        'link_merchant_id_1' => env('KBANK_LINK_MERCHANT_ID_1', '401926120244001'),
        'link_merchant_id_2' => env('KBANK_LINK_MERCHANT_ID_2', '401926158478001'),
        'inquiry_payment_link_url' => env('KBANK_LINK_INQUIRY_URL', 'https://dev-kpaymentgateway-services.kasikornbank.com/KPGW-Payment-Webapi/public/api/payment-link')
    ],
];
