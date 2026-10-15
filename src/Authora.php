<?php

namespace Abolaradev\Authora;

use Abolaradev\Authora\Enums\AuthenticationAction;
use Abolaradev\Authora\Enums\Feature;
use Abolaradev\Authora\Services\AuthRateLimiter;
use Abolaradev\Authora\Services\FeatureManager;
use Closure;

class Authora
{
    private array $credentials = [];

    /**
     * Set the credentials for the current authentication operation.
     *
     * @param array $credentials The credentials to be validated and processed.
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
        return FeatureManager::execute(Feature::Register, function () {
            $registerClass = Feature::Register->value;

            return app($registerClass)
                ->validate($this->credentials)
                ->authenticate();
        });
    }

    /**
     * Authenticate a user using password credentials.
     *
     * @return mixed
     */
    public function login(): mixed
    {
        return FeatureManager::execute(Feature::Login, function () {
            $loginClass = Feature::Login->value;

            return app($loginClass)
                ->validate($this->credentials)
                ->authenticate();
        });
    }

    /**
     * Log out the authenticated user.
     *
     * @return mixed
     */
    public function logout(): mixed
    {
        return FeatureManager::execute(Feature::Logout, function () {
            $logoutClass = Feature::Logout->value;

            return app($logoutClass)->handle();
        });
    }

    /**
     * Create an OTP-based authentication service.
     *
     * @return mixed
     */
    public function otp(): mixed
    {
        return FeatureManager::execute(Feature::Otp, function () {
            $otpClass = Feature::Otp->value;

            return app($otpClass, [
                'credentials' => $this->credentials,
            ]);
        });
    }

    /**
     * Redirect the user to the specified OAuth provider.
     *
     * @param string $provider The OAuth provider identifier.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function oauth(string $provider): \Illuminate\Http\RedirectResponse
    {
        return FeatureManager::execute(Feature::OAuth, function () use ($provider) {
            $route = route('oauth.redirect', ['provider' => $provider]);

            return redirect($route);
        });
    }

    /**
     * Send the email verification notification using the configured action.
     *
     * @return mixed
     */
    public function sendEmailVerificationNotification(): mixed
    {
        return FeatureManager::execute(Feature::EmailVerification, function () {
            $emailVerificationClass = Feature::EmailVerification->value;

            return app($emailVerificationClass)->send();
        });
    }

    /**
     * Resolve the password reset service with the current credentials.
     *
     * @return mixed
     */
    public function passwordReset(): mixed
    {
        return FeatureManager::execute(Feature::ResetPassword, function () {
            $resetPasswordClass = Feature::ResetPassword->value;

            return app($resetPasswordClass, [
                'credentials' => $this->credentials,
            ]);
        });
    }

    /**
     * Create a rate limiter for the given authentication action.
     *
     * @param AuthenticationAction $action The authentication action to limit.
     * @param Closure|null $onLimit The callback executed when the rate limit is exceeded.
     * @return AuthRateLimiter
     */
    public function rateLimiter(AuthenticationAction $action,?Closure $onLimit = null): AuthRateLimiter 
    {
        return app(AuthRateLimiter::class, [
            'action' => $action,
            'onLimit' => $onLimit,
        ]);
    }
}