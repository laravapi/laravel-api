<?php

if (! function_exists('api')) {
    function api($api)
    {
        return new LaravelApi\LaravelApi\LaravelApi($api);
    }
}
