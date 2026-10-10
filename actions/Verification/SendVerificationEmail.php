<?php

namespace Abolaradev\Authora\Actions\Verification;

use Abolaradev\Authora\Contracts\ShouldSendNotification;
use Illuminate\Support\Facades\Auth;

class SendVerificationEmail implements ShouldSendNotification
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