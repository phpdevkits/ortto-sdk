# Ortto Account API Implementation Plan

## Overview

This document outlines the plan for implementing Ortto Account API support in the PHP SDK. Based on the Ortto API documentation, accounts represent organizations/companies in the CDP that can have associated contacts (people).

## Current Status

### Documented Endpoints (Account - Instance Schema)
- ✅ `POST /v1/instance-schema/get` - Retrieve schema data (documented in `.ai/ortto/account/instance-schema-get.md`)
  - Implemented: `src/Requests/Account/GetAccountSchema.php`
  - Tests: `tests/Unit/Requests/Account/GetAccountSchemaTest.php`

### Documented Endpoints (Accounts - Organizations)
- ✅ `POST /v1/accounts/merge` - Create or update multiple accounts (documented in `.ai/ortto/accounts/merge.md`)
  - Implemented: `src/Requests/Accounts/MergeAccounts.php`
  - Tests: `tests/Unit/Requests/Accounts/MergeAccountsTest.php`

### Known But Undocumented Endpoints
The following endpoints were discovered in the API reference index but lack detailed documentation:
- ❓ `POST /v1/accounts/get` - Retrieve one or more accounts
- ❓ `POST /v1/accounts/get-by-ids` - Retrieve accounts by their IDs
- ❓ `PUT /v1/accounts/archive` - Archive accounts
- ❓ `PUT /v1/accounts/restore` - Restore archived accounts
- ❓ `DELETE /v1/accounts/delete` - Delete accounts
- ❓ `POST /v1/accounts/contacts/add` - Add contacts to an account
- ❓ `POST /v1/accounts/contacts/remove` - Remove contacts from an account

## Implementation Tasks

### Phase 1: Documentation & Schema Definition
- [x] Create `.ai/ortto/account/` directory structure
- [x] Create `.ai/ortto/accounts/` directory structure (for organizations)
- [x] Document `instance-schema/get` endpoint
- [x] Document `accounts/merge` endpoint
- [x] Define account field types and their formats (namespace `o` for built-in, `oc` for custom)
- [ ] Document account-person relationship model
- [ ] Research and document remaining account endpoints (requires access to full API docs or testing)

### Phase 2: OpenAPI Specification
- [x] Add Account entity schemas to `openapi.yaml`:
  - [x] Instance schema structures (InstanceSchemaRequest, InstanceSchemaResponse, etc.)
  - [x] Account field types (AccountFields, AccountRecord)
  - [x] Account merge strategies (reuses MergeStrategy enum)
  - [x] Account find strategies (reuses FindStrategy enum)
- [x] Add `instance-schema/get` endpoint to OpenAPI spec
- [x] Add `accounts/merge` endpoint to OpenAPI spec with 9 examples
- [x] Create separate tags: "Account" (instance schema) vs "Accounts" (organizations)
- [ ] Add placeholder/partial specs for other account endpoints when documented
- [ ] Update README with account coverage

### Phase 3: SDK Implementation
- [ ] Create `src/Data/Account.php` DTO (for future organization data handling)
- [ ] Create `src/Data/AccountFactory.php` test factory
- [x] Implement request classes:
  - [x] `GetAccountSchema.php` (Account namespace - instance schema)
  - [x] `MergeAccounts.php` (Accounts namespace - organizations)
  - [ ] `GetAccounts.php`
  - [ ] `GetAccountsByIds.php`
  - [ ] `ArchiveAccounts.php`
  - [ ] `RestoreAccounts.php`
  - [ ] `DeleteAccounts.php`
  - [ ] `AddContactsToAccount.php`
  - [ ] `RemoveContactsFromAccount.php`
- [ ] Create `src/Resources/AccountResource.php` resource (for instance schema)
- [ ] Create `src/Resources/AccountsResource.php` resource (for organizations)
- [ ] Add facade methods to `Ortto` facade
- [x] Write comprehensive test suite with 100% coverage for implemented endpoints

### Phase 4: Testing & Validation
- [x] Create test fixtures for implemented endpoints
- [x] Write unit tests with 100% code coverage (GetAccountSchema, MergeAccounts)
- [x] Write unit tests with 100% type coverage
- [ ] Add integration tests (optional, requires real API key)
- [x] Validate against PHPStan max level
- [x] Run Rector refactoring checks
- [x] Validate with Pint code style

## Account Entity Structure (Confirmed)

### Account Fields Format

Account/organization fields follow the format:
- **Built-in**: `{type}:o:{field}` (e.g., `str:o:name`, `str:o:website`)
- **Custom**: `{type}:oc:{field}` (e.g., `str:oc:custom-field`)

### Confirmed Account Field Types
- `str:o:name` - Organization name
- `str:o:website` - Website URL
- `int:o:employees` - Number of employees
- `str:o:industry` - Industry classification
- `geo:o:city` - City (requires `{"name": "value"}` format)
- `str:o:address` - Street address
- `geo:o:region` - Region/State (requires `{"name": "value"}` format)
- `geo:o:country` - Country (requires `{"name": "value"}` format)
- `str:o:postal` - Postal code
- `str:o:source` - Initial source
- Custom fields with `oc` namespace (up to 100 supported)

### Account-Person Relationship
Based on the Person API documentation showing `idt::o` field (organization/account ID), the relationship is:
- Person records can have an `idt::o` field linking to an account
- Accounts can have multiple associated contacts
- Endpoints exist to add/remove contacts from accounts

## Dependencies & Prerequisites

### Required Information
To complete implementation, we need:
1. **Full endpoint documentation** for undocumented account endpoints
2. **Complete account field list** with types and formats
3. **Example responses** showing actual account data structures
4. **Merge/filter behavior** specifics for accounts
5. **Relationship management** details for account-contact associations

### Potential Sources
- Ortto official API documentation (may require support request)
- API testing with valid credentials
- Ortto SDK examples in other languages
- Ortto support team assistance

## Questions for Clarification

1. ✅ **Field Structure**: Confirmed - accounts use `type:o:field` for built-in, `type:oc:field` for custom
2. ✅ **Merge Strategies**: Confirmed - supports same strategies (1=Append, 2=Overwrite, 3=Ignore)
3. ❓ **Filtering**: Can accounts be filtered using the same filter operators as persons?
4. ✅ **Batch Limits**: Confirmed - 1-100 accounts per merge request
5. ❓ **Required Fields**: What fields are required when creating an account? (merge_by is required but can be any field)
6. ❓ **Contact Relationships**: How are account-contact relationships managed in merge operations?

## Answered Questions

1. **`merge_by` Requirement**: Unlike person endpoints, `merge_by` is **required** for accounts (no defaults)
2. **Account ID Constraint**: If merging by account ID, it must be the only field in `merge_by`
3. **Find Strategies**: Supports same find strategies as persons (0=Any, 1=NextOnlyIfPreviousEmpty, 2=All)
4. **Geographic Fields**: Require object format with `name` member: `{"name": "San Francisco"}`
5. **Tags**: Supported via `tags` and `unset_tags` arrays, applied regardless of create/update status
6. **Namespace Separation**: Instance schema uses `Account` namespace, organizations use `Accounts` namespace

## Current Deliverables

### Completed
1. ✅ Account documentation directories:
   - `.ai/ortto/account/` - Instance schema endpoints
   - `.ai/ortto/accounts/` - Organization management endpoints
2. ✅ Endpoint documentation:
   - `instance-schema-get.md` - Schema retrieval
   - `merge.md` - Organization create/update
3. ✅ Implementation planning documents
4. ✅ OpenAPI specification with both endpoints and comprehensive examples
5. ✅ SDK implementation:
   - `GetAccountSchema` - Full implementation with tests
   - `MergeAccounts` - Full implementation with tests
6. ✅ Test suites with 100% coverage
7. ✅ Quality checks passing (PHPStan max, Pint, Peck, Rector)

### To Complete
1. ⏳ README updates
2. ⏳ Additional endpoint implementations (get, get-by-ids, archive, restore, delete, contacts)

## Next Steps

1. **Immediate**: Complete OpenAPI spec additions for documented endpoint
2. **Short-term**: Research undocumented endpoints through:
   - Ortto support documentation requests
   - API testing with valid credentials
   - Community resources or example code
3. **Long-term**: Implement SDK support once full documentation is available

## Notes

- The Ortto documentation at https://help.ortto.com/a-802-account is limited to the schema endpoint
- Additional account endpoints are known to exist but lack public documentation
- Implementation will follow the same patterns established for Person endpoints
- All code must maintain 100% test coverage and pass PHPStan max level checks
