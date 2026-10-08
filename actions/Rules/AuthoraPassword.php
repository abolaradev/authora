<?php

namespace App\Actions\Rules;

use Illuminate\Validation\Rules\Password;

class AuthoraPassword 
{
    /**
     * Get the default password validation rules.
     *
     * @return array<int, string|\Illuminate\Validation\Rules\Password>
     */
    public static function rules(): array
    {
        return [
            'required',
             Password::min(8)
                     ->max(16)
                     ->mixedCase()
                     ->symbols()
                     ->numbers()
        ];
    }
}
