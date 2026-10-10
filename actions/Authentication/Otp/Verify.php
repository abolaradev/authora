<?php

namespace App\Actions\Authentication\Otp;

use Abolaradev\Authora\Contracts\ShouldAuthenticate;
use Abolaradev\Authora\Traits\WithAuthenticationRedirect;
use Abolaradev\Authora\Traits\WithValidation;
use Abolaradev\Otp\Exceptions\OtpException;
use Abolaradev\Otp\Facades\Otp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class Verify implements ShouldAuthenticate
{
    use WithValidation , WithAuthenticationRedirect;

    /**
     * Get the validation rules for OTP verification.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'code' => 'required | integer | digits:6'
        ];
    }

    /**
     * Verify the OTP and authenticate the user.
     *
     * @return static
     *
     * @throws ValidationException
     */
    public function authenticate(): static
    {
        try {
        
            $validated = $this->getValidatedData();

            $mobile = Session::get('mobile');

            Otp::from($mobile)
               ->purpose('auth')
               ->token($validated['code'])
               ->verify();
               
            $user=User::updateOrCreate(
                [
                    'mobile' => $mobile
                ]
                ,
                [
                    'mobile' => $mobile,
                ]
            );

            $user->forceFill([
                'mobile_verified_at' => now()
            ])->save();
            
            Auth::login($user);

            Session::forget('mobile');

            Session::regenerate();

            return $this;

        } catch (OtpException $e) {

            throw ValidationException::withMessages([
                'code' => $e->getMessage()
            ]);

        }
    }
}