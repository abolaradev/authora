<?php

namespace Abolaradev\Authora\Controllers;

use Abolaradev\Authora\Traits\HandlesAuthRedirects;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;

class EmailVerificationController extends Controller
{
    use HandlesAuthRedirects;

    /**
     * Display the email verification notice if a view is configured.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function notice(): ?View
    {
        $view = config('authora.verification.notice_view');

        if(is_null($view)){
            return null;
        }

        return view($view);
    }

    /**
     * Verify the user's email address and redirect them to the configured destination.
     *
     * @param \Illuminate\Foundation\Auth\EmailVerificationRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        $request->fulfill();

        return $this->redirectToDashboard();
    }
}
