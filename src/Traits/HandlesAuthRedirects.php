<?php

namespace Abolaradev\Authora\Traits;

use Illuminate\Http\RedirectResponse;

trait HandlesAuthRedirects
{
    /**
     * Redirect to the specified destination.
     *
     * @param string|null $to The redirect destination.
     * @return RedirectResponse
     */
    public function redirectTo(?string $to = null): RedirectResponse
    {
        return redirect($to);
    }

    /**
     * Redirect to the configured login page.
     *
     * @return RedirectResponse
     */
    public function redirectToLogin(): RedirectResponse
    {
        $login = config('authora.redirect_to.login');

        return $this->redirectTo($login);
    }

    /**
     * Redirect to the configured dashboard page.
     *
     * @return RedirectResponse
     */
    public function redirectToDashboard(): RedirectResponse
    {
        $dashboard = config('authora.redirect_to.dashboard');

        return $this->redirectTo($dashboard);
    }
}