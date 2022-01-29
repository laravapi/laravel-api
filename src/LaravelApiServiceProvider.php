<?php

namespace LaravelApi\LaravelApi;

use Illuminate\Support\ServiceProvider;
use LaravelApi\LaravelApi\Commands\CheckApi;
use LaravelApi\LaravelApi\Commands\Help;
use LaravelApi\LaravelApi\Commands\InstallApiCommand;
use LaravelApi\LaravelApi\Commands\LaravelApiDiscovery;
use LaravelApi\LaravelApi\Commands\ListApis;
use LaravelApi\LaravelApi\Commands\SetEnvKeys;

class LaravelApiServiceProvider extends ServiceProvider
{
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallApiCommand::class,
                LaravelApiDiscovery::class,
                SetEnvKeys::class,
                Help::class,
                ListApis::class,
                CheckApi::class,
            ]);
        }

        $this->registerManifestManager();
    }

    public function boot()
    {
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
