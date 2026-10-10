<?php

namespace Abolaradev\Authora\Services;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class UserEmailVerification
{
    /**
     * Send the email verification notification using the configured action.
     *
     * @return mixed
     */
    public function send(): mixed
    {
        $emailVerificationClass = self::emailVerificationConfig('send');

        return (new $emailVerificationClass)->send();
    }

    /**
     * Configure the email verification notification's subject and view.
     *
     * @return void
     */
    public static function configure(): void
    {
        $subject = self::emailVerificationConfig('subject');
        $view = self::emailVerificationConfig('view');

        if(!empty($subject) && !empty($view)){

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

    /**
     * Retrieve an email verification setting from the package configuration.
     *
     * @param string $key The configuration key to retrieve.
     * @return string
     */
    private static function emailVerificationConfig(string $key): ?string
    {
        return config("authora.verification.email.$key");
    }
}