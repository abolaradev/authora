<?php

namespace App\Actions\Password;

use App\Actions\Rules\Email;
use App\Actions\Rules\Password as RulesPassword;
use Abolaradev\Authora\Traits\HandlesAuthRedirects;
use Abolaradev\Authora\Traits\WithValidation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class SaveNewPassword
{
    use WithValidation , HandlesAuthRedirects;

    /**
     * Get the validation rules for the password reset form.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'token' => 'required',
            'email' => Email::rules(),
            'password' => RulesPassword::rules(),
            'password_confirmation' => 'same:password'
        ];
    }

    /**
     * Reset the user's password using the provided token.
     * 
     * @throws ValidationException If the password reset fails.
     */
    public function save()
    {
        $validated = $this->getValidatedData();

        $status = Password::reset(
            $validated,
            function (User $user, string $password) {

                $user->forceFill(['password' => Hash::make($password)])
                     ->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        if($status !== Password::PasswordReset){
            throw ValidationException::withMessages([
                'password' => __($status)
            ]);
        }

        return $this->redirectToLogin()
                    ->with('status' , __($status));
       
    }
}