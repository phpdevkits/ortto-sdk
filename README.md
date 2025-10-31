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
composer require nunomaduro/ortto-sdk
```

Publish the configuration file:

```bash
php artisan vendor:publish --provider="NunoMaduro\OrttoSdk\OrttoServiceProvider"
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
use NunoMaduro\OrttoSdk\Facades\Ortto;

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

## API Reference

For detailed information about available endpoints and parameters, refer to the [Ortto API Documentation](https://help.ortto.com/developer/latest/).

## Development

Clone the repository and install dependencies:

```bash
git clone https://github.com/nunomaduro/ortto-sdk.git
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

The package includes comprehensive test coverage using PEST:

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for recent changes.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security-related issues, please email security@example.com instead of using the issue tracker.

## Credits

- Built with [Saloon](https://docs.saloon.dev/)
- Ortto API documentation: [https://help.ortto.com](https://help.ortto.com)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
