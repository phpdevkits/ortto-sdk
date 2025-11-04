# Ortto Account API Implementation Plan

## Overview

This document outlines the plan for implementing Ortto Account API support in the PHP SDK. Based on the Ortto API documentation, accounts represent organizations/companies in the CDP that can have associated contacts (people).

## Current Status

### Documented Endpoints
- ✅ `POST /v1/instance-schema/get` - Retrieve schema data (documented in `.ai/ortto/account/instance-schema-get.md`)

### Known But Undocumented Endpoints
The following endpoints were discovered in the API reference index but lack detailed documentation:
- ❓ `POST /v1/accounts/get` - Retrieve one or more accounts
- ❓ `POST /v1/accounts/get-by-ids` - Retrieve accounts by their IDs
- ❓ `POST /v1/accounts/merge` - Create or update multiple accounts
- ❓ `PUT /v1/accounts/archive` - Archive accounts
- ❓ `PUT /v1/accounts/restore` - Restore archived accounts
- ❓ `DELETE /v1/accounts/delete` - Delete accounts
- ❓ `POST /v1/accounts/contacts/add` - Add contacts to an account
- ❓ `POST /v1/accounts/contacts/remove` - Remove contacts from an account

## Implementation Tasks

### Phase 1: Documentation & Schema Definition
- [x] Create `.ai/ortto/account/` directory structure
- [x] Document `instance-schema/get` endpoint
- [ ] Research and document remaining account endpoints (requires access to full API docs or testing)
- [ ] Define account field types and their formats
- [ ] Document account-person relationship model
- [ ] Create example requests/responses for each endpoint

### Phase 2: OpenAPI Specification
- [ ] Add Account entity schemas to `openapi.yaml`:
  - [ ] Account field types (similar to person fields)
  - [ ] Account merge strategies
  - [ ] Account filter structures
  - [ ] Account-contact relationship structures
- [ ] Add `instance-schema/get` endpoint to OpenAPI spec
- [ ] Add placeholder/partial specs for other account endpoints
- [ ] Include comprehensive examples for all scenarios
- [ ] Update README with account coverage

### Phase 3: SDK Implementation (Future)
- [ ] Create `src/Data/Account.php` DTO
- [ ] Create `src/Data/AccountFactory.php` test factory
- [ ] Implement request classes:
  - [ ] `GetInstanceSchema.php`
  - [ ] `GetAccounts.php`
  - [ ] `GetAccountsByIds.php`
  - [ ] `MergeAccounts.php`
  - [ ] `ArchiveAccounts.php`
  - [ ] `RestoreAccounts.php`
  - [ ] `DeleteAccounts.php`
  - [ ] `AddContactsToAccount.php`
  - [ ] `RemoveContactsFromAccount.php`
- [ ] Create `src/Resources/Accounts.php` resource
- [ ] Add facade methods to `Ortto` facade
- [ ] Write comprehensive test suite with 100% coverage

### Phase 4: Testing & Validation
- [ ] Create test fixtures for all endpoints
- [ ] Write unit tests with 100% code coverage
- [ ] Write unit tests with 100% type coverage
- [ ] Add integration tests (optional, requires real API key)
- [ ] Validate against PHPStan max level
- [ ] Run Rector refactoring checks
- [ ] Validate with Pint code style

## Account Entity Structure (Preliminary)

### Account Fields (Estimated based on Person pattern)

Similar to person fields, account fields likely follow the format:
- Built-in: `{type}::{field}` (e.g., `str::name`, `str::domain`)
- Custom: `{type}:cm:{field}` (e.g., `str:cm:industry`)

### Expected Account Field Types
- `str::name` - Account/company name
- `str::domain` - Company domain
- `str::industry` - Industry classification
- `geo::country` - Country location
- `geo::city` - City location
- `int::employees` - Number of employees
- `str::account_id` - Unique account identifier
- Custom fields with `cm` namespace

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

1. **Field Structure**: Do accounts use the same field type prefixes as persons (str::, int::, geo::, etc.)?
2. **Merge Strategies**: Do accounts support the same merge strategies (1=Append, 2=Overwrite, 3=Ignore)?
3. **Filtering**: Can accounts be filtered using the same filter operators as persons?
4. **Batch Limits**: What are the max batch sizes for account operations?
5. **Required Fields**: What fields are required when creating an account?
6. **Contact Relationships**: How are account-contact relationships managed in merge operations?

## Current Deliverables (This Task)

### Completed
1. ✅ Account documentation directory: `.ai/ortto/account/`
2. ✅ Instance schema endpoint documentation
3. ✅ This implementation plan document

### To Complete
1. ⏳ OpenAPI specification updates
2. ⏳ Task checklist in `.ai/specs/account-api-tasks.md`
3. ⏳ README updates

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
