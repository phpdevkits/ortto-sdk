# Activity Definition Implementation - Task Checklist

**Feature**: Ortto Activity Definition API Integration
**Status**: Ready for Implementation
**Total Tasks**: 38 tasks across 4 phases
**Estimated Effort**: 2-3 days
**Date**: 2025-11-05

---

## Progress Tracking

### Overall Progress
- **Phase 1**: ✅ Complete (Documentation gathered)
- **Phase 2**: ✅ Complete (Plan approved)
- **Phase 3a**: ⬜️ 0/9 tasks (0%) - Create Endpoint
- **Phase 3b**: ⬜️ 0/6 tasks (0%) - Modify Endpoint
- **Phase 3c**: ⬜️ 0/5 tasks (0%) - Delete Endpoint
- **Phase 4**: ⬜️ 0/6 tasks (0%) - Completion
- **Total**: ⬜️ 2/38 tasks (5%)

---

## Phase 1: Documentation Gathering ✅

### Task 1.1: Fetch API Documentation ✅
- [x] Fetch create endpoint documentation
- [x] Fetch modify endpoint documentation
- [x] Fetch delete endpoint documentation
- [x] Review existing local documentation in `.ai/ortto/activity/`
- [x] Understand API request/response structures

### Task 1.2: Analyze Existing Codebase ✅
- [x] Review `ActivityResource` implementation
- [x] Review `CreateActivities` request pattern
- [x] Review existing Data classes (`ActivityData`, `ActivityLocationData`)
- [x] Review test patterns and MockClient usage
- [x] Identify reusable components (`ActivityDisplayType` enum)

**Phase 1 Complete**: All documentation gathered and analyzed

---

## Phase 2: Planning ✅

### Task 2.1: Create Implementation Plan ✅
- [x] Document all 3 endpoints
- [x] Design Data class structure
- [x] Plan Request class architecture
- [x] Define test coverage requirements
- [x] Create timeline estimate

### Task 2.2: Get Plan Approval ✅
- [x] Present plan to user
- [x] Confirm approach (Data classes vs arrays)
- [x] Confirm scope (all 3 endpoints)
- [x] Confirm integration point (extend ActivityResource)

**Phase 2 Complete**: Plan approved, ready for implementation

---

## Phase 3a: Create Endpoint Implementation

**Goal**: Implement activity definition creation with full test coverage

### Task 3a.1: Create ActivityIcon Enum ⏳
- [ ] Create `src/Enums/ActivityIcon.php`
- [ ] Add all 15 icon cases with string values
- [ ] Add PHPDoc comments for each icon
- [ ] Follow existing enum pattern
- [ ] Use `declare(strict_types=1);`

**Icons to include**:
- Calendar, Caution, Clicked, Coupon, Download
- Email, Eye, Flag, Happy, Money
- Page, Phone, Reload, Tag, Time

### Task 3a.2: Create ActivityAttributeDefinitionData Class ⏳
- [ ] Create `src/Data/ActivityAttributeDefinitionData.php`
- [ ] Implement `Arrayable` interface
- [ ] Add properties: `name`, `displayType`, `fieldId`
- [ ] Add `toArray()` method with snake_case conversion
- [ ] Handle enum-to-string conversion for `displayType`
- [ ] Add PHPDoc type hints
- [ ] Use `declare(strict_types=1);`

### Task 3a.3: Create ActivityDisplayStyleData Class ⏳
- [ ] Create `src/Data/ActivityDisplayStyleData.php`
- [ ] Implement `Arrayable` interface
- [ ] Add properties: `type`, `title`, `attributeName`, `attributeFieldId`
- [ ] Add `toArray()` method with conditional field inclusion
- [ ] Convert to snake_case (e.g., `attributeName` → `attribute_name`)
- [ ] Add PHPDoc type hints
- [ ] Use `declare(strict_types=1);`

### Task 3a.4: Create ActivityDefinitionData Class ⏳
- [ ] Create `src/Data/ActivityDefinitionData.php`
- [ ] Implement `Arrayable` interface
- [ ] Add all properties (name, iconId, trackConversionValue, etc.)
- [ ] Add `toArray()` method with conditional field inclusion
- [ ] Handle enum-to-string conversion for `iconId`
- [ ] Handle nested Data object conversion (displayStyle, attributes)
- [ ] Add PHPDoc type hints
- [ ] Use `declare(strict_types=1);`

### Task 3a.5: Create CreateActivityDefinition Request ⏳
- [ ] Create `src/Requests/Activity/CreateActivityDefinition.php`
- [ ] Extend `Saloon\Http\Request`
- [ ] Implement `HasBody` interface with `HasJsonBody` trait
- [ ] Add constructor with `definition` parameter
- [ ] Set method to `POST`
- [ ] Implement `resolveEndpoint()` returning `/definitions/activity/create`
- [ ] Implement `defaultBody()` handling both array and Data object
- [ ] Add PHPDoc with `@throws Throwable`
- [ ] Use `declare(strict_types=1);`

### Task 3a.6: Add createDefinition() to ActivityResource ⏳
- [ ] Open `src/Resources/ActivityResource.php`
- [ ] Import required classes (`ActivityDefinitionData`, `CreateActivityDefinition`)
- [ ] Add `createDefinition()` method
- [ ] Accept `array|ActivityDefinitionData` parameter
- [ ] Return `Response`
- [ ] Add PHPDoc comments
- [ ] Add `@throws Throwable`
- [ ] Follow alphabetical method ordering

### Task 3a.7: Create Enum Tests ⏳
- [ ] Create `tests/Unit/Enums/ActivityIconTest.php`
- [ ] Test each icon value (15 tests)
- [ ] Test total icon count is 15
- [ ] Use PEST framework
- [ ] Ensure 100% coverage

### Task 3a.8: Create Data Class Tests ⏳
- [ ] Create `tests/Unit/Data/ActivityAttributeDefinitionDataTest.php`
  - [ ] Test: converts to array with name and display type
  - [ ] Test: converts with display type enum
  - [ ] Test: converts with field_id
  - [ ] Test: converts with "do-not-map"
  - [ ] Test: converts with empty string field_id

- [ ] Create `tests/Unit/Data/ActivityDisplayStyleDataTest.php`
  - [ ] Test: converts with type only
  - [ ] Test: converts with activity_attribute type
  - [ ] Test: converts with activity_template type
  - [ ] Test: converts with all fields

- [ ] Create `tests/Unit/Data/ActivityDefinitionDataTest.php`
  - [ ] Test: converts with required fields only
  - [ ] Test: converts with all optional fields
  - [ ] Test: converts with icon enum
  - [ ] Test: converts with icon string
  - [ ] Test: converts with displayStyle Data object
  - [ ] Test: converts with displayStyle array
  - [ ] Test: converts with attributes array
  - [ ] Test: handles nested Data objects

### Task 3a.9: Create Request and Resource Tests ⏳
- [ ] Create `tests/Unit/Requests/Activity/CreateActivityDefinitionTest.php`
  - [ ] Test: creates basic activity definition
  - [ ] Test: creates with all fields
  - [ ] Test: creates with display style (activity only)
  - [ ] Test: creates with display style (activity_attribute)
  - [ ] Test: creates with display style (activity_template)
  - [ ] Test: creates with icon enum
  - [ ] Test: creates with attributes
  - [ ] Test: creates with Data object

- [ ] Update `tests/Unit/Resources/ActivityResourceTest.php`
  - [ ] Test: createDefinition() method works
  - [ ] Test: createDefinition() with Data object
  - [ ] Test: createDefinition() with array

- [ ] Create test fixtures (auto-record or manual)
  - [ ] `activity/create_definition_basic.json`
  - [ ] `activity/create_definition_full.json`
  - [ ] `activity/create_definition_with_template.json`

**Phase 3a Deliverables**:
- ✅ 1 enum (ActivityIcon)
- ✅ 3 Data classes
- ✅ 1 Request class
- ✅ 1 Resource method
- ✅ 30+ test cases
- ✅ 100% code and type coverage

---

## Phase 3b: Modify Endpoint Implementation

**Goal**: Implement activity definition modification with full test coverage

### Task 3b.1: Create ModifyActivityDefinition Request ⏳
- [ ] Create `src/Requests/Activity/ModifyActivityDefinition.php`
- [ ] Extend `Saloon\Http\Request`
- [ ] Implement `HasBody` interface with `HasJsonBody` trait
- [ ] Add constructor with `activityFieldId` and `definition` parameters
- [ ] Set method to `PUT`
- [ ] Implement `resolveEndpoint()` returning `/definitions/activity/modify`
- [ ] Implement `defaultBody()` including `activity_field_id` + definition data
- [ ] Add PHPDoc with `@throws Throwable`
- [ ] Use `declare(strict_types=1);`

### Task 3b.2: Add modifyDefinition() to ActivityResource ⏳
- [ ] Open `src/Resources/ActivityResource.php`
- [ ] Import `ModifyActivityDefinition`
- [ ] Add `modifyDefinition()` method
- [ ] Accept `activityFieldId` and `definition` parameters
- [ ] Return `Response`
- [ ] Add PHPDoc comments
- [ ] Add `@throws Throwable`
- [ ] Follow alphabetical method ordering

### Task 3b.3: Create Request Tests ⏳
- [ ] Create `tests/Unit/Requests/Activity/ModifyActivityDefinitionTest.php`
  - [ ] Test: modifies activity definition
  - [ ] Test: modifies icon
  - [ ] Test: modifies display style
  - [ ] Test: modifies attributes
  - [ ] Test: modifies boolean settings

- [ ] Create test fixture
  - [ ] `activity/modify_definition.json`

### Task 3b.4: Create Resource Tests ⏳
- [ ] Update `tests/Unit/Resources/ActivityResourceTest.php`
  - [ ] Test: modifyDefinition() method works
  - [ ] Test: modifyDefinition() includes activity_field_id

### Task 3b.5: Verify Coverage ⏳
- [ ] Run tests for modify endpoint
- [ ] Verify 100% code coverage
- [ ] Verify 100% type coverage
- [ ] Fix any coverage gaps

**Phase 3b Deliverables**:
- ✅ 1 Request class
- ✅ 1 Resource method
- ✅ 7+ test cases
- ✅ 100% code and type coverage maintained

---

## Phase 3c: Delete Endpoint Implementation

**Goal**: Implement activity definition deletion with full test coverage

### Task 3c.1: Create DeleteActivityDefinition Request ⏳
- [ ] Create `src/Requests/Activity/DeleteActivityDefinition.php`
- [ ] Extend `Saloon\Http\Request`
- [ ] Implement `HasBody` interface with `HasJsonBody` trait
- [ ] Add constructor with `activityFieldId` parameter
- [ ] Set method to `DELETE`
- [ ] Implement `resolveEndpoint()` returning `/definitions/activity/delete`
- [ ] Implement `defaultBody()` returning `['activity_field_id' => $activityFieldId]`
- [ ] Add PHPDoc with `@throws Throwable`
- [ ] Use `declare(strict_types=1);`

### Task 3c.2: Add deleteDefinition() to ActivityResource ⏳
- [ ] Open `src/Resources/ActivityResource.php`
- [ ] Import `DeleteActivityDefinition`
- [ ] Add `deleteDefinition()` method
- [ ] Accept `activityFieldId` parameter
- [ ] Return `Response`
- [ ] Add PHPDoc comments
- [ ] Add `@throws Throwable`
- [ ] Follow alphabetical method ordering (after createDefinition, before modifyDefinition)

### Task 3c.3: Create Request Tests ⏳
- [ ] Create `tests/Unit/Requests/Activity/DeleteActivityDefinitionTest.php`
  - [ ] Test: deletes activity definition
  - [ ] Test: response contains archived_activity field

- [ ] Create test fixture
  - [ ] `activity/delete_definition.json`

### Task 3c.4: Create Resource Tests ⏳
- [ ] Update `tests/Unit/Resources/ActivityResourceTest.php`
  - [ ] Test: deleteDefinition() method works

### Task 3c.5: Verify Coverage ⏳
- [ ] Run tests for delete endpoint
- [ ] Verify 100% code coverage
- [ ] Verify 100% type coverage
- [ ] Fix any coverage gaps

**Phase 3c Deliverables**:
- ✅ 1 Request class
- ✅ 1 Resource method
- ✅ 3+ test cases
- ✅ 100% code and type coverage maintained

---

## Phase 4: Completion & Quality Assurance

**Goal**: Verify all quality standards met and complete documentation

### Task 4.1: Run Full Test Suite ⏳
- [ ] Run `composer test`
- [ ] Verify all tests pass (should be 210+ tests now)
- [ ] Verify code coverage exactly 100%
- [ ] Verify type coverage exactly 100%
- [ ] Fix any failing tests

### Task 4.2: Run Code Quality Checks ⏳
- [ ] Run `composer test:types` (PHPStan max level)
- [ ] Run `composer test:lint` (Pint formatting)
- [ ] Run `composer test:refactor` (Rector checks)
- [ ] Run `composer test:typos` (Peck typo checking)
- [ ] Fix any issues found
- [ ] Ensure all checks pass

### Task 4.3: Manual API Testing (Optional) ⏳
- [ ] Test create definition with real API (if API key available)
- [ ] Test modify definition with real API
- [ ] Test delete definition with real API
- [ ] Verify responses match expectations
- [ ] Update fixtures if needed

### Task 4.4: Update Documentation ⏳
- [ ] Update `CLAUDE.md` with:
  - [ ] Activity Definition endpoints section
  - [ ] All 3 endpoints documented
  - [ ] ActivityIcon enum usage
  - [ ] Field ID format notes
  - [ ] Display style options
  - [ ] Add to "Using Enums in Tests" section

### Task 4.5: Update Local API Documentation ⏳
- [ ] Verify `.ai/ortto/activity/definition-create.md` is accurate
- [ ] Verify `.ai/ortto/activity/definition-modify.md` is accurate
- [ ] Verify `.ai/ortto/activity/definition-delete.md` is accurate
- [ ] Add any missing details from implementation

### Task 4.6: Final Review ⏳
- [ ] Review all new code for consistency
- [ ] Verify all methods have PHPDoc comments
- [ ] Verify all classes use `declare(strict_types=1);`
- [ ] Verify alphabetical ordering in Resource methods
- [ ] Verify test coverage is comprehensive
- [ ] Mark all tasks complete

**Phase 4 Deliverables**:
- ✅ All tests passing (210+ tests total)
- ✅ 100% code coverage
- ✅ 100% type coverage
- ✅ All quality checks passing
- ✅ Documentation updated
- ✅ Feature complete and production-ready

---

## Key Metrics

### Code Metrics
- [ ] Code Coverage: Target 100%
- [ ] Type Coverage: Target 100%
- [ ] Test Count: Target 210+ tests (current + 45 new)
- [ ] Request Classes: 3 new (6 total)
- [ ] Data Classes: 3 new (6 total)
- [ ] Enums: 1 new (6 total)
- [ ] Resource Methods: 3 new (4 total in ActivityResource)

---

## Implementation Notes

### Code Standards
- Always use `declare(strict_types=1);`
- Follow PSR-12 coding standards
- Use named parameters in constructors
- Alphabetical ordering for imports and methods
- PHPDoc on all public methods

### Testing Strategy
- Use MockClient for all API tests
- Auto-record fixtures on first run
- Organize tests by component type
- Test newest features first in file
- Use descriptive test names in snake_case
- Always use enums instead of magic strings

### Data Class Patterns
- Implement `Arrayable` interface
- Use `toArray()` for conversion
- Handle enum-to-string conversion
- Convert property names to snake_case
- Only include non-null optional fields

### Quality Checklist
- [ ] PHPStan max level: 0 errors
- [ ] Pint: Code formatted
- [ ] Rector: All rules pass
- [ ] Peck: No typos
- [ ] Tests: 100% coverage

---

## Risk Mitigation

### Complex Nested Structures
**Risk**: Display style and attributes are nested objects
**Mitigation**: Use Data classes for type safety and validation

### Multiple Optional Fields
**Risk**: Many optional parameters could lead to errors
**Mitigation**: Comprehensive test coverage of all combinations

### API Field Naming
**Risk**: Inconsistent naming (camelCase vs snake_case)
**Mitigation**: toArray() methods handle conversion

---

## Success Criteria Checklist

### Functional ✅
- [ ] Create endpoint working
- [ ] Modify endpoint working
- [ ] Delete endpoint working
- [ ] All enums implemented
- [ ] All Data classes working
- [ ] Resource integration complete

### Quality ✅
- [ ] 100% code coverage
- [ ] 100% type coverage
- [ ] PHPStan passing
- [ ] Pint passing
- [ ] Rector passing
- [ ] Peck passing

### Documentation ✅
- [ ] CLAUDE.md updated
- [ ] API docs verified
- [ ] Usage examples added
- [ ] Edge cases documented

### Testing ✅
- [ ] 45+ new tests
- [ ] All fixtures created
- [ ] Edge cases covered
- [ ] Manual testing done (if API available)

---

**Task List Version**: 1.0
**Created**: 2025-11-05
**Status**: Ready to Begin
**Next Step**: Start Phase 3a, Task 3a.1 - Create ActivityIcon Enum
