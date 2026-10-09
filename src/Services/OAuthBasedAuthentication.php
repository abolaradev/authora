<?php

namespace Abolaradev\Authora\Services;

use Illuminate\Http\RedirectResponse;

class OAuthBasedAuthentication extends AuthenticationService
{
    /**
     * Redirect the user to the OAuth provider.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect(): RedirectResponse
    {
        $route = route('oauth.redirect', ['provider' => $this->getCredentials()]);

        return redirect($route);
    }
}