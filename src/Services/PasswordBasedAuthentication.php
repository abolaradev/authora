<?php

namespace Abolaradev\Authora\Services;

use Abolaradev\Authora\Contracts\ShouldAuthenticate;

class PasswordBasedAuthentication extends AuthenticationService
{
    /**
     * Register a new user using password-based authentication.
     *
     * @return ShouldAuthenticate
     */
    public function register(): ShouldAuthenticate
    {
        $registerClass = config('authora.authentication.password.register');
       
        return (new $registerClass)->validate($this->getCredentials())
                                   ->authenticate();
    }

    /**
     * Authenticate a user using password credentials.
     *
     * @return ShouldAuthenticate
     */
    public function login(): ShouldAuthenticate
    {
        $loginClass = config('authora.authentication.password.login');

         return (new $loginClass)->validate($this->getCredentials())
                                 ->authenticate();
    }
}