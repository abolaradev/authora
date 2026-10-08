<?php

namespace Abolaradev\Authora\Contracts;

interface ShouldValidationRule
{
    /**
     * Get the validation rules.
     *
     * @return array
     */
    public static function rules(): array;
}