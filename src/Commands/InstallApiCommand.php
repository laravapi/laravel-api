<?php

namespace LaravelApi\LaravelApi\Commands;

use Exception;
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

        try {
            $this->loadApiWrapper($this->apiInfo['wrapperClass']);
        }
        catch (Exception $exception) {
            $this->error('Couldn\'t install ' . $this->apiInfo['wrapperPackage'] . ' via composer.');
            $this->warn('Please try again or install the package manually with "composer require ' . $this->apiInfo['wrapperPackage'] .'".');
            return self::FAILURE;
        }

        $this->info($this->apiInfo['wrapperPackage'] . ' installed successfully.');
        $this->storeApiManifest();
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
            $this->call(SetEnvKeysCommand::class, ['apiKey' => $this->apiKey]);
        }
    }

    private function showHelp()
    {
        $this->call(HelpCommand::class, ['apiKey' => $this->apiKey]);
    }
}
