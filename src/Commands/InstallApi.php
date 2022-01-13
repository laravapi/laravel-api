<?php

namespace LaravelApi\LaravelApi\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

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

        $this->storeApiManifest($apiInfo);

        shell_exec('composer require ' . $apiInfo['package']);

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
        return Http::get('https://laravel-api.com/api/services/' . $api)
            ->json();
    }

    protected function storeApiManifest(mixed $apiInfo): void
    {
        $manifestPath = app()->bootstrapPath('cache/laravel-api-manifest.php');
        if (File::exists($manifestPath)) {
            $apiManifest = include $manifestPath;
            $apiManifest[$apiInfo['name']] = $apiInfo;

        } else {
            $apiManifest = [$apiInfo['name'] => $apiInfo];
        }
        File::put($manifestPath, '<?php return ' . var_export($apiManifest, true) . ';', true);
    }
}
