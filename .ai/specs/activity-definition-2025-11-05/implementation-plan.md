# Activity Definition Endpoints - Implementation Plan

**Feature**: Ortto Activity Definition API Integration
**Status**: Planning Phase
**Endpoints**: 3 endpoints (create, modify, delete)
**Estimated Effort**: 2-3 days
**Date**: 2025-11-05

---

## Executive Summary

This document outlines the implementation plan for Ortto's Activity Definition API endpoints. These endpoints allow programmatic creation, modification, and deletion of custom activity schemas in the Ortto CDP before tracking activity events.

**Current State**: Activity events endpoint (`/activities/create`) is fully implemented with 100% test coverage.
**Target State**: Add activity definition management endpoints to the existing `ActivityResource`.

---

## 1. API Endpoints Overview

### Available Endpoints

| Endpoint | HTTP Method | Path | Purpose |
|----------|-------------|------|---------|
| Create Definition | POST | `/v1/definitions/activity/create` | Create custom activity schema |
| Modify Definition | PUT | `/v1/definitions/activity/modify` | Update existing activity schema |
| Delete Definition | DELETE | `/v1/definitions/activity/delete` | Archive activity definition |

**Regional Endpoints**: Already handled by existing `OrttoConnector`
- Default (AP3): `https://api.ap3api.com/v1`
- Australia: `https://api.au.ap3api.com/v1`
- Europe: `https://api.eu.ap3api.com/v1`

---

## 2. Existing Implementation Analysis

### What Already Exists ✅

**ActivityResource** (`src/Resources/ActivityResource.php`):
- Has `create()` method for activity events
- Accessed via `Ortto::activity()`
- Follows established SDK patterns

**Supporting Classes**:
- `ActivityData` - For activity events (100% test coverage)
- `ActivityLocationData` - For location data (100% test coverage)
- `ActivityDisplayType` enum - 14 display types (perfect for reuse!)
- `ActivityTimeframe` enum - For time filtering
- `CreateActivities` request class

**Test Patterns**:
- MockClient with fixtures
- PEST framework
- 100% code and type coverage required
- Fixtures auto-recorded in `tests/Fixtures/Saloon/activity/`

---

## 3. New Components Required

### 3.1 Enums (1 new file)

**ActivityIcon.php** (`src/Enums/ActivityIcon.php`):
```php
enum ActivityIcon: string
{
    case Calendar = 'calendar-illustration-icon';
    case Caution = 'caution-illustration-icon';
    case Clicked = 'clicked-illustration-icon';
    case Coupon = 'coupon-illustration-icon';
    case Download = 'download-illustration-icon';
    case Email = 'email-illustration-icon';
    case Eye = 'eye-illustration-icon';
    case Flag = 'flag-activities-illustration-icon';
    case Happy = 'happy-illustration-icon';
    case Money = 'moneys-illustration-icon';
    case Page = 'page-illustration-icon';
    case Phone = 'phone-illustration-icon';
    case Reload = 'reload-illustration-icon';
    case Tag = 'tag-illustration-icon';
    case Time = 'time-illustration-icon';
}
```

**Total**: 15 supported icons

### 3.2 Data Classes (3 new files)

**ActivityAttributeDefinitionData** (`src/Data/ActivityAttributeDefinitionData.php`):
```php
class ActivityAttributeDefinitionData implements Arrayable
{
    public function __construct(
        public string $name,                            // Required
        public string|ActivityDisplayType $displayType, // Required
        public ?string $fieldId = null,                 // Optional
    ) {}
}
```

**Purpose**: Represents an attribute in an activity definition
**Field ID Options**: `null`, `""`, `"do-not-map"`, or CDP field ID

**ActivityDisplayStyleData** (`src/Data/ActivityDisplayStyleData.php`):
```php
class ActivityDisplayStyleData implements Arrayable
{
    public function __construct(
        public string $type,                    // Required
        public ?string $title = null,           // For template type
        public ?string $attributeName = null,   // For attribute type
        public ?string $attributeFieldId = null,
    ) {}
}
```

**Purpose**: Configures how activity displays in feeds
**Types**: `"activity"`, `"activity_attribute"`, `"activity_template"`

**ActivityDefinitionData** (`src/Data/ActivityDefinitionData.php`):
```php
class ActivityDefinitionData implements Arrayable
{
    public function __construct(
        public string $name,                                    // Required
        public string|ActivityIcon $iconId,                     // Required
        public ?bool $trackConversionValue = null,
        public ?bool $touch = null,
        public ?bool $filterable = null,
        public ?bool $visibleInFeeds = null,
        public array|ActivityDisplayStyleData|null $displayStyle = null,
        public ?array $attributes = null,  // Array of ActivityAttributeDefinitionData
    ) {}
}
```

**Purpose**: Main activity definition structure

### 3.3 Request Classes (3 new files)

**CreateActivityDefinition** (`src/Requests/Activity/CreateActivityDefinition.php`):
```php
class CreateActivityDefinition extends Request implements HasBody
{
    public function __construct(
        protected array|ActivityDefinitionData $definition,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/definitions/activity/create';
    }
}
```

**ModifyActivityDefinition** (`src/Requests/Activity/ModifyActivityDefinition.php`):
```php
class ModifyActivityDefinition extends Request implements HasBody
{
    public function __construct(
        protected string $activityFieldId,              // NEW - required
        protected array|ActivityDefinitionData $definition,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/definitions/activity/modify';
    }
}
```

**DeleteActivityDefinition** (`src/Requests/Activity/DeleteActivityDefinition.php`):
```php
class DeleteActivityDefinition extends Request implements HasBody
{
    public function __construct(
        protected string $activityFieldId,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/definitions/activity/delete';
    }
}
```

### 3.4 Resource Methods (extend ActivityResource)

**Add to ActivityResource**:
```php
public function createDefinition(array|ActivityDefinitionData $definition): Response
{
    return $this->connector->send(
        request: new CreateActivityDefinition(definition: $definition)
    );
}

public function modifyDefinition(
    string $activityFieldId,
    array|ActivityDefinitionData $definition
): Response {
    return $this->connector->send(
        request: new ModifyActivityDefinition(
            activityFieldId: $activityFieldId,
            definition: $definition
        )
    );
}

public function deleteDefinition(string $activityFieldId): Response
{
    return $this->connector->send(
        request: new DeleteActivityDefinition(activityFieldId: $activityFieldId)
    );
}
```

### 3.5 Tests (7 new files, ~45 tests)

**Test Files**:
```
tests/Unit/Enums/ActivityIconTest.php                           (16 tests)
tests/Unit/Data/ActivityAttributeDefinitionDataTest.php         (5 tests)
tests/Unit/Data/ActivityDisplayStyleDataTest.php                (4 tests)
tests/Unit/Data/ActivityDefinitionDataTest.php                  (8 tests)
tests/Unit/Requests/Activity/CreateActivityDefinitionTest.php   (8 tests)
tests/Unit/Requests/Activity/ModifyActivityDefinitionTest.php   (5 tests)
tests/Unit/Requests/Activity/DeleteActivityDefinitionTest.php   (2 tests)
tests/Unit/Resources/ActivityResourceTest.php                   (+3 tests)
```

**Fixtures**:
```
tests/Fixtures/Saloon/activity/create_definition_basic.json
tests/Fixtures/Saloon/activity/create_definition_full.json
tests/Fixtures/Saloon/activity/create_definition_with_template.json
tests/Fixtures/Saloon/activity/modify_definition.json
tests/Fixtures/Saloon/activity/delete_definition.json
```

---

## 4. API Specifications

### 4.1 Create Activity Definition

**Endpoint**: `POST /v1/definitions/activity/create`

**Request Body Example**:
```json
{
  "name": "product-purchase",
  "icon_id": "moneys-illustration-icon",
  "track_conversion_value": true,
  "touch": true,
  "filterable": true,
  "visible_in_feeds": true,
  "display_style": {
    "type": "activity_attribute",
    "attribute_name": "product-name"
  },
  "attributes": [
    {
      "name": "product-name",
      "display_type": "text"
    },
    {
      "name": "quantity",
      "display_type": "integer",
      "field_id": "do-not-map"
    }
  ]
}
```

**Response**:
```json
{
  "custom_activity": {
    "activity_field_id": "act:cm:product-purchase",
    "name": "Product Purchase",
    "state": "awaiting_implementation",
    "display_mode": {
      "type": "activity_attribute",
      "attribute_field_id": "str:cm:product-name"
    },
    "attributes": [
      {
        "name": "product-name",
        "field_id": "str:cm:product-name",
        "display_type": "text",
        "liquid_name": "activity.custom.product_purchase.product_name"
      }
    ],
    "icon_id": "moneys-illustration-icon",
    "track_conversion_value": true,
    "touch": true,
    "filterable": true,
    "visible_in_feeds": true,
    "created_at": "2025-11-05T...",
    "edited_at": "2025-11-05T..."
  }
}
```

**Validation Rules**:
- Name must be unique
- Icon must be from supported list
- Display type: "activity", "activity_attribute", or "activity_template"
- Attributes use `ActivityDisplayType` enum values

### 4.2 Modify Activity Definition

**Endpoint**: `PUT /v1/definitions/activity/modify`

**Request Body Example**:
```json
{
  "activity_field_id": "act:cm:product-purchase",
  "name": "product-purchase",
  "icon_id": "cart-illustration-icon",
  "track_conversion_value": true,
  "touch": true,
  "filterable": true,
  "visible_in_feeds": true,
  "display_style": {...},
  "attributes": [...]
}
```

**Differences from Create**:
- Requires `activity_field_id` in request body
- Cannot modify: activity_field_id, state (system-managed)
- Can modify: all other fields

### 4.3 Delete Activity Definition

**Endpoint**: `DELETE /v1/definitions/activity/delete`

**Request Body**:
```json
{
  "activity_field_id": "act:cm:product-purchase"
}
```

**Response**:
```json
{
  "archived_activity": "act:cm:product-purchase"
}
```

**Important**: Archives only, not full deletion. Full deletion requires UI action.

---

## 5. Display Style Configuration

### Option 1: Activity Only
```php
new ActivityDisplayStyleData(type: 'activity')
```
**Displays**: "Product Purchase"

### Option 2: Activity with Attribute
```php
new ActivityDisplayStyleData(
    type: 'activity_attribute',
    attributeName: 'product-name'
)
```
**Displays**: "Product Purchase: Premium Plan"

### Option 3: Custom Template
```php
new ActivityDisplayStyleData(
    type: 'activity_template',
    title: 'Purchased {{product-name}} (qty: {{quantity}})'
)
```
**Displays**: "Purchased Premium Plan (qty: 2)"

---

## 6. Implementation Phases

### Phase 3a: Create Endpoint (Day 1)

**Tasks**:
1. Create `ActivityIcon` enum with 15 cases
2. Create `ActivityAttributeDefinitionData` class
3. Create `ActivityDisplayStyleData` class
4. Create `ActivityDefinitionData` class
5. Create `CreateActivityDefinition` request class
6. Add `createDefinition()` to ActivityResource
7. Write comprehensive tests (25+ tests)

**Success Criteria**:
- All tests pass
- 100% code coverage
- 100% type coverage
- PHPStan max level passes

### Phase 3b: Modify Endpoint (Day 2)

**Tasks**:
1. Create `ModifyActivityDefinition` request class
2. Add `modifyDefinition()` to ActivityResource
3. Write tests (5+ tests)

**Success Criteria**:
- All tests pass
- Maintains 100% coverage

### Phase 3c: Delete Endpoint (Day 2)

**Tasks**:
1. Create `DeleteActivityDefinition` request class
2. Add `deleteDefinition()` to ActivityResource
3. Write tests (2+ tests)

**Success Criteria**:
- All tests pass
- Maintains 100% coverage

### Phase 4: Completion & Documentation (Day 3)

**Tasks**:
1. Run full test suite
2. Verify PHPStan max level passes
3. Verify all quality checks pass
4. Update CLAUDE.md
5. Update documentation

**Success Criteria**:
- `composer test` passes 100%
- No regressions
- Documentation complete

---

## 7. Usage Examples

### Create Activity Definition

```php
use PhpDevKits\Ortto\Facades\Ortto;
use PhpDevKits\Ortto\Data\ActivityDefinitionData;
use PhpDevKits\Ortto\Data\ActivityAttributeDefinitionData;
use PhpDevKits\Ortto\Data\ActivityDisplayStyleData;
use PhpDevKits\Ortto\Enums\ActivityIcon;
use PhpDevKits\Ortto\Enums\ActivityDisplayType;

// Using Data classes (recommended)
$definition = new ActivityDefinitionData(
    name: 'product-purchase',
    iconId: ActivityIcon::Money,
    trackConversionValue: true,
    touch: true,
    filterable: true,
    visibleInFeeds: true,
    displayStyle: new ActivityDisplayStyleData(
        type: 'activity_attribute',
        attributeName: 'product-name'
    ),
    attributes: [
        new ActivityAttributeDefinitionData(
            name: 'product-name',
            displayType: ActivityDisplayType::Text
        ),
        new ActivityAttributeDefinitionData(
            name: 'quantity',
            displayType: ActivityDisplayType::Integer,
            fieldId: 'do-not-map'
        ),
    ]
);

$response = Ortto::activity()->createDefinition($definition);

// Using plain arrays (also supported)
$response = Ortto::activity()->createDefinition([
    'name' => 'product-purchase',
    'icon_id' => 'moneys-illustration-icon',
    'track_conversion_value' => true,
    'touch' => true,
    'filterable' => true,
    'visible_in_feeds' => true,
    'display_style' => [
        'type' => 'activity_attribute',
        'attribute_name' => 'product-name'
    ],
    'attributes' => [
        ['name' => 'product-name', 'display_type' => 'text'],
        ['name' => 'quantity', 'display_type' => 'integer', 'field_id' => 'do-not-map'],
    ]
]);
```

### Modify Activity Definition

```php
$response = Ortto::activity()->modifyDefinition(
    activityFieldId: 'act:cm:product-purchase',
    definition: new ActivityDefinitionData(
        name: 'product-purchase',
        iconId: ActivityIcon::Cart,  // Changed icon
        trackConversionValue: true,
        touch: true,
        filterable: true,
        visibleInFeeds: true
    )
);
```

### Delete Activity Definition

```php
$response = Ortto::activity()->deleteDefinition('act:cm:product-purchase');
// Returns: ['archived_activity' => 'act:cm:product-purchase']
```

---

## 8. Quality Standards

### Code Coverage
- **Code coverage**: Exactly 100% required
- **Type coverage**: Exactly 100% required
- **Framework**: PEST

### Static Analysis
- **PHPStan**: Max level
- **Rector**: All rules passing
- **Pint**: Code style compliant

### Testing
- MockClient with fixture auto-recording
- Comprehensive edge case coverage
- Integration tests via Resource methods

---

## 9. Timeline Estimate

| Phase | Tasks | Time |
|-------|-------|------|
| Phase 3a: Create | Enum, 3 Data classes, Request, Tests | 1 day |
| Phase 3b: Modify | Request, Resource method, Tests | 0.5 day |
| Phase 3c: Delete | Request, Resource method, Tests | 0.5 day |
| Phase 4: QA | Testing, docs, quality checks | 0.5 day |
| **Total** | **All components** | **2-3 days** |

---

## 10. Success Criteria

### Functional ✅
- [ ] All 3 endpoints implemented
- [ ] Create definition working
- [ ] Modify definition working
- [ ] Delete definition working
- [ ] ActivityIcon enum complete
- [ ] Data classes with validation
- [ ] Resource methods integrated

### Quality ✅
- [ ] 100% code coverage
- [ ] 100% type coverage
- [ ] PHPStan max level passing
- [ ] All quality checks passing

### Testing ✅
- [ ] 45+ test cases
- [ ] Fixtures created
- [ ] Edge cases covered

---

## 11. Dependencies

### Prerequisites ✅
- Saloon integration functional
- Test infrastructure ready
- MockClient configured
- ActivityDisplayType enum exists (reusable!)

### External ⚠️
- Ortto API key needed
- Test account for fixtures

---

## 12. Next Steps

1. **Review & Approve Plan**
2. **Create Task Checklist**
3. **Begin Phase 3a Implementation**

---

**Document Version**: 1.0
**Created**: 2025-11-05
**Status**: Awaiting Approval
