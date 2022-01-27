<?php

namespace LaravelApi\LaravelApi\Commands;

use LaravelApi\LaravelApi\ManifestManager;

class LaravelApiDiscovery extends GeneralCommand
{
    protected $signature = 'api:discover';

    protected $description = 'Discover installed API clients.';

    public function handle(): int
    {
        $apiInfo = $this->api();

        $apiManifest = collect($apiInfo)
            ->filter(fn (array $apiClientInfo) => class_exists($apiClientInfo['wrapperClass']))
            ->mapWithKeys(fn($apiClientInfo) => [$apiClientInfo['key'] => $apiClientInfo['wrapperClass']])
            ->toArray();

        app(ManifestManager::class)->putManifest($apiManifest);

        return self::SUCCESS;
    }
}
