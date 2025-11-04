# Retrieve activity feed for a contact (activities)

The activities endpoint of the person entity is used to retrieve the activity feed for a specific contact in your Ortto account's customer data platform (CDP).

## HTTP method and request resource

```
POST https://api.ap3api.com/v1/person/get/activities
```

> **NOTE:** Ortto customers who have their instance region set to Australia or Europe will need to use specific service endpoints relative to the region:
>
> - Australia: `https://api.au.ap3api.com/v1/person/get/activities`
> - Europe: `https://api.eu.ap3api.com/v1/person/get/activities`
>
> All other Ortto users will use the default service endpoint (`https://api.ap3api.com/`).

## Path and query parameters

This endpoint takes no additional path and/or query parameters.

## Headers

This endpoint requires a custom API key and content type (`application/json` for the request body) in the header of the request:

- `X-Api-Key: CUSTOM-PRIVATE-API-KEY`
- `Content-Type: application/json`

## Request body

The request body consists of a JSON object whose valid elements are listed in the table below.

### Example request body - Basic retrieval

```json
{
  "person_id": "0069061b5bda4060a5765300"
}
```

### Example request body - With activity filter

```json
{
  "person_id": "0069061b5bda4060a5765300",
  "activities": ["act::o", "act::c"],
  "limit": 20,
  "offset": 0
}
```

### Example request body - With timeframe filter

```json
{
  "person_id": "0069061b5bda4060a5765300",
  "timeframe": {
    "type": "last-7-days"
  },
  "limit": 50
}
```

### Valid request body elements

| Element | Type | Description |
|---------|------|-------------|
| **person_id** | string | **Required.** Unique identifier for the contact whose activities you want to retrieve |
| **activities** | array of strings | Optional. Activity IDs to filter by (e.g., `["act::o", "act::c"]` for opens and clicks). If not provided, returns all activities |
| **limit** | integer | Optional. Number of records to return per page. Default and maximum is typically 40-50 depending on API configuration |
| **offset** | integer | Optional. Starting point for pagination. Use with limit to paginate through results |
| **timeframe** | object | Optional. Time range filter for activities. Object with `type` property. See timeframe options below |

### Timeframe options

The `timeframe` parameter is an object with a `type` property. The `type` property supports the following relative period values:

| Value | Description |
|-------|-------------|
| `last-24-hours` | Activities from the last 24 hours |
| `last-7-days` | Activities from the last 7 days |
| `last-30-days` | Activities from the last 30 days |
| `today` | Activities from today |
| `yesterday` | Activities from yesterday |
| `this-week` | Activities from the current week |
| `this-month` | Activities from the current month |
| `this-quarter` | Activities from the current quarter |
| `this-year` | Activities from the current year |
| `all` | All activities (no time filter) |

### Finding activity IDs

To find activity IDs for filtering:

1. Navigate to CDP > Activities section in Ortto UI
2. Click on the target activity
3. Extract the ID from the URL (e.g., `act::o` for opens, `act::c` for clicks)

Common activity IDs include:
- `act::o` - Email opens
- `act::c` - Email clicks
- `act::s` - Email sends
- Custom activities will have unique IDs

## Response structure

The endpoint returns a JSON object containing the activity feed data, metadata, and pagination information.

### Example response

```json
{
  "activities": [
    {
      "activity_id": "act::o",
      "activity_name": "Email open",
      "timestamp": "2025-11-03T14:23:17Z",
      "fields": {
        "str::email": "contact@example.com",
        "str::campaign-name": "Weekly Newsletter"
      }
    },
    {
      "activity_id": "act::c",
      "activity_name": "Email click",
      "timestamp": "2025-11-03T14:25:42Z",
      "fields": {
        "str::email": "contact@example.com",
        "str::campaign-name": "Weekly Newsletter",
        "str::link-url": "https://example.com/product"
      }
    }
  ],
  "meta": {
    "total_count": 145,
    "field_ids": ["act::o", "act::c"],
    "has_next": true,
    "retention_policy": "90-days"
  },
  "offset": 0,
  "next_offset": 50
}
```

### Response fields

| Field | Type | Description |
|-------|------|-------------|
| **activities** | array | Array of activity objects |
| → **activity_id** | string | Unique identifier for the activity type (e.g., "act::o") |
| → **activity_name** | string | Human-readable name of the activity |
| → **timestamp** | string | ISO 8601 timestamp when the activity occurred |
| → **fields** | object | Activity-specific fields and metadata (varies by activity type) |
| **meta** | object | Metadata about the response |
| → **total_count** | integer | Total number of activities matching the query |
| → **field_ids** | array | List of activity IDs included in the response |
| → **has_next** | boolean | Whether there are more results available |
| → **retention_policy** | string | Data retention policy applied to activities |
| **offset** | integer | Current pagination offset |
| **next_offset** | integer | Offset to use for retrieving the next page |

## Rate limits

**Important:** This endpoint has a rate limit of **1 request per second**. Ensure your implementation respects this limit to avoid API errors.

## Pagination

To paginate through results:

1. Make initial request with desired `limit`
2. Use `next_offset` from response in subsequent request's `offset` parameter
3. Continue until `has_next` is `false`

Example pagination flow:
```json
// Request 1
{ "person_id": "123", "limit": 50, "offset": 0 }

// Response 1: next_offset = 50, has_next = true

// Request 2
{ "person_id": "123", "limit": 50, "offset": 50 }

// Response 2: next_offset = 100, has_next = false
```

## Notes

- Response fields vary depending on the activity type
- Activities are typically returned in reverse chronological order (newest first)
- The `person_id` parameter is always required
- When no activities match the filter criteria, the response will have an empty `activities` array
- Respect the 1 request per second rate limit
- Use pagination for contacts with large activity histories
