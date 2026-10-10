<?php

namespace Abolaradev\Authora;

use Abolaradev\Authora\Contracts\ShouldAuthenticate;
use Abolaradev\Authora\Services\OAuthBasedAuthentication;
use Abolaradev\Authora\Services\OtpBasedAuthentication;
use Abolaradev\Authora\Services\PasswordBasedAuthentication;
use Abolaradev\Authora\Services\UserEmailVerification;

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
        return app(PasswordBasedAuthentication::class,['credentials' => $credentials])
               ->register();
    }

    /**
     * Authenticate a user using password credentials.
     *
     * @param array $credentials
     * @return ShouldAuthenticate
     */
    public function login(array $credentials): ShouldAuthenticate
    {
        return app(PasswordBasedAuthentication::class,['credentials' => $credentials])
               ->login();
    }

    /**
     * Create an OTP-based authentication service.
     *
     * @param array $credentials
     * @return OtpBasedAuthentication
     */
    public function otp(array $credentials): OtpBasedAuthentication
    {
        return app(OtpBasedAuthentication::class,['credentials' => $credentials]);
    }

    /**
     * Create an OAuth-based authentication service for the given provider.
     *
     * @param string $provider The OAuth provider name.
     * @return OAuthBasedAuthentication
     */
    public function oauth(string $provider): OAuthBasedAuthentication
    {
        return app(OAuthBasedAuthentication::class , ['credentials' => $provider]);
    }

    /**
     * Log out the authenticated user.
     */
    public function logout(): void
    {
        $logoutClass = config('authora.logout');

        (new $logoutClass)->handle();
    }

    /**
     * Send an email verification notification to the user.
     *
     * @return mixed
     */
    public function sendVerificationEmail(): mixed
    {
        return app(UserEmailVerification::class)->send();
    }
}