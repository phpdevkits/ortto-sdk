w# Retrieve people's subscription statuses (subscriptions)

The subscriptions Ortto endpoint of the person entity is used to retrieve subscription status information for one or more people in your Ortto account's customer data platform (CDP).

## HTTP method and request resource

```
POST https://api.ap3api.com/v1/person/subscriptions
```

> **NOTE:** Ortto customers who have their instance region set to Australia or Europe will need to use specific service endpoints relative to the region:
>
> - Australia: `https://api.au.ap3api.com/v1/person/subscriptions`
> - Europe: `https://api.eu.ap3api.com/v1/person/subscriptions`
>
> All other Ortto users will use the default service endpoint (`https://api.ap3api.com/`).

## Path and query parameters

This endpoint takes no additional path and/or query parameters.

## Headers

This endpoint requires a custom API key and content type (`application/json` for the request body) in the header of the request:

- `X-Api-Key: CUSTOM-PRIVATE-API-KEY`
- `Content-Type: application/json`

## Request body

The request body consists of a JSON object with a `people` array containing person identifiers.

### Example request body

```json
{
  "people": [
    {
      "person_id": "00647687d2e43b25a0261f00"
    },
    {
      "email": "contact@example.com"
    },
    {
      "external_id": "ext-12345"
    }
  ]
}
```

### Valid request body elements

| Element | Type | Description |
|---------|------|-------------|
| **people** | array of objects | Array of person objects to retrieve subscription statuses for |
| → **person_id** | string (optional) | Ortto person ID |
| → **email** | string (optional) | Email address |
| → **external_id** | string (optional) | External identifier |

**Field Requirements:** Each person object requires at least one identifier. The API prioritizes `person_id`, then `email`, then `external_id`.

## Response structure

The response returns an array of people with their subscription information.

### Example response

```json
{
  "people": [
    {
      "person_status": "merged",
      "person_id": "00647687d2e43b25a0261f00",
      "subscriptions": [
        {
          "audience_id": "624bd7905a9d367aaf824083",
          "audience_name": "Newsletter Subscribers",
          "member_from": "2024-01-15T10:30:00Z",
          "subscribed": true,
          "subscribed_from": "2024-01-15T10:30:00Z",
          "unsubscribed_from": null,
          "sms_opted_in": false,
          "sms_opted_out_from": null
        }
      ],
      "email_permissions": true,
      "sms_permissions": false
    }
  ]
}
```

### Response fields

| Field | Type | Description |
|-------|------|-------------|
| **person_status** | string | Status of the person (e.g., "merged") |
| **person_id** | string | Ortto person identifier |
| **subscriptions** | array | Array of audience subscription objects |
| → **audience_id** | string | Unique audience identifier |
| → **audience_name** | string | Name of the audience |
| → **member_from** | string | ISO 8601 timestamp when became member |
| → **subscribed** | boolean | Current email subscription status |
| → **subscribed_from** | string | ISO 8601 timestamp when subscribed |
| → **unsubscribed_from** | string/null | ISO 8601 timestamp when unsubscribed (or null) |
| → **sms_opted_in** | boolean | Current SMS opt-in status |
| → **sms_opted_out_from** | string/null | ISO 8601 timestamp when opted out (or null) |
| **email_permissions** | boolean | Overall email permission status |
| **sms_permissions** | boolean | Overall SMS permission status |

## Notes

- No pagination or filtering options available beyond person identifiers
- Timestamps use ISO 8601 format
- Optional fields (`unsubscribed_from`, `sms_opted_out_from`) only present when applicable
- Response includes all audiences the person is a member of
