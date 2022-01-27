<?php

namespace LaravelApi\LaravelApi\Commands;

use Illuminate\Support\Facades\Http;
use LaravelApi\LaravelApi\ManifestManager;

class ListApis extends GeneralCommand
{
    protected $signature = 'api:list';
    protected $description = 'List available APIS';

    public function handle(): int
    {
        $apis = collect($this->api())
            ->sortBy('name')
            ->map(fn($api) => [
                'name' => $api['name'],
                'package' => $api['apiPackage'],
                'description' => $api['description'],
                'command' => 'php artisan api:install ' . $api['key'],
                'more-info' => 'https://laravel-api.com/apis?api=' . $api['key'],
            ]);

        $this->table(
            ['API', 'Package', 'Description', 'Installation', 'More Information'],
            $apis
        );

        return self::SUCCESS;
    }
}