<?php

namespace LaravelApi\LaravelApi\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use LaravelApi\LaravelApi\ManifestManager;

class LaravelApiDiscovery extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:discovery';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Discover given api clients and write to manifest.';

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

    private function api(string $api = '')
    {
        return Http::get('https://laravel-api.com/api/services/' . $api)
            ->json();
    }
}
