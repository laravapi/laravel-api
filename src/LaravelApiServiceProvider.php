<?php

namespace LaravelApi\LaravelApi;

use LaravelApi\LaravelApi\Commands\InstallApi;
use LaravelApi\LaravelApi\Commands\LaravelApiDiscovery;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelApiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-api')
            ->hasCommand(LaravelApiDiscovery::class)
            ->hasCommand(InstallApi::class);
    }
}
