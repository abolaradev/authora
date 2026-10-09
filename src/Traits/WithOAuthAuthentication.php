<?php

namespace Abolaradev\Authora\Traits;

use Laravel\Socialite\Contracts\User;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

trait WithOAuthAuthentication
{
    /**
     * Initialize the OAuth provider.
     *
     * @param string $provider The OAuth provider name.
     */
    public function __construct(private string $provider)
    {}

    /**
     * Redirect the user to the OAuth provider for authentication.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirect(): RedirectResponse
    {
        return Socialite::driver($this->provider)
                        ->with(['prompt' => 'select_account'])
                        ->redirect();
    }

    /**
     * Retrieve the authenticated user's details from the OAuth provider.
     *
     * @return \Laravel\Socialite\Contracts\User
     */
    public function getUser(): User
    {
        return Socialite::driver($this->provider)
                        ->user();
    }
}