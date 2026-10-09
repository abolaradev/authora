<?php

namespace Abolaradev\Authora\Services;

use Abolaradev\Authora\Controllers\OAuthController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

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

    /**
     * Register the OAuth authentication routes.
     *
     * @return void
     */
    public static function routes(): void
    {
        $routes = config("authora.authentication.oauth.routes");
    
        Route::middleware(['web' , 'guest'])->controller(OAuthController::class)->group(function() use($routes){

            Route::get($routes['redirect'] , 'redirect')
                 ->name('oauth.redirect');
            
            Route::get($routes['callback'] , 'callback')
                 ->name('oauth.callback');
        });
    }
}