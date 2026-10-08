<?php

namespace App\Actions\Authentication\Password;

use Abolaradev\Authora\Contracts\ShouldAuthenticate;
use Abolaradev\Authora\Traits\WithAuthenticationRedirect;
use Abolaradev\Authora\Traits\WithValidation;
use App\Actions\Rules\Email;
use App\Actions\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Arr;

class Register implements ShouldAuthenticate
{
    use WithValidation , WithAuthenticationRedirect;

    /**
     * Get the validation rules for user registration.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'name' => 'required | alpha_dash',
            'email' => Arr::flatten([Email::rules(),'unique:users']),
            'password' => Password::rules(),
            'password_confirmation' => 'same:password'
        ];
    }

    /**
     * Register and authenticate a new user.
     *
     * @return static
     */
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