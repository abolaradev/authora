<?php

use Abolaradev\Authora\Enums\Feature;

return [

    /*
    |--------------------------------------------------------------------------
    | Redirect URLs
    |--------------------------------------------------------------------------
    |
    | Define the URLs users should be redirected to after authentication.
    |
    */

    'redirect_to' => [
        'login' => '/login',
        'dashboard' => '/dashboard',
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Configure the maximum number of authentication attempts and the
    | number of seconds before the attempt counter is reset.
    |
    */

    'rate_limiter' => [
        'max_attempts' => 50,
        'decay_seconds' => 3600,
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Options
    |--------------------------------------------------------------------------
    |
    | Configure the options for individual authentication features.
    |
    */

    'options' => [

        /*
        |--------------------------------------------------------------------------
        | OAuth Authentication
        |--------------------------------------------------------------------------
        |
        | Register OAuth providers and customize their redirect and callback
        | routes. Each provider should reference its corresponding action class.
        |
        */

        'oauth' => [
            'providers' => [
                // 'google' => \App\Actions\Authentication\OAuth\Google::class,
            ],

            'routes' => [
                'redirect' => '/auth/{provider}/redirect',
                'callback' => '/auth/{provider}/callback',
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Email Verification
        |--------------------------------------------------------------------------
        |
        | Configure the email notification, verification notice view, and routes.
        |
        */

        'email_verification' => [

            'notice_view' => null,

            'routes' => [
                'notice' => '/email/verify',
                'verify' => '/email/verify/{id}/{hash}',
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Enabled Features
    |--------------------------------------------------------------------------
    |
    | List the authentication features that are enabled for the application.
    |
    */

    'features' => [
        Feature::Register,
        Feature::Login,
        Feature::Logout,
        Feature::ResetPassword,
        // Feature::OAuth,
        // Feature::Otp,
        Feature::EmailVerification,
    ],

];