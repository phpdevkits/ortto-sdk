# Activity API Implementation Task Checklist

## Documentation Tasks

### Activity Endpoints
- [x] Document `/v1/activities/create` endpoint
- [x] Document `/v1/definitions/activity/create` endpoint
- [x] Document `/v1/definitions/activity/modify` endpoint
- [x] Document `/v1/definitions/activity/delete` endpoint
- [x] Document `/v1/person/get/activities` endpoint (already exists)

## SDK Implementation Tasks

### Data Classes
- [ ] Create `src/Data/ActivityData.php`:
  - [ ] Implement `Arrayable` interface
  - [ ] Add properties: activityId, attributes, personId, fields, mergeBy
  - [ ] Add optional: created, key, location, mergeStrategy
  - [ ] Add `toArray()` method
  - [ ] Add factory support
- [ ] Create `src/Data/ActivityLocationData.php`:
  - [ ] Static factory: `fromIp(string $ip)`
  - [ ] Static factory: `fromCoordinates(float $lat, float $lng)`
  - [ ] Static factory: `fromAddress(array $address)`
  - [ ] Add `toArray()` method for each type

### Enums
- [ ] Create `src/Enums/ActivityDisplayType.php`:
  - [ ] text, large_text, email, phone, link
  - [ ] integer, decimal, currency
  - [ ] date, time, bool
  - [ ] single_select, multi_select, object
- [ ] Create `src/Enums/ActivityIconId.php`:
  - [ ] calendar-icon, email-icon, phone-icon, download-icon
  - [ ] cart-icon, chat-icon, form-icon, page-icon
  - [ ] search-icon, social-icon, video-icon, webinar-icon
  - [ ] custom-icon-1, custom-icon-2
- [ ] Create `src/Enums/ActivityDisplayStyleType.php`:
  - [ ] activity, activity_attribute, activity_template

### Request Classes
- [x] `src/Requests/Person/GetPersonActivities.php` (already exists)
- [ ] `src/Requests/Activity/CreateActivities.php`:
  - [ ] Extend Saloon `Request`
  - [ ] Define POST method
  - [ ] Set endpoint path `/v1/activities/create`
  - [ ] Accept activities (array), async (bool)
  - [ ] Handle person_id OR (fields + merge_by) approaches
  - [ ] Support location data (3 formats)
  - [ ] Support backdating with created timestamp
- [ ] `src/Requests/Activity/CreateActivityDefinition.php`:
  - [ ] Extend Saloon `Request`
  - [ ] Define POST method
  - [ ] Set endpoint path `/v1/definitions/activity/create`
  - [ ] Accept activityFieldId, name, attributes, etc.
  - [ ] Support icons, display styles, conversion tracking
- [ ] `src/Requests/Activity/ModifyActivityDefinition.php`:
  - [ ] Extend Saloon `Request`
  - [ ] Define PUT method
  - [ ] Set endpoint path `/v1/definitions/activity/modify`
  - [ ] Support partial updates
- [ ] `src/Requests/Activity/DeleteActivityDefinition.php`:
  - [ ] Extend Saloon `Request`
  - [ ] Define DELETE method
  - [ ] Set endpoint path `/v1/definitions/activity/delete`
  - [ ] Accept activityFieldId parameter

### Resource Class
- [ ] Create `src/Resources/ActivityResource.php`:
  - [ ] Add `create()` method for CreateActivities
  - [ ] Add `createDefinition()` method
  - [ ] Add `modifyDefinition()` method
  - [ ] Add `deleteDefinition()` method

### Connector Integration
- [ ] Update `src/Ortto.php`:
  - [ ] Add `activity()` method accessor
  - [ ] Configure ActivityResource

### Testing

#### Test Fixtures
- [ ] Create fixtures in `tests/Fixtures/Saloon/activity/`:
  - [ ] `create_activities_with_person_id.json`
  - [ ] `create_activities_with_merge.json`
  - [ ] `create_activities_backdate.json`
  - [ ] `create_activities_location_ip.json`
  - [ ] `create_activities_location_coords.json`
  - [ ] `create_activities_location_address.json`
  - [ ] `create_activities_async.json`
  - [ ] `create_activities_bulk.json`
  - [ ] `create_activities_with_attributes.json`
  - [ ] `create_definition_basic.json`
  - [ ] `create_definition_with_icon.json`
  - [ ] `modify_definition.json`
  - [ ] `delete_definition.json`

#### Unit Tests
- [ ] Create `tests/Unit/Data/`:
  - [ ] `ActivityDataTest.php` - Test data class
  - [ ] `ActivityLocationDataTest.php` - Test location factories
- [ ] Create `tests/Unit/Enums/`:
  - [ ] `ActivityDisplayTypeTest.php` - Test enum values
  - [ ] `ActivityIconIdTest.php` - Test enum values
  - [ ] `ActivityDisplayStyleTypeTest.php` - Test enum values
- [ ] Create `tests/Unit/Requests/Activity/`:
  - [ ] `CreateActivitiesTest.php`:
    - [ ] Test with person_id
    - [ ] Test with fields + merge_by
    - [ ] Test backdating
    - [ ] Test location data (all 3 formats)
    - [ ] Test async mode
    - [ ] Test bulk activities
    - [ ] Test with activity attributes
  - [ ] `CreateActivityDefinitionTest.php`:
    - [ ] Test basic definition creation
    - [ ] Test with custom attributes
    - [ ] Test with icon configuration
    - [ ] Test with display styles
  - [ ] `ModifyActivityDefinitionTest.php`:
    - [ ] Test partial updates
    - [ ] Test icon changes
    - [ ] Test attribute modifications
  - [ ] `DeleteActivityDefinitionTest.php`:
    - [ ] Test definition deletion
- [ ] Create `tests/Unit/Resources/`:
  - [ ] `ActivityResourceTest.php`:
    - [ ] Test create() method
    - [ ] Test createDefinition() method
    - [ ] Test modifyDefinition() method
    - [ ] Test deleteDefinition() method

#### Coverage Requirements
- [ ] Ensure 100% code coverage for all new classes
- [ ] Ensure 100% type coverage
- [ ] All tests passing

### Code Quality
- [ ] Pass PHPStan analysis (max level)
- [ ] Pass Rector refactoring checks
- [ ] Pass Laravel Pint formatting
- [ ] Fix any typos with Peck
- [ ] Validate no dead code

## Documentation Update Tasks

### API Documentation
- [ ] Create `README.md` in `src/Requests/Activity/`:
  - [ ] Add usage examples for creating activities
  - [ ] Document activity field ID formats
  - [ ] Include location data examples
  - [ ] Show backdating examples
- [ ] Update main README (if exists):
  - [ ] Add Activity API support status
  - [ ] Include code examples

### Internal Documentation
- [ ] Update `CLAUDE.md` if needed:
  - [ ] Add activity entity information
  - [ ] Document activity field formats
  - [ ] Note activity-person relationships

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
- [ ] Create PR with Activity API support

## Current Progress

**Completed Tasks: 5**

### Documentation (Complete)
- ✅ Documented all 4 activity endpoints
- ✅ Documentation exists in `.ai/ortto/activity/`

### SDK Implementation (Partial)
- ✅ GetPersonActivities already implemented
- ✅ ActivityTimeframe enum exists
- ❌ CreateActivities - NOT IMPLEMENTED
- ❌ Activity Definitions - NOT IMPLEMENTED
- ❌ ActivityResource - NOT IMPLEMENTED
- ❌ Data classes - NOT IMPLEMENTED
- ❌ Additional enums - NOT IMPLEMENTED

**Next Immediate Tasks:**
1. Create ActivityDisplayType enum
2. Create ActivityData and ActivityLocationData classes
3. Implement CreateActivities request class
4. Create ActivityResource with create() method
5. Add comprehensive tests

## Implementation Phases

### Phase 1: Core Activity Creation (HIGH PRIORITY)
Focus on the most commonly used endpoint for tracking customer events:
1. CreateActivities endpoint
2. Required enums (ActivityDisplayType)
3. Data classes for type safety
4. ActivityResource with create() method
5. Comprehensive tests

### Phase 2: Activity Definitions (MEDIUM PRIORITY)
Admin/setup endpoints for managing activity schemas:
1. CreateActivityDefinition endpoint
2. ModifyActivityDefinition endpoint
3. DeleteActivityDefinition endpoint
4. Additional enums (ActivityIconId, ActivityDisplayStyleType)
5. Tests for all definition endpoints

### Phase 3: Polish & Documentation
1. Update all documentation
2. Add usage examples
3. Ensure 100% coverage
4. Final QA pass

## Notes

- GetPersonActivities already exists and is fully functional
- Activity field format: `act::o`, `act::c`, `act::s` (built-in), `act:cm:{name}` (custom)
- Activity attributes use field formats: `str:cm:{name}`, `int::v`, `int:cm:{name}`, etc.
- Can reuse existing MergeStrategy and FindStrategy enums
- Rate limit on GetPersonActivities: 1 request per second
- Activities support backdating up to 90 days
- Max 100 activities per CreateActivities request
- Location data has 3 formats: IP-based, coordinates, postal address
- Activity definitions are archived in 2-step process (API + UI completion)
- Follow established SDK patterns from Person and Accounts resources
