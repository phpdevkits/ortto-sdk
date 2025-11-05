# Account API Implementation Task Checklist

## Documentation Tasks

### Schema Endpoint
- [x] Create account documentation directory
- [x] Document `/v1/instance-schema/get` endpoint
- [x] Add request/response examples
- [x] Document supported namespaces

### Organization Management Endpoints
- [x] Document `/v1/accounts/merge` endpoint
- [ ] Document `/v1/accounts/get` endpoint
- [ ] Document `/v1/accounts/get-by-ids` endpoint
- [ ] Document `/v1/accounts/archive` endpoint
- [ ] Document `/v1/accounts/restore` endpoint
- [ ] Document `/v1/accounts/delete` endpoint
- [ ] Document `/v1/accounts/contacts/add` endpoint
- [ ] Document `/v1/accounts/contacts/remove` endpoint

## OpenAPI Specification Tasks

### Schema Definitions
- [x] Define namespace schema structures (InstanceSchemaRequest, InstanceSchemaResponse, etc.)
- [x] Define `AccountFields` schema
- [x] Define `AccountRecord` schema
- [x] Reuse `MergeStrategy` enum (from Person endpoints)
- [x] Reuse `FindStrategy` enum (from Person endpoints)
- [x] Define `MergeAccountsRequest` schema
- [x] Define `MergeAccountsResponse` schema
- [ ] Define `AccountFilter` schema (for future get endpoint)
- [ ] Define `AccountContact` relationship schema (for future contact endpoints)

### Endpoint Specifications
- [x] Add `POST /v1/instance-schema/get` with:
  - [x] Request body schema
  - [x] Response schema with namespace structures
  - [x] Examples for all namespaces, specific namespace, multiple
  - [x] Error responses
- [x] Add `POST /v1/accounts/merge` with:
  - [x] Request body schema (MergeAccountsRequest)
  - [x] Response schema (MergeAccountsResponse)
  - [x] 9 comprehensive examples (create, update, bulk, strategies, tags, geo fields, etc.)
  - [x] Error responses
- [x] Add "Account" tag for instance schema endpoints
- [x] Add "Accounts" tag for organization management endpoints
- [ ] Add `POST /v1/accounts/get` (when documented)
- [ ] Add `POST /v1/accounts/get-by-ids` (when documented)
- [ ] Add `PUT /v1/accounts/archive` (when documented)
- [ ] Add `PUT /v1/accounts/restore` (when documented)
- [ ] Add `DELETE /v1/accounts/delete` (when documented)
- [ ] Add `POST /v1/accounts/contacts/add` (when documented)
- [ ] Add `POST /v1/accounts/contacts/remove` (when documented)

### Examples
- [x] Add instance-schema/get examples (5 examples added)
- [x] Add accounts/merge examples (9 examples added)

## SDK Implementation Tasks

### Data Classes (Future)
- [ ] Create `src/Data/Account.php`:
  - [ ] Implement `Arrayable` interface
  - [ ] Add `$fields` property
  - [ ] Add `toArray()` method
  - [ ] Add factory support
- [ ] Create `src/Data/InstanceSchema.php` for schema responses
- [ ] Create `src/Data/AccountContact.php` for relationship data

### Test Factories (Future)
- [ ] Create `tests/Factories/AccountFactory.php`:
  - [ ] Extend base factory pattern
  - [ ] Generate account fields
  - [ ] Support `state()` method
  - [ ] Generate realistic test data
- [ ] Create `tests/Factories/InstanceSchemaFactory.php`

### Request Classes
- [x] `src/Requests/Account/GetAccountSchema.php`:
  - [x] Extend Saloon `Request`
  - [x] Define POST method
  - [x] Set endpoint path `/instance-schema/get`
  - [x] Accept namespaces parameter (array of AccountNamespace|string)
  - [x] Handle empty array for all namespaces
  - [x] Created AccountNamespace enum with 45 cases
- [x] `src/Requests/Accounts/MergeAccounts.php`:
  - [x] Extend Saloon `Request`
  - [x] Define POST method
  - [x] Set endpoint path `/v1/accounts/merge`
  - [x] Accept accounts, mergeBy (required), mergeStrategy, findStrategy, async
  - [x] Handle enum value conversion
- [ ] `src/Requests/Accounts/GetAccounts.php`
- [ ] `src/Requests/Accounts/GetAccountsByIds.php`
- [ ] `src/Requests/Accounts/ArchiveAccounts.php`
- [ ] `src/Requests/Accounts/RestoreAccounts.php`
- [ ] `src/Requests/Accounts/DeleteAccounts.php`
- [ ] `src/Requests/Accounts/AddContactsToAccount.php`
- [ ] `src/Requests/Accounts/RemoveContactsFromAccount.php`

### Resource Class
- [ ] Create `src/Resources/Accounts.php`:
  - [ ] Add `getInstanceSchema()` method
  - [ ] Add `get()` method
  - [ ] Add `getByIds()` method
  - [ ] Add `merge()` method
  - [ ] Add `archive()` method
  - [ ] Add `restore()` method
  - [ ] Add `delete()` method
  - [ ] Add `addContacts()` method
  - [ ] Add `removeContacts()` method

### Facade Integration
- [ ] Add `accounts()` method to `OrttoConnector`
- [ ] Add facade accessor in `Ortto` facade
- [ ] Update connector resource registration

### Testing
- [x] Create test fixtures in `tests/Fixtures/Saloon/account/`:
  - [x] `get_account_schema_all_namespaces.json`
  - [x] `get_account_schema_specific_namespace.json`
  - [x] `get_account_schema_multiple_namespaces.json`
- [x] Create test fixtures in `tests/Fixtures/Saloon/accounts/`:
  - [x] `merge_accounts_create.json`
  - [x] `merge_accounts_merge.json`
  - [x] `merge_accounts_bulk.json`
  - [x] `merge_accounts_with_tags.json`
  - [x] `merge_accounts_merge_strategy.json`
  - [x] `merge_accounts_find_strategy.json`
- [x] Write unit tests in `tests/Unit/Requests/Account/`:
  - [x] `GetAccountSchemaTest.php` (4 tests)
  - [x] Test all namespaces request
  - [x] Test specific namespace request
  - [x] Test multiple namespaces request
  - [x] Test accepts enum values
- [x] Write unit tests in `tests/Unit/Requests/Accounts/`:
  - [x] `MergeAccountsTest.php` (6 tests)
  - [x] Test creates new account
  - [x] Test merges existing account
  - [x] Test bulk merge
  - [x] Test merge strategies
  - [x] Test find strategies
  - [x] Test tag handling
- [x] Ensure 100% code coverage (GetAccountSchema: 100%, MergeAccounts: 100%)
- [x] Ensure 100% type coverage
- [ ] Add account data tests in `tests/Unit/Data/` (when Data classes created):
  - [ ] `AccountDataTest.php`
  - [ ] `InstanceSchemaDataTest.php`

### Code Quality
- [x] Pass PHPStan analysis (max level) - GetAccountSchema & MergeAccounts
- [x] Pass Rector refactoring checks
- [x] Pass Laravel Pint formatting
- [x] Fix any typos with Peck
- [x] Validate no dead code

## Documentation Update Tasks

### README Updates
- [ ] Update `.ai/ortto/specs/README.md`:
  - [ ] Add Account endpoints section
  - [ ] Document instance-schema/get
  - [ ] Add account field types
  - [ ] Include usage examples
  - [ ] Note pending endpoints

### Project Documentation
- [ ] Update `CLAUDE.md` if needed:
  - [ ] Add account entity information
  - [ ] Document account-person relationships
  - [ ] Note account field format
- [ ] Update main README.md (if exists):
  - [ ] Add account API support status
  - [ ] Include code examples

## Validation & Release Tasks

- [ ] Run full test suite: `composer test`
- [ ] Validate code coverage: `composer test:unit`
- [ ] Run type coverage: `composer test:type-coverage`
- [ ] Run static analysis: `composer test:types`
- [ ] Check code style: `composer test:lint`
- [ ] Check for typos: `composer test:typos`
- [ ] Run refactor checks: `composer test:refactor`
- [ ] Manual API testing with real credentials (optional)
- [ ] Update CHANGELOG.md
- [ ] Create PR with account API support

## Current Progress

**Completed Tasks: 50+**

### Documentation (Complete for implemented endpoints)
- ✅ Created account/accounts documentation directories
- ✅ Documented instance-schema/get endpoint
- ✅ Documented accounts/merge endpoint
- ✅ Created implementation plan
- ✅ Created task checklist

### OpenAPI Specification (Complete for implemented endpoints)
- ✅ Added all required schemas
- ✅ Added instance-schema/get endpoint with 5 examples
- ✅ Added accounts/merge endpoint with 9 examples
- ✅ Created separate tags for Account vs Accounts

### SDK Implementation (2 endpoints complete)
- ✅ Implemented GetAccountSchema with AccountNamespace enum
- ✅ Implemented MergeAccounts
- ✅ 10 tests written (100% coverage)
- ✅ All quality checks passing

**Next Immediate Tasks:**
1. Update README.md with account coverage
2. Research full documentation for remaining endpoints (get, get-by-ids, archive, restore, delete, contacts)
3. Create Resource classes when ready
4. Add facade integration

## Notes

- Some tasks are blocked pending full API documentation
- Focus first on fully implementing documented endpoint (instance-schema/get)
- Follow existing Person endpoint patterns for consistency
- Maintain 100% test coverage requirement
- All implementations must pass PHPStan max level
