<?php

namespace LaravelApi\LaravelApi\Commands;

use Illuminate\Support\Facades\Http;
use LaravelApi\LaravelApi\ManifestManager;

class LaravelApiDiscovery extends GeneralCommand
{
    protected $signature = 'api:discover';

    protected $description = 'Discover installed API clients.';

    public function handle(): int
    {
        $apiInfo = $this->api();

        $apiManifest = collect($apiInfo)
            ->filter(fn (array $apiClientInfo) => class_exists($apiClientInfo['definition']))
            ->mapWithKeys(fn($apiClientInfo) => [$apiClientInfo['key'] => $apiClientInfo['definition']])
            ->toArray();

        app(ManifestManager::class)->putManifest($apiManifest);

        return self::SUCCESS;
    }
}
