<?php

namespace LaravelApi\LaravelApi\Commands;

use LaravelApi\LaravelApi\ManifestManager;

class InstallApiCommand extends ApiCommand
{
    protected $command = 'install';
    protected $description = 'Install a new API.';

    protected $apiMustBeInstalled = false;

    protected function handleCommand(): int
    {
        $this->warn('Installing ' . $this->apiInfo['wrapperPackage'] . ' via composer...');
        shell_exec('composer require ' . $this->apiInfo['wrapperPackage'] . ' --quiet');
        $this->info($this->apiInfo['wrapperPackage'] . ' installed successfully.');
        $this->storeApiManifest();
        $this->loadApiWrapper($this->apiInfo['wrapperClass']);
        $this->handleEnvKeys();
        $this->info('API "' . $this->apiInfo['name'] . '" was installed successfully!' . PHP_EOL);
        $this->showHelp();

        return self::SUCCESS;
    }

    private function storeApiManifest(): void
    {
        app(ManifestManager::class)
            ->add($this->apiInfo['key'], $this->apiInfo['wrapperClass']);
    }


    private function handleEnvKeys()
    {
        if(count($this->neededEnvKeys) > 0 && $this->confirm('Do you want to set your .env keys now?', true)) {
            $this->call(SetEnvKeys::class, ['apiKey' => $this->apiKey]);
        }
    }

    private function showHelp()
    {
        $this->call(Help::class, ['apiKey' => $this->apiKey]);
    }
}
