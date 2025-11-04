# Activity Endpoints Implementation Specification

## Overview

This specification documents the implementation of the GetPersonActivities endpoint for the Ortto SDK, which retrieves the activity feed for a specific contact.

## Endpoint Details

**API Endpoint:** `POST /v1/person/get/activities`

**Purpose:** Retrieve activity feed for a contact with optional filtering by activity type, timeframe, and pagination support.

**Rate Limit:** 1 request per second

## Implementation Components

### 1. Enum: ActivityTimeframe

**Location:** `src/Enums/ActivityTimeframe.php`

**Type:** String-backed enum

**Cases:**
- `Last24Hours` → 'last-24-hours'
- `Last7Days` → 'last-7-days'
- `Last30Days` → 'last-30-days'
- `Today` → 'today'
- `Yesterday` → 'yesterday'
- `ThisWeek` → 'this-week'
- `ThisMonth` → 'this-month'
- `ThisQuarter` → 'this-quarter'
- `ThisYear` → 'this-year'
- `All` → 'all'

**Usage:** Provides type-safe timeframe filtering options while allowing string fallback for flexibility.

### 2. Request Class: GetPersonActivities

**Location:** `src/Requests/Person/GetPersonActivities.php`

**Extends:** Saloon's `Request` class with `HasBody` interface and `HasJsonBody` trait

**Constructor Parameters:**

| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| `personId` | string | Yes | - | Unique identifier for the contact |
| `activities` | ?array | No | null | Activity IDs to filter (e.g., ['act::o', 'act::c']) |
| `limit` | ?int | No | null | Number of records per page (API default: 40-50) |
| `offset` | ?int | No | null | Pagination offset |
| `timeframe` | string\|ActivityTimeframe\|null | No | null | Time range filter |

**Request Body Structure:**

```php
[
    'person_id' => '0069061b5bda4060a5765300',  // Required
    'activities' => ['act::o', 'act::c'],        // Optional
    'limit' => 50,                                // Optional
    'offset' => 0,                                // Optional
    'timeframe' => 'last-7-days',                 // Optional
]
```

**Implementation Details:**

- Required field: `person_id` (always included in body)
- Optional fields: Only included when not null
- Enum handling: Supports both `ActivityTimeframe` enum and raw string values
- Conversion: Enum cases converted to string values via `->value`

### 3. Test Suite: GetPersonActivitiesTest

**Location:** `tests/Unit/Person/GetPersonActivitiesTest.php`

**Framework:** PEST

**Test Coverage Matrix:**

| Test Name | Parameters | Purpose |
|-----------|------------|---------|
| `gets activities with all parameters` | All params including enum timeframe | Verify complete parameter handling |
| `gets activities with timeframe filter using enum` | personId + enum timeframe | Test enum timeframe conversion |
| `gets activities with timeframe filter using string` | personId + string timeframe | Test string timeframe handling |
| `gets activities with pagination` | personId + limit + offset | Verify pagination parameters |
| `gets activities with activity IDs filter` | personId + activities array | Test activity filtering |
| `gets activities with basic request` | personId only | Test minimal required request |

**Test Pattern:**

1. Initialize `Ortto` instance in `beforeEach` hook
2. Create `MockClient` with fixture mapping
3. Send request via `$this->ortto->withMockClient()->send()`
4. Chain assertions with `expect()->and()` pattern
5. Verify response structure and key fields

**Fixture Naming Convention:**

- `person/get_activities_basic` - Minimal request
- `person/get_activities_with_filter` - With activity IDs
- `person/get_activities_with_pagination` - With limit/offset
- `person/get_activities_with_timeframe` - With enum timeframe
- `person/get_activities_with_timeframe_string` - With string timeframe
- `person/get_activities_with_all_parameters` - Complete request

**Fixture Auto-Recording:**

When tests run for the first time, `MockClient` will:
1. Make real API calls to Ortto
2. Record responses as JSON fixtures in `tests/Fixtures/Saloon/person/`
3. Use recorded fixtures for subsequent test runs

### 4. Documentation

**Location:** `.ai/ortto/person/activities.md`

**Structure:**

- HTTP method and endpoint with regional variants
- Authentication headers
- Request body parameters with types and descriptions
- Timeframe options table
- Response structure with examples
- Response fields documentation
- Rate limit warnings
- Pagination guidance
- Implementation notes

## API Behavior Details

### Required vs Optional Parameters

**Required:**
- `person_id` - Always included in request body

**Optional (conditionally included):**
- `activities` - Only when filtering by specific activity types
- `limit` - Only when paginating (API determines default)
- `offset` - Only when requesting specific page
- `timeframe` - Only when filtering by time range

### Response Structure

```json
{
  "activities": [
    {
      "activity_id": "act::o",
      "activity_name": "Email open",
      "timestamp": "2025-11-03T14:23:17Z",
      "fields": { /* activity-specific fields */ }
    }
  ],
  "meta": {
    "total_count": 145,
    "field_ids": ["act::o", "act::c"],
    "has_next": true,
    "retention_policy": "90-days"
  },
  "offset": 0,
  "next_offset": 50
}
```

### Pagination Flow

1. Initial request with `limit` (e.g., 50)
2. Check `meta.has_next` in response
3. Use `next_offset` value in subsequent request's `offset` parameter
4. Continue until `has_next` is `false`

### Activity ID Examples

Common activity IDs:
- `act::o` - Email opens
- `act::c` - Email clicks
- `act::s` - Email sends
- Custom activities have unique IDs defined in Ortto CDP

## Edge Cases and Validation

### Invalid Person ID
**Scenario:** person_id doesn't exist
**Expected:** API returns error response

### Empty Activities Array
**Scenario:** `activities: []`
**Expected:** Treated as no filter (returns all activities)

### Invalid Timeframe
**Scenario:** Unsupported timeframe string
**Expected:** API returns validation error

### Rate Limiting
**Scenario:** More than 1 request per second
**Expected:** API returns rate limit error (429 status)

### No Activities Found
**Scenario:** Contact has no activities matching filters
**Expected:** Response with empty `activities` array, valid meta object

## Type Safety and Coverage

**PHPStan Level:** max

**Coverage Requirements:**
- 100% code coverage
- 100% type coverage

**Type Safety Features:**
- Backed enum for timeframe values
- Typed constructor parameters
- Nullable types for optional parameters
- Union types for enum|string support

## Integration Points

**Service Provider:** Already registered via `OrttoServiceProvider`

**Connector:** Uses existing `OrttoConnector` with region-based URL resolution

**Authentication:** X-Api-Key header via connector configuration

**No additional integration required** - follows existing SDK patterns

## Testing Strategy

### Unit Tests
- Test all parameter combinations
- Verify request body structure
- Validate enum conversion
- Check pagination handling
- Test both enum and string timeframe values

### Fixture Management
- Use MockClient for controlled test environment
- Auto-record real API responses on first run
- Maintain fixtures for consistent test results
- One fixture per test scenario

### Quality Checks
- Run `composer test` for full suite
- Verify `composer test:types` passes (PHPStan max)
- Ensure `composer test:type-coverage` shows 100%
- Check `composer test:lint` passes (Pint formatting)

## Implementation Checklist

- [x] Create ActivityTimeframe enum
- [x] Create GetPersonActivities request class
- [x] Implement conditional body field inclusion
- [x] Handle enum-to-string conversion
- [x] Create comprehensive test suite
- [x] Write API documentation
- [x] Document response structure
- [x] Add pagination guidance
- [x] Include rate limit warnings
- [x] Document fixture patterns
- [x] Create this specification document

## Future Enhancements

### Potential Additions
1. **Bulk Activity Retrieval** - Get activities for multiple contacts
2. **Activity Export** - Export activities to CSV/JSON
3. **Real-time Activity Streaming** - WebSocket or SSE support
4. **Activity Aggregation** - Summary statistics endpoint
5. **Custom Activity Creation** - POST endpoint for custom activities

### Data Classes
Consider creating:
- `ActivityData` - Typed activity object
- `ActivityFactory` - Factory for test data generation
- `ActivityCollection` - Typed collection of activities

These would follow the existing patterns used for `PersonData` and `PersonFactory`.

## References

- **API Documentation:** https://help.ortto.com/a-773-retrieve-activity-feed-for-a-given-contact
- **Saloon Documentation:** https://docs.saloon.dev/
- **Ortto API Base:** https://api.ap3api.com/
- **SDK Repository:** phpdevkits/ortto-sdk
- **Test Framework:** PEST (https://pestphp.com/)

## Notes

- All implementations follow established SDK patterns
- No breaking changes to existing code
- Backward compatible with current SDK usage
- Ready for production use after test verification
- Rate limiting must be handled by consumer applications
