<?php

namespace LaravelApi\LaravelApi\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

abstract class GeneralCommand extends Command
{
    protected function api(string $apiKey = null): mixed
    {
        return Http::get('https://laravel-api.com/api/services/' . $apiKey)
            ->json();
    }
}
