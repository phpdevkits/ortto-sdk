# Custom Fields Implementation - Task Checklist

**Feature**: Ortto Custom Fields API Integration
**Status**: Planning Complete - Ready for Implementation
**Total Tasks**: 38 tasks across 5 phases
**Estimated Effort**: 3-5 days

---

## Phase 1: Foundation (Enums & Data Classes)

**Goal**: Create type-safe foundation with enums, data classes, and API documentation

### Task 1.1: Create CustomFieldType Enum ⏳
- [ ] Create `src/Enums/CustomFieldType.php`
- [ ] Add all 14 field type cases with string values
- [ ] Add PHPDoc comments describing each type
- [ ] Organize alphabetically
- [ ] Follow existing enum pattern

**Field Types to Include**:
```php
- Text = 'text'
- LargeText = 'large_text'
- Integer = 'integer'
- Decimal = 'decimal'
- Currency = 'currency'
- Price = 'price'
- Date = 'date'
- Time = 'time'
- Bool = 'bool'
- Phone = 'phone'
- SingleSelect = 'single_select'
- MultiSelect = 'multi_select'
- Link = 'link'
- Object = 'object'
```

### Task 1.2: Create CustomFieldResponseData Class ⏳
- [ ] Create `src/Data/CustomFieldResponseData.php`
- [ ] Implement `Arrayable` interface
- [ ] Add properties: `name`, `fieldId`, `displayType`, `values`, `trackChanges`
- [ ] Add `toArray()` method
- [ ] Add factory support
- [ ] Add PHPDoc type hints

### Task 1.3: Create PersonCustomFieldData Class ⏳
- [ ] Create `src/Data/PersonCustomFieldData.php`
- [ ] Implement `Arrayable` interface
- [ ] Add properties: `id`, `name`, `displayType`, `liquidName`, `dicItems`, `trackedValue`
- [ ] Add `toArray()` method
- [ ] Add factory support
- [ ] Add PHPDoc type hints

### Task 1.4: Create AccountCustomFieldData Class ⏳
- [ ] Create `src/Data/AccountCustomFieldData.php`
- [ ] Implement `Arrayable` interface
- [ ] Add properties: `id`, `name`, `displayType`, `liquidName`, `dicItems`
- [ ] Note: No `trackedValue` for Account fields
- [ ] Add `toArray()` method
- [ ] Add factory support
- [ ] Add PHPDoc type hints

### Task 1.5: Create Factory Classes ⏳
- [ ] Create `tests/Factories/CustomFieldResponseDataFactory.php`
- [ ] Create `tests/Factories/PersonCustomFieldDataFactory.php`
- [ ] Create `tests/Factories/AccountCustomFieldDataFactory.php`
- [ ] Extend `BaseFactory` pattern
- [ ] Generate realistic test data
- [ ] Support `state()` method for overrides

### Task 1.6: Create API Documentation - Person Create ⏳
- [ ] Create `.ai/ortto/person/custom-field-create.md`
- [ ] Document endpoint: `POST /v1/person/custom-field/create`
- [ ] Include request/response structures
- [ ] Add multiple examples (text, select, with tracking)
- [ ] Document validation rules
- [ ] Note field limits and restrictions

### Task 1.7: Create API Documentation - Person Get ⏳
- [ ] Create `.ai/ortto/person/custom-field-get.md`
- [ ] Document endpoint: `POST /v1/person/custom-field/get`
- [ ] Include response structure with tracked_value
- [ ] Add example responses
- [ ] Document liquid_name usage

### Task 1.8: Create API Documentation - Person Update ⏳
- [ ] Create `.ai/ortto/person/custom-field-update.md`
- [ ] Document endpoint: `PUT /v1/person/custom-field/update`
- [ ] Include all value modification types
- [ ] Document processing priority (replace > add > remove)
- [ ] Add examples for each modification type
- [ ] Note change tracking toggle

### Task 1.9: Create API Documentation - Account Endpoints ⏳
- [ ] Create `.ai/ortto/accounts/custom-field-create.md`
- [ ] Create `.ai/ortto/accounts/custom-field-get.md`
- [ ] Create `.ai/ortto/accounts/custom-field-update.md`
- [ ] Document differences from Person endpoints
- [ ] Note: No track_changes support
- [ ] Note: No tracked_value in responses

**Phase 1 Deliverables**:
- ✅ 1 new enum with 14 cases
- ✅ 3 new data classes with factories
- ✅ 6 API documentation files
- ✅ All with 100% type coverage

---

## Phase 2: Person Custom Field Requests

**Goal**: Implement all Person custom field API endpoints with complete test coverage

### Task 2.1: Create CreatePersonCustomField Request ⏳
- [ ] Create `src/Requests/Person/CustomField/CreatePersonCustomField.php`
- [ ] Extend `Saloon\Http\Request`
- [ ] Implement `HasBody` with `HasJsonBody` trait
- [ ] Add constructor: `type`, `name`, `values?`, `trackChanges?`
- [ ] Method: `POST`
- [ ] Endpoint: `/person/custom-field/create`
- [ ] Implement `defaultBody()` with conditional parameters
- [ ] Accept enum or string for `type`
- [ ] Add PHPDoc with `@throws Throwable`

### Task 2.2: Create GetPersonCustomFields Request ⏳
- [ ] Create `src/Requests/Person/CustomField/GetPersonCustomFields.php`
- [ ] Extend `Saloon\Http\Request`
- [ ] No body needed (no HasBody trait)
- [ ] Method: `POST`
- [ ] Endpoint: `/person/custom-field/get`
- [ ] Simple request class with no parameters

### Task 2.3: Create UpdatePersonCustomField Request ⏳
- [ ] Create `src/Requests/Person/CustomField/UpdatePersonCustomField.php`
- [ ] Extend `Saloon\Http\Request`
- [ ] Implement `HasBody` with `HasJsonBody` trait
- [ ] Add constructor: `fieldId`, `replaceValues?`, `addValues?`, `removeValues?`, `trackChanges?`
- [ ] Method: `PUT`
- [ ] Endpoint: `/person/custom-field/update`
- [ ] Implement `defaultBody()` with conditional parameters
- [ ] Add PHPDoc with `@throws Throwable`

### Task 2.4: Update PersonResource with Custom Field Methods ⏳
- [ ] Open `src/Resources/PersonResource.php`
- [ ] Add `createCustomField()` method (alphabetically ordered)
- [ ] Add `getCustomFields()` method (alphabetically ordered)
- [ ] Add `updateCustomField()` method (alphabetically ordered)
- [ ] All methods return `Response`
- [ ] All methods throw `Throwable`
- [ ] Use named parameters
- [ ] Follow existing method pattern

### Task 2.5: Create Person Create Tests ⏳
- [ ] Create `tests/Unit/Requests/Person/CustomField/CreatePersonCustomFieldTest.php`
- [ ] Test: Creates text field successfully
- [ ] Test: Creates a single select field with values
- [ ] Test: Creates multi select field with values
- [ ] Test: Creates field with change tracking enabled
- [ ] Test: Validates values required for select types
- [ ] Test: Handles field name conversion to a kebab-case
- [ ] Use MockClient with fixtures
- [ ] Verify response structure

### Task 2.6: Create Person Get Tests ⏳
- [ ] Create `tests/Unit/Requests/Person/CustomField/GetPersonCustomFieldsTest.php`
- [ ] Test: Retrieves all person custom fields
- [ ] Test: Response includes tracked_value for each field
- [ ] Test: Response includes dic_items for select fields
- [ ] Test: Response includes liquid_name for all fields
- [ ] Use MockClient with fixtures

### Task 2.7: Create Person Update Tests ⏳
- [ ] Create `tests/Unit/Requests/Person/CustomField/UpdatePersonCustomFieldTest.php`
- [ ] Test: Replaces all field values
- [ ] Test: Adds new values to existing
- [ ] Test: Removes specified values
- [ ] Test: Updates change tracking setting
- [ ] Test: Validates only select types can update values
- [ ] Test: Prioritizes replace over add over remove
- [ ] Use MockClient with fixtures

### Task 2.8: Create Test Fixtures for Person Endpoints ⏳
- [ ] Create `tests/Fixtures/Saloon/person/custom-field/create_text_field.json`
- [ ] Create `tests/Fixtures/Saloon/person/custom-field/create_single_select_field.json`
- [ ] Create `tests/Fixtures/Saloon/person/custom-field/create_with_tracking.json`
- [ ] Create `tests/Fixtures/Saloon/person/custom-field/get_all_fields.json`
- [ ] Create `tests/Fixtures/Saloon/person/custom-field/update_replace_values.json`
- [ ] Create `tests/Fixtures/Saloon/person/custom-field/update_add_values.json`
- [ ] Create `tests/Fixtures/Saloon/person/custom-field/update_remove_values.json`
- [ ] All fixtures follow existing naming convention

### Task 2.9: Create PersonResource Tests for Custom Fields ⏳
- [ ] Update `tests/Unit/Resources/PersonResourceTest.php`
- [ ] Test: `createCustomField()` method works correctly
- [ ] Test: `getCustomFields()` method works correctly
- [ ] Test: `updateCustomField()` method works correctly
- [ ] Use MockClient pattern
- [ ] Verify correct Request class instantiated

**Phase 2 Deliverables**:
- ✅ 3 new Request classes
- ✅ 3 new Resource methods
- ✅ 18+ test cases
- ✅ 7+ test fixtures
- ✅ 100% code and type coverage

---

## Phase 3: Account Custom Field Requests

**Goal**: Implement all Account custom field API endpoints with complete test coverage

### Task 3.1: Create CreateAccountCustomField Request ⏳
- [ ] Create `src/Requests/Accounts/CustomField/CreateAccountCustomField.php`
- [ ] Extend `Saloon\Http\Request`
- [ ] Implement `HasBody` with `HasJsonBody` trait
- [ ] Add constructor: `type`, `name`, `values?`
- [ ] Note: No `trackChanges` parameter (not supported for accounts)
- [ ] Method: `POST`
- [ ] Endpoint: `/accounts/custom-field/create`
- [ ] Implement `defaultBody()` with conditional values
- [ ] Accept enum or string for `type`

### Task 3.2: Create GetAccountCustomFields Request ⏳
- [ ] Create `src/Requests/Accounts/CustomField/GetAccountCustomFields.php`
- [ ] Extend `Saloon\Http\Request`
- [ ] No body needed
- [ ] Method: `POST`
- [ ] Endpoint: `/accounts/custom-field/get`
- [ ] Simple request class with no parameters

### Task 3.3: Create UpdateAccountCustomField Request ⏳
- [ ] Create `src/Requests/Accounts/CustomField/UpdateAccountCustomField.php`
- [ ] Extend `Saloon\Http\Request`
- [ ] Implement `HasBody` with `HasJsonBody` trait
- [ ] Add constructor: `fieldId`, `replaceValues?`, `addValues?`, `removeValues?`
- [ ] Note: No `trackChanges` parameter (not supported for accounts)
- [ ] Method: `PUT`
- [ ] Endpoint: `/accounts/custom-field/update`
- [ ] Implement `defaultBody()` with conditional parameters

### Task 3.4: Update AccountsResource with Custom Field Methods ⏳
- [ ] Open `src/Resources/AccountsResource.php`
- [ ] Add `createCustomField()` method (alphabetically ordered)
- [ ] Add `getCustomFields()` method (alphabetically ordered)
- [ ] Add `updateCustomField()` method (alphabetically ordered)
- [ ] No `trackChanges` parameter in any method
- [ ] All methods return `Response`
- [ ] All methods throw `Throwable`
- [ ] Use named parameters

### Task 3.5: Create Account Create Tests ⏳
- [ ] Create `tests/Unit/Requests/Accounts/CustomField/CreateAccountCustomFieldTest.php`
- [ ] Test: Creates text field successfully
- [ ] Test: Creates single select field with values
- [ ] Test: Creates multi select field with values
- [ ] Test: Does NOT accept trackChanges parameter
- [ ] Use MockClient with fixtures

### Task 3.6: Create Account Get Tests ⏳
- [ ] Create `tests/Unit/Requests/Accounts/CustomField/GetAccountCustomFieldsTest.php`
- [ ] Test: Retrieves all account custom fields
- [ ] Test: Response does NOT include tracked_value
- [ ] Test: Response includes dic_items for select fields
- [ ] Test: Response includes liquid_name for all fields
- [ ] Use MockClient with fixtures

### Task 3.7: Create Account Update Tests ⏳
- [ ] Create `tests/Unit/Requests/Accounts/CustomField/UpdateAccountCustomFieldTest.php`
- [ ] Test: Replaces all field values
- [ ] Test: Adds new values to existing
- [ ] Test: Removes specified values
- [ ] Test: Does NOT accept trackChanges parameter
- [ ] Use MockClient with fixtures

### Task 3.8: Create Test Fixtures for Account Endpoints ⏳
- [ ] Create `tests/Fixtures/Saloon/accounts/custom-field/create_text_field.json`
- [ ] Create `tests/Fixtures/Saloon/accounts/custom-field/create_single_select_field.json`
- [ ] Create `tests/Fixtures/Saloon/accounts/custom-field/get_all_fields.json`
- [ ] Create `tests/Fixtures/Saloon/accounts/custom-field/update_replace_values.json`
- [ ] Create `tests/Fixtures/Saloon/accounts/custom-field/update_add_values.json`
- [ ] Create `tests/Fixtures/Saloon/accounts/custom-field/update_remove_values.json`

### Task 3.9: Create AccountsResource Tests for Custom Fields ⏳
- [ ] Update `tests/Unit/Resources/AccountsResourceTest.php`
- [ ] Test: `createCustomField()` method works correctly
- [ ] Test: `getCustomFields()` method works correctly
- [ ] Test: `updateCustomField()` method works correctly
- [ ] Use MockClient pattern
- [ ] Verify correct Request class instantiated

**Phase 3 Deliverables**:
- ✅ 3 new Request classes
- ✅ 3 new Resource methods
- ✅ 12+ test cases
- ✅ 6+ test fixtures
- ✅ 100% code and type coverage

---

## Phase 4: Quality Assurance & Testing

**Goal**: Verify all quality standards met and comprehensive testing complete

### Task 4.1: Data Class Tests ⏳
- [ ] Create `tests/Unit/Data/CustomFieldResponseDataTest.php`
- [ ] Create `tests/Unit/Data/PersonCustomFieldDataTest.php`
- [ ] Create `tests/Unit/Data/AccountCustomFieldDataTest.php`
- [ ] Test: `toArray()` methods work correctly
- [ ] Test: Factory support works
- [ ] Test: All properties accessible
- [ ] 100% coverage for each Data class

### Task 4.2: Enum Tests ⏳
- [ ] Create `tests/Unit/Enums/CustomFieldTypeTest.php`
- [ ] Test: All 14 enum cases exist
- [ ] Test: Enum values match API requirements
- [ ] Test: Can convert enum to string value
- [ ] 100% coverage for enum

### Task 4.3: Run Full Test Suite ⏳
- [ ] Run `composer test`
- [ ] Verify all tests pass (should be 80+ total tests now)
- [ ] Verify code coverage exactly 100%
- [ ] Verify type coverage exactly 100%
- [ ] Fix any failing tests

### Task 4.4: Run Code Quality Checks ⏳
- [ ] Run `composer test:types` (PHPStan max level)
- [ ] Run `composer test:lint` (Pint formatting)
- [ ] Run `composer test:refactor` (Rector checks)
- [ ] Run `composer test:typos` (Peck typo checking)
- [ ] Fix any issues found
- [ ] Ensure all checks pass

### Task 4.5: Manual API Testing ⏳
- [ ] Test create text field with real API
- [ ] Test create select field with real API
- [ ] Test get all fields with real API
- [ ] Test update field values with real API
- [ ] Test change tracking with real API
- [ ] Verify field IDs match expected format
- [ ] Verify responses match fixtures

### Task 4.6: Edge Case Validation ⏳
- [ ] Test creating field with special characters in name
- [ ] Test creating select field without values (should fail)
- [ ] Test enabling tracking on multi-select (should work but tracking won't apply)
- [ ] Test update with multiple value modification types (only first should apply)
- [ ] Test account endpoints reject trackChanges
- [ ] Test field limit behavior (if approaching 100)

**Phase 4 Deliverables**:
- ✅ All tests passing (80+ tests total)
- ✅ 100% code coverage
- ✅ 100% type coverage
- ✅ All quality checks passing
- ✅ Manual testing complete
- ✅ Edge cases validated

---

## Phase 5: Documentation & Integration

**Goal**: Complete all documentation and usage examples

### Task 5.1: Update README.md ⏳
- [ ] Add "Custom Fields" section after "People Management"
- [ ] Include examples for:
  - Creating a text field
  - Creating a select field with values
  - Getting all custom fields
  - Updating field options
- [ ] Note the 100 field limit
- [ ] Note Person vs Account differences

### Task 5.2: Update CLAUDE.md ⏳
- [ ] Add "Custom Fields" section under "Implemented Endpoints"
- [ ] Document all 6 endpoints
- [ ] Note field type enum usage
- [ ] Note field ID format: `{type}:cm:{field-name}`
- [ ] Document 100 field limit
- [ ] Document change tracking limitations
- [ ] Add to "Using Enums in Tests" section

### Task 5.3: Create Usage Examples ⏳
- [ ] Create `examples/custom-fields/` directory
- [ ] Create `create-text-field.php` example
- [ ] Create `create-select-field.php` example
- [ ] Create `get-all-fields.php` example
- [ ] Create `update-field-options.php` example
- [ ] All examples use enums properly
- [ ] All examples have clear comments

### Task 5.4: Review API Documentation ⏳
- [ ] Review all 6 API documentation files for accuracy
- [ ] Ensure consistent formatting
- [ ] Verify all examples are correct
- [ ] Add troubleshooting sections
- [ ] Add "Related Endpoints" cross-references

### Task 5.5: Final Documentation Review ⏳
- [ ] Spell-check all documentation
- [ ] Verify all code examples work
- [ ] Check all internal links
- [ ] Verify changelog updated (if applicable)
- [ ] Ensure consistent terminology throughout

**Phase 5 Deliverables**:
- ✅ README.md updated with custom fields
- ✅ CLAUDE.md updated with implementation notes
- ✅ 4+ usage examples created
- ✅ All API documentation reviewed
- ✅ Documentation complete and accurate

---

## Progress Tracking

### Overall Progress
- **Phase 1**: ⬜️ 0/9 tasks (0%)
- **Phase 2**: ⬜️ 0/9 tasks (0%)
- **Phase 3**: ⬜️ 0/9 tasks (0%)
- **Phase 4**: ⬜️ 0/6 tasks (0%)
- **Phase 5**: ⬜️ 0/5 tasks (0%)
- **Total**: ⬜️ 0/38 tasks (0%)

### Key Metrics
- [ ] Code Coverage: Target 100%
- [ ] Type Coverage: Target 100%
- [ ] Test Count: Target 80+ tests
- [ ] Request Classes: 6 total (0/6)
- [ ] Data Classes: 3 total (0/3)
- [ ] Enums: 1 total (0/1)
- [ ] Resource Methods: 6 total (0/6)
- [ ] API Docs: 6 total (0/6)

---

## Implementation Notes

### Testing Strategy
- Use MockClient for all API tests
- Auto-record fixtures on first run
- Organize tests by Request class
- Follow reverse chronological order (newest first)
- Use descriptive test names in snake_case
- Always use enums instead of magic strings

### Code Organization
- Methods in Resources: alphabetical order
- Imports: alphabetical order
- Test cases: newest first
- Follow existing SDK patterns

### Quality Standards
- 100% code coverage (exactly)
- 100% type coverage (exactly)
- PHPStan max level passing
- All Rector rules passing
- Pint formatting applied
- No typos (Peck check)

### Special Considerations
- Max 100 custom fields per account
- Use dedicated test account
- Field names auto-convert to kebab-case
- Change tracking only for Person fields
- Multi-select fields cannot track changes
- Update endpoint: replace > add > remove priority

---

## Risk Mitigation

### Field Limit (100 fields max)
**Solution**: Use dedicated test Ortto account, manually clean up test fields periodically

### Change Tracking Confusion
**Solution**: Clear documentation, separate Person/Account implementations, comprehensive tests

### Field Name Conversion
**Solution**: Document behavior clearly, add examples showing conversion, test edge cases

### Update Logic Priority
**Solution**: Comprehensive tests for all three modification types, validate only one used

---

## Success Criteria Checklist

### Functional ✅
- [ ] All 6 API endpoints working
- [ ] Person custom fields fully functional
- [ ] Account custom fields fully functional
- [ ] All field types supported
- [ ] Change tracking working for Person

### Quality ✅
- [ ] 100% code coverage
- [ ] 100% type coverage
- [ ] PHPStan passing
- [ ] Pint passing
- [ ] Rector passing
- [ ] Peck passing

### Documentation ✅
- [ ] API docs complete (6 files)
- [ ] README updated
- [ ] CLAUDE.md updated
- [ ] Examples created
- [ ] Limitations documented

### Testing ✅
- [ ] 30+ test cases
- [ ] All fixtures created
- [ ] Edge cases covered
- [ ] Manual testing done

---

**Task List Version**: 1.0
**Created**: 2025-11-05
**Status**: Ready to Begin
**Next Step**: Start Phase 1, Task 1.1
