# MergeAccounts Implementation Plan

## Overview

Implement the `/v1/accounts/merge` endpoint for creating or updating organizations (accounts) in Ortto, following the same patterns established by `GetAccountSchema` and `MergePeople`.

## Current Status

- ✅ Documentation fetched from https://help.ortto.com/a-278-create-or-update-one-or-more-organizations-merge
- ✅ Endpoint documentation created at `.ai/ortto/accounts/merge.md`
- ⏳ Request class implementation
- ⏳ Test suite implementation
- ⏳ OpenAPI specification update

## Implementation Phases

### Phase 1: Request Class (`src/Requests/Accounts/MergeAccounts.php`)

**Pattern Reference**: Similar to `MergePeople` request at `src/Requests/Person/MergePeople.php`

**Constructor Parameters**:
- `accounts` (array, required) - Array of account records (1-100 max)
  - Type: `array<int, array<string, mixed>>`
  - Each account contains: `fields`, optional `tags`, optional `unset_tags`
- `mergeBy` (array, required) - Field IDs for merge logic
  - Type: `string[]`
  - **Important**: Unlike person endpoints, this is REQUIRED (no defaults)
  - Constraint: If merging by account ID, it must be the only field
- `mergeStrategy` (int|MergeStrategy, optional) - Default: 2 (Overwrite)
  - Type: `int|MergeStrategy`
  - Reuse existing enum from `src/Enums/MergeStrategy.php`
- `findStrategy` (int|FindStrategy, optional) - Default: 0 (Any)
  - Type: `int|FindStrategy`
  - Reuse existing enum from `src/Enums/FindStrategy.php`
- `async` (bool, optional) - Default: false
  - Type: `bool`

**Endpoint**: `/v1/accounts/merge`

**Request Body Mapping**:
```php
[
    'accounts' => $this->accounts,
    'merge_by' => $this->mergeBy,
    'merge_strategy' => is_int($this->mergeStrategy) ? $this->mergeStrategy : $this->mergeStrategy->value,
    'find_strategy' => is_int($this->findStrategy) ? $this->findStrategy : $this->findStrategy->value,
    'async' => $this->async,
]
```

**Key Differences from MergePeople**:
- No suppression list logic needed
- `merge_by` parameter is required (no default)
- Accounts use namespace `o` (organization) instead of empty string
- Custom account fields use namespace `oc` instead of `cm`

### Phase 2: Test Suite (`tests/Unit/Requests/Accounts/MergeAccountsTest.php`)

**Test Cases** (ordered newest first):

1. **Handles tags and unset_tags** - Verify tag management
2. **Accepts find strategy enums** - Test `FindStrategy::Any` and `FindStrategy::NextOnlyIfPreviousEmpty`
3. **Accepts merge strategy enums** - Test `MergeStrategy::OverwriteExisting`, `MergeStrategy::AppendOnly`, `MergeStrategy::Ignore`
4. **Merges multiple accounts in bulk** - Create/update 5+ accounts in one request
5. **Merges existing account** - Test response status: "merged"
6. **Creates new account** - Test response status: "created"

**Mock Client Pattern**:
```php
$mockClient = new MockClient([
    MergeAccounts::class => MockResponse::fixture('account/merge_accounts_create'),
]);

$response = $this->ortto
    ->withMockClient($mockClient)
    ->send(new MergeAccounts(...));
```

**Fixtures Location**: `tests/Fixtures/Saloon/accounts/`
- `merge_accounts_create.json` - New account creation
- `merge_accounts_merge.json` - Existing account update
- `merge_accounts_bulk.json` - Multiple accounts
- `merge_accounts_with_tags.json` - Tag operations
- (Auto-generated on first test run via MockClient)

### Phase 3: OpenAPI Specification Update

**File**: `.ai/ortto/specs/openapi.yaml`

**Add**:
- `/v1/accounts/merge` endpoint under `paths:`
- Request body schema with examples
- Response schema with status values ("created", "merged")
- Account field format documentation
- Tag operations examples

**Example Scenarios**:
- Create single account
- Update existing account (merge)
- Bulk create/update
- Using merge strategies (Append, Overwrite, Ignore)
- Tag management (add/remove)
- Geographic fields with `name` member

### Phase 4: Quality Assurance

**Required Checks** (all must pass):
- ✅ 100% code coverage (`composer test:unit`)
- ✅ 100% type coverage (`composer test:type-coverage`)
- ✅ PHPStan max level (`composer test:types`)
- ✅ Pint formatting (`composer test:lint`)
- ✅ Peck typo check (`composer test:typos`)
- ✅ Rector refactor check (`composer test:refactor`)

**Full test suite**: `composer test`

## Account Entity Structure

### Built-in Fields (namespace `o`)

| Field ID | Type | Description |
|----------|------|-------------|
| `str:o:name` | string | Organization name |
| `str:o:website` | string | Website URL |
| `int:o:employees` | integer | Number of employees |
| `str:o:industry` | string | Industry |
| `geo:o:city` | geo | City (requires `name` member) |
| `str:o:address` | string | Street address |
| `geo:o:region` | geo | Region/State (requires `name` member) |
| `geo:o:country` | geo | Country (requires `name` member) |
| `str:o:postal` | string | Postal code |
| `str:o:source` | string | Source |

### Custom Fields (namespace `oc`)

- Up to 100 custom fields supported
- Format: `type:oc:field-name`
- Must be created in Ortto CDP before use

### Field Types

- `str` - String value
- `int` - Integer value
- `geo` - Geographical object (requires `{"name": "value"}` structure)

## Merge Behavior Notes

1. **Mandatory merge_by**: Unlike person endpoints, no defaults exist; at least one field required
2. **Account ID constraint**: If merging by account ID, it must be the only field in `merge_by`
3. **Null handling**: Set values to `null` to exclude from searches; use `0` or `""` for empty inclusions
4. **Tags**: Applied regardless of create/update status; can use `unset_tags` to remove existing tags

## Questions for Clarification

None - the endpoint is well-documented and follows established patterns.

## Dependencies

**Existing Code to Reuse**:
- ✅ `src/Enums/MergeStrategy.php` - Merge strategy enum (1, 2, 3)
- ✅ `src/Enums/FindStrategy.php` - Find strategy enum (0, 1, 2)
- ✅ Saloon Request/MockClient patterns from other requests

**No New Dependencies Required**
