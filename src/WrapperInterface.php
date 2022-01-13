<?php

namespace LaravelApi\LaravelApi;

interface WrapperInterface
{
    public function boot(): void;

    public function config(): array;
}
