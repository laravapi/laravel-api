<?php

if (! function_exists('api')) {
    function api(string $apiKey)
    {
        return new LaravelApi\LaravelApi\LaravelApi($apiKey);
    }
}
