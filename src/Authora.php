<?php

namespace Abolaradev\Authora;

use Abolaradev\Authora\Enums\AuthenticationAction;
use Abolaradev\Authora\Services\AuthRateLimiter;
use Abolaradev\Authora\Services\OtpBasedAuthentication;
use Abolaradev\Authora\Services\UserResetPassword;
use Closure;
use Illuminate\Http\RedirectResponse;

class Authora
{
    private array $credentials = [];

    /**
     * Set the credentials for the current authentication operation.
     *
     * @param array $credentials The credentials to be validated and processed.
     * 
     * @return $this
     */
    public function credentials(array $credentials): static
    {
        $this->credentials = $credentials;

        return $this;
    }

    /**
     * Register a new user using password-based authentication.
     *
     * @return mixed
     */
    public function register(): mixed
    {
        $registerClass = config('authora.authentication.password.register');
       
        return (new $registerClass)->validate($this->credentials)
                                   ->authenticate();
    }

    /**
     * Authenticate a user using password credentials.
     *
     * @return mixed
     */
    public function login(): mixed
    {
        $loginClass = config('authora.authentication.password.login');

         return (new $loginClass)->validate($this->credentials)
                                 ->authenticate();
    }

    /**
     * Log out the authenticated user.
     * 
     * @return mixed
     */
    public function logout(): mixed
    {
        $logoutClass = config('authora.logout');

        return (new $logoutClass)->handle();
    }

    /**
     * Create an OTP-based authentication service.
     *
     * @return OtpBasedAuthentication
     */
    public function otp(): OtpBasedAuthentication
    {
        return app(OtpBasedAuthentication::class,['credentials'=>$this->credentials]);
    }

    /**
     * Redirect the user to the OAuth provider.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function oauth(string $provider): RedirectResponse
    {
        $route = route('oauth.redirect', ['provider' => $provider]);

        return redirect($route);
    }

    /**
     * Send the email verification notification using the configured action.
     *
     * @return mixed
     */
    public function sendEmailVerificationNotification(): mixed
    {
        $emailVerificationClass = config('authora.verification.email.send');

        return (new $emailVerificationClass)->send();
    }

    /**
     * Resolve the password reset service with the given credentials.
     *
     * @return UserResetPassword
     */
    public function passwordReset(): UserResetPassword
    {
        return app(UserResetPassword::class,['credentials'=>$this->credentials]);
    }
}