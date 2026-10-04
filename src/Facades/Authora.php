<?php

namespace Abolaradev\Authora\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Abolaradev\Authora\Authora
 */
class Authora extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Abolaradev\Authora\Authora::class;
    }
}
