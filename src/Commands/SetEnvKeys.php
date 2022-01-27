<?php

namespace LaravelApi\LaravelApi\Commands;

use Dotenv\Dotenv;
use Illuminate\Support\Facades\File;

class SetEnvKeys extends ApiCommand
{
    protected $command = 'keys';
    protected $description = 'Set the API\'s .env keys.';

    protected function handleCommand(): int
    {
        if(count($this->neededEnvKeys) == 0) {
            $this->info('This API needs no .env keys.');
            return self::SUCCESS;
        }

        $this->displayEnvKeyStatus();

        $missingKeys = array_diff($this->neededEnvKeys, array_keys($_ENV));

        if(count($missingKeys) == 0) {
            $this->info(PHP_EOL . 'All needed .env keys are set already!');
            return self::SUCCESS;
        }
        
        foreach($this->apiWrapper->config() as $credentialKey => $config) {
            if(in_array($credentialKey, $missingKeys)) {
                $key = $this->ask('Enter credentials for ' . $credentialKey . ' (' . $config['description'] . ')');
                File::append('.env', PHP_EOL . $credentialKey . '=' . $key);
            }
        }

        return self::SUCCESS;
    }
}
