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
        shell_exec('composer require ' . $this->apiInfo['package']);
        $this->storeApiManifest($this->apiInfo);
        $this->handleEnvKeys();
        $this->info('API ' . $this->apiKey . ' was installed successfully!' . PHP_EOL);
        $this->showHelp();

        return self::SUCCESS;
    }

    private function storeApiManifest(mixed $apiInfo): void
    {
        app(ManifestManager::class)
            ->add($apiInfo['key'], $apiInfo['definition']);
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
