<?php

namespace App\Actions\Rules;

use Abolaradev\Authora\Contracts\ShouldValidationRule;
use Illuminate\Validation\Rules\Password as PasswordRule;

class Password implements ShouldValidationRule
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
             PasswordRule::min(8)
                         ->max(16)
                         ->mixedCase()
                         ->symbols()
                         ->numbers()
        ];
    }
}
