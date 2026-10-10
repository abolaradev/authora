<?php

namespace Abolaradev\Authora\Actions\Verification;

use Illuminate\Support\Facades\Auth;

class SendVerificationEmail
{
    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function send(): void
    {
        Auth::user()->sendEmailVerificationNotification();
    }
}