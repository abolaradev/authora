<?php

namespace Abolaradev\Authora\Traits;

use Illuminate\Support\Facades\Validator;

trait WithValidation
{
    private array $validated=[];

    /**
     * Validate the given authentication credentials.
     *
     * @param array<string, mixed> $credentials The credentials to validate.
     * @return static
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validate(array $credentials): static
    {
        $this->validated=Validator::make(
            data: $credentials,
            rules: $this->rules(),
            messages: $this->messages()
        )->validate();

        return $this;
    }

    /**
     * Get the validated authentication credentials.
     *
     * @return array<string, mixed>
     */
    protected function getValidatedData(): array
    {
        return $this->validated;
    }

    /**
     * Get the custom validation messages.
     *
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [];
    }

    /**
     * Get the validation rules for the action.
     *
     * @return array<string, mixed>
     */
    abstract protected function rules(): array;
}