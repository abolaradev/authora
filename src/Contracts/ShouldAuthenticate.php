<?php

namespace Abolaradev\Authora\Contracts;


interface ShouldAuthenticate
{
    /**
     * Authenticate the user.
     */
    public function authenticate();
}