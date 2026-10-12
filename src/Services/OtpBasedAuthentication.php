<?php

namespace Abolaradev\Authora\Services;

class OtpBasedAuthentication
{
    public function __construct(private array $credentials)
    {}

    /**
     * Dispatch an OTP using the configured authentication action.
     *
     * @return mixed
     */
    public function dispatch(): mixed
    {
        $otpSendClass = config('authora.authentication.otp.dispatch');

        return (new $otpSendClass)->validate($this->credentials)
                                  ->send();
    }

    /**
     * Verify the OTP using the configured authentication action.
     *
     * @return mixed
     */
    public function verify(): mixed
    {
        $otpVerifyClass = config('authora.authentication.otp.verify');

        return (new $otpVerifyClass)->validate($this->credentials)
                                    ->authenticate();
    }
}