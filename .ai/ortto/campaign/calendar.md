# Campaign Calendar API

Retrieve a list of sent and scheduled campaigns within a specified date range.

## Endpoint

```
POST /v1/campaign/calendar
```

## Authentication

```
X-Api-Key: CUSTOM-PRIVATE-API-KEY
Content-Type: application/json
```

## Request Parameters

All parameters are required.

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `start` | object | Yes | Start period with `year` (YYYY format) and `month` (1-12 integer) |
| `end` | object | Yes | End period with `year` (YYYY format) and `month` (1-12 integer) |
| `timezone` | string | Yes | Timezone for the campaign list (e.g., "Australia/Sydney") |

### Example Request

```json
{
  "start": {
    "year": 2024,
    "month": 3
  },
  "end": {
    "year": 2024,
    "month": 4
  },
  "timezone": "Australia/Sydney"
}
```

## Response Structure

### Top-Level Fields

| Field | Type | Description |
|-------|------|-------------|
| `campaigns` | array | Array of campaign objects |
| `meta` | object | Account-level statistics |
| `today` | string | Response generation timestamp |

### Campaign Object Fields

Each campaign in the `campaigns` array contains:

| Field | Type | Description |
|-------|------|-------------|
| `id` | string | Campaign unique identifier |
| `name` | string | Internal campaign name |
| `type` | string | Campaign type (email, SMS, push notification) |
| `state` | string | Campaign state (e.g., "sent") |
| `asset_id` | string | Asset identifier for report lookup |
| `audience` | object | Filter conditions and audience size |
| `sending_at` | string | Send initiation timestamp |
| `sent_at` | string | Send completion timestamp |
| `a_b_testing` | object | A/B test details if applicable |
| `resend` | field | Indicates if campaign is a resend |
| `resent_at` | string | Resend timestamp if applicable |

### Meta Object Fields

Account-level statistics:

| Field | Type | Description |
|-------|------|-------------|
| `total_campaigns` | integer | Total non-draft campaigns across all time |
| `total_active_campaigns` | integer | Count of active campaigns |

## Campaign Types Returned

The endpoint returns:
- Sent email campaigns
- Sent SMS campaigns
- Sent push notification campaigns
- Scheduled campaigns within the specified timeframe

## Notes

- No path or query parameters required
- All data sent in request body
- Returns campaigns for the specified month range
- Resend campaigns tracked separately via `resend` field and `resent_at` timestamp

## References

- [Official Documentation](https://help.ortto.com/a-695-retrieve-a-list-of-sent-campaigns-calendar)
