<?php

namespace App\Actions\Rules;

use Abolaradev\Authora\Contracts\ShouldValidationRule;

class Email implements ShouldValidationRule
{
    /**
     * Get the default email validation rules.
     *
     * @return array
     */
    public static function rules(): array
    {
        return ['required' , 'email:filter,rfc,dns'];
    }
}