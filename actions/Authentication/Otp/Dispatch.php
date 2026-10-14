<?php

namespace App\Actions\Authentication\Otp;

use Abolaradev\Authora\Contracts\ShouldSendNotification;
use Abolaradev\Authora\Traits\HandlesAuthRedirects;
use Abolaradev\Authora\Traits\WithValidation;
use Abolaradev\Otp\Exceptions\OtpException;
use Abolaradev\Otp\Facades\Otp;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class Dispatch implements ShouldSendNotification
{
    use WithValidation , HandlesAuthRedirects;

    /**
     * Get the validation rules for OTP recipient.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'mobile' => 'required | ir_mobile'
        ];
    }

    /**
     * Dispatch an OTP to the given mobile number.
     *
     * @return static
     *
     * @throws ValidationException
     */
    public function send(): static
    {
        try {

            $validated = $this->getValidatedData();

            Otp::to($validated['mobile'])
               ->purpose('auth')
               ->dispatch();

            Session::put('mobile' , $validated['mobile']);

            return $this;

        } catch (OtpException $e) {

            throw ValidationException::withMessages([
                'mobile' => $e->getMessage()
            ]);

        }
    }
}