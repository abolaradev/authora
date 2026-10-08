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
        $otpSendClass = $this->action('authentication.otp.dispatch');

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
        $otpVerifyClass = $this->action('authentication.otp.verify');

        return (new $otpVerifyClass)->validate($this->getCredentials())
                                    ->authenticate();
    }
}