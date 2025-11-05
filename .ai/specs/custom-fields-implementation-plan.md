# Ortto Custom Fields API - Implementation Plan

## Executive Summary

This document outlines the comprehensive implementation plan for Ortto's Custom Fields API. The API provides 6 endpoints for managing custom fields for both Person (contacts) and Accounts (organizations). This is a greenfield feature with no existing implementation in the SDK.

**Status**: Planning Phase
**Estimated Effort**: 3-5 days
**Complexity**: Medium-High
**Total Tasks**: 38 tasks across 5 phases

---

## 1. API Endpoints Overview

### Available Endpoints

| Endpoint | HTTP Method | Path | Purpose | Entity |
|----------|-------------|------|---------|--------|
| Create Custom Field | POST | `/v1/person/custom-field/create` | Create custom contact field | Person |
| Get Custom Fields | POST | `/v1/person/custom-field/get` | Retrieve all contact fields | Person |
| Update Custom Field | PUT | `/v1/person/custom-field/update` | Update multi-select person field options | Person |
| Create Custom Field | POST | `/v1/accounts/custom-field/create` | Create custom account field | Account |
| Get Custom Fields | POST | `/v1/accounts/custom-field/get` | Retrieve all account fields | Account |
| Update Custom Field | PUT | `/v1/accounts/custom-field/update` | Update multi-select account field options | Account |

**Regional Endpoints**: Already handled by existing `OrttoConnector`
- Default (AP3): `https://api.ap3api.com/v1`
- Australia: `https://api.au.ap3api.com/v1`
- Europe: `https://api.eu.ap3api.com/v1`

---

## 2. Field Types Supported

Custom fields support 14 different field types:

| Type | API Value | Description | Person | Account |
|------|-----------|-------------|--------|---------|
| Text | `text` | Up to 500 characters | ✅ | ✅ |
| Long text | `large_text` | 500+ characters | ✅ | ✅ |
| Number | `integer` | Whole numbers | ✅ | ✅ |
| Decimal | `decimal` | Floating-point numbers | ✅ | ✅ |
| Currency | `currency` | Decimal with default currency symbol | ✅ | ✅ |
| Multi-currency | `price` | Decimal with ISO currency codes | ✅ | ✅ |
| Date | `date` | Day, month, year | ✅ | ✅ |
| Date-time | `time` | Timestamp | ✅ | ✅ |
| Boolean | `bool` | True/false value | ✅ | ✅ |
| Phone | `phone` | Local/international format | ✅ | ✅ |
| Single select | `single_select` | Single choice from defined options | ✅ | ✅ |
| Multi select | `multi_select` | Multiple choices from defined options | ✅ | ✅ |
| Link | `link` | URL reference | ✅ | ✅ |
| Object | `object` | JSON object (max 15,000 bytes) | ✅ | ✅ |

**Note**: `aggregate` type (calculated from activities) is NOT supported via API - Person only, UI creation only.

---

## 3. Custom Field Naming Convention

### Field ID Format

Custom fields follow the pattern: `{type}:cm:{field-name}`

**Examples**:
- `str:cm:job-title` - Text field
- `int:cm:employee-count` - Integer field
- `bol:cm:is-premium` - Boolean field
- `sst:cm:industry` - Single select field
- `mst:cm:interests` - Multi select field

### Type Prefixes

| Prefix | Type |
|--------|------|
| `str:cm:` | String/Text |
| `int:cm:` | Integer |
| `dec:cm:` | Decimal |
| `cur:cm:` | Currency |
| `pri:cm:` | Price (multi-currency) |
| `bol:cm:` | Boolean |
| `dtz:cm:` | Date/DateTime |
| `phn:cm:` | Phone |
| `geo:cm:` | Geographic |
| `sst:cm:` | Single select |
| `mst:cm:` | Multi select |
| `lnk:cm:` | Link |
| `obj:cm:` | Object |

### Name Conversion

- API auto-converts field names to lowercase kebab-case
- Example: "Job Title" → `job-title` → `str:cm:job-title`
- Spaces become hyphens, uppercase becomes lowercase

---

## 4. API Specifications

### 4.1 Create Custom Field (POST)

**Endpoints**:
- Person: `POST /v1/person/custom-field/create`
- Account: `POST /v1/accounts/custom-field/create`

**Request Body**:
```json
{
  "type": "text",              // Required: field type
  "name": "Job Title",         // Required: field name
  "values": ["opt1", "opt2"],  // Required for select types only
  "track_changes": true        // Optional: enable change tracking (Person only)
}
```

**Response**:
```json
{
  "name": "Job Title",
  "field_id": "str:cm:job-title",
  "display_type": "text",
  "values": [],
  "track_changes": false
}
```

**Validation**:
- Max 100 custom fields per Ortto account
- Duplicate field names not permitted
- `values` required only for `single_select` and `multi_select` types
- `track_changes` not supported for multi-select fields
- `track_changes` only available for Person fields

### 4.2 Get Custom Fields (POST)

**Endpoints**:
- Person: `POST /v1/person/custom-field/get`
- Account: `POST /v1/accounts/custom-field/get`

**Request**: No body required

**Response (Person)**:
```json
{
  "fields": [
    {
      "field": {
        "id": "str:cm:job-title",
        "name": "Job Title",
        "display_type": "text",
        "liquid_name": "{{person.cm.job_title}}",
        "dic_items": []
      },
      "tracked_value": false
    }
  ]
}
```

**Response (Account)**:
```json
{
  "fields": [
    {
      "id": "str:cm:industry",
      "name": "Industry",
      "display_type": "text",
      "liquid_name": "{{organization.cm.industry}}"
    }
  ]
}
```

**Notes**:
- Person response includes `tracked_value` (whether field updates generate activities)
- Account response does NOT include tracking metadata
- Returns all custom fields (no pagination)

### 4.3 Update Custom Field (PUT)

**Endpoints**:
- Person: `PUT /v1/person/custom-field/update`
- Account: `PUT /v1/accounts/custom-field/update`

**Request Body**:
```json
{
  "field_id": "sst:cm:industry",
  "replace_values": ["Tech", "Finance"],  // Optional: replaces all values
  "add_values": ["Healthcare"],           // Optional: appends to existing
  "remove_values": ["Retail"],            // Optional: removes specified
  "track_changes": true                   // Optional: toggle tracking (Person only)
}
```

**Response**:
```json
{
  "field_id": "sst:cm:industry",
  "values": ["Tech", "Finance", "Healthcare"],
  "track_changes": true
}
```

**Update Logic**:
- Only `single_select` and `multi_select` field types can have values updated
- Processing priority: `replace_values` → `add_values` → `remove_values`
- Only one value modification parameter should be used per request
- Multi-select fields cannot enable change tracking

---

## 5. SDK Structure Design

### 5.1 Directory Structure

```
src/
├── Enums/
│   └── CustomFieldType.php           // NEW: All 14 field types
├── Data/
│   ├── CustomFieldData.php           // NEW: Base custom field data
│   ├── PersonCustomFieldData.php     // NEW: Person-specific (with tracked_value)
│   ├── AccountCustomFieldData.php    // NEW: Account-specific
│   └── CustomFieldResponseData.php   // NEW: For create responses
├── Requests/
│   ├── Person/
│   │   └── CustomField/              // NEW: Person custom field requests
│   │       ├── CreatePersonCustomField.php
│   │       ├── GetPersonCustomFields.php
│   │       └── UpdatePersonCustomField.php
│   └── Accounts/
│       └── CustomField/              // NEW: Account custom field requests
│           ├── CreateAccountCustomField.php
│           ├── GetAccountCustomFields.php
│           └── UpdateAccountCustomField.php
└── Resources/
    ├── PersonResource.php            // UPDATE: Add custom field methods
    └── AccountsResource.php          // UPDATE: Add custom field methods
```

### 5.2 CustomFieldType Enum

```php
<?php

namespace PhpDevKits\Ortto\Enums;

enum CustomFieldType: string
{
    case Text = 'text';
    case LargeText = 'large_text';
    case Integer = 'integer';
    case Decimal = 'decimal';
    case Currency = 'currency';
    case Price = 'price';
    case Date = 'date';
    case Time = 'time';
    case Bool = 'bool';
    case Phone = 'phone';
    case SingleSelect = 'single_select';
    case MultiSelect = 'multi_select';
    case Link = 'link';
    case Object = 'object';
}
```

### 5.3 Data Class Example

```php
<?php

namespace PhpDevKits\Ortto\Data;

use Illuminate\Contracts\Support\Arrayable;

class PersonCustomFieldData implements Arrayable
{
    public function __construct(
        public string $id,
        public string $name,
        public string $displayType,
        public string $liquidName,
        public array $dicItems = [],
        public bool $trackedValue = false,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'display_type' => $this->displayType,
            'liquid_name' => $this->liquidName,
            'dic_items' => $this->dicItems,
            'tracked_value' => $this->trackedValue,
        ];
    }
}
```

### 5.4 Request Class Example

```php
<?php

namespace PhpDevKits\Ortto\Requests\Person\CustomField;

use PhpDevKits\Ortto\Enums\CustomFieldType;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreatePersonCustomField extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public string|CustomFieldType $type,
        public string $name,
        public ?array $values = null,
        public ?bool $trackChanges = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/person/custom-field/create';
    }

    protected function defaultBody(): array
    {
        $body = [
            'type' => $this->type instanceof CustomFieldType
                ? $this->type->value
                : $this->type,
            'name' => $this->name,
        ];

        if ($this->values !== null) {
            $body['values'] = $this->values;
        }

        if ($this->trackChanges !== null) {
            $body['track_changes'] = $this->trackChanges;
        }

        return $body;
    }
}
```

### 5.5 Resource Methods

**PersonResource additions**:

```php
/**
 * Create a custom field for person contacts
 *
 * @param  string|CustomFieldType  $type
 * @param  string  $name
 * @param  string[]|null  $values
 * @param  bool|null  $trackChanges
 * @throws Throwable
 */
public function createCustomField(
    string|CustomFieldType $type,
    string $name,
    ?array $values = null,
    ?bool $trackChanges = null
): Response {
    return $this->connector->send(
        request: new CreatePersonCustomField(
            type: $type,
            name: $name,
            values: $values,
            trackChanges: $trackChanges,
        ),
    );
}

/**
 * Get all custom fields for person contacts
 *
 * @throws Throwable
 */
public function getCustomFields(): Response {
    return $this->connector->send(
        request: new GetPersonCustomFields(),
    );
}

/**
 * Update custom field options or change tracking
 *
 * @param  string  $fieldId
 * @param  string[]|null  $replaceValues
 * @param  string[]|null  $addValues
 * @param  string[]|null  $removeValues
 * @param  bool|null  $trackChanges
 * @throws Throwable
 */
public function updateCustomField(
    string $fieldId,
    ?array $replaceValues = null,
    ?array $addValues = null,
    ?array $removeValues = null,
    ?bool $trackChanges = null
): Response {
    return $this->connector->send(
        request: new UpdatePersonCustomField(
            fieldId: $fieldId,
            replaceValues: $replaceValues,
            addValues: $addValues,
            removeValues: $removeValues,
            trackChanges: $trackChanges,
        ),
    );
}
```

---

## 6. Implementation Phases

### Phase 1: Foundation (Enums & Data Classes)

**Goals**:
- Create type-safe enums for field types
- Create Data classes with factory support
- Create API documentation files

**Deliverables**:
- `CustomFieldType` enum with 14 field types
- `CustomFieldData`, `PersonCustomFieldData`, `AccountCustomFieldData`
- `CustomFieldResponseData` for create endpoint responses
- Factories for all Data classes
- 6 API documentation markdown files in `.ai/ortto/`

**Testing**:
- Enum test with 100% coverage
- Data class tests with 100% coverage
- Factory tests

### Phase 2: Person Custom Field Requests

**Goals**:
- Implement all 3 Person custom field endpoints
- Add methods to PersonResource
- Complete test coverage

**Deliverables**:
- `CreatePersonCustomField` request
- `GetPersonCustomFields` request
- `UpdatePersonCustomField` request
- PersonResource methods: `createCustomField()`, `getCustomFields()`, `updateCustomField()`
- 18+ test cases with 100% coverage
- Test fixtures for all scenarios

**Testing**:
- Create tests (text field, select fields, with tracking, validations)
- Get tests (retrieve all, verify tracked_value, verify dic_items)
- Update tests (replace, add, remove, tracking, validations)
- Resource method tests

### Phase 3: Account Custom Field Requests

**Goals**:
- Implement all 3 Account custom field endpoints
- Add methods to AccountsResource
- Complete test coverage

**Deliverables**:
- `CreateAccountCustomField` request
- `GetAccountCustomFields` request
- `UpdateAccountCustomField` request
- AccountsResource methods: `createCustomField()`, `getCustomFields()`, `updateCustomField()`
- 12+ test cases with 100% coverage
- Test fixtures for all scenarios

**Testing**:
- Create tests (text field, select fields, no tracking support)
- Get tests (retrieve all, no tracked_value)
- Update tests (replace, add, remove)
- Resource method tests

### Phase 4: Quality Assurance

**Goals**:
- Verify 100% code coverage
- Verify 100% type coverage
- Pass all quality checks

**Deliverables**:
- All tests passing
- PHPStan max level passing
- Pint formatting passing
- Rector checks passing
- Peck typo checks passing

**Testing**:
- Run full test suite
- Manual API testing with real Ortto account
- Edge case validation
- Error handling verification

### Phase 5: Integration & Documentation

**Goals**:
- Update SDK documentation
- Create usage examples
- Document limitations and best practices

**Deliverables**:
- README.md custom fields section
- CLAUDE.md updated with implementation notes
- Usage examples in `examples/` directory
- API documentation in `.ai/ortto/`

---

## 7. Special Considerations

### 7.1 Field Limit Constraint

**Issue**: Max 100 custom fields per Ortto account

**Impact**:
- Test fields accumulate over time
- Could hit limit during development/testing
- No delete endpoint available

**Mitigation**:
- Use dedicated test Ortto account
- Track created test fields
- Manually delete via UI when limit approached
- Consider field naming convention for test fields (e.g., `test-*`)

### 7.2 Change Tracking Rules

**Person Fields**:
- Change tracking available for most field types
- NOT available for multi-select fields
- Generates custom activities when field value changes

**Account Fields**:
- Change tracking NOT supported at all
- `track_changes` parameter should not be accepted

**Implementation**:
- Validate tracking only for Person endpoints
- Document this difference clearly
- Test that Account methods reject `track_changes`

### 7.3 Field Name Conversion

**Behavior**:
- API automatically converts field names to lowercase kebab-case
- "Job Title" → `job-title`
- "Employee Count" → `employee-count`

**SDK Approach**:
- Accept user input as-is
- Document the conversion behavior
- Do NOT pre-convert in SDK (let API handle it)
- Return actual field ID from API in response

### 7.4 Select Field Values

**Requirements**:
- `values` required for `single_select` and `multi_select` types
- `values` must be array of strings
- Values can be modified after creation via UPDATE endpoint

**Processing Priority** (Update endpoint):
1. `replace_values` - If present, replaces ALL existing values
2. `add_values` - If present (and no replace), appends new values
3. `remove_values` - If present (and no replace/add), removes specified values

**Implementation**:
- Validate values required for select types in Create
- Allow only one value modification type per Update request
- Test all three modification scenarios

### 7.5 Testing Strategy

**Fixture Auto-Recording**:
- MockClient automatically records real API responses
- Stored in `tests/Fixtures/Saloon/`
- Subsequent runs use recorded fixtures

**Test Organization**:
- Person custom field tests: `tests/Unit/Requests/Person/CustomField/`
- Account custom field tests: `tests/Unit/Requests/Accounts/CustomField/`
- Resource tests: `tests/Unit/Resources/`
- Data class tests: `tests/Unit/Data/`
- Enum tests: `tests/Unit/Enums/`

**Coverage Requirements**:
- Code coverage: Exactly 100%
- Type coverage: Exactly 100%
- PEST framework

---

## 8. Risk Assessment

### Low Risk ✅
- API endpoints well documented with clear examples
- Established pattern from existing Request implementations
- No complex dependencies on other SDK features
- Type-safe enums prevent invalid field types

### Medium Risk ⚠️
- 100 field limit could impact testing workflow
- Field name conversion might cause confusion
- Change tracking eligibility rules differ between entities
- Update endpoint priority logic needs careful testing

### High Risk ❌
- None identified

### Mitigation Strategies

**For Field Limit**:
- Dedicated test account
- Field naming convention for test fields
- Manual cleanup process documented
- Monitor field count during development

**For Name Conversion**:
- Clear documentation with examples
- Test cases showing conversion
- Warning in PHPDoc comments

**For Change Tracking**:
- Separate Person/Account implementations
- Validation in constructor
- Clear error messages
- Comprehensive test coverage

**For Update Logic**:
- Test all three value modification types
- Test priority (replace > add > remove)
- Validate only one parameter used
- Edge case testing (empty arrays, etc.)

---

## 9. Success Criteria

### Functional Requirements ✅
- [ ] All 6 API endpoints implemented
- [ ] Person custom field operations working
- [ ] Account custom field operations working
- [ ] Field type enum with all 14 types
- [ ] Data classes with factory support
- [ ] Resource methods properly integrated

### Quality Requirements ✅
- [ ] 100% code coverage maintained
- [ ] 100% type coverage maintained
- [ ] PHPStan max level passing
- [ ] Pint formatting passing
- [ ] Rector checks passing
- [ ] Peck typo checks passing

### Documentation Requirements ✅
- [ ] API documentation for all 6 endpoints
- [ ] README updated with custom fields section
- [ ] CLAUDE.md updated with implementation notes
- [ ] Usage examples created
- [ ] Edge cases documented
- [ ] Limitations clearly stated

### Testing Requirements ✅
- [ ] 30+ test cases across all endpoints
- [ ] Test fixtures for all scenarios
- [ ] Edge case coverage
- [ ] Error handling tests
- [ ] Resource method tests
- [ ] Data class tests
- [ ] Enum tests

---

## 10. Dependencies

### Prerequisites
- ✅ Saloon integration functional
- ✅ Test infrastructure in place
- ✅ MockClient fixture auto-recording configured
- ✅ PHPStan and Rector configured
- ✅ Existing Request/Resource patterns established

### External Dependencies
- ⚠️ Ortto API key with custom field permissions
- ⚠️ Test Ortto account (dedicated for testing)
- ⚠️ Access to Ortto documentation

### Internal Dependencies
- ✅ No blocking dependencies
- ✅ Custom fields are independent feature
- ℹ️ Created custom fields can be used in MergePeople/MergeAccounts

---

## 11. Timeline Estimate

| Phase | Tasks | Complexity | Estimated Time |
|-------|-------|-----------|----------------|
| Phase 1: Foundation | 9 | Medium | 1 day |
| Phase 2: Person | 9 | Medium-High | 1-2 days |
| Phase 3: Account | 9 | Medium | 1 day |
| Phase 4: QA | 6 | Low-Medium | 0.5 day |
| Phase 5: Docs | 5 | Low | 0.5 day |
| **Total** | **38** | **Medium-High** | **3-5 days** |

**Assumptions**:
- Experienced with Saloon and SDK patterns
- Ortto test account available
- No unexpected API issues
- Focus time available

---

## 12. Next Steps

1. **Review & Approve Plan** - Confirm approach and structure
2. **Set Up Test Account** - Dedicated Ortto instance for testing
3. **Create Task Tracking** - Detailed task list with checkboxes
4. **Start Phase 1** - Begin with foundation (enums, data classes)
5. **Iterate Through Phases** - Complete each phase with full testing
6. **Final Review** - Code review, documentation review, manual testing

---

## Appendix A: Example Usage

### Create Custom Text Field

```php
use PhpDevKits\Ortto\Facades\Ortto;
use PhpDevKits\Ortto\Enums\CustomFieldType;

$response = Ortto::person()->createCustomField(
    type: CustomFieldType::Text,
    name: 'Job Title'
);

// Response: field_id = "str:cm:job-title"
```

### Create Custom Select Field

```php
$response = Ortto::person()->createCustomField(
    type: CustomFieldType::SingleSelect,
    name: 'Industry',
    values: ['Technology', 'Finance', 'Healthcare'],
    trackChanges: true
);

// Response: field_id = "sst:cm:industry"
```

### Get All Custom Fields

```php
$response = Ortto::person()->getCustomFields();

$fields = $response->json('fields');
// Returns array of all custom fields with metadata
```

### Update Field Options

```php
$response = Ortto::person()->updateCustomField(
    fieldId: 'sst:cm:industry',
    addValues: ['Retail', 'Manufacturing']
);

// Adds new options to existing select field
```

---

## Appendix B: Field Type Reference

| CustomFieldType | API Value | Example Field ID | Use Case |
|-----------------|-----------|------------------|----------|
| `Text` | `text` | `str:cm:job-title` | Job titles, names |
| `LargeText` | `large_text` | `str:cm:bio` | Descriptions, notes |
| `Integer` | `integer` | `int:cm:employee-count` | Counts, quantities |
| `Decimal` | `decimal` | `dec:cm:rating` | Ratings, scores |
| `Currency` | `currency` | `cur:cm:budget` | Money values (single currency) |
| `Price` | `price` | `pri:cm:deal-value` | Money values (multi-currency) |
| `Date` | `date` | `dtz:cm:hire-date` | Dates only |
| `Time` | `time` | `dtz:cm:last-contact` | Date + time |
| `Bool` | `bool` | `bol:cm:is-vip` | True/false flags |
| `Phone` | `phone` | `phn:cm:office-phone` | Phone numbers |
| `SingleSelect` | `single_select` | `sst:cm:industry` | Pick one option |
| `MultiSelect` | `multi_select` | `mst:cm:interests` | Pick multiple options |
| `Link` | `link` | `lnk:cm:linkedin` | URLs |
| `Object` | `object` | `obj:cm:metadata` | JSON data |

---

**Document Version**: 1.0
**Last Updated**: 2025-11-05
**Status**: Ready for Implementation
