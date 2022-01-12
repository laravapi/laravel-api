<?php

namespace LaravelApi\LaravelApi\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

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
        // Get information for API
        // Install composer package


        $apiInfo = $this->api($this->argument('api'));

        // shell_exec('composer require ' . $apiInfo['package']);

        $class = $apiInfo['definition'];

        /*
        foreach((new $class)->neededCredentials() as $credentialKey => $description) {
            $key = $this->ask('Enter credentials for ' . $credentialKey . ' (' . $description . ')');
            File::append('.env', PHP_EOL . $credentialKey . '=' . $key);
        }
        */

        /*
        'twitter' => [
        'consumer_key' => env('TWITTER_CONSUMER_KEY'),
        'consumer_secret' => env('TWITTER_CONSUMER_SECRET'),
        'access_token' => env('TWITTER_ACCESS_TOKEN'),
        'access_token_secret' => env('TWITTER_ACCESS_TOKEN_SECRET'),
    ],
config(['twitter.consumer_key' => env('TWITTER_CONSUMER_KEY')]);
        */

    }

    private function api($api)
    {
        $apis = [
            'twitter' => [
                'package' => 'laravapi/twitter',
                'definition' => 'Laravapi\Twitter\TwitterWrapper',

            ],
            'youtube' => [],
        ];

        return $apis[$api];
    }
}
