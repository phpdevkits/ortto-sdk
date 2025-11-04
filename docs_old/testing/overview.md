# Testing Overview

## Introduction

Ortto SDK is built with testing in mind. The package uses [Lawman](https://github.com/JonPurvis/lawman), a PestPHP plugin specifically designed for testing Saloon-based API integrations, to ensure architectural integrity and reliability.

## Testing Philosophy

Ortto SDK follows these testing principles:

1. **Architecture First** - Use Lawman to validate that our Saloon connectors and requests follow best practices
2. **100% Coverage** - Every line of code is covered by tests
3. **Type Safety** - 100% type coverage ensures type-safe code
4. **Realistic Mocking** - Mock API responses that match real Ortto API behavior
5. **Fast Execution** - Tests run quickly without hitting external APIs

## Testing Tools

### Pest

[Pest](https://pestphp.com/) is our testing framework, providing an elegant syntax for writing tests:

```php
test('can create a contact', function () {
    $response = Ortto::people()->merge([
        'email' => 'test@example.com',
        'first_name' => 'Test',
    ]);

    expect($response)->toBeSuccessful();
});
```

### Lawman

[Lawman](https://github.com/JonPurvis/lawman) is a PestPHP plugin that provides specialized assertions for Saloon architecture:

```php
test('Ortto connector follows Saloon architecture', function () {
    expect(Ortto::class)
        ->toBeSaloonConnector()
        ->toUseAcceptsJson();
});
```

### Orchestra Testbench

[Orchestra Testbench](https://github.com/orchestral/testbench) provides a Laravel testing environment for package development.

## Test Suite Structure

Our test suite is organized into:

```
tests/
├── TestCase.php              # Base test case with Orchestra setup
├── Pest.php                  # Pest configuration
└── Unit/                     # Unit tests
    ├── OrttoTest.php         # Connector tests
    ├── Person/               # People resource tests
    │   ├── MergePeopleTest.php
    │   └── GetPersonTest.php
    ├── Activity/             # Activities tests
    └── Campaign/             # Campaign tests
```

## Running Tests

### Full Test Suite

Run all tests with code coverage, type coverage, linting, and static analysis:

```bash
composer test
```

### Individual Test Suites

Run specific test suites:

```bash
# Unit tests only with code coverage
composer test:unit

# PHPStan static analysis
composer test:types

# Type coverage check
composer test:type-coverage

# Linting check
composer test:lint

# Typo checking
composer test:typos

# Rector refactoring check
composer test:refactor
```

### Running Specific Tests

```bash
# Run a specific test file
vendor/bin/pest tests/Unit/OrttoTest.php

# Run tests matching a pattern
vendor/bin/pest --filter="merge people"

# Run tests with coverage for specific file
vendor/bin/pest tests/Unit/OrttoTest.php --coverage
```

## Code Coverage Requirements

Ortto SDK maintains 100% code coverage. Every line of production code must be tested:

```bash
composer test:unit
```

This will fail if coverage is not exactly 100%.

## Type Coverage

We also maintain 100% type coverage using Pest's type coverage plugin:

```bash
composer test:type-coverage
```

This ensures every parameter, return type, and property has explicit type declarations.

## Writing Your First Test

### Setting Up

When testing your Ortto integration in your application, install the testing dependencies:

```bash
composer require --dev phpdevkits/ortto-sdk jonpurvis/lawman
```

### Basic Test Example

```php
<?php

use PhpDevKits\Ortto\Facades\Ortto;

test('can sync user to Ortto', function () {
    // Mock the Ortto API response
    $this->mockOrttoResponse('people.merge', [
        'status' => 'success',
        'people_merged' => 1,
    ]);

    $user = User::factory()->create([
        'email' => 'john@example.com',
        'first_name' => 'John',
        'last_name' => 'Doe',
    ]);

    $response = Ortto::people()->merge([
        'email' => $user->email,
        'first_name' => $user->first_name,
        'last_name' => $user->last_name,
    ]);

    expect($response)->toBeSuccessful();
});
```

## Next Steps

- [Architecture Tests](/testing/architecture-tests) - Learn how to use Lawman for architecture testing
- [Unit Tests](/testing/unit-tests) - Write comprehensive unit tests
- [Mocking](/testing/mocking) - Mock Ortto API responses for testing

## Best Practices

::: tip Don't Hit the Real API
Always mock API responses in tests. Never make real API calls during automated testing.
:::

::: tip Test Edge Cases
Test not just the happy path, but also error scenarios, rate limiting, and network failures.
:::

::: tip Use Factories
Use Laravel factories to generate test data consistently.
:::

::: tip Keep Tests Fast
Fast tests encourage frequent running. Use mocking to avoid slow external calls.
:::
