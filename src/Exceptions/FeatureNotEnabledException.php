<?php

namespace Abolaradev\Authora\Exceptions;

use RuntimeException;

class FeatureNotEnabledException extends RuntimeException
{
    public function __construct(string $feature)
    {
        parent::__construct("The [{$feature}] feature is not enabled.");
    }
}