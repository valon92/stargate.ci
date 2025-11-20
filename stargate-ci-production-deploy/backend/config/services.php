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
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    /*
    |--------------------------------------------------------------------------
    | External API Services
    |--------------------------------------------------------------------------
    |
    | Configuration for external API services used in the Stargate project
    |
    */

    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
        'organization' => env('OPENAI_ORGANIZATION'),
        'timeout' => env('OPENAI_TIMEOUT', 30),
        'max_tokens' => env('OPENAI_MAX_TOKENS', 4000),
        'temperature' => env('OPENAI_TEMPERATURE', 0.7),
    ],

    'softbank' => [
        'api_key' => env('SOFTBANK_API_KEY'),
        'base_url' => env('SOFTBANK_BASE_URL', 'https://api.softbank.com/v1'),
        'timeout' => env('SOFTBANK_TIMEOUT', 30),
        'version' => env('SOFTBANK_API_VERSION', '2024-01'),
    ],

    'oracle' => [
        'api_key' => env('ORACLE_API_KEY'),
        'base_url' => env('ORACLE_BASE_URL', 'https://api.oracle.com/v1'),
        'timeout' => env('ORACLE_TIMEOUT', 30),
        'client_id' => env('ORACLE_CLIENT_ID'),
        'client_secret' => env('ORACLE_CLIENT_SECRET'),
    ],

    'mgx' => [
        'api_key' => env('MGX_API_KEY'),
        'base_url' => env('MGX_BASE_URL', 'https://api.mgx.com/v1'),
        'timeout' => env('MGX_TIMEOUT', 30),
        'client_id' => env('MGX_CLIENT_ID'),
        'client_secret' => env('MGX_CLIENT_SECRET'),
    ],


    'google' => [
        'analytics' => [
            'tracking_id' => env('GOOGLE_ANALYTICS_TRACKING_ID'),
            'measurement_id' => env('GOOGLE_ANALYTICS_MEASUREMENT_ID'),
        ],
    ],

];