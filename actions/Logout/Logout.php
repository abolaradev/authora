<?php

namespace App\Actions\Logout;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Logout
{
    /**
     * Log out the authenticated user and invalidate the current session.
     */
    public function handle(): void
    {
        Auth::logout();

        Session::invalidate();
        Session::regenerateToken();
    }
}