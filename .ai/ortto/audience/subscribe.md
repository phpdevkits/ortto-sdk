# Subscribe or unsubscribe people to/from an audience (subscribe)

The subscribe Ortto endpoint of the audience entity is used to subscribe or unsubscribe people to/from an audience in your Ortto account's customer data platform (CDP).

## HTTP method and request resource

```
PUT https://api.ap3api.com/v1/audience/subscribe
```

> **NOTE:** Ortto customers who have their instance region set to Australia or Europe will need to use specific service endpoints relative to the region:
>
> - Australia: `https://api.au.ap3api.com/v1/audience/subscribe`
> - Europe: `https://api.eu.ap3api.com/v1/audience/subscribe`
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

### Example request body - Email subscription

```json
{
  "audience_id": "61380ac92593ecf2de4fd705",
  "people": [
    {
      "email": "person1@example.com",
      "subscribed": true
    },
    {
      "email": "person2@example.com",
      "subscribed": false
    }
  ],
  "async": true
}
```

### Example request body - SMS subscription

```json
{
  "audience_id": "61380ac92593ecf2de9ab302",
  "people": [
    {
      "email": "person3@example.com",
      "sms_opted_in": true
    },
    {
      "email": "person4@example.com",
      "sms_opted_in": false
    }
  ],
  "async": true
}
```

### Valid request body elements

| Element | Type | Description |
|---------|------|-------------|
| **audience_id** | string | Identifier for the target audience |
| **people** | array of objects | Collection of contact records to update. Each person object should contain an identifier (`email`, `person_id`, or `external_id`) |
| **subscribed** | boolean | Email permission status. Set to `true` to subscribe, `false` to unsubscribe |
| **sms_opted_in** | boolean | SMS permission status. Set to `true` to opt-in, `false` to opt-out |
| **async** | boolean | When `true`, enables asynchronous processing of the request |

## Subscribe vs. Unsubscribe

**To subscribe:**
- Email: Set `subscribed: true`
- SMS: Set `sms_opted_in: true`

**To unsubscribe:**
- Email: Set `subscribed: false`
- SMS: Set `sms_opted_in: false`

## Important Requirements

> **CRITICAL:** This endpoint does NOT add people to audiences.
>
> **Audience membership is automatic** - determined by audience filter rules configured in the Ortto UI (e.g., tags, country, custom fields).
>
> This endpoint only changes **opt-in/opt-out preferences** for people who are already audience members.

### How Audience Membership Works

**Automatic Membership:**
- Audiences have filter criteria set in Ortto UI (e.g., "users with tag: VIP" or "country: USA")
- When a person's data matches the filter, Ortto automatically adds them as an audience member
- There is **no API endpoint** to manually add people to audiences

**What This Endpoint Does:**
- For people who are **already audience members**, it sets their opt-in preferences
- `subscribed: true/false` - Email opt-in status for this specific audience
- `sms_opted_in: true/false` - SMS opt-in status for this specific audience

### Automatic Subscriptions via Merge Endpoint

When creating/updating people via `/v1/person/merge`:
- `bol::p: true` (emailPermission) → Auto-subscribes to "Subscribers" audience
- `bol::sp: true` (smsPermission) → Auto-subscribes to "SMS subscribers" audience

## Combined Updates

Both `subscribed` and `sms_opted_in` parameters can be included in a single request to update both email and SMS opt-in preferences simultaneously.

## Other Communication Channels

The documentation does not specify how to manage opt-in/opt-out for:
- WhatsApp notifications
- iOS push notifications
- Android push notifications
- Web push notifications

## Response structure

```json
{
  "audience_id": "string",
  "people": [
    {
      "person_status": "by-id",
      "status": "subscribed"
    },
    {
      "person_status": "by-id",
      "status": "unsubscribed"
    }
  ]
}
```

### Response fields

| Field | Type | Description |
|-------|------|-------------|
| **audience_id** | string | The audience ID from the request |
| **people** | array | Array of status objects for each person |
| → **person_status** | string | How the person was identified (e.g., "by-id", "merged") |
| → **status** | string | Result: "subscribed" or "unsubscribed" |

## Notes

- Use person identifiers: `email`, `person_id`, or `external_id`
- Multiple people can be updated in a single request
- Asynchronous processing recommended for bulk updates
- People must be audience members (via filter rules) before opt-in changes apply
