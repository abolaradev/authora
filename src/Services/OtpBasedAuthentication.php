<?php

namespace Abolaradev\Authora\Services;

use Abolaradev\Authora\Contracts\ShouldAuthenticate;

class OtpBasedAuthentication extends AuthenticationService
{
    /**
     * Dispatch an OTP using the configured authentication action.
     *
     * @return mixed
     */
    public function dispatch(): mixed
    {
        $otpSendClass = config('authora.authentication.otp.dispatch');

        return (new $otpSendClass)->validate($this->getCredentials())
                                  ->dispatch();
    }

    /**
     * Verify the OTP using the configured authentication action.
     *
     * @return ShouldAuthenticate
     */
    public function verify(): ShouldAuthenticate
    {
        $otpVerifyClass = config('authora.authentication.otp.verify');

        return (new $otpVerifyClass)->validate($this->getCredentials())
                                    ->authenticate();
    }
}