<?php

namespace LaravelApi\LaravelApi\Commands;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

abstract class ApiCommand extends GeneralCommand
{
    protected $apiMustBeInstalled = true;

    protected $signature;
    protected $apiKey;
    protected $apiInfo;
    protected $apiWrapper;
    protected $isInstalled;
    protected $neededEnvKeys;

    abstract protected function handleCommand(): int;

    public function __construct()
    {
        $this->signature = 'api:' . $this->command . '{apiKey?}';
        parent::__construct();
    }

    public function handle(): int
    {
        $this->apiKey = $this->argument('apiKey');

        if(is_null($this->apiKey)) {
            $this->warn('This command has the format "php artisan api:' . $this->command . ' api".' . PHP_EOL . 'Please enter an API.' . PHP_EOL . $this->apiListMessage());
            return self::INVALID;
        }

        $this->apiInfo = $this->api($this->apiKey);

        if (is_null($this->apiInfo)) {
            $this->warn('The API "' . $this->argument('apiKey') . '" is not known.' . PHP_EOL . $this->apiListMessage());
            return self::INVALID;
        }

        $this->isInstalled = $this->isInstalled($this->apiInfo['wrapperClass']);

        if ($this->apiMustBeInstalled && !$this->isInstalled) {
            $this->warn('The API "' . $this->argument('apiKey') . '" must be installed to run this command.' . PHP_EOL . 'Please run "php artisan api:install ' . $this->argument('apiKey') . '" to install this API.');
            return self::INVALID;
        }

        if ($this->isInstalled) {
            $this->apiWrapper = $this->loadApiWrapper($this->apiInfo['wrapperClass']);
            $this->neededEnvKeys = array_keys($this->apiWrapper->config());
        }

        return $this->handleCommand();
    }

    protected function loadApiWrapper(string $apiWrapperClassName)
    {
        if (!class_exists($apiWrapperClassName)) {
            $classMap = require base_path('vendor/composer/autoload_classmap.php');
            require $classMap[$apiWrapperClassName];
        }

        return new $apiWrapperClassName;
    }

    private function isInstalled(string $apiWrapperClassName)
    {
        $classMap = require base_path('vendor/composer/autoload_classmap.php');
        return Arr::has($classMap, $apiWrapperClassName);
    }

    protected function displayEnvKeyStatus()
    {
        $availableEnvKeys = array_keys($_ENV);

        foreach($this->neededEnvKeys as $neededEnvKey) {
            if(in_array($neededEnvKey, $availableEnvKeys)) {
                $this->info('✅ ' . $neededEnvKey . ' already set.');
            }
            else {
                $this->info('❌ ' . $neededEnvKey . ' not set yet.');
            }
        }
    }

    private function apiListMessage()
    {
        return 'Please visit https://laravel-api.com/apis or run "php artisan api:list" to get an overview over the available APIs.';
    }
}
