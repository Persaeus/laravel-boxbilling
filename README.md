# A minimalist FOSSBilling API bridge for Laravel.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nihilsen/laravel-fossbilling.svg?style=flat-square)](https://packagist.org/packages/nihilsen/laravel-fossbilling)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/nihilsen/laravel-fossbilling/run-tests?label=tests)](https://github.com/nihilsen/laravel-fossbilling/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/nihilsen/laravel-fossbilling/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/nihilsen/laravel-fossbilling/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/nihilsen/laravel-fossbilling.svg?style=flat-square)](https://packagist.org/packages/nihilsen/laravel-fossbilling)

This package adds a simple interface for interacting with the API of a [FOSSBilling](https://github.com/FOSSBilling/FOSSBilling) instance.

Basic authentication via a token is supported for `Client` and `Admin` endpoints.

> **Warning**
> *FOSSBilling* is under active development but is currenly available as beta software. As such, parts of the API may still be subject to change.

## Installation

You can install the package via composer:

```bash
composer require nihilsen/laravel-fossbilling
```


If you wish, you may publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-fossbilling-config"
```

In the published config file, you may configure the `url` for the FOSSBilling instance as well as the `token` for authenticated requests:

```php
return [

    /*
    |--------------------------------------------------------------------------
    | Url
    |--------------------------------------------------------------------------
    |
    | The base url for all FOSSBilling API requests.
    |
    */

    'url' => env('FOSSBILLING_API_URL'),

    /*
    |--------------------------------------------------------------------------
    | Token
    |--------------------------------------------------------------------------
    |
    | The authentication token for authenticated API requests.
    |
    */

    'token' => env('FOSSBILLING_API_TOKEN'),

];
```

Alternatively, you may configure these options via your `.env` enviroment file:

```env

FOSSBILLING_API_URL='https://fossbilling.tld/api'
FOSSBILLING_API_TOKEN='your_secret_fossbilling_token'

```

## Usage

API calls follow a format similar to that used internally in FOSSBilling.

The starting point should always be the `Nihilsen\FOSSBilling\Facades\FOSSBilling` facade.

Request parameters **MUST** be passed as *named parameters*.

```php

use Nihilsen\FOSSBilling\Facades\FOSSBilling;

# Determine FOSSBilling version (endpoint: guest/system/version)
$version = FOSSBilling::guest()->system_version();

# Get client by id (endpoint: admin/client/get)
$client = FOSSBilling::admin()->client_get(id: 42);

```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [nihilsen](https://github.com/nihilsen)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
