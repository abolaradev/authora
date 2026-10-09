<?php

namespace Abolaradev\Authora\Contracts;

use Laravel\Socialite\Contracts\User;
use Symfony\Component\HttpFoundation\RedirectResponse;

interface ShouldAuthenticateWithOAuth extends ShouldAuthenticate 
{
    /**
     * Redirect the user to the OAuth provider for authentication.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirect(): RedirectResponse;

    /**
     * Retrieve the authenticated user's information from the OAuth provider.
     *
     * @return \Laravel\Socialite\Contracts\User
     */
    public function getUser(): User;
}