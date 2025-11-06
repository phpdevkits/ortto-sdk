# Tag API Implementation Tasks

## Phase 1: Documentation & Planning ✅
- [x] Research endpoint documentation
- [x] Create implementation plan
- [x] Create task list

## Phase 2: Core Implementation

### Enums
- [ ] Create `src/Enums/TagSource.php`
  - [ ] Define 4 values: Csv, Api, Manual, Zapier
  - [ ] Add PHPDoc with value descriptions
- [ ] Create `src/Enums/TagType.php`
  - [ ] Define 2 values: Person (""), Account ("account")
  - [ ] Add PHPDoc explaining empty string convention
- [ ] Create `tests/Unit/Enums/TagSourceTest.php`
  - [ ] Test all 4 enum values
- [ ] Create `tests/Unit/Enums/TagTypeTest.php`
  - [ ] Test both enum values
  - [ ] Test empty string for Person type

### Data Classes
- [ ] Create `src/Data/TagData.php`
  - [ ] Add all 13 properties from API response
  - [ ] Implement Arrayable interface
  - [ ] Use TagSource and TagType enums
  - [ ] Add toArray() method
  - [ ] Add proper PHPDoc annotations
- [ ] Create `tests/Factories/TagDataFactory.php`
  - [ ] Extend BaseFactory
  - [ ] Generate realistic test data
  - [ ] Use Faker for dynamic values
  - [ ] Support state() customization
- [ ] Create `tests/Unit/Data/TagDataTest.php`
  - [ ] Test toArray() transformation
  - [ ] Test with TagSource enum (use enum in test)
  - [ ] Test with TagType enum (use enum in test)
  - [ ] Test nullable fields
  - [ ] Test integer ID handling

### Request Class
- [ ] Create `src/Requests/Tag/GetTags.php`
  - [ ] POST method to `/tags/get`
  - [ ] Constructor with optional `q` parameter
  - [ ] Implement HasBody interface
  - [ ] Use HasJsonBody trait
  - [ ] defaultBody() method (conditional)
  - [ ] Add PHPDoc annotations
- [ ] Create `tests/Unit/Requests/Tag/GetTagsTest.php`
  - [ ] Test get all tags (empty body)
  - [ ] Test get with search parameter
  - [ ] Use MockClient with fixtures
  - [ ] **MANDATORY: Use TagSource/TagType enums in assertions**
  - [ ] Verify request body structure

### Resource Class
- [ ] Create `src/Resources/TagResource.php`
  - [ ] Extend BaseResource
  - [ ] Implement `get(?string $q = null): Response`
  - [ ] Send GetTags request via connector
  - [ ] Add PHPDoc annotations
- [ ] Create `tests/Unit/Resources/TagResourceTest.php`
  - [ ] Test get() method without search
  - [ ] Test get() method with search
  - [ ] **MANDATORY: Use TagSource/TagType enums in test data**
  - [ ] Verify response structure

### Connector Integration
- [ ] Update `src/Ortto.php`
  - [ ] Add TagResource import
  - [ ] Add `tag(): TagResource` method
  - [ ] Support config override pattern
  - [ ] Follow activity()/person()/account() pattern

### Documentation
- [ ] Create `.ai/ortto/tag/get.md`
  - [ ] Save endpoint documentation
  - [ ] Include parameters table
  - [ ] Include response structure
  - [ ] Add usage examples

## Phase 3: Testing & Quality Assurance

### Test Fixtures
- [ ] Auto-record `tests/Fixtures/Saloon/tag/get_tags_all.json`
- [ ] Auto-record `tests/Fixtures/Saloon/tag/get_tags_with_search.json`

### Quality Checks
- [ ] Run `composer test:lint` - Pint formatting check
- [ ] Run `composer test:types` - PHPStan static analysis
- [ ] Run `composer test:type-coverage` - 100% type coverage
- [ ] Run `composer test:typos` - Peck typo checking
- [ ] Run `composer test:unit` - PEST tests with 100% coverage
- [ ] Run `composer test:refactor` - Rector refactoring check
- [ ] Run `composer refactor` - Apply Rector suggestions
- [ ] Run `composer test` - Full test suite

### Code Review Checklist
- [ ] All tests use enums (TagSource, TagType) instead of strings
- [ ] 100% code coverage achieved
- [ ] 100% type coverage achieved
- [ ] PHPStan max level passes with no errors
- [ ] No typos found by Peck
- [ ] All Rector rules applied
- [ ] Proper PHPDoc annotations on all methods
- [ ] Follows established SDK patterns
- [ ] No hardcoded strings in tests

## Phase 4: Finalization

### Git & Commit
- [ ] Review all changes with `git diff`
- [ ] Stage all files with `git add .`
- [ ] Commit with descriptive message (no Claude footer)
- [ ] Verify commit with `git log -1`

### Documentation Update
- [ ] Update task list with completion status
- [ ] Mark all items as complete

## File Checklist

### Source Files (7 files)
- [ ] `.ai/ortto/tag/get.md`
- [ ] `src/Enums/TagSource.php`
- [ ] `src/Enums/TagType.php`
- [ ] `src/Data/TagData.php`
- [ ] `src/Requests/Tag/GetTags.php`
- [ ] `src/Resources/TagResource.php`
- [ ] `src/Ortto.php` (updated)

### Test Files (6 files)
- [ ] `tests/Factories/TagDataFactory.php`
- [ ] `tests/Unit/Enums/TagSourceTest.php`
- [ ] `tests/Unit/Enums/TagTypeTest.php`
- [ ] `tests/Unit/Data/TagDataTest.php`
- [ ] `tests/Unit/Requests/Tag/GetTagsTest.php`
- [ ] `tests/Unit/Resources/TagResourceTest.php`

### Auto-Generated Files (2 files)
- [ ] `tests/Fixtures/Saloon/tag/get_tags_all.json`
- [ ] `tests/Fixtures/Saloon/tag/get_tags_with_search.json`

## Notes

### Critical Requirements
⚠️ **MANDATORY**: All tests MUST use TagSource and TagType enums instead of hardcoded strings
⚠️ **REQUIRED**: 100% code coverage and 100% type coverage
⚠️ **REQUIRED**: PHPStan max level compliance

### Implementation Order
1. Enums first (needed by Data classes and tests)
2. Data classes and factories
3. Request class
4. Resource class
5. Connector update
6. Comprehensive tests
7. Quality checks
8. Commit

### Testing Strategy
- Use Saloon MockClient for all tests
- Auto-record fixtures on first run
- Use enum values in all assertions
- Test both with and without search parameter
- Verify response structure matches API docs

### Success Criteria
✅ Can call `$ortto->tag()->get()` to retrieve all tags
✅ Can call `$ortto->tag()->get(q: 'search')` to filter tags
✅ All tests pass with 100% coverage
✅ Type-safe access via TagData class
✅ Enums used throughout for TagSource and TagType
