<?php

use Illuminate\Support\Facades\Http;
use LaravelApi\LaravelApi\Commands\ListApisCommand;
use LaravelApi\LaravelApi\ManifestManager;

it('lists available apis', function () {
    // Arrange
    Http::fake([
        '*' => Http::response([
            [
                "key" => "twitter",
                "name" => "Twitter",
                "wrapperClass" => "LaravelApi\\Twitter\\TwitterWrapper",
                "wrapperPackage" => "laravel-api/twitter",
                "apiPackage" => "abraham/twitteroauth",
                "description" => "This is the description for the Twitter Api"
            ]
        ]),
    ]);

    // Act & Assert
    $this->artisan(ListApisCommand::class)
        ->expectsTable(
            ['API', 'Package', 'Installation', 'More Information'],
            [
                [
                    "name" => "  Twitter",
                    "package" => "abraham/twitteroauth",
                    "command" => "php artisan api:install twitter",
                    "more-info" => "https://laravel-api.com/apis?api=twitter",
                ]
            ]);
});

it('shows if an api is already installed', function () {
    // Arrange
    $this->instance(ManifestManager::class, Mockery::mock(ManifestManager::class, function ($mock) {
        $mock->shouldReceive('getManifest')
            ->once()
            ->andReturn(["twitter" => "LaravelApi\Twitter\TwitterWrapper"]);
    }));

    Http::fake([
        '*' => Http::response([
            [
                "key" => "twitter",
                "name" => "Twitter",
                "wrapperClass" => "LaravelApi\\Twitter\\TwitterWrapper",
                "wrapperPackage" => "laravel-api/twitter",
                "apiPackage" => "abraham/twitteroauth",
                "description" => "This is the description for the Twitter Api"
            ]
        ]),
    ]);

    // Act & Assert
    $this->artisan(ListApisCommand::class)
        ->expectsTable(
            ['API', 'Package', 'Installation', 'More Information'],
            [
                [
                    "name" => "✓ Twitter",
                    "package" => "abraham/twitteroauth",
                    "command" => "php artisan api:install twitter",
                    "more-info" => "https://laravel-api.com/apis?api=twitter",
                ]
            ]);
});
