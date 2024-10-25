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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'binance' => [
        'base_url' => env('BINANCE_API_URL', 'https://api.binance.com'),
    ],
    'poloniex' => [
        'base_url' => env('POLONIEX_API_URL', 'https://api.poloniex.com'),
    ],
    'jbex' => [
        'base_url' => env('JBEX_API_URL', 'https://api.jbex.com'),
    ],
    'bybit' => [
        'base_url' => env('BYBIT_API_URL', 'https://api.bybit.com'),
    ],
    'whitebit' => [
        'base_url' => env('WHITEBIT_API_URL', 'https://whitebit.com/api/v4'),
    ],
];
