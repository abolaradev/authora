<?php

namespace Abolaradev\Authora;

use Abolaradev\Authora\Contracts\ShouldAuthenticate;
use Abolaradev\Authora\Services\OtpBasedAuthentication;
use Abolaradev\Authora\Services\PasswordBasedAuthentication;

class Authora
{
    /**
     * Register a new user using password-based authentication.
     *
     * @param array $credentials
     * @return ShouldAuthenticate
     */
    public function register(array $credentials): ShouldAuthenticate
    {
        return (new PasswordBasedAuthentication($credentials))->register();
    }

    /**
     * Authenticate a user using password credentials.
     *
     * @param array $credentials
     * @return ShouldAuthenticate
     */
    public function login(array $credentials): ShouldAuthenticate
    {
        return (new PasswordBasedAuthentication($credentials))->login();
    }

    /**
     * Create an OTP-based authentication service.
     *
     * @param array $credentials
     * @return OtpBasedAuthentication
     */
    public function otp(array $credentials): OtpBasedAuthentication
    {
        return new OtpBasedAuthentication($credentials);
    }

    /**
     * Log out the authenticated user.
     */
    public function logout(): void
    {
        $logoutClass = config('authora.actions.logout');

        (new $logoutClass)->handle();
    }
}