<?php

namespace Abolaradev\Authora\Services;

class UserResetPassword
{
    public function __construct(private array $credentials)
    {}

    /**
     * Validate the credentials and send a password reset link.
     *
     * @return string
     */
    public function sendResetLink(): string
    {
        $sendResetLinkClass = config('authora.password.reset');

        return (new $sendResetLinkClass)->validate($this->credentials)
                                        ->send();
    }

    /**
     * Validate the credentials and save the new password.
     *
     * @return string
     */
    public function saveNewPassword(): string
    {
        $saveNewPasswordClass = config('authora.password.save');

        return (new $saveNewPasswordClass)->validate($this->credentials)
                                          ->save();
    }
}
