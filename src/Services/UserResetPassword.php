<?php

namespace Abolaradev\Authora\Services;

use Abolaradev\Authora\Enums\Feature;

class UserResetPassword
{
    private array $credentials;

    private array $actions;

    public function __construct(array $credentials)
    {
        $this->credentials = $credentials;
        $this->actions = Feature::resetPasswordActions();
    }

    /**
     * Validate the credentials and send a password reset link.
     *
     * @return string
     */
    public function sendResetLink(): string
    {
        $sendResetLinkClass = $this->actions['send_link'];

        return app($sendResetLinkClass)->validate($this->credentials)
                                       ->send();
    }

    /**
     * Validate the credentials and save the new password.
     *
     * @return string
     */
    public function saveNewPassword(): string
    {
        $saveNewPasswordClass = $this->actions['save_password'];

        return app($saveNewPasswordClass)->validate($this->credentials)
                                         ->save();
    }
}
