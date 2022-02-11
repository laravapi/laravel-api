<?php

namespace LaravelApi\LaravelApi\Commands;

use Illuminate\Support\Facades\Http;
use LaravelApi\LaravelApi\ManifestManager;

class ListApisCommand extends GeneralCommand
{
    protected $signature = 'api:list';
    protected $description = 'List available APIS';

    public function handle(): int
    {
        $installedApis = array_keys(app(ManifestManager::class)->getManifest());

        $apis = collect($this->api())
            ->sortBy(fn($api) => (in_array($api['key'], $installedApis) ? 0 : 1) . $api['name'])
            ->map(fn($api) => [
                'name' => (in_array($api['key'], $installedApis) ? '✓ ' : '  ') . $api['name'],
                'package' => $api['apiPackage'],
                'command' => 'php artisan api:install ' . $api['key'],
                'more-info' => 'https://laravel-api.com/apis?api=' . $api['key'],
            ]);

        $this->table(
            ['API', 'Package', 'Installation', 'More Information'],
            $apis
        );

        return self::SUCCESS;
    }
}
