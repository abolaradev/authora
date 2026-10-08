<?php

namespace Abolaradev\Authora\Contracts;

interface ShouldAuthenticate
{
    /**
     * Authenticate the user.
     *
     * @return static
     */
    public function authenticate(): static;
}