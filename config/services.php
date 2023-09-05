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

    'copyright' => [
        'key' => env('COPYRIGHT'),
    ],

    'role' => [
        'admin' => 'Admin',
        'engineer' => 'Engineer',
    ],
    'pick_by' => [
        'UPS' => 'shipped_by_UPS',
        'EPT' => 'shipped_by_EPT',
        'FSE' => 'picked_by_FSE',
    ],

    'drop_by' => [
        'UPS' => 'dropped_by_UPS',
        'EPT' => 'dropped_by_EPT',
        'FSE' => 'dropped_by_FSE',
    ],

    'type' => [
        'hospital' => 'Hospital',
        'hub' => 'Hub',
    ],

    'status' => [
        'available' => 'Available',
        'busy' => 'Under Investigation',
        'calibration' => 'Calibration',
    ],

    'tool_condition' => [
        'good' => 'good',
        'bad' => 'bad',
    ],

    'from_mail' => '',

    'admin_tool_request_return_filters' => [
        'new' => 'New',
        'history' => 'History',
    ],

];
