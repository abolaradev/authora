<?php

namespace Abolaradev\Authora\Traits;

trait WithAuthenticationRedirect
{
    /**
     * Redirect the user after authentication.
     *
     * @param string|null $to The URL to redirect to.
     */
    public function thenRedirect(?string $to = null)
    {
        $url = $to ?? config('authora.redirect_to');

        return redirect($url);
    }
}