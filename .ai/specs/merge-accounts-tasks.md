# MergeAccounts Implementation Tasks

## Documentation Tasks

- [x] Fetch endpoint documentation from Ortto Help Center
- [x] Create `.ai/ortto/accounts/merge.md` with complete API documentation
- [x] Create implementation plan at `.ai/specs/merge-accounts-implementation-plan.md`
- [x] Create task list at `.ai/specs/merge-accounts-tasks.md`
- [x] Update `.ai/ortto/specs/openapi.yaml` with `/v1/accounts/merge` endpoint

## Request Class Implementation

- [x] Create `src/Requests/Accounts/MergeAccounts.php`
  - [x] Extend Saloon's `Request` class
  - [x] Implement `HasBody` interface with `HasJsonBody` trait
  - [x] Set method to `POST`
  - [x] Define constructor with parameters:
    - [x] `accounts` (array, required)
    - [x] `mergeBy` (array, required)
    - [x] `mergeStrategy` (int|MergeStrategy, default: 2)
    - [x] `findStrategy` (int|FindStrategy, default: 0)
    - [x] `async` (bool, default: false)
  - [x] Implement `resolveEndpoint()` returning `/v1/accounts/merge`
  - [x] Implement `defaultBody()` with proper array mapping
  - [x] Add PHPDoc annotations for array types
  - [x] Handle enum value conversion in body

## Test Organization Updates

- [x] Move `tests/Unit/Account/GetAccountSchemaTest.php` to `tests/Unit/Requests/Account/GetAccountSchemaTest.php`
- [x] Update any references to the old test location

## Test Suite Implementation

- [x] Create `tests/Unit/Requests/Accounts/MergeAccountsTest.php`
  - [x] Add `beforeEach` hook to initialize Ortto instance
  - [x] Test: Handles tags and unset_tags
  - [x] Test: Accepts find strategy enums
  - [x] Test: Accepts merge strategy enums
  - [x] Test: Merges multiple accounts in bulk
  - [x] Test: Merges existing account (status: "merged")
  - [x] Test: Creates new account (status: "created")
  - [x] Verify MockClient auto-generates fixtures
  - [x] Ensure all assertions cover status codes and response structure

## Test Fixtures

Manually created (endpoint not yet available in API):
- [x] `tests/Fixtures/Saloon/accounts/merge_accounts_create.json`
- [x] `tests/Fixtures/Saloon/accounts/merge_accounts_merge.json`
- [x] `tests/Fixtures/Saloon/accounts/merge_accounts_bulk.json`
- [x] `tests/Fixtures/Saloon/accounts/merge_accounts_with_tags.json`
- [x] `tests/Fixtures/Saloon/accounts/merge_accounts_merge_strategy.json`
- [x] `tests/Fixtures/Saloon/accounts/merge_accounts_find_strategy.json`

## OpenAPI Specification

- [x] Add `/v1/accounts/merge` endpoint to `.ai/ortto/specs/openapi.yaml`
  - [x] Define request schema (AccountFields, AccountRecord, MergeAccountsRequest)
  - [x] Define response schema (MergeAccountsResponse, MergeAccountsResponseAccount)
  - [x] Add example: Create single account
  - [x] Add example: Update existing account
  - [x] Add example: Bulk create/update
  - [x] Add example: Append-only merge strategy
  - [x] Add example: Overwrite merge strategy
  - [x] Add example: Ignore merge strategy
  - [x] Add example: Tag management
  - [x] Add example: Geographic fields
  - [x] Add example: Multiple merge-by fields
  - [x] Add "Accounts" tag for organization management endpoints

## Quality Checks

- [x] Run `composer test:unit` - Verify 100% code coverage (MergeAccounts: 100%)
- [x] Run `composer test:type-coverage` - Verify 100% type coverage
- [x] Run `composer test:types` - Verify PHPStan max level passes (No errors)
- [x] Run `composer test:lint` - Verify Pint formatting passes
- [x] Run `composer test:typos` - Verify Peck typo check passes
- [x] Run `composer test:refactor` - Verify Rector refactor check passes
- [x] Run `composer test` - Verify full test suite passes (10 Account/Accounts tests passing)

## Validation

- [x] Verify request accepts both enum and int values for strategies
- [x] Verify `merge_by` parameter is required
- [x] Verify accounts array structure matches API requirements
- [x] Verify tag and unset_tag handling
- [x] Verify async parameter handling
- [x] Verify geographic field format (with `name` member)
- [x] Verify response status values ("created", "merged")

## Documentation

- [x] Ensure all code has proper PHPDoc comments
- [x] Verify parameter descriptions are accurate
- [x] Verify array type annotations are complete
- [x] Verify enum usage is documented

## Final Reorganization

- [x] Separate Account (instance schema) from Accounts (organizations)
- [x] Move MergeAccounts to `src/Requests/Accounts/` namespace
- [x] Move test to `tests/Unit/Requests/Accounts/`
- [x] Move documentation to `.ai/ortto/accounts/`
- [x] Move fixtures to `tests/Fixtures/Saloon/accounts/`
- [x] Update all file references and namespaces
- [x] Update OpenAPI spec tags to distinguish Account vs Accounts
- [x] Verify all tests still pass after reorganization
