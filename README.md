# Ortto SDK for Laravel

A modern, elegant Laravel SDK for the [Ortto](https://ortto.com) Customer Data Platform API. Built on top of [Saloon](https://docs.saloon.dev/), this package provides a fluent interface to interact with Ortto's REST API.

## Features

- Full Ortto API coverage (People, Activities, Campaigns, and more)
- Built with Saloon for robust HTTP communication
- Laravel integration via service provider
- Type-safe requests and responses
- Comprehensive test coverage
- Modern PHP 8.4+ syntax

## Requirements

- PHP 8.4 or higher
- Laravel 10.x or 11.x

## Installation

Install the package via Composer:

```bash
composer require phpdevkits/ortto-sdk
```

Publish the configuration file:

```bash
php artisan vendor:publish --provider="PhpDevKits\Ortto\OrttoServiceProvider"
```

Add your Ortto API credentials to your `.env` file:

```env
ORTTO_API_KEY=your-api-key-here
ORTTO_REGION=ap3  # Options: ap3 (default), au, eu
```

## Configuration

The configuration file will be published to `config/ortto.php`:

```php
return [
    'api_key' => env('ORTTO_API_KEY'),
    'region' => env('ORTTO_REGION', 'ap3'),
];
```

### Service Endpoints

Ortto uses region-specific endpoints:
- **Default (AP3)**: `https://api.ap3api.com/` - For most Ortto users
- **Australia**: `https://api.au1api.com/` - For AU region instances
- **Europe**: `https://api.eu1api.com/` - For EU region instances

## Usage

### People Management

```php
use PhpDevKits\Ortto\Facades\Ortto;

// Create or update a person
Ortto::people()->merge([
    'email' => 'user@example.com',
    'first_name' => 'John',
    'last_name' => 'Doe',
]);

// Get a person by email
$person = Ortto::people()->get('user@example.com');

// Delete a person
Ortto::people()->delete('user@example.com');
```

### Activities

```php
// Track a custom activity
Ortto::activities()->create([
    'email' => 'user@example.com',
    'activity_name' => 'product_viewed',
    'attributes' => [
        'product_id' => '12345',
        'product_name' => 'Premium Widget',
    ],
]);
```

### Campaigns

```php
// Send a campaign email
Ortto::campaigns()->send([
    'campaign_id' => 'your-campaign-id',
    'recipients' => [
        ['email' => 'user@example.com'],
    ],
]);
```

## Extending the SDK

The SDK is designed to be extensible, allowing you to customize behavior for your specific needs.

### Caching Responses

The SDK is built on [Saloon](https://docs.saloon.dev/) and supports the [Saloon Cache Plugin](https://docs.saloon.dev/installable-plugins/caching-responses).

#### Cache All Requests

Extend the Ortto connector to add caching to all API requests:

```php
use PhpDevKits\Ortto\Ortto;
use Saloon\CachePlugin\Contracts\Cacheable;
use Saloon\CachePlugin\Traits\HasCaching;
use Saloon\CachePlugin\Contracts\Driver;
use Saloon\CachePlugin\Drivers\LaravelCacheDriver;

class CachedOrtto extends Ortto implements Cacheable
{
    use HasCaching;

    public function resolveCacheDriver(): Driver
    {
        return new LaravelCacheDriver('redis');
    }

    public function cacheExpiryInSeconds(): int
    {
        return 3600; // Cache for 1 hour
    }
}

// Usage
$ortto = new CachedOrtto();
$response = $ortto->person()->get(
    fields: [PersonField::Email->value, PersonField::FirstName->value]
);
// Subsequent identical requests will use the cache
```

#### Cache Specific Requests

Extend individual request classes for granular caching control:

```php
use PhpDevKits\Ortto\Requests\Person\GetPeople;
use Saloon\CachePlugin\Contracts\Cacheable;
use Saloon\CachePlugin\Traits\HasCaching;
use Saloon\CachePlugin\Contracts\Driver;
use Saloon\CachePlugin\Drivers\LaravelCacheDriver;

class CachedGetPeople extends GetPeople implements Cacheable
{
    use HasCaching;

    public function resolveCacheDriver(): Driver
    {
        return new LaravelCacheDriver('redis');
    }

    public function cacheExpiryInSeconds(): int
    {
        return 600; // Cache for 10 minutes
    }
}

// Usage
$ortto = new Ortto();
$response = $ortto->send(
    new CachedGetPeople(
        fields: [PersonField::Email->value]
    )
);
```

### Custom Resource Classes

Override resource classes to add custom behavior like logging, rate limiting, or validation:

```php
// app/Ortto/CustomPersonResource.php
namespace App\Ortto;

use PhpDevKits\Ortto\Resources\PersonResource;
use Saloon\Http\Response;
use Illuminate\Support\Facades\Log;

class CustomPersonResource extends PersonResource
{
    public function get(array $fields, ...): Response
    {
        // Add custom logic before the request
        Log::info('Fetching people with fields', ['fields' => $fields]);

        // Call the parent method
        $response = parent::get($fields, ...);

        // Add custom logic after the request
        Log::info('Fetched people', [
            'status' => $response->status(),
            'count' => count($response->json('contacts', []))
        ]);

        return $response;
    }

    // Override other methods as needed
}
```

Configure your custom resource in `config/ortto.php`:

```php
'resources' => [
    'person' => \App\Ortto\CustomPersonResource::class,
],
```

Now all calls to `$ortto->person()` will use your custom resource:

```php
$ortto = new Ortto();
$ortto->person()->get(...); // Uses CustomPersonResource
```

### Advanced: Combining Caching with Custom Resources

```php
namespace App\Ortto;

use PhpDevKits\Ortto\Resources\PersonResource;
use PhpDevKits\Ortto\Requests\Person\GetPeople;
use Saloon\CachePlugin\Contracts\Cacheable;
use Saloon\CachePlugin\Traits\HasCaching;
use Saloon\CachePlugin\Contracts\Driver;
use Saloon\CachePlugin\Drivers\LaravelCacheDriver;
use Saloon\Http\Response;

class CachedPersonResource extends PersonResource
{
    public function get(array $fields, ...): Response
    {
        // Create a cached version of GetPeople
        $request = new class(...) extends GetPeople implements Cacheable {
            use HasCaching;

            public function resolveCacheDriver(): Driver
            {
                return new LaravelCacheDriver('redis');
            }

            public function cacheExpiryInSeconds(): int
            {
                return 600;
            }
        };

        return $this->connector->send($request);
    }
}
```

## Documentation

Browse the `docs/` directory for detailed documentation:

- **[Introduction](docs_old/introduction.md)** - Learn about Ortto SDK and its features
- **[Installation Guide](docs_old/installation.md)** - Get set up in minutes
- **[Testing Overview](docs_old/testing/overview.md)** - Testing with Pest and Lawman
- **[Architecture Tests](docs_old/testing/architecture-tests.md)** - Validate Saloon architecture with Lawman
- **[Mocking](docs_old/testing/mocking.md)** - Mock API responses with Saloon MockClient

## API Reference

For detailed information about Ortto's API endpoints and parameters, refer to the [Ortto API Documentation](https://help.ortto.com/developer/latest/).

## Development

Clone the repository and install dependencies:

```bash
git clone https://github.com/phpdevkits/ortto-sdk.git
cd ortto-sdk
composer install
```

### Code Quality

Keep a modern codebase with **Pint**:
```bash
composer lint
```

Run refactors using **Rector**:
```bash
composer refactor
```

Run static analysis using **PHPStan**:
```bash
composer test:types
```

Run unit tests using **PEST**:
```bash
composer test:unit
```

Run the entire test suite:
```bash
composer test
```

## Testing

The package includes comprehensive test coverage using PEST and [Lawman](https://github.com/JonPurvis/lawman) for architecture testing:

```bash
composer test
```

Learn more about testing in our [Testing Documentation](docs_old/testing/overview.md).

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for recent changes.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security-related issues, please email security@example.com instead of using the issue tracker.

## Credits

This package is built with modern PHP tools and frameworks:

- **[Laravel](https://laravel.com/)** - The PHP framework for web artisans
- **[Saloon](https://docs.saloon.dev/)** - Build beautiful API integrations
- **[Pest PHP](https://pestphp.com/)** - An elegant PHP testing framework
- **[Lawman](https://github.com/JonPurvis/lawman)** - Architecture testing for Saloon
- **[PHPStan](https://phpstan.org/)** - PHP static analysis tool
- **[Laravel Pint](https://laravel.com/docs/pint)** - PHP code style fixer
- **[Rector](https://getrector.com/)** - Instant upgrades and automated refactoring

Special thanks to:
- [Ortto](https://ortto.com) for their Customer Data Platform API
- [Ortto API Documentation](https://help.ortto.com/developer/latest/)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
