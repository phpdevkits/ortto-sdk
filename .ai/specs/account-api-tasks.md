# Account API Implementation Task Checklist

## Documentation Tasks

### Schema Endpoint
- [x] Create account documentation directory
- [x] Document `/v1/instance-schema/get` endpoint
- [x] Add request/response examples
- [x] Document supported namespaces

### Additional Endpoints (Pending Full Documentation)
- [ ] Document `/v1/accounts/get` endpoint
- [ ] Document `/v1/accounts/get-by-ids` endpoint
- [ ] Document `/v1/accounts/merge` endpoint
- [ ] Document `/v1/accounts/archive` endpoint
- [ ] Document `/v1/accounts/restore` endpoint
- [ ] Document `/v1/accounts/delete` endpoint
- [ ] Document `/v1/accounts/contacts/add` endpoint
- [ ] Document `/v1/accounts/contacts/remove` endpoint

## OpenAPI Specification Tasks

### Schema Definitions
- [ ] Define `AccountFieldString` schema
- [ ] Define `AccountFieldBoolean` schema
- [ ] Define `AccountFieldInteger` schema
- [ ] Define `AccountFieldGeo` schema
- [ ] Define `AccountFieldDate` schema
- [ ] Define `AccountMergeStrategy` enum
- [ ] Define `AccountFindStrategy` enum
- [ ] Define `AccountFilter` schema
- [ ] Define `AccountContact` relationship schema
- [ ] Define namespace schema structures

### Endpoint Specifications
- [ ] Add `POST /v1/instance-schema/get` with:
  - [ ] Request body schema
  - [ ] Response schema with namespace structures
  - [ ] Examples for all namespaces, specific namespace
  - [ ] Error responses
- [ ] Add `POST /v1/accounts/get` (when documented)
- [ ] Add `POST /v1/accounts/get-by-ids` (when documented)
- [ ] Add `POST /v1/accounts/merge` (when documented)
- [ ] Add `PUT /v1/accounts/archive` (when documented)
- [ ] Add `PUT /v1/accounts/restore` (when documented)
- [ ] Add `DELETE /v1/accounts/delete` (when documented)
- [ ] Add `POST /v1/accounts/contacts/add` (when documented)
- [ ] Add `POST /v1/accounts/contacts/remove` (when documented)

### Examples
- [ ] Add instance-schema/get examples:
  - [ ] Get all namespaces
  - [ ] Get custom fields namespace (cm)
  - [ ] Get Salesforce namespace (sf)
  - [ ] Get multiple specific namespaces
- [ ] Add comprehensive examples for other endpoints when documented

## SDK Implementation Tasks (Future Phase)

### Data Classes
- [ ] Create `src/Data/Account.php`:
  - [ ] Implement `Arrayable` interface
  - [ ] Add `$fields` property
  - [ ] Add `toArray()` method
  - [ ] Add factory support
- [ ] Create `src/Data/InstanceSchema.php` for schema responses
- [ ] Create `src/Data/AccountContact.php` for relationship data

### Test Factories
- [ ] Create `tests/Factories/AccountFactory.php`:
  - [ ] Extend base factory pattern
  - [ ] Generate account fields
  - [ ] Support `state()` method
  - [ ] Generate realistic test data
- [ ] Create `tests/Factories/InstanceSchemaFactory.php`

### Request Classes
- [ ] `src/Requests/Account/GetInstanceSchema.php`:
  - [ ] Extend Saloon `Request`
  - [ ] Define POST method
  - [ ] Set endpoint path
  - [ ] Accept namespaces parameter
  - [ ] Handle empty array for all namespaces
- [ ] `src/Requests/Account/GetAccounts.php`
- [ ] `src/Requests/Account/GetAccountsByIds.php`
- [ ] `src/Requests/Account/MergeAccounts.php`
- [ ] `src/Requests/Account/ArchiveAccounts.php`
- [ ] `src/Requests/Account/RestoreAccounts.php`
- [ ] `src/Requests/Account/DeleteAccounts.php`
- [ ] `src/Requests/Account/AddContactsToAccount.php`
- [ ] `src/Requests/Account/RemoveContactsFromAccount.php`

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
- [ ] Create test fixtures in `tests/Fixtures/Saloon/account/`:
  - [ ] `get_instance_schema_all.json`
  - [ ] `get_instance_schema_cm.json`
  - [ ] `get_instance_schema_sf.json`
  - [ ] Additional fixtures for other endpoints when available
- [ ] Write unit tests in `tests/Unit/Account/`:
  - [ ] `GetInstanceSchemaTest.php`
  - [ ] Test all namespaces request
  - [ ] Test specific namespace request
  - [ ] Test multiple namespaces request
  - [ ] Test empty response handling
  - [ ] Additional tests for other endpoints
- [ ] Ensure 100% code coverage
- [ ] Ensure 100% type coverage
- [ ] Add account data tests in `tests/Unit/Data/`:
  - [ ] `AccountDataTest.php`
  - [ ] `InstanceSchemaDataTest.php`

### Code Quality
- [ ] Pass PHPStan analysis (max level)
- [ ] Pass Rector refactoring checks
- [ ] Pass Laravel Pint formatting
- [ ] Fix any typos with Peck
- [ ] Validate no dead code

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

**Completed Tasks: 4**
- ✅ Created account documentation directory
- ✅ Documented instance-schema/get endpoint
- ✅ Created implementation plan
- ✅ Created this task checklist

**Next Immediate Tasks:**
1. Add Account schemas to OpenAPI spec
2. Add instance-schema/get endpoint to OpenAPI spec
3. Update README.md with account coverage
4. Research full documentation for remaining endpoints

## Notes

- Some tasks are blocked pending full API documentation
- Focus first on fully implementing documented endpoint (instance-schema/get)
- Follow existing Person endpoint patterns for consistency
- Maintain 100% test coverage requirement
- All implementations must pass PHPStan max level
