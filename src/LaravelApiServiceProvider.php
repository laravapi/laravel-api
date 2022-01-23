<?php

namespace LaravelApi\LaravelApi;

use Illuminate\Support\Facades\File;
use LaravelApi\LaravelApi\Commands\InstallApiCommand;
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
            ->hasCommand(InstallApiCommand::class);

        $this->registerManifestManager();
        $this->bootApis();
    }

    private function registerManifestManager(): void
    {
        $defaultManifestPath = $this->isRunningServerless()
            ? '/tmp/storage/bootstrap/cache/laravel-api-manifest.php'
            : app()->bootstrapPath('cache/laravel-api-manifest.php');

        $this->app->singleton(ManifestManager::class, function () use ($defaultManifestPath) {
            return new ManifestManager($defaultManifestPath);
        });
    }

    private function bootApis(): void
    {
        collect(app(ManifestManager::class)
            ->getManifest())
            ->each(function($apiWrapperClassName) {
                $apiWrapper = (new $apiWrapperClassName);

                foreach($apiWrapper->config() as $envKey => $config) {
                    config([$config['config'] => env($envKey)]);
                }

                if(method_exists($apiWrapper, 'boot')) {
                    $apiWrapper->boot();
                }
            });
    }

    private function isRunningServerless(): bool
    {
        return in_array($_ENV['SERVER_SOFTWARE'] ?? null, [
            'vapor',
            'bref',
        ]);
    }
}
