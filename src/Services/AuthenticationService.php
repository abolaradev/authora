<?php

namespace Abolaradev\Authora\Services;

abstract class AuthenticationService
{
    /**
     * Create an authentication service with the given credentials.
     *
     * @param array $credentials
     */
    public function __construct(private string|array $credentials=[])
    {}

    /**
     * Get the authentication credentials.
     *
     * @return array<string, mixed>
     */
    protected function getCredentials(): string|array
    {
        return $this->credentials;
    }
}