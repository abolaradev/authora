<?php

namespace Abolaradev\Authora;

use Abolaradev\Authora\Contracts\ShouldAuthenticate;
use Abolaradev\Authora\Services\OtpBasedAuthentication;
use Illuminate\Support\Arr;

class Authora
{
    /**
     * Resolve an authentication action class from the configuration.
     *
     * @param string $action The authentication action key.
     * @return class-string
     */
    private function authenticateAction(string $action): string
    {
        $config = config('authora.actions.authentication');

        return Arr::get($config, $action);
    }

    /**
     * Register a new user using password authentication.
     *
     * @param array<string, mixed> $credentials
     * @return \Abolaradev\Authora\Contracts\ShouldAuthenticate
     */
    public function register(array $credentials): ShouldAuthenticate
    {
        $registerClass = $this->authenticateAction('password.register');

        return (new $registerClass)->validate($credentials)
                                   ->authenticate();
    }

    /**
     * Authenticate a user using password credentials.
     *
     * @param array<string, mixed> $credentials
     * @return \Abolaradev\Authora\Contracts\ShouldAuthenticate
     */
    public function login(array $credentials): ShouldAuthenticate
    {
        $loginClass = $this->authenticateAction('password.login');

        return (new $loginClass)->validate($credentials)
                                ->authenticate();
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
        $logoutClass = config('authora.logout');

        (new $logoutClass)->handle();
    }
}