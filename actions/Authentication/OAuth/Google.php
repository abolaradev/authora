<?php

namespace App\Actions\Authentication\OAuth;

use Abolaradev\Authora\Contracts\ShouldAuthenticateWithOAuth;
use Abolaradev\Authora\Traits\WithAuthenticationRedirect;
use Abolaradev\Authora\Traits\WithOAuthAuthentication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class Google implements ShouldAuthenticateWithOAuth
{
    use WithOAuthAuthentication , WithAuthenticationRedirect;

    /**
     * Authenticate the user using their Google account.
     *
     * @return RedirectResponse
     */
    public function authenticate(): RedirectResponse
    {
        $googleUser = $this->getUser();

        $user = User::updateOrCreate(
            [
                'email' => $googleUser->getEmail()
            ]
            ,
            [
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'email_verified_at' => now()
            ]
        );

        Auth::login($user);

        Session::regenerate();

        return $this->thenRedirect();
    }
}