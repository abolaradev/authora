<?php

namespace App\Actions\Verification;

use Abolaradev\Authora\Contracts\ShouldSendNotification;
use Abolaradev\Authora\Enums\AuthenticationAction;
use Abolaradev\Authora\Facades\Authora;
use Illuminate\Support\Facades\Auth;

class SendEmailVerificationNotification implements ShouldSendNotification
{
    /**
     * Send the email verification notification.
     *
     */
    public function send()
    {
        $execute = Authora::rateLimiter(AuthenticationAction::SendEmailVerification)
                          ->maxAttempts(3)
                          ->execute();
      
        if($execute){
            $user = Auth::user();

            if(!$user->hasVerifiedEmail()){
                Auth::user()->sendEmailVerificationNotification();
            }
        }
    }
}