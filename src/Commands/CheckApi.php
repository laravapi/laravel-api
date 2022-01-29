<?php

namespace LaravelApi\LaravelApi\Commands;

class CheckApi extends ApiCommand
{
    protected $command = 'check';
    protected $description = 'Check if the API keys in .env work.';

    protected function handleCommand(): int
    {
        $checkResult = api($this->apiKey)->check();

        if($checkResult) {
            $this->info('The entered API keys are correct and the API is responding.');
            return self::SUCCESS;
        }
        else {
            $this->error('The entered API keys are not correct or the API is not responding.');
            return self::INVALID;
        }
    }
}
