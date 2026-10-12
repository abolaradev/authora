<?php

namespace Abolaradev\Authora\Services;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class UserEmailVerification
{
    /**
     * Configure and customize the email verification notification's subject and view.
     *
     * @return void
     */
    public static function customization(): void
    {
        $subject = config("authora.verification.email.subject");
        $view = config("authora.verification.email.view");

        if(!is_null($subject) && !is_null($view)){

            VerifyEmail::toMailUsing(function(object $notifiable, string $url) use($subject,$view) {
                return (new MailMessage)
                    ->subject($subject)
                    ->view($view,[
                        'user' => $notifiable,
                        'url' => $url
                    ]);
            });
        }
    }
}