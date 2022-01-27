<?php

namespace LaravelApi\LaravelApi\Commands;

use Dotenv\Dotenv;
use Illuminate\Support\Facades\File;

class Help extends ApiCommand
{
    protected $command = 'help';
    protected $description = 'Show help for an API.';

    protected function handleCommand(): int
    {
        $this->info('ToDo: Show available methods and information how to test them');

        return self::SUCCESS;
    }
}
