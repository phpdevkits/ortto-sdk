# Campaign - Get All Campaigns API

Export campaign data from Ortto for auditing and external analysis across multiple campaign types.

## Endpoint

```
POST /v1/campaign/get-all
```

## Authentication

```
X-Api-Key: CUSTOM-API-KEY
Content-Type: application/json
```

## Request Parameters

All parameters are optional and sent in the request body.

| Parameter | Type | Description |
|-----------|------|-------------|
| `type` | string | Single campaign type: `all`, `email`, `playbook`, `sms`, `journey`, `push`, `whatsapp` |
| `types` | array | Multiple campaign types: `["email", "playbook", "journey"]` |
| `state` | string | Status filter: `draft`, `scheduled`, `sending`, `sent`, `cancelled`, `on`, `off` |
| `folder_id` | string | Filter campaigns by folder ID |
| `campaign_ids` | array | Specific campaign IDs to retrieve |
| `limit` | integer | Results per page (1-50, default: 50) |
| `offset` | integer | Pagination offset for batching results |
| `q` | string | Search query against campaign names |
| `sort` | string | Sort by: `name`, `state`, `edited_at`, `created_at`, `delivered`, `opens`, `conversions`, `revenue`, etc. |
| `sort_order` | string | `asc` or `desc` |

### Example Request

```json
{
  "type": "journey",
  "state": "on",
  "folder_id": "6842c82de2f490232b196392",
  "q": "welcome",
  "sort_order": "desc",
  "sort": "name",
  "limit": 5
}
```

## Response Structure

```json
{
  "campaigns": [
    {
      "id": "6842c850c999be9c835e731a",
      "name": "Campaign name",
      "type": "journey",
      "state": "on",
      "folder_id": "6842c82de2f490232b196392",
      "created_at": "2025-06-06T10:52:00.688Z",
      "edited_at": "2025-06-23T10:19:38.354Z",
      "delivered": 0,
      "opens": 0,
      "clicks": 0,
      "conversions": 0,
      "revenue": 0
    }
  ],
  "next_offset": 1,
  "has_more": false,
  "folder_id": "6842c82de2f490232b196392"
}
```

### Response Fields

| Field | Type | Description |
|-------|------|-------------|
| `campaigns` | array | Collection of campaign objects |
| `campaigns[].id` | string | Unique campaign identifier |
| `campaigns[].name` | string | Campaign name |
| `campaigns[].type` | string | Campaign type (email, journey, sms, etc.) |
| `campaigns[].state` | string | Campaign state (draft, on, off, sent, etc.) |
| `campaigns[].folder_id` | string | Parent folder identifier |
| `campaigns[].created_at` | string | ISO 8601 timestamp |
| `campaigns[].edited_at` | string | ISO 8601 timestamp |
| `campaigns[].delivered` | integer | Total delivered count |
| `campaigns[].opens` | integer | Total opens count |
| `campaigns[].clicks` | integer | Total clicks count |
| `campaigns[].conversions` | integer | Total conversions count |
| `campaigns[].revenue` | number | Total revenue generated |
| `next_offset` | integer | Starting point for next page of results |
| `has_more` | boolean | Indicates if additional campaigns exist beyond current page |
| `folder_id` | string | Folder ID from request (if provided) |

## Constraints & Limitations

- Limit accepts between 1 and 50 values
- Default limit is 50 records per request
- Use `offset` parameter incrementally for pagination
- Campaign IDs and folder IDs found in browser URL after opening in Ortto dashboard
- Use either `type` (single) or `types` (multiple), not both

## Use Cases

- Export campaign performance data for external analysis
- Filter campaigns by folder and type
- Search campaigns by name
- Sort campaigns by performance metrics
- Paginate through large campaign collections

## References

- [Official Documentation](https://help.ortto.com/a-887-using-the-api-to-export-campaign-data)
