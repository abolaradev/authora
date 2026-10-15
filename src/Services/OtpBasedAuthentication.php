<?php

namespace Abolaradev\Authora\Services;

use Abolaradev\Authora\Enums\Feature;

class OtpBasedAuthentication
{
    private array $credentials;

    private array $actions;

    public function __construct(array $credentials)
    {
        $this->credentials = $credentials;
        $this->actions = Feature::otpActions();
    }

    /**
     * Dispatch an OTP using the configured authentication action.
     *
     * @return mixed
     */
    public function dispatch(): mixed
    {
        $otpSendClass = $this->actions['dispatch'];

        return app($otpSendClass)->validate($this->credentials)
                                 ->send();
    }

    /**
     * Verify the OTP using the configured authentication action.
     *
     * @return mixed
     */
    public function verify(): mixed
    {
        $otpVerifyClass = $this->actions['verify'];

        return app($otpVerifyClass)->validate($this->credentials)
                                    ->authenticate();
    }
}