<?php

namespace Abolaradev\Authora\Controllers;

use Abolaradev\Authora\Contracts\ShouldAuthenticateWithOAuth;
use Illuminate\Routing\Controller;

class OAuthController extends Controller
{
    /**
     * Redirect the user to the specified OAuth provider.
     *
     * @param string $provider The OAuth provider name.
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirect(string $provider)
    {
        return $this->provider($provider)->redirect(); 
    }

    /**
     * Handle the OAuth provider callback and authenticate the user.
     *
     * @param string $provider The OAuth provider name.
     * @return \Illuminate\Http\RedirectResponse
     */
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
