# Audience Endpoints Implementation Spec

Implementation specification for Ortto SDK audience-related endpoints.

## Overview

Implement three audience endpoints to manage audience retrieval and subscription status tracking.

**Implementation Order:**
1. GetAudiences - Retrieve existing audiences
2. SubscribeToAudience - Manage audience subscriptions
3. GetPeopleSubscriptions - Retrieve subscription statuses

**Rationale:** GetAudiences must be first to obtain audience IDs for use in Subscribe and Subscriptions endpoints.

## 1. GetAudiences Endpoint

### Request Class

**File:** `src/Requests/Audience/GetAudiences.php`

**Structure:**
```php
class GetAudiences extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected ?string $searchTerm = null,
        protected ?bool $withFilter = null,
        protected ?int $limit = null,
        protected ?int $offset = null,
        protected ?bool $archived = null,
        protected ?bool $retention = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/audiences/get';
    }
}
```

**Parameters:**
- All optional
- Conditional body building (only include non-null params)
- Default limit/max: 40

**Response Structure:**
- Array of audience objects
- Each with: id, name, type, members count, etc.
- No wrapper object - direct array response

### Test Scenarios

1. Get audiences with default parameters (empty body)
2. Get audiences with search term
3. Get audiences with filters included (`with_filter: true`)
4. Get audiences with pagination (limit + offset)
5. Get archived audiences

**Fixtures:**
- `audience/get_audiences_default.json`
- `audience/get_audiences_with_search.json`
- `audience/get_audiences_with_filter.json`
- `audience/get_audiences_paginated.json`
- `audience/get_audiences_archived.json`

---

## 2. SubscribeToAudience Endpoint

### Request Class

**File:** `src/Requests/Audience/SubscribeToAudience.php`

**Structure:**
```php
class SubscribeToAudience extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param array<int, array{email?: string, person_id?: string, external_id?: string, subscribed?: bool, sms_opted_in?: bool}> $people
     */
    public function __construct(
        protected string $audienceId,
        protected array $people,
        protected bool $async = false,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/audience/subscribe';
    }

    protected function defaultBody(): array
    {
        return [
            'audience_id' => $this->audienceId,
            'people' => $this->people,
            'async' => $this->async,
        ];
    }
}
```

**Parameters:**
- `audienceId` - Required, audience identifier
- `people` - Required, array of person objects with identifiers + permissions
- `async` - Optional, default false

**People Array Structure:**
Each person object can have:
- One identifier: `email` OR `person_id` OR `external_id`
- One or both permissions: `subscribed` (email), `sms_opted_in` (SMS)

**Important:** Contacts must already be members of the audience before permission changes apply.

### Data Classes Needed

**File:** `src/Data/SubscriptionPerson.php`

```php
class SubscriptionPerson implements Arrayable
{
    public function __construct(
        public ?string $email = null,
        public ?string $personId = null,
        public ?string $externalId = null,
        public ?bool $subscribed = null,
        public ?bool $smsOptedIn = null,
    ) {}

    public function toArray(): array
    {
        $data = [];

        if ($this->email !== null) {
            $data['email'] = $this->email;
        }

        if ($this->personId !== null) {
            $data['person_id'] = $this->personId;
        }

        if ($this->externalId !== null) {
            $data['external_id'] = $this->externalId;
        }

        if ($this->subscribed !== null) {
            $data['subscribed'] = $this->subscribed;
        }

        if ($this->smsOptedIn !== null) {
            $data['sms_opted_in'] = $this->smsOptedIn;
        }

        return $data;
    }
}
```

### Test Scenarios

1. Subscribe people to audience by email (email permission)
2. Subscribe people by person_id (SMS permission)
3. Unsubscribe people from audience (email)
4. Unsubscribe from SMS
5. Update both email and SMS permissions together
6. Async vs sync processing

**Prerequisites for tests:**
- Need real audience ID from GetAudiences
- People must be members of the audience

**Fixtures:**
- `audience/subscribe_email.json`
- `audience/subscribe_sms.json`
- `audience/unsubscribe_email.json`
- `audience/unsubscribe_sms.json`
- `audience/subscribe_both.json`

---

## 3. GetPeopleSubscriptions Endpoint

### Request Class

**File:** `src/Requests/Person/GetPeopleSubscriptions.php`

**Structure:**
```php
class GetPeopleSubscriptions extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param array<int, array{person_id?: string, email?: string, external_id?: string}> $people
     */
    public function __construct(
        protected array $people,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/person/subscriptions';
    }

    protected function defaultBody(): array
    {
        return [
            'people' => $this->people,
        ];
    }
}
```

**Parameters:**
- `people` - Required, array of person identifier objects

**Response Structure:**
```php
[
    'people' => [
        [
            'person_status' => 'merged',
            'person_id' => 'string',
            'subscriptions' => [
                [
                    'audience_id' => 'string',
                    'audience_name' => 'string',
                    'member_from' => 'ISO 8601',
                    'subscribed' => bool,
                    'subscribed_from' => 'ISO 8601',
                    'unsubscribed_from' => 'ISO 8601|null',
                    'sms_opted_in' => bool,
                    'sms_opted_out_from' => 'ISO 8601|null',
                ],
            ],
            'email_permissions' => bool,
            'sms_permissions' => bool,
        ],
    ],
]
```

### Test Scenarios

1. Get subscriptions by person_id
2. Get subscriptions by email
3. Get subscriptions by external_id
4. Get subscriptions for multiple people

**Prerequisites:**
- Need people with audience memberships
- Can use people from earlier tests

**Fixtures:**
- `person/get_subscriptions_by_person_id.json`
- `person/get_subscriptions_by_email.json`
- `person/get_subscriptions_by_external_id.json`
- `person/get_subscriptions_multiple.json`

---

## Implementation Dependencies

```
GetAudiences (no deps)
    ↓
SubscribeToAudience (needs: audience IDs from GetAudiences)
    ↓
GetPeopleSubscriptions (needs: subscribed people from SubscribeToAudience)
```

## Testing Strategy

### GetAudiences Tests
- Use existing audiences from Ortto account
- No setup required
- Straightforward mocking

### SubscribeToAudience Tests
- Use real audience ID from GetAudiences fixture
- Create test people first
- Add people to audience (may need manual step or use Ortto UI)
- Then test subscription permission changes

### GetPeopleSubscriptions Tests
- Use people who have been subscribed via SubscribeToAudience
- Or use existing people from Ortto account with subscriptions
- Test various identifier types

## Data Classes Summary

### New DTOs Needed

1. **SubscriptionPerson** (`src/Data/SubscriptionPerson.php`)
   - Used in SubscribeToAudience request
   - Implements Arrayable
   - Handles flexible identifiers + permissions

2. **Optional: SubscriptionData** (`src/Data/SubscriptionData.php`)
   - Response object for subscription details
   - Could be useful for type-safe response handling
   - Not strictly necessary if working with arrays

### Factories Needed

**SubscriptionPersonFactory** (`tests/Factories/SubscriptionPersonFactory.php`)
- Generates test subscription person data
- Supports state() for custom values
- Similar pattern to PersonFactory

## Enums Needed

None - all values are strings, booleans, or integers.

## Total Implementation

**Files to create:**
- 3 Request classes
- 1-2 Data classes
- 1 Factory
- 3 Test files
- ~15 fixture files

**Estimated complexity:** Medium
- GetAudiences: Simple (like GetPeople)
- SubscribeToAudience: Medium (requires DTO)
- GetPeopleSubscriptions: Simple (like GetPeopleByIds)

**Total tests:** ~12-15 tests
**Expected coverage:** 100% code and type coverage maintained
