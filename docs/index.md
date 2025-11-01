# Ortto SDK for Laravel

A modern, elegant Laravel SDK for the [Ortto](https://ortto.com) Customer Data Platform API. Built on top of [Saloon](https://docs.saloon.dev/), this package provides a fluent interface to interact with Ortto's REST API.

## Features

- Full Ortto API coverage (People, Activities, Campaigns, and more)
- Built with Saloon for robust HTTP communication
- Laravel integration via service provider
- Type-safe requests and responses
- Comprehensive test coverage with [Lawman](https://github.com/JonPurvis/lawman)
- Modern PHP 8.4+ syntax

## Requirements

- PHP 8.4 or higher
- Laravel 10.x or 11.x
- An active [Ortto account](https://ortto.com) with API access

## Quick Links

- [Installation](installation.md) - Get started in minutes
- [Introduction](introduction.md) - Learn about the SDK
- [Testing Overview](testing/overview.md) - Testing with Pest and Lawman
- [Architecture Tests](testing/architecture-tests.md) - Validate Saloon architecture
- [Mocking](testing/mocking.md) - Mock API responses with Saloon

## Quick Example

```php
use PhpDevKits\Ortto\Ortto;

// Create the connector
$ortto = new Ortto();

// The SDK uses Saloon's connector pattern
// Base URL is configured in config/ortto.php
$baseUrl = $ortto->resolveBaseUrl(); // https://api.eu.ap3api.com/v1
```

::: tip
This SDK is currently in development. The API is subject to change. Check back soon for complete implementation of People, Activities, and Campaigns resources.
:::

## Documentation

- **[Installation](installation.md)** - Installation and configuration
- **[Introduction](introduction.md)** - Overview and features
- **[Testing Overview](testing/overview.md)** - Testing philosophy and tools
- **[Architecture Tests](testing/architecture-tests.md)** - Testing with Lawman
- **[Mocking](testing/mocking.md)** - Using Saloon mocking for tests

## Credits

- Built with [Saloon](https://docs.saloon.dev/)
- Architecture testing with [Lawman](https://github.com/JonPurvis/lawman)
- Ortto API documentation: [help.ortto.com](https://help.ortto.com/developer/latest/)

## License

The MIT License (MIT). Please see [License File](../LICENSE.md) for more information.
