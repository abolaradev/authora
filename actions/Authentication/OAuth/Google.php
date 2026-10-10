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
     * @return static
     */
    public function authenticate(): static
    {
        $googleUser = $this->getUser();

        $user = User::updateOrCreate(
            [
                'email' => $googleUser->getEmail()
            ]
            ,
            [
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail()
            ]
        );

        $user->forceFill([
            'email_verified_at' => now()
        ])->save();

        Auth::login($user);

        Session::regenerate();

        return $this;
    }
}