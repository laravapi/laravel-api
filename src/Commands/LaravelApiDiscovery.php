<?php

namespace LaravelApi\LaravelApi\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

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
        $manifestPath = app()->bootstrapPath('cache/laravel-api-manifest.php');
        $apiInfo = $this->api();

        $apiManifest = collect($apiInfo)
            ->filter(function (array $apiClientInfo) {
            return class_exists($apiClientInfo['definition']);
        })->mapWithKeys(fn($service) => [$service['name'] => $service]);

        File::put($manifestPath, '<?php return ' . var_export($apiManifest->toArray(), true) . ';', true);

        return self::SUCCESS;
    }

    private function api(string $api = '')
    {
        return Http::get('https://laravel-api.com/api/services/' . $api)
            ->json();
    }
}
