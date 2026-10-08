<?php

namespace App\Actions\Authentication\Password;

use Abolaradev\Authora\Contracts\ShouldAuthenticate;
use Abolaradev\Authora\Traits\WithAuthenticationRedirect;
use Abolaradev\Authora\Traits\WithValidation;
use App\Actions\Rules\Email;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class Login implements ShouldAuthenticate
{
    use WithValidation , WithAuthenticationRedirect;

    /**
     * Get the validation rules for password authentication.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'email' => Email::rules(),
            'password' => 'required',
            'remember' => ' boolean'
        ];
    }

    /**
     * Authenticate the user using their email and password.
     *
     * @return static
     *
     * @throws ValidationException
     */
    public function authenticate(): static
    {
        $validated = $this->getValidatedData();

        $user = User::where('email','=',$validated['email'])->first();

        if(!$user || !Hash::check($validated['password'],$user->password)){
            throw ValidationException::withMessages([
                 'email' => 'The provided login credentials are incorrect.'
            ]);
        }

        Auth::login($user,$validated['remember'] ?? false);

        Session::regenerate();

        return $this;
    }
}