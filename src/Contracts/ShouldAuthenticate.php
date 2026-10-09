<?php

namespace Abolaradev\Authora\Contracts;

use Illuminate\Http\RedirectResponse;

interface ShouldAuthenticate
{
    /**
     * Authenticate the user.
     *
     * @return static
     */
    public function authenticate(): static|RedirectResponse;
}