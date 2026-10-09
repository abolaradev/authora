<?php

namespace Abolaradev\Authora\Controllers;

use Abolaradev\Authora\Contracts\ShouldAuthenticateWithOAuth;

class OAuthController
{
    public function redirect(string $provider)
    {
        return $this->provider($provider)->redirect(); 
    }

    public function callback(string $provider)
    {
        return $this->provider($provider)->authenticate(); 
    }

    /**
     * Resolve the configured OAuth provider.
     */
    protected function provider(string $provider): ShouldAuthenticateWithOAuth
    {
        $providerClass = config("authora.authentication.oauth.providers.{$provider}");

        return new $providerClass($provider);
    }
}
