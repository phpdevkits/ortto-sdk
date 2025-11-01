# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel SDK for the [Ortto](https://ortto.com) Customer Data Platform API. The package is built on top of [Saloon](https://docs.saloon.dev/) for HTTP communication and provides a fluent interface to interact with Ortto's REST API.

**Target PHP Version**: 8.4+
**Target Laravel Versions**: 10.x, 11.x
**HTTP Client**: Saloon v3

## Development Commands

### Testing
```bash
# Run full test suite (lint, type coverage, typos, unit tests, types, refactor checks)
composer test

# Run individual test components
composer test:unit              # PEST unit tests with 100% code coverage requirement
composer test:types             # PHPStan static analysis at max level
composer test:type-coverage     # PEST type coverage - requires exactly 100%
composer test:lint              # Pint formatting check (dry-run)
composer test:typos             # Peck typo checking
composer test:refactor          # Rector refactor check (dry-run)
```

### Code Quality
```bash
composer lint                   # Auto-fix code style with Laravel Pint
composer refactor               # Apply Rector refactoring rules
```

### Running Single Tests
```bash
vendor/bin/pest tests/Feature.php              # Run specific test file
vendor/bin/pest --filter="test name"           # Run tests matching pattern
```

## Architecture

### Saloon Integration

This SDK is built on Saloon, a modern PHP HTTP client abstraction. Key architectural components:

1. **Connector** (`PhpDevKits\Ortto\OrttoConnector`): Main entry point that handles authentication and base URL configuration
   - Region-based endpoints (ap3, au, eu)
   - API key authentication via X-Api-Key header

2. **Requests**: Individual API endpoint requests extending Saloon's `Request` class
   - Located in `src/Requests/` directory
   - Each request defines HTTP method, endpoint, and request/response DTOs

3. **Resources**: Logical groupings of related requests
   - `People`: Contact management (merge, get, delete)
   - `Activities`: Event tracking
   - `Campaigns`: Campaign management
   - Located in `src/Resources/` directory

### Laravel Integration

- **Service Provider**: `PhpDevKits\Ortto\OrttoServiceProvider`
  - Registers the Ortto connector in Laravel's service container
  - Publishes configuration file to `config/ortto.php`

- **Facade**: `PhpDevKits\Ortto\Facades\Ortto`
  - Provides static access to Ortto resources
  - Usage: `Ortto::people()->merge([...])`

- **Configuration**: Located at `config/ortto-sdk.php`
  - `api_key`: Ortto API key from env (ORTTO_API_KEY)
  - `region`: API region - ap3 (default), au, or eu (ORTTO_REGION)

### Ortto API Endpoints

The SDK targets these Ortto API base URLs:
- **Default (AP3)**: `https://api.ap3api.com/` - For most Ortto users
- **Australia**: `https://api.au1api.com/` - For AU region instances
- **Europe**: `https://api.eu1api.com/` - For EU region instances

Refer to [Ortto API Documentation](https://help.ortto.com/developer/latest/) for endpoint details.

## Code Quality Standards

### PHPStan Configuration
- **Level**: max
- **Scope**: `src/` directory only
- Reports unmatched ignored errors

### Rector Rules
Applies the following preset rule sets to `src/` and `tests/`:
- Dead code elimination
- Code quality improvements
- Type declarations
- Privatization
- Early returns
- Strict booleans
- PHP version-specific improvements

**Exception**: Skips `AddOverrideAttributeToOverriddenMethodsRector`

### Test Coverage Requirements
- **Code coverage**: Exactly 100% required
- **Type coverage**: Exactly 100% required
- Tests written in PEST framework

### Peck Typo Checking
Ignored words: php, ortto, sdk, filesystems, favicon, js

## Package Namespace

All code lives under the `PhpDevKits\Ortto` namespace, following PSR-4 autoloading:
- `src/` → `PhpDevKits\Ortto\`
- `tests/` → `Tests\`

## Important Implementation Notes

When implementing new Ortto API endpoints:

1. Create a new Request class in `src/Requests/` extending Saloon's `Request`
2. Define the HTTP method, endpoint path, and configure request/response handling
3. Group related requests into Resource classes in `src/Resources/`
4. Add facade methods for convenient static access
5. Ensure 100% test coverage with PEST tests in `tests/`
6. Use type-safe arrays and DTOs for request/response data
7. Follow Saloon best practices for authentication, middleware, and error handling