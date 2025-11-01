# Merge Strategy Test Plan

Comprehensive test plan for all merge_strategy options in the Ortto MergePeople endpoint.

## Test Organization

Use Pest's `describe()` blocks to group tests by strategy for better organization.

## Merge Strategy Overview

From the Ortto API documentation and `src/Enums/MergeStrategy.php`:

| Strategy Value | Enum Case | Behavior |
|----------------|-----------|----------|
| 1 | AppendOnly | Only adds new data (fields without a value). Existing values unchanged. |
| 2 | OverwriteExisting | Updates all specified fields, overwriting existing values. Default strategy. |
| 3 | Ignore | No updates to existing contacts, but creates new contacts. |

## Tests to Implement

### AppendOnly Strategy (3 tests)

#### Test 1: Append new fields without overwriting existing
**Test Name**: `append only adds new fields without overwriting existing`

**Fixture**: `person/merge_people_append_only_update.json`

**Scenario**:
- Contact exists with: `str::first: "John"`, `str::last: "Doe"`
- Request sends: `str::first: "Jonathan"`, `str::last: "Doe Jr."`, `str:cm:job-title: "Engineer"`

**Expected**:
- Status: `"merged"`
- `str::first` remains "John" (not overwritten)
- `str::last` remains "Doe" (not overwritten)
- `str:cm:job-title` added as "Engineer" (new field)

**Test Data**:
```php
Person::factory()->state([
    'str::email' => 'john.append@example.com',
    'str::first' => 'Jonathan',
    'str::last' => 'Doe Jr.',
    'str:cm:job-title' => 'Engineer',
])->make()
```

**Assertions**:
- Response status: 200
- `people[0].status` equals `'merged'`
- `people[0]` has `person_id`

---

#### Test 2: Append only creates new contact
**Test Name**: `append only creates new contact when not exists`

**Fixture**: `person/merge_people_append_only_create.json`

**Scenario**:
- Contact does NOT exist
- All fields are set during creation

**Expected**:
- Status: `"created"`

**Test Data**:
```php
Person::factory()->state([
    'str::email' => 'new.append@example.com',
    'str::first' => 'New',
    'str::last' => 'User',
    'str:cm:job-title' => 'Designer',
])->make()
```

**Assertions**:
- Response status: 200
- `people[0].status` equals `'created'`

---

#### Test 3: Append only with skip_non_existing
**Test Name**: `append only with skip non existing updates only existing contacts`

**Fixture**: `person/merge_people_append_only_skip_non_existing.json`

**Scenario**:
- Contact does NOT exist
- `skip_non_existing: true`

**Expected**:
- Contact is NOT created
- Request skipped

**Test Data**:
```php
Person::factory()->state([
    'str::email' => 'nonexistent.append@example.com',
    'str:cm:job-title' => 'Manager',
])->make()
```

**Additional Parameters**:
```php
skipNonExisting: true
```

**Assertions**:
- Response status: 200
- People array empty or has skip status

---

### OverwriteExisting Strategy (4 tests)

#### Test 1: Overwrite existing fields
**Test Name**: `overwrite existing updates all specified fields`

**Fixture**: `person/merge_people_overwrite_existing_update.json`

**Scenario**:
- Contact exists with: `str::first: "Jane"`, `str::last: "Smith"`, `str:cm:job-title: "Analyst"`
- Request sends: `str::first: "Janet"`, `str:cm:job-title: "Senior Analyst"`

**Expected**:
- Status: `"merged"`
- `str::first` changes to "Janet"
- `str::last` remains "Smith" (not specified in request)
- `str:cm:job-title` changes to "Senior Analyst"

**Test Data**:
```php
Person::factory()->state([
    'str::email' => 'jane.overwrite@example.com',
    'str::first' => 'Janet',
    'str:cm:job-title' => 'Senior Analyst',
])->make()
```

**Assertions**:
- Response status: 200
- `people[0].status` equals `'merged'`

---

#### Test 2: Clear field with null
**Test Name**: `overwrite existing clears field with null value`

**Fixture**: `person/merge_people_overwrite_null_field.json`

**Scenario**:
- Contact exists with: `str:cm:job-title: "Developer"`
- Request sends: `str:cm:job-title: null`

**Expected**:
- Status: `"merged"`
- `str:cm:job-title` cleared (set to null)

**Test Data**:
```php
Person::factory()->state([
    'str::email' => 'clear.field@example.com',
    'str:cm:job-title' => null,
])->make()
```

**Assertions**:
- Response status: 200
- `people[0].status` equals `'merged'`

---

#### Test 3: Overwrite creates new contact
**Test Name**: `overwrite existing creates new contact when not exists`

**Fixture**: `person/merge_people_overwrite_create.json`

**Scenario**:
- Contact does NOT exist
- This is the default behavior (already tested in existing tests)

**Expected**:
- Status: `"created"`

**Note**: This might be redundant with existing "person is created when email does not exist" test.

---

#### Test 4: Overwrite with skip_non_existing
**Test Name**: `overwrite existing with skip non existing updates only existing contacts`

**Fixture**: `person/merge_people_overwrite_skip_non_existing.json`

**Scenario**:
- Contact does NOT exist
- `skip_non_existing: true`

**Expected**:
- Contact NOT created
- Request skipped

**Test Data**:
```php
Person::factory()->state([
    'str::email' => 'nonexistent.overwrite@example.com',
    'str::first' => 'Should',
    'str::last' => 'NotCreate',
])->make()
```

**Additional Parameters**:
```php
skipNonExisting: true
```

---

### Ignore Strategy (3 tests)

#### Test 1: Ignore does not update existing
**Test Name**: `ignore strategy does not update existing contact`

**Fixture**: `person/merge_people_ignore_no_update.json`

**Scenario**:
- Contact exists with: `str::first: "Existing"`, `str::last: "User"`
- Request sends: `str::first: "Updated"`, `str::last: "Name"`, `str:cm:job-title: "New Job"`

**Expected**:
- Status: `"merged"` (contact found)
- All field values remain unchanged
- No new fields added

**Test Data**:
```php
Person::factory()->state([
    'str::email' => 'existing.ignore@example.com',
    'str::first' => 'Updated',
    'str::last' => 'Name',
    'str:cm:job-title' => 'New Job',
])->make()
```

**Assertions**:
- Response status: 200
- `people[0].status` equals `'merged'`

---

#### Test 2: Ignore creates new contact
**Test Name**: `ignore strategy creates new contact when not exists`

**Fixture**: `person/merge_people_ignore_create.json`

**Scenario**:
- Contact does NOT exist
- Ignore strategy only affects updates

**Expected**:
- Status: `"created"`
- Contact created normally

**Test Data**:
```php
Person::factory()->state([
    'str::email' => 'new.ignore@example.com',
    'str::first' => 'New',
    'str::last' => 'Contact',
    'str:cm:job-title' => 'Tester',
])->make()
```

**Assertions**:
- Response status: 200
- `people[0].status` equals `'created'`

---

#### Test 3: Ignore with skip_non_existing (read-only mode)
**Test Name**: `ignore strategy with skip non existing enforces read only mode`

**Fixture**: `person/merge_people_ignore_skip_non_existing.json`

**Scenario**:
- Contact does NOT exist
- `ignore` strategy prevents updates + `skip_non_existing: true` prevents creates
- This combination = read-only mode (no creates, no updates)

**Expected**:
- No contact created
- No updates performed

**Test Data**:
```php
Person::factory()->state([
    'str::email' => 'readonly.ignore@example.com',
    'str::first' => 'Read',
    'str::last' => 'Only',
])->make()
```

**Additional Parameters**:
```php
skipNonExisting: true
```

**Assertions**:
- Response status: 200
- No contact created or updated

---

## Implementation Notes

### Verification Limitations

**Important**: The Ortto API `/v1/person/merge` endpoint only returns `status` and `person_id` in responses, NOT the full updated contact data.

Therefore:
- ✅ We CAN verify that merge happened (`status: "merged"` or `"created"`)
- ❌ We CANNOT directly assert which specific fields were changed/appended/ignored
- ✅ Fixtures document expected behavior
- ✅ To verify actual field changes, use the GetPeople request (once implemented) in integration tests

### Test Organization Structure

```php
describe('AppendOnly merge strategy', function() {
    test('adds new fields without overwriting existing', ...);
    test('creates new contact when not exists', ...);
    test('with skip non existing updates only existing contacts', ...);
});

describe('OverwriteExisting merge strategy', function() {
    test('updates all specified fields', ...);
    test('clears field with null value', ...);
    test('creates new contact when not exists', ...);
    test('with skip non existing updates only existing contacts', ...);
});

describe('Ignore merge strategy', function() {
    test('does not update existing contact', ...);
    test('creates new contact when not exists', ...);
    test('with skip non existing enforces read only mode', ...);
});
```

### Fixture Capture Process

For each test:
1. Create test with MockClient pointing to fixture
2. Run test (will hit real API and auto-create fixture)
3. Verify fixture contains expected status
4. Re-run test to ensure it passes with mocked fixture

### Total Tests Summary

- **Current**: 15 tests passing
- **New**: 10 merge strategy tests
- **Total**: 25 tests
- **Fixtures**: 10 new JSON fixtures needed

### Documentation Reference

All behavior documented in `.ai/ortto/person/merge.md` under "Merge strategy" and "Key combinations to achieve different merge strategies" sections.