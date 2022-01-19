<?php

namespace LaravelApi\LaravelApi\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use LaravelApi\LaravelApi\ManifestManager;

class InstallApiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:install {apiKey}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install a new API.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $apiInfo = $this->api($this->argument('apiKey'));

        shell_exec('composer require ' . $apiInfo['package']);

        $this->storeApiManifest($apiInfo);

        $apiWrapper = $this->loadApiWrapper($apiInfo['definition']);

        foreach($apiWrapper->config() as $credentialKey => $config) {
            $key = $this->ask('Enter credentials for ' . $credentialKey . ' (' . $config['description'] . ')');
            File::append('.env', PHP_EOL . $credentialKey . '=' . $key);
        }

        return self::SUCCESS;
    }

    private function api(string $apiKey): mixed
    {
        return Http::get('https://laravel-api.com/api/services/' . $apiKey)
            ->json();
    }

    private function storeApiManifest(mixed $apiInfo): void
    {
        app(ManifestManager::class)
            ->add($apiInfo['key'], $apiInfo['definition']);
    }

    private function loadApiWrapper(string $apiWrapperClassName)
    {
         if(!class_exists($apiWrapperClassName)) {
            $classMap = require base_path('vendor/composer/autoload_classmap.php');
            require $classMap[$apiWrapperClassName];
        }

        return new $apiWrapperClassName;
    }
}
