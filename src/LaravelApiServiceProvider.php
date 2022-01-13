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

        $apiDefinitions = include app()->bootstrapPath('cache/laravel-api-manifest.php');

        collect($apiDefinitions)->each(function(array $apiClientInfo) {
            $apiWrapper = (new $apiClientInfo['definition']);

            foreach($apiWrapper->config() as $envKey => $configKey) {
                config([$configKey => env($envKey)]);
            }

            $apiWrapper->boot();
        });

    }
}
