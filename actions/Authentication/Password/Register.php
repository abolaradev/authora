<?php

namespace App\Actions\Authentication\Password;

use Abolaradev\Authora\Contracts\ShouldAuthenticate;
use Abolaradev\Authora\Traits\WithAuthenticationRedirect;
use Abolaradev\Authora\Traits\WithValidation;
use App\Actions\Rules\AuthoraPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class Register implements ShouldAuthenticate
{
    use WithValidation , WithAuthenticationRedirect;

    protected function rules(): array
    {
        return [
            'name' => 'required | alpha_dash',
            'email' => 'required  | email:filter,rfc,dns | unique:users',
            'password' => AuthoraPassword::rules(),
            'password_confirmation' => 'same:password'
        ];
    }


    public function authenticate(): static
    {
        $validated = $this->getValidatedData();

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password']
        ]);

        Auth::login($user);

        Session::regenerate();

        return $this;
    }
}