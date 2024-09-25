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

    'facebook' => [
        'page_id' => '412442358622297',
        'page_access_token' => 'EAAUrQQlB87YBO6niBKwnNdV6vu3PUoSBUtp3l8QtYFPFnvI2BkFCkOqHHQn0iuvZBZBSwh0XedD6b8RGSi1xiWSHI31VDMPTZAxXSEIJADRqClxEq9ZClfqPUzJEGZCCmGrd3qTwr2aqYnGZAKgvInPuMZB2zBnBTWtUOoXYWBZBIUCvvp7BMO9lUzziZBZAJMAB1m6ZC42j39NwsmoIA8qTsd38ltd',
    ],

];
