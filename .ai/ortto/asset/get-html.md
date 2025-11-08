# Asset - Get HTML API

Retrieve the HTML content and email metadata for a specified asset within Ortto's customer data platform (CDP).

## Endpoint

```
POST /v1/assets/get-html
```

## Authentication

```
X-Api-Key: CUSTOM-PRIVATE-API-KEY
Content-Type: application/json
```

## Request Parameters

All parameters are sent in the request body.

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `asset_id` | string | Yes | The unique identifier for the asset to retrieve |

### Example Request

```json
{
  "asset_id": "63f3f1d0ae7e17e725033fe3"
}
```

## Response Structure

| Field | Type | Description |
|-------|------|-------------|
| `html` | string | The complete HTML content of the asset |
| `from_email` | string | Sender's email address |
| `from_name` | string | Sender's display name |
| `subject` | string | Email subject line (may contain variables) |
| `preview` | string | Preview text displayed in email clients |
| `reply_to` | string | Reply-to email address |

### Example Response

```json
{
  "html": "<full html>",
  "from_email": "emily+professional@ortto.com",
  "from_name": "",
  "subject": "Hi {{ people.first-name }}, welcome!",
  "preview": "",
  "reply_to": ""
}
```

## Important Notes

- Only assets created in the Asset Manager contain retrievable asset IDs
- Assets in draft, unpublished, or published status can be accessed
- A/B test variants generate separate asset IDs (Variant A and Variant B)

## References

- [Official Documentation](https://help.ortto.com/a-801-retrieve-html-of-an-asset)
