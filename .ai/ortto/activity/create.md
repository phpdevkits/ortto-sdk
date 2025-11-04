# Create custom activity events (create)

The create endpoint of the activities entity is used to create custom activity events for contacts in your Ortto account's customer data platform (CDP).

## HTTP method and request resource

```
POST https://api.ap3api.com/v1/activities/create
```

> **NOTE:** Ortto customers who have their instance region set to Australia or Europe will need to use specific service endpoints relative to the region:
>
> - Australia: `https://api.au.ap3api.com/v1/activities/create`
> - Europe: `https://api.eu.ap3api.com/v1/activities/create`
>
> All other Ortto users will use the default service endpoint (`https://api.ap3api.com/`).

## Path and query parameters

This endpoint takes no additional path and/or query parameters.

## Headers

This endpoint requires a custom API key and content type (`application/json` for the request body) in the header of the request:

- `X-Api-Key: CUSTOM-PRIVATE-API-KEY`
- `Content-Type: application/json`

## Request body

The request body consists of a JSON object with an `activities` array. Each activity object can create or update a contact and associate an activity event with them.

### Example request body - Basic activity creation

```json
{
  "activities": [
    {
      "activity_id": "act:cm:product-purchase",
      "attributes": {
        "str:cm:product-name": "Premium Plan",
        "int::v": 5000
      },
      "fields": {
        "str::email": "customer@example.com",
        "str::first": "John"
      },
      "merge_by": ["str::email"]
    }
  ],
  "async": false
}
```

### Example request body - With backdating

```json
{
  "activities": [
    {
      "activity_id": "act:cm:order-completed",
      "attributes": {
        "str:cm:order-id": "ORD-12345",
        "int::v": 29900
      },
      "fields": {
        "str::email": "customer@example.com"
      },
      "created": "2025-10-15T14:30:00Z",
      "key": "order-ORD-12345",
      "merge_by": ["str::email"]
    }
  ]
}
```

### Example request body - Using person_id

```json
{
  "activities": [
    {
      "activity_id": "act:cm:page-view",
      "attributes": {
        "str:cm:page-url": "/pricing",
        "str:cm:referrer": "google"
      },
      "person_id": "00647687d2e43b25a0261f00"
    }
  ]
}
```

### Example request body - With location data

```json
{
  "activities": [
    {
      "activity_id": "act:cm:store-visit",
      "attributes": {
        "str:cm:store-name": "Melbourne CBD"
      },
      "fields": {
        "str::email": "visitor@example.com"
      },
      "location": {
        "custom": {
          "name": "Melbourne",
          "region": "Victoria",
          "country": "Australia",
          "timezone": "Australia/Melbourne"
        }
      },
      "merge_by": ["str::email"]
    }
  ]
}
```

### Valid request body elements

| Element | Type | Description |
|---------|------|-------------|
| **activities** | array of objects | **Required.** Array of activity events to create (max 100 per request) |
| **async** | boolean | Optional. Enable asynchronous processing for better performance with large volumes. Default: `false` |

### Activity object elements

| Element | Type | Description |
|---------|------|-------------|
| **activity_id** | string | **Required.** Custom activity identifier (e.g., `act:cm:product-purchase`) |
| **attributes** | object | **Required.** Activity-specific fields using format `type:namespace:field` |
| **person_id** | string | Person's unique ID. If provided, ignores `fields` and `merge_by`. Faster than merge lookup |
| **fields** | object | Contact data to create/update. Uses format `type::field` or `type:namespace:field`. Required if `person_id` not provided |
| **merge_by** | array of strings | Field IDs to match existing contacts (max 2). Required if `person_id` not provided. Examples: `["str::email"]`, `["str::email", "str::phone"]` |
| **created** | string | ISO 8601 timestamp for backdating activities (up to 90 days or retention period) |
| **key** | string | Combined with `created` to create unique identifier for duplicate prevention |
| **location** | object | Geographic location data. See location options below |
| **merge_strategy** | integer | Override API key merge setting: 1 (append only), 2 (overwrite), 3 (ignore existing) |

### Attribute field types

Activity attributes use the format `type:namespace:field`:

| Type | Description | Example |
|------|-------------|---------|
| `str:cm:field-name` | String/text value | `str:cm:product-name: "Premium Plan"` |
| `int::v` | Conversion value (currency × 1000) | `int::v: 5000` (represents $5.00) |
| `int:cm:field-name` | Integer value | `int:cm:quantity: 3` |
| `bol:cm:field-name` | Boolean value | `bol:cm:is-member: true` |
| `dtz:cm:field-name` | Date/time value | ISO 8601 timestamp |
| `obj:cm:field-name` | JSON object | Complex nested data structures |

### Person field types

Standard person fields use the format `type::field`:

| Field | Description |
|-------|-------------|
| `str::email` | Email address |
| `str::first` | First name |
| `str::last` | Last name |
| `str::phone` | Phone number |
| `bol::p` | Email permission (true/false) |
| `bol::sp` | SMS permission (true/false) |

Custom person fields use `type:cm:field-name` format.

### Location options

The `location` object supports three methods:

**1. IP-based geolocation:**
```json
"location": {
  "source_ip": "119.18.0.218"
}
```

**2. Custom location (coordinates):**
```json
"location": {
  "custom": {
    "name": "Melbourne",
    "region": "Victoria",
    "country": "Australia",
    "country_code": "AU",
    "latitude": -37.8136,
    "longitude": 144.9631,
    "timezone": "Australia/Melbourne"
  }
}
```

**3. Postal address:**
```json
"location": {
  "address": {
    "address1": "123 Main St",
    "city": "Melbourne",
    "region": "VIC",
    "country": "Australia",
    "postal_code": "3000"
  }
}
```

## Backdating and duplicate prevention

**Backdating activities:**
- Use the `created` field with ISO 8601 timestamp
- Activities up to 90 days old (or your retention period) process immediately
- Activities older than 90 days are queued (may take up to 1 hour to process)

**Preventing duplicates:**
- Combine `key` attribute with `created` timestamp
- If same `key` + `created` pair sent multiple times, Ortto merges requests
- Prevents duplicate activity ingestion

Example:
```json
{
  "created": "2025-10-15T14:30:00Z",
  "key": "order-12345",
  "activity_id": "act:cm:purchase"
}
```

## Request limits

| Limit | Value |
|-------|-------|
| Max activities per request | 100 |
| Max payload size | 2 MB |
| Max activity events per activity per contact per 24 hours | 50 |

## Response structure

### Example response - Synchronous processing

```json
{
  "activities": [
    {
      "person_id": "00647687d2e43b25a0261f00",
      "status": "ingested",
      "person_status": "created",
      "activity_id": "act:cm:product-purchase"
    },
    {
      "person_id": "00647687d2e43b25a0261f01",
      "status": "ingested",
      "person_status": "updated",
      "activity_id": "act:cm:product-purchase"
    }
  ]
}
```

### Example response - Asynchronous processing

```json
{
  "activities": [
    {
      "person_id": "00647687d2e43b25a0261f00",
      "status": "queued",
      "person_status": "queued",
      "activity_id": "act:cm:product-purchase"
    }
  ]
}
```

### Response fields

| Field | Type | Description |
|-------|------|-------------|
| **activities** | array | Array of activity result objects |
| → **person_id** | string | Ortto person ID (if processed) |
| → **status** | string | Activity processing status: `"ingested"` (synchronous) or `"queued"` (async) |
| → **person_status** | string | Person record status: `"created"` (new contact), `"updated"` (existing), or `"queued"` (async) |
| → **activity_id** | string | The activity ID from the request |

## Important notes

### Empty vs null values

**For search/filter behavior:**
- Set to `0` or `""` (empty string) - Allows field to be found in searches
- Set to `null` - Excludes field from searches

### Merge behavior

When using `merge_by`:
1. Ortto searches for existing contact matching the field(s)
2. If found, updates contact based on `merge_strategy`
3. If not found, creates new contact

When using `person_id`:
- Skips merge lookup (faster)
- Directly attaches activity to the specified contact
- Ignores `fields` object entirely

### Async processing

Set `async: true` for:
- Bulk operations (many activities)
- Better performance with high volumes
- When immediate confirmation not required

Activities are queued and processed in order.

### Activity definitions

Before creating activities, ensure the activity definition exists in your Ortto CDP:
1. Navigate to CDP > Activities
2. Create custom activity definition with matching `activity_id`
3. Define activity fields and settings

Or use the Activity Definition API endpoints to create definitions programmatically.

## Error responses

**400 Bad Request** - Invalid parameters or malformed JSON

**401 Unauthorized** - Invalid or missing API key

**413 Payload Too Large** - Request exceeds 2 MB limit

**429 Too Many Requests** - Rate limit exceeded

## Common use cases

### E-commerce purchases
Track product purchases with conversion value:
```json
{
  "activity_id": "act:cm:purchase",
  "attributes": {
    "str:cm:product-name": "Widget Pro",
    "int::v": 4999,
    "int:cm:quantity": 2
  }
}
```

### Page views
Track website interactions:
```json
{
  "activity_id": "act:cm:page-view",
  "attributes": {
    "str:cm:page-url": "/pricing",
    "str:cm:referrer": "google"
  }
}
```

### Event registrations
Track event sign-ups:
```json
{
  "activity_id": "act:cm:event-registration",
  "attributes": {
    "str:cm:event-name": "Webinar: Getting Started",
    "dtz:cm:event-date": "2025-11-15T18:00:00Z"
  }
}
```

### Support tickets
Track customer support interactions:
```json
{
  "activity_id": "act:cm:support-ticket",
  "attributes": {
    "str:cm:ticket-id": "TICK-789",
    "str:cm:ticket-subject": "Login Issue",
    "str:cm:priority": "High"
  }
}
```
