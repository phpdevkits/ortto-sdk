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

### Ortto API Documentation

Ortto API endpoint documentation is stored locally in the `.ai/ortto/` directory:
- Documentation is organized by resource/entity (e.g., `person/`, `activity/`, `campaign/`)
- Each endpoint has its own markdown file within the resource subfolder
- File naming follows the endpoint name (e.g., `get.md`, `merge.md`, `delete.md`)
- Example structure:
  ```
  .ai/ortto/
  ├── person/
  │   ├── get.md
  │   ├── merge.md
  │   └── delete.md
  ├── activity/
  │   └── create.md
  └── campaign/
      └── send.md
  ```

When implementing or modifying SDK endpoints, always refer to the corresponding documentation file in `.ai/ortto/` for accurate API specifications, request/response formats, and field definitions.

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
Ignored words: php, ortto, sdk, filesystems, favicon, js, integrations, testbench, asc, desc, param

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

### Testing with Saloon MockClient

Tests use Saloon's `MockClient` to mock HTTP responses:

```php
$mockClient = new MockClient([
    MergePeople::class => MockResponse::fixture('person/merge_people_ok'),
]);

$response = $this->ortto
    ->withMockClient($mockClient)
    ->send(new MergePeople(...));
```

**Fixture Auto-Recording**: When a fixture doesn't exist, MockClient automatically:
1. Makes a real API call to Ortto
2. Records the response as a JSON fixture in `tests/Fixtures/Saloon/`
3. Uses the recorded fixture for subsequent test runs

**Test Organization**:
- Tests related to Request classes should be placed in `tests/Unit/Requests/` directory
  - Example: Tests for `src/Requests/Person/MergePeople.php` go in `tests/Unit/Requests/Person/MergePeopleTest.php`
- New tests should be placed **after** `beforeEach`/`afterEach` hooks but **before** older tests
- Newest tests first, oldest tests last (reverse chronological order)
- Use descriptive test names in snake_case
- Fixture names should match test purpose
- **ALWAYS use enums in tests** instead of magic strings:
  - Use `PersonField::Email->value` instead of `'str::email'`
  - Use `MergeStrategy::OverwriteExisting` instead of `2`
  - Use `FindStrategy::Any` instead of `0`
  - Use `ActivityTimeframe::Last7Days` instead of `'last-7-days'`
  - Use `SortOrder::Asc` instead of `'asc'`
  - This improves code readability, type safety, and IDE autocomplete

### Ortto API Field Requirements

**Person Entity Fields**:
- Built-in fields use format: `{type}::{field}` (e.g., `str::email`, `str::first`)
- Custom fields use format: `{type}:cm:{field}` (e.g., `str:cm:job-title`)
- Custom fields must be created in Ortto CDP before use in tests

**Using PersonField Enum**:
- **ALWAYS** use `PersonField` enum for built-in Ortto fields in code
- Use `PersonField::Email->value` to get the string value when needed
- For array contexts, use array mapping: `[PersonField::Email->value, PersonField::FirstName->value]`
- Example: Instead of `'str::email'`, use `PersonField::Email->value`
- Available enum cases:
  - String fields: `ExternalId`, `Email`, `FirstName`, `LastName`, `FullName`, `PhoneNumber`, `PostalCode`
  - Boolean fields: `EmailPermission`, `SmsPermission`
  - Geo fields: `City`, `Country`
  - DateTime fields: `Birthdate`
- Custom fields (with `:cm:`) should still use string literals as they're user-defined

**GetPeople/GetPeopleByIds Requirements**:
- `fields` parameter is **required** (min: 1)
- GetPeople: max 100 fields
- GetPeopleByIds: max 20 fields
- GetPeopleByIds returns contacts as **object keyed by person_id**, not array

**MergePeople Behavior**:
- Field name in request body: `merge_by` (NOT `merged_by`)
- Status responses: `"created"` (new contact) or `"merged"` (updated existing)
- Suppression list only blocks **NEW** contact creation, NOT updates to existing contacts
- `skipSuppressionCheck: true` bypasses suppression list entirely

### Data Classes and Factories

**Person Data Class** (`src/Data/Person.php`):
- Implements `Arrayable` interface
- Has factory support via `PersonFactory`
- `toArray()` returns `['fields' => $this->fields]` structure for API requests

**PersonFactory** (`tests/Factories/PersonFactory.php`):
- Custom factory (not Eloquent-based)
- Use `state()` method to override default fields
- Generates: `str::ei` (UUID), `str::email`, `str::first`, `str::last`, `str::name`

### Implemented Endpoints

**Person Entity**:
- `MergePeople` (`POST /person/merge`) - Create or update people
  - Parameters: people, mergeBy, mergeStrategy, findStrategy, suppressionListFieldId, skipNonExisting, async, skipSuppressionCheck
  - Enums: `MergeStrategy` (AppendOnly=1, OverwriteExisting=2, Ignore=3), `FindStrategy` (Any=0, NextOnlyIfPreviousEmpty=1, All=2)

- `GetPeople` (`POST /person/get`) - Retrieve people with filters/pagination
  - Parameters: limit, sortByFieldId, sortOrder, offset, cursorId, fields (required), q, type, filter
  - Enums: `SortOrder` (Asc, Desc)

- `GetPeopleByIds` (`POST /person/get-by-ids`) - Retrieve specific people by IDs
  - Parameters: contactIds (required), fields (required)
  - Response: contacts keyed by person_id