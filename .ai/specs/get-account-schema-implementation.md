# GetAccountSchema Implementation Documentation

## Overview

Implementation of the `GetAccountSchema` request class for retrieving Ortto instance schema data. This endpoint allows developers to discover available custom fields, integration fields, and their definitions programmatically.

**Status**: ✅ Fully Implemented
**Date**: November 4, 2025

## Files Created

### 1. Request Class
**Path**: `src/Requests/Account/GetAccountSchema.php`

```php
<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Requests\Account;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetAccountSchema extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<int, string>  $namespaces  Array of namespace IDs to retrieve. Empty array returns all namespaces.
     */
    public function __construct(
        protected array $namespaces = [],
    ) {}

    public function resolveEndpoint(): string
    {
        return '/instance-schema/get';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return [
            'namespaces' => $this->namespaces,
        ];
    }
}
```

**Key Features**:
- POST request to `/instance-schema/get`
- Optional `namespaces` parameter (defaults to empty array for all namespaces)
- Follows Saloon request pattern with `HasBody` and `HasJsonBody`
- Full type safety with PHPDoc annotations

### 2. Test Class
**Path**: `tests/Unit/Account/GetAccountSchemaTest.php`

Three comprehensive tests covering:
1. **gets_multiple_namespace_schemas** - Request multiple specific namespaces
2. **gets_specific_namespace_schema** - Request single namespace
3. **gets_all_namespaces_when_empty_array_provided** - Default behavior (all namespaces)

**Test Pattern**:
- Uses `MockClient` with auto-generated fixtures
- Tests ordered newest first (reverse chronological)
- Fluent expectation chains
- Proper PHPDoc `@throws Throwable` annotations

### 3. Test Fixtures
**Path**: `tests/Fixtures/Saloon/account/`

Auto-generated fixtures:
- `get_account_schema_all_namespaces.json` (255KB - contains all available namespaces)
- `get_account_schema_specific_namespace.json`
- `get_account_schema_multiple_namespaces.json`

### 4. Configuration Update
**Path**: `peck.json`

Added `"namespaces"` to ignored words list to prevent false typo detection.

## API Endpoint Details

### Endpoint
```
POST https://api.ap3api.com/v1/instance-schema/get
```

### Request Body
```json
{
  "namespaces": []
}
```

- **Empty array**: Returns ALL available namespaces
- **Specific namespaces**: Returns only requested ones (e.g., `["cm", "sf"]`)

### Supported Namespaces
The endpoint supports 40+ namespace IDs including:

| Namespace | Description |
|-----------|-------------|
| `cm` | Custom activities, attributes, and fields |
| `a2` | Autopilot Journeys |
| `sf` | Salesforce integration |
| `sh` | Shopify integration |
| `st` | Stripe integration |
| `zd` | Zendesk integration |
| `ghl` | GoHighLevel |
| `hs` | HubSpot |
| `itbl` | Iterable |
| `klaviyo` | Klaviyo |
| `pipedrive` | Pipedrive |

### Response Structure
```json
{
  "namespaces": {
    "cm": {
      "fields": {
        "str:cm:field-name": {
          "id": "str:cm:field-name",
          "display_type": "text",
          "name": "Field Name",
          "attributes": {
            "value": {
              "liquid_variable": "{{contact.field.cm.field_name}}"
            }
          }
        }
      }
    }
  }
}
```

**Response Fields**:
- `namespaces` - Object containing requested namespace data
- `fields` - Schema definitions indexed by field ID
- `id` - Field identifier
- `display_type` - Field classification (text, geo, bool, date, etc.)
- `name` - Human-readable field name
- `attributes` - Sub-fields with liquid template variables
- `triggers` - Conditional logic operations (set, set_if_greater, set_if_less)

## Usage Examples

### Get All Namespaces
```php
use PhpDevKits\Ortto\Requests\Account\GetAccountSchema;

$response = $ortto->send(new GetAccountSchema);
// or explicitly:
$response = $ortto->send(new GetAccountSchema(namespaces: []));

$allSchemas = $response->json('namespaces');
```

### Get Specific Namespace
```php
$response = $ortto->send(new GetAccountSchema(namespaces: ['cm']));

$customFields = $response->json('namespaces.cm.fields');
```

### Get Multiple Namespaces
```php
$response = $ortto->send(
    new GetAccountSchema(namespaces: ['cm', 'sf', 'sh'])
);

$customFields = $response->json('namespaces.cm');
$salesforceFields = $response->json('namespaces.sf');
$shopifyFields = $response->json('namespaces.sh');
```

## Code Quality Results

All quality checks passed:

✅ **Pint (Code Style)**: PASS - Laravel coding standards
✅ **PHPStan (Static Analysis)**: PASS - Max level
✅ **Rector (Refactoring)**: PASS - No issues
✅ **Peck (Typos)**: PASS - No misspellings
✅ **PEST (Unit Tests)**: 3/3 tests passed, 11 assertions
✅ **Code Coverage**: 100.0% (exactly)
✅ **Type Coverage**: 100.0% (exactly)

## Implementation Notes

### Design Decisions

1. **Class Naming**: Chose `GetAccountSchema` over `GetInstanceSchema` or `GetAccountInstanceSchema`
   - More concise and clear
   - Follows pattern of `GetPeople`, `GetAudiences`
   - "Instance schema" is implementation detail

2. **No Data Classes**:
   - Response is complex nested schema data
   - No transformation needed - used raw JSON
   - No reusability across endpoints required

3. **No Factory Classes**:
   - Simple array parameter, no complex data structures
   - No Data class to factory

4. **Test Organization**:
   - Tests ordered newest first (reverse chronological)
   - Comprehensive coverage of all parameter variations
   - Uses auto-generated fixtures from real API

### Challenges & Solutions

**Challenge**: Peck typo checker flagged "namespaces" as misspelling
**Solution**: Added to `peck.json` ignore list

**Challenge**: Understanding response structure
**Solution**: Generated fixtures revealed `namespaces` wrapper object

**Challenge**: Determining whether Data classes were needed
**Solution**: Analyzed existing patterns - simple request/response doesn't require DTOs

## Testing Strategy

### Test Coverage
- **All namespaces**: Default behavior with empty array
- **Specific namespace**: Single namespace retrieval
- **Multiple namespaces**: Multiple namespace retrieval

### Test Assertions
Each test verifies:
- HTTP 200 status code
- Response is valid JSON array
- Response has `namespaces` key
- Namespaces value is an array

### Fixtures
Fixtures auto-generated on first test run via Saloon's MockClient:
- Real API responses ensure accuracy
- Fixtures committed to version control for CI/CD
- Large fixtures (255KB) indicate rich schema data available

## Related Documentation

- **API Docs**: `.ai/ortto/account/instance-schema-get.md`
- **OpenAPI Spec**: `.ai/ortto/specs/openapi.yaml` (endpoint fully documented)
- **Implementation Plan**: `.ai/specs/account-api-implementation-plan.md`
- **Task Checklist**: `.ai/specs/account-api-tasks.md`

## Future Enhancements

Potential improvements for future iterations:

1. **Data Class**: Create `InstanceSchemaData` if complex schema manipulation is needed
2. **Helper Methods**: Add methods to filter/search schema fields
3. **Caching**: Consider caching schema responses (schemas change infrequently)
4. **Resource Class**: Add to `AccountResource` when resource pattern is implemented
5. **Facade Method**: Add `Ortto::account()->getSchema()` convenience method

## Validation Checklist

- [x] Request class follows project patterns
- [x] Proper type hints and PHPDoc annotations
- [x] `declare(strict_types=1);` included
- [x] Tests follow naming conventions (snake_case)
- [x] Tests ordered newest first
- [x] 100% code coverage achieved
- [x] 100% type coverage achieved
- [x] PHPStan max level passes
- [x] Pint code style passes
- [x] Peck typo check passes
- [x] Rector refactoring check passes
- [x] All fixtures generated successfully
- [x] Implementation documented

## Conclusion

The `GetAccountSchema` request has been successfully implemented following all project standards and patterns. The implementation is production-ready with full test coverage, comprehensive documentation, and passing all quality checks.

This endpoint provides essential functionality for developers to programmatically discover available custom fields and integration schemas, enabling dynamic form generation, validation, and integration mapping.
