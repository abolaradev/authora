<?php

namespace Abolaradev\Authora\Actions\Authentication\Password;

use App\Actions\Rules\Email;
use Abolaradev\Authora\Contracts\ShouldAuthenticate;
use Abolaradev\Authora\Enums\AuthenticationAction;
use Abolaradev\Authora\Facades\Authora;
use Abolaradev\Authora\Traits\HandlesAuthRedirects;
use Abolaradev\Authora\Traits\WithValidation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class Login implements ShouldAuthenticate
{
    use WithValidation , HandlesAuthRedirects;

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
     * @throws ValidationException
     */
    public function authenticate()
    {
        $execute = Authora::rateLimiter(AuthenticationAction::Login)
                          ->execute();
      
        if($execute){
             $validated = $this->getValidatedData();

            $user = User::where('email','=',$validated['email'])->first();

            if(!$user || !Hash::check($validated['password'],$user->password)){
                throw ValidationException::withMessages([
                    'email' => __('auth.failed'),
                ]);
            }

            Auth::login($user,$validated['remember'] ?? false);

            Session::regenerate();

            return $this->redirectToDashboard();
        }
    }
}