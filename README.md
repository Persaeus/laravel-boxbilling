# A minimalist BoxBilling API bridge for Laravel.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nihilsen/laravel-boxbilling.svg?style=flat-square)](https://packagist.org/packages/nihilsen/laravel-boxbilling)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/nihilsen/laravel-boxbilling/run-tests?label=tests)](https://github.com/nihilsen/laravel-boxbilling/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/nihilsen/laravel-boxbilling/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/nihilsen/laravel-boxbilling/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/nihilsen/laravel-boxbilling.svg?style=flat-square)](https://packagist.org/packages/nihilsen/laravel-boxbilling)

This package adds a simple interface for interacting with the API of a [BoxBilling](https://github.com/BoxBilling/BoxBilling) instance.

Basic authentication via a token is supported for `Client` and `Admin` endpoints.

## Installation

You can install the package via composer:

```bash
composer require nihilsen/laravel-boxbilling
```

If you wish, you may publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-boxbilling-config"
```

In the published config file, you may configure the `url` for the BoxBilling instance as well as the `token` for authenticated requests:

```php
return [

    /*
    |--------------------------------------------------------------------------
    | Url
    |--------------------------------------------------------------------------
    |
    | The base url for all BoxBilling API requests.
    |
    */

    'url' => env('BOXBILLING_API_URL'),

    /*
    |--------------------------------------------------------------------------
    | Token
    |--------------------------------------------------------------------------
    |
    | The authentication token for authenticated API requests.
    |
    */

    'token' => env('BOXBILLING_API_TOKEN'),

];
```

Alternatively, you may configure these options via your `.env` enviroment file:

```env

BOXBILLING_API_URL='https://boxbilling.tld/api'
BOXBILLING_API_TOKEN='your_secret_boxbilling_token'

```

## Usage

API calls follow a format similar to that used internally in BoxBilling.

The starting point should always be the `BoxBilling` facade.

Request parameters **MUST** be passed as _named parameters_.

```php

use Nihilsen\BoxBilling\Facades\BoxBilling;

# Determine BoxBilling version (endpoint: guest/system/version)
$version = BoxBilling::guest()->system_version();

# Get client by id (endpoint: admin/client/get)
$client = BoxBilling::admin()->client_get(id: 42);

# Get profile of client by id (endpoint: client/profile/get)
$profile = BoxBilling::client(id: 42)->profile_get();

```

### Paginated results

Paginated results are collected into a `Nihilsen\BoxBilling\Collection` instance, which is subclass of `Illuminate\Support\LazyCollection`.

```php
use Nihilsen\BoxBilling\Facades\BoxBilling;

/** @var Nihilsen\BoxBilling\Collection **/
$tickets = BoxBilling::admin()->support_ticket_get_list(page: 1, per_page: 10);

# Select a random ticket 
$ticket = $tickets->random();
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

-   [nihilsen](https://github.com/nihilsen)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
