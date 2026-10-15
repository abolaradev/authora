<?php

use Abolaradev\Authora\Controllers\EmailVerificationController;
use Abolaradev\Authora\Controllers\OAuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function(){

    // Register the OAuth authentication routes.
    Route::middleware('guest')->controller(OAuthController::class)->group(function(){
        $routes = config("authora.options.oauth.routes");

        Route::get($routes['redirect'] , 'redirect')
             ->name('oauth.redirect');
        
        Route::get($routes['callback'] , 'callback')
             ->name('oauth.callback');
    });

    // Register the Email Verification Routes
    Route::middleware('auth')->controller(EmailVerificationController::class)->group(function(){
        $routes = config("authora.options.email_verification.routes");

        Route::get($routes['notice'],'notice')
             ->name('verification.notice');
        
        Route::get($routes['verify'],'verify')
             ->middleware('signed')
             ->name('verification.verify');
    });
});