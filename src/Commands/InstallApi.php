<?php

namespace LaravelApi\LaravelApi\Commands;

use Composer\Autoload\ClassLoader;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use LaravelApi\LaravelApi\ManifestManager;

class InstallApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:install {api}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $apiInfo = $this->api($this->argument('api'));

        shell_exec('composer require ' . $apiInfo['package']);

        $this->storeApiManifest($apiInfo);

        $definitionClass = $this->loadDefinitionClass($apiInfo['definition']);

        foreach($definitionClass->config() as $credentialKey => $config) {
            $key = $this->ask('Enter credentials for ' . $credentialKey . ' (' . $config['description'] . ')');
            File::append('.env', PHP_EOL . $credentialKey . '=' . $key);
        }

        return self::SUCCESS;
    }

    private function api($api)
    {
        return Http::get('https://laravel-api.com/api/services/' . $api)
            ->json();
    }

    private function storeApiManifest(mixed $apiInfo): void
    {
        app(ManifestManager::class)
            ->add($apiInfo['name'], $apiInfo['definition']);
    }

    private function loadDefinitionClass($definitionClass)
    {
        if(!class_exists($definitionClass)) {
            $classMap = require base_path('vendor/composer/autoload_classmap.php');
            require $classMap[$definitionClass];
        }

        return new $definitionClass;
    }
}
