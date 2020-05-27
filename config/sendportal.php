<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Supported Locale
    |--------------------------------------------------------------------------
    |
    | This array holds the list of supported locale for Sendportal.
    |
    */

    'locale' => [
        'supported' => [
            'en' => ['name' => 'English', 'native' => 'English']
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Auth Settings
    |--------------------------------------------------------------------------
    |
    | Configure the Sendportal authentication functionality.
    |
    */
    'auth' => [
        'register' => env('SENDPORTAL_REGISTER', false),
        'password_reset' => env('SENDPORTAL_PASSWORD_RESET', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | API Throttle Settings
    |--------------------------------------------------------------------------
    |
    | Configure the Sendportal API throttling.
    | For more information see https://laravel.com/docs/master/routing#rate-limiting
    |
    */
    'throttle_middleware' => 'throttle:' . env('SENDPORTAL_THROTTLE_MIDDLEWARE', '60,1'),
];
