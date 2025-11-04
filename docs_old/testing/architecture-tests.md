# Architecture Tests with Lawman

## Introduction

[Lawman](https://github.com/JonPurvis/lawman) is a PestPHP plugin specifically designed for testing Saloon-based API integrations. It provides elegant, readable assertions that validate your API connectors and requests follow Saloon best practices.

## Why Architecture Tests?

Architecture tests ensure that:

- Your connectors properly extend Saloon's `Connector` class
- Requests implement required interfaces and traits
- Authentication is configured correctly
- Plugins like rate limiting, caching, and pagination are properly set up
- Timeout and retry configurations meet your requirements

Without Lawman, these tests would require verbose, boilerplate-heavy code. Lawman makes them simple and expressive.

## Installation

Install Lawman in your test environment:

```bash
composer require --dev jonpurvis/lawman
```

Lawman automatically integrates with Pest through Laravel's package auto-discovery.

## Testing Connectors

### Basic Connector Test

Validate that your connector extends Saloon's Connector:

```php
use PhpDevKits\Ortto\Ortto;

test('Ortto is a valid Saloon connector', function () {
    expect(Ortto::class)->toBeSaloonConnector();
});
```

This single assertion validates that:
- The class extends `Saloon\Http\Connector`
- Required methods are implemented
- The connector follows Saloon's architecture

### Testing JSON Acceptance

Verify the connector accepts JSON responses:

```php
test('Ortto connector accepts JSON', function () {
    expect(Ortto::class)->toUseAcceptsJson();
});
```

This confirms the `AcceptsJson` trait is used, which sets appropriate headers for JSON APIs.

### Testing Base URL Configuration

```php
test('Ortto connector resolves base URL correctly', function () {
    $connector = new Ortto();

    expect($connector->resolveBaseUrl())
        ->toBeString()
        ->toContain('api')
        ->toContain('ortto');
});
```

### Complete Connector Test

A comprehensive connector test might look like:

```php
use PhpDevKits\Ortto\Ortto;

test('Ortto connector architecture', function () {
    expect(Ortto::class)
        ->toBeSaloonConnector()
        ->toUseAcceptsJson();

    $connector = new Ortto();

    expect($connector->resolveBaseUrl())
        ->toBe(config('ortto.url'));
});
```

## Testing Requests

### Basic Request Test

Validate that a request class properly extends Saloon's Request:

```php
use PhpDevKits\Ortto\Requests\People\MergePeopleRequest;

test('MergePeopleRequest is a valid Saloon request', function () {
    expect(MergePeopleRequest::class)->toBeSaloonRequest();
});
```

### Testing HTTP Method

```php
test('MergePeopleRequest uses POST method', function () {
    $request = new MergePeopleRequest([
        'email' => 'test@example.com',
    ]);

    expect($request)
        ->resolveMethod()->toBe('POST');
});
```

### Testing Request Endpoint

```php
test('MergePeopleRequest resolves correct endpoint', function () {
    $request = new MergePeopleRequest([
        'email' => 'test@example.com',
    ]);

    expect($request)
        ->resolveEndpoint()->toBe('/v1/people/merge');
});
```

### Complete Request Test

```php
use PhpDevKits\Ortto\Requests\People\MergePeopleRequest;

test('MergePeopleRequest architecture', function () {
    expect(MergePeopleRequest::class)
        ->toBeSaloonRequest();

    $request = new MergePeopleRequest([
        'email' => 'test@example.com',
        'first_name' => 'John',
    ]);

    expect($request)
        ->resolveMethod()->toBe('POST')
        ->resolveEndpoint()->toContain('people/merge');
});
```

## Testing Authentication

### API Key Authentication

Test that your connector uses the correct authentication:

```php
test('Ortto connector uses API key authentication', function () {
    $connector = new Ortto();

    $headers = $connector->headers()->all();

    expect($headers)
        ->toHaveKey('X-Api-Key')
        ->and($headers['X-Api-Key'])->toBe(config('ortto.api_key'));
});
```

### Bearer Token Authentication

If using bearer tokens:

```php
test('connector uses bearer authentication', function () {
    expect(YourConnector::class)->toUseBearerTokenAuth();
});
```

## Testing Plugins

### Rate Limiting

Test that rate limiting is properly configured:

```php
test('connector implements rate limiting', function () {
    expect(Ortto::class)->toUseRateLimitPlugin();
});
```

### Pagination

For paginated endpoints:

```php
test('request implements pagination', function () {
    expect(YourPaginatedRequest::class)->toUsePaginationPlugin();
});
```

### Caching

Test caching configuration:

```php
test('connector uses caching', function () {
    expect(Ortto::class)->toUseCachingPlugin();
});
```

## Testing Retry Logic

### Retry Configuration

```php
test('connector has retry configuration', function () {
    expect(Ortto::class)
        ->toUseRetries()
        ->toHaveMaxRetries(3)
        ->toUseExponentialBackoff();
});
```

## Testing Timeouts

### Connection Timeout

```php
test('connector has appropriate connection timeout', function () {
    expect(Ortto::class)->toHaveConnectionTimeout(30);
});
```

### Request Timeout

```php
test('connector has appropriate request timeout', function () {
    expect(Ortto::class)->toHaveRequestTimeout(60);
});
```

## Real-World Example

Here's a complete architecture test suite for the Ortto SDK:

```php
<?php

use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\People\MergePeopleRequest;
use PhpDevKits\Ortto\Requests\People\GetPersonRequest;
use PhpDevKits\Ortto\Requests\Activities\CreateActivityRequest;

describe('Ortto Connector Architecture', function () {
    test('is a valid Saloon connector', function () {
        expect(Ortto::class)->toBeSaloonConnector();
    });

    test('accepts JSON responses', function () {
        expect(Ortto::class)->toUseAcceptsJson();
    });

    test('resolves base URL from config', function () {
        $connector = new Ortto();

        expect($connector->resolveBaseUrl())
            ->toBe(config('ortto.url'));
    });

    test('includes API key in headers', function () {
        $connector = new Ortto();
        $headers = $connector->headers()->all();

        expect($headers)
            ->toHaveKey('X-Api-Key')
            ->and($headers['X-Api-Key'])->not->toBeEmpty();
    });
});

describe('People Requests Architecture', function () {
    test('MergePeopleRequest is valid', function () {
        expect(MergePeopleRequest::class)->toBeSaloonRequest();
    });

    test('GetPersonRequest is valid', function () {
        expect(GetPersonRequest::class)->toBeSaloonRequest();
    });
});

describe('Activities Requests Architecture', function () {
    test('CreateActivityRequest is valid', function () {
        expect(CreateActivityRequest::class)->toBeSaloonRequest();
    });
});
```

## Benefits of Architecture Tests

### 1. Catch Breaking Changes Early

If you accidentally break the Saloon architecture (e.g., forget to extend `Connector`), tests catch it immediately.

### 2. Documentation

Architecture tests serve as living documentation of your API structure.

### 3. Refactoring Confidence

When refactoring, architecture tests ensure you maintain Saloon best practices.

### 4. Onboarding

New developers can understand the API structure by reading architecture tests.

## Best Practices

::: tip Test at Multiple Levels
Test both the connector and individual request classes for comprehensive coverage.
:::

::: tip Group Related Tests
Use Pest's `describe()` blocks to organize tests by component (Connector, Requests, Resources).
:::

::: tip Combine with Unit Tests
Architecture tests validate structure; unit tests validate behavior. Use both.
:::

::: tip Update Tests When Adding Features
When adding new requests or resources, add corresponding architecture tests.
:::

## Next Steps

- [Unit Tests](/testing/unit-tests) - Test request and response behavior
- [Mocking](/testing/mocking) - Mock API responses for testing
- [Lawman Documentation](https://github.com/JonPurvis/lawman) - Learn more about Lawman
