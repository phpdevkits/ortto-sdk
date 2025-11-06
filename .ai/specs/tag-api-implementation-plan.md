# Tag API Implementation Plan

## Overview
Implement the Ortto "Retrieve a list of tags" endpoint with full type safety following Person/Account/Activity patterns.

**Endpoint:** `POST /v1/tags/get`
**Documentation:** https://help.ortto.com/a-263-retrieve-a-list-of-tags-get
**Pattern:** TagResource with tag() accessor + Data classes + Enums

## Endpoint Details

### Request
- **Method:** POST
- **Path:** `/v1/tags/get`
- **Authentication:** X-Api-Key header
- **Content-Type:** application/json

### Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `q` | string | No | Search term for filtering tags. Uses token-based search with AND logic |

### Response Structure
Returns an array of tag objects:

| Field | Type | Description |
|-------|------|-------------|
| `id` | integer | Tag identifier |
| `instance_id` | string | Account instance name |
| `name` | string | Tag name |
| `source` | string | Creation source (csv, api, manual, zapier) |
| `created_by_id` | string | User ID who created the tag |
| `created_by_name` | string | Creator's name |
| `created_by_email` | string | Creator's email |
| `created_at` | timestamp | Creation date (ISO 8601) |
| `last_used` | timestamp | Last usage date (ISO 8601) |
| `count` | integer | Total contacts with this tag |
| `sms_opted_in` | integer | Contacts with SMS permission |
| `subscribers` | integer | Contacts with email permission |
| `type` | string | Empty string = person tag; "account" = account tag |

## Files to Create

### 1. Documentation
- `.ai/ortto/tag/get.md` - Local copy of API documentation

### 2. Enums (2 files)
- `src/Enums/TagSource.php` - String-backed enum
  - Values: Csv="csv", Api="api", Manual="manual", Zapier="zapier"
- `src/Enums/TagType.php` - String-backed enum
  - Values: Person="", Account="account"

### 3. Data Classes
- `src/Data/TagData.php` - Type-safe tag response object
  - Implements Arrayable interface
  - Properties: id, instance_id, name, source, created_by_id, created_by_name, created_by_email, created_at, last_used, count, sms_opted_in, subscribers, type
  - Uses TagSource and TagType enums

### 4. Request
- `src/Requests/Tag/GetTags.php`
  - POST method to `/tags/get`
  - Constructor: `__construct(?string $q = null)`
  - Uses HasJsonBody trait
  - Conditional body: only include `q` if provided

### 5. Resource
- `src/Resources/TagResource.php`
  - Extends BaseResource
  - Method: `get(?string $q = null): Response`

### 6. Connector Update
- Update `src/Ortto.php`
  - Add `tag(): TagResource` method
  - Follow person()/account()/activity() pattern

### 7. Test Files
- `tests/Factories/TagDataFactory.php` - Factory for test data
- `tests/Unit/Enums/TagSourceTest.php` - Enum value tests
- `tests/Unit/Enums/TagTypeTest.php` - Enum value tests
- `tests/Unit/Data/TagDataTest.php` - Data class tests
- `tests/Unit/Requests/Tag/GetTagsTest.php` - Request tests
- `tests/Unit/Resources/TagResourceTest.php` - Resource tests

### 8. Fixtures (auto-recorded)
- `tests/Fixtures/Saloon/tag/get_tags_all.json`
- `tests/Fixtures/Saloon/tag/get_tags_with_search.json`

## Implementation Steps

### Step 1: Create Local Documentation
- Download and save API documentation to `.ai/ortto/tag/get.md`
- Include endpoint details, parameters, response structure, examples

### Step 2: Create Enums
- Create `TagSource` enum with 4 values
- Create `TagType` enum with 2 values (empty string for Person, "account" for Account)
- Create enum tests verifying correct values

### Step 3: Create TagData Class
- Implement all 13 properties from API response
- Use TagSource and TagType enums for type safety
- Implement toArray() method
- Add proper PHPDoc annotations
- Consider nullable fields appropriately

### Step 4: Create TagDataFactory
- Extend BaseFactory pattern (like PersonFactory, PersonSubscriptionDataFactory)
- Generate realistic test data
- Support state() method for customization
- Use Faker for dynamic data

### Step 5: Create GetTags Request
- POST method to `/tags/get`
- Optional `q` parameter in constructor
- HasJsonBody trait with defaultBody() method
- Only include `q` in body if provided (conditional)

### Step 6: Create TagResource
- Extend BaseResource
- Implement `get(?string $q = null): Response` method
- Send GetTags request via connector

### Step 7: Update Ortto Connector
- Add import for TagResource
- Add `tag(): TagResource` method
- Support config override via `ortto.resources.tag`
- Follow exact pattern from activity(), person(), account()

### Step 8: Create Comprehensive Tests
**MANDATORY: All tests MUST use enums instead of hardcoded strings**

- **TagSourceTest**: Verify all 4 enum values
- **TagTypeTest**: Verify both enum values
- **TagDataTest**:
  - Test toArray() transformation
  - Test with TagSource enum usage
  - Test with TagType enum usage
  - Test nullable fields
- **GetTagsTest**:
  - Test get all tags (no search)
  - Test get with search term
  - Use MockClient with fixtures
  - **Use TagSource/TagType enums in assertions**
- **TagResourceTest**:
  - Test resource get() method
  - Test with search parameter
  - **Use TagSource/TagType enums in test data**

### Step 9: Run Tests and Record Fixtures
- Run `composer test`
- MockClient will auto-record API responses to fixtures
- Verify 100% code coverage
- Verify 100% type coverage
- Fix any PHPStan errors
- Apply Rector suggestions

### Step 10: Final Validation and Commit
- Run `composer test` one final time
- Ensure all quality checks pass
- Git add and commit with descriptive message
- No Claude Code footer in commit message

## Key Requirements

### Mandatory Rules
✅ **All tests MUST use enums** (TagSource, TagType) instead of hardcoded strings
✅ 100% code coverage required
✅ 100% type coverage required
✅ PHPStan max level compliance
✅ Follow established SDK patterns (Person/Activity/Account)
✅ Proper PHPDoc annotations with types
✅ Use CarbonImmutable for any dynamic dates

### Code Quality
- Laravel Pint formatting
- Rector refactoring rules applied
- No typos (Peck validation)
- Proper namespacing and imports

### Testing
- Use Saloon MockClient with auto-fixture recording
- Test both success and edge cases
- Use factories for test data generation
- Enum usage in all test assertions

## Usage Example (After Implementation)

```php
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Enums\TagSource;
use PhpDevKits\Ortto\Enums\TagType;

// Get all tags
$ortto = new Ortto;
$response = $ortto->tag()->get();
$tags = $response->json();

// Search for specific tags
$response = $ortto->tag()->get(q: 'vip customer');

// With type-safe TagData (if implementing from() method)
foreach ($response->json() as $tagArray) {
    if ($tagArray['source'] === TagSource::Api->value) {
        // Handle API-created tags
    }

    if ($tagArray['type'] === TagType::Account->value) {
        // Handle account tags
    }
}
```

## Special Considerations

1. **No Pagination**: Unlike GetPeople/GetAccounts, this endpoint returns all matching tags without pagination
2. **Search Behavior**: The `q` parameter uses AND logic between tokens
3. **Empty String Type**: Person tags have `type=""` which is unusual - handle carefully in enum
4. **Integer ID**: Tag IDs are integers, not MongoDB ObjectId strings like other entities
5. **Optional Request Body**: Can send empty `{}` body to get all tags
6. **Two Timestamps**: Both `created_at` and `last_used` use ISO 8601 format

## Success Criteria

- [ ] All 12+ files created
- [ ] 100% test coverage achieved
- [ ] 100% type coverage achieved
- [ ] All tests use enums (TagSource, TagType)
- [ ] PHPStan max level passes
- [ ] Peck typo check passes
- [ ] Rector refactoring applied
- [ ] Git commit completed
- [ ] Can retrieve tags via `$ortto->tag()->get()`
- [ ] Search functionality works correctly
