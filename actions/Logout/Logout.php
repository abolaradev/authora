<?php

namespace App\Actions\Logout;

use Abolaradev\Authora\Traits\HandlesAuthRedirects;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Logout
{
    use HandlesAuthRedirects;

    /**
     * Log out the authenticated user and invalidate the current session.
     */
    public function handle()
    {
        Auth::logout();

        Session::invalidate();
        Session::regenerateToken();

        return $this->redirectToLogin();
    }
}