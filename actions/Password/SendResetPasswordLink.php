<?php

namespace App\Actions\Password;

use App\Actions\Rules\Email;
use Abolaradev\Authora\Contracts\ShouldSendNotification;
use Abolaradev\Authora\Traits\WithValidation;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class SendResetPasswordLink implements ShouldSendNotification
{
    use WithValidation;

    /**
     * Get the validation rules for the email field.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'email' => Email::rules()
        ];
    }

    /**
     * Send a password reset link to the user's email address.
     *
     * @return string
     *
     * @throws ValidationException If the reset link could not be sent.
     */
    public function send(): string
    {
        $validated = $this->getValidatedData();
        $status = Password::sendResetLink($validated);

        
        if($status !== Password::ResetLinkSent){
            throw ValidationException::withMessages([
                'email' => __($status)
            ]);
        }
        
        return __($status);
    }
}