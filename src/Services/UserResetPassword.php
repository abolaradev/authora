<?php

namespace Abolaradev\Authora\Services;

class UserResetPassword extends AuthenticationService
{
    /**
     * Validate the credentials and send a password reset link.
     *
     * @return string
     */
    public function sendResetLink(): string
    {
        $sendResetLinkClass = config('authora.password.reset');

        return (new $sendResetLinkClass)->validate($this->getCredentials())
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

        return (new $saveNewPasswordClass)->validate($this->getCredentials())
                                            ->save();
    }
}
