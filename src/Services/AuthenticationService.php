<?php

namespace Abolaradev\Authora\Services;

use Illuminate\Support\Arr;

abstract class AuthenticationService
{
    /**
     * Create an authentication service with the given credentials.
     *
     * @param array $credentials
     */
    public function __construct(private array $credentials=[])
    {}

    /**
     * Get the authentication credentials.
     *
     * @return array<string, mixed>
     */
    protected function getCredentials(): array
    {
        return $this->credentials;
    }

    /**
     * Resolve an authentication action from the configuration.
     *
     * @param string $action The authentication action key.
     * @return class-string
     */
    protected function action(string $action): string
    {
        $config = config('authora.actions');

        return Arr::get($config, $action);
    }
}