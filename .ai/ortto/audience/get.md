# Retrieve a list of audiences (get)

The get Ortto endpoint of the audience entity is used to retrieve a list of audiences from your Ortto account's customer data platform (CDP).

## HTTP method and request resource

```
POST https://api.ap3api.com/v1/audiences/get
```

> **NOTE:** Ortto customers who have their instance region set to Australia or Europe will need to use specific service endpoints relative to the region:
>
> - Australia: `https://api.au.ap3api.com/v1/audiences/get`
> - Europe: `https://api.eu.ap3api.com/v1/audiences/get`
>
> All other Ortto users will use the default service endpoint (`https://api.ap3api.com/`).

## Path and query parameters

This endpoint takes no additional path and/or query parameters.

## Headers

This endpoint requires a custom API key and content type (`application/json` for the request body) in the header of the request:

- `X-Api-Key: CUSTOM-PRIVATE-API-KEY`
- `Content-Type: application/json`

## Request body

The request body consists of a JSON object whose valid elements are listed in the table below. All parameters are optional.

### Example request body

```json
{
  "search_term": "subscribers",
  "with_filter": true,
  "limit": 10,
  "offset": 0
}
```

### Valid request body elements

| Element | Type | Description |
|---------|------|-------------|
| **search_term** | string | Text to search audience names |
| **with_filter** | boolean | When `true`, includes filter conditions in response (default: `false`) |
| **limit** | integer | Number of audiences per page (default/max: 40) |
| **offset** | integer | Number of audiences to skip for pagination |
| **archived** | boolean | When `true`, returns archived audiences |
| **retention** | boolean | When `true`, returns retention-type audiences |

## Response structure

The endpoint returns an array of audience objects.

### Example response

```json
[
  {
    "id": "624bd7905a9d367aaf824083",
    "instance_id": "myorttoaccount",
    "icon_id": "mobile-illustration-icon",
    "type": "permanent",
    "building": false,
    "ready": true,
    "is_public": false,
    "name": "SMS subscribers",
    "members": 53,
    "added_last_30_days": 3
  }
]
```

### Response fields

| Field | Type | Description |
|-------|------|-------------|
| **id** | string | Unique audience identifier |
| **instance_id** | string | Ortto account name |
| **icon_id** | string | Icon identifier for UI display |
| **type** | string | Audience type (e.g., "permanent") |
| **building** | boolean | Whether audience is currently building |
| **ready** | boolean | Whether audience has finished building |
| **is_public** | boolean | Included in email preference center |
| **sms_is_public** | boolean | Included in SMS preference center |
| **sms_public_name** | string | SMS preference center display name |
| **sms_public_description** | string | SMS preference center description |
| **filter** | array | Entry criteria filter conditions (when `with_filter: true`) |
| **created** | string | Creation timestamp (ISO 8601) |
| **created_by_id** | string | Creator user ID |
| **created_by_name** | string | Creator user name |
| **edited_at** | string | Last edit timestamp (ISO 8601) |
| **name** | string | Internal audience name |
| **public_name** | string | Email preference center name |
| **public_description** | string | Email preference center description |
| **template_id** | string | System/integration template identifier |
| **exit_selection** | string | Exit criteria (empty = default behavior) |
| **destinations** | object/null | Connected destinations or null |
| **sms_opted_in** | integer | SMS-opted members count |
| **subscribers** | integer | Valid subscriber count |
| **members** | integer | Total member count |
| **added_last_30_days** | integer | New members in past month |

## Pagination

Use `limit` and `offset` together to retrieve multiple pages of results with a maximum of 40 audiences per request.
