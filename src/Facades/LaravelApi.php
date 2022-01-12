<?php

namespace LaravelApi\LaravelApi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \laravel-api\LaravelApi\LaravelApi
 */
class LaravelApi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-api';
    }
}
