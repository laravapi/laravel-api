# This is a wrapper package for using APIs in a Laravel application.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/christophrumpel/laravel-api.svg?style=flat-square)](https://packagist.org/packages/christophrumpel/laravel-api)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/christophrumpel/laravel-api/run-tests?label=tests)](https://github.com/christophrumpel/laravel-api/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/christophrumpel/laravel-api/Check%20&%20fix%20styling?label=code%20style)](https://github.com/christophrumpel/laravel-api/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/christophrumpel/laravel-api.svg?style=flat-square)](https://packagist.org/packages/christophrumpel/laravel-api)

The easiest way to embed APIs in a Laravel project.

## Installation

You can install the package via composer:

```bash
composer require christophrumpel/laravel-api
```

## Usage

```php
php artisan api:install twitter
```

Please provide the credentials when asked for, they will automatically be placed in your `.env` file.

To check if the credentials are correct, run the test command:
```php
php artisan api:test twitter
```

Now you can the use the api client like:
```php
Twitter::tweet('Draußen scheint die Sonne');
```

## Fakes

```php
Twitter::fake();

Twitter::tweet('sdfdsfdsf');

Twitter::assertTweeted('fdsfdsf')
```

## Advantages

- you dont have to search for API packages
- automatic setup (service provider, config, etc.)
- consistent commands for install, testing, etc.

## Dear API Developers

If you want your client to be included in Laravel APIs, or add your own client wrapper, check here....

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Christoph Rumpel & Sebastian Schöps](https://github.com/christophrumpel)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
