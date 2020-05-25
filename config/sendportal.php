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
];