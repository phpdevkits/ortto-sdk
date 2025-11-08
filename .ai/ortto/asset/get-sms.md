# Asset - Get SMS API

Retrieve content and details from an SMS asset, including message body, character count, encoding type, segment count, and mapped links.

## Endpoint

```
POST /v1/assets/get-sms
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
| `asset_id` | string | Yes | Unique identifier for the SMS asset |
| `contact_id` | string | No | Populates merge tags using specific contact data |
| `show_fallbacks` | boolean | No | Returns merge tag fallbacks (default: false) |
| `raw` | boolean | No | Includes full liquid syntax for merge tags (default: false) |
| `use_published` | boolean | No | Returns published version if true, draft if false |

### Example Request

```json
{
  "asset_id": "63f3f1d0ae7e17e725033fe3"
}
```

## Response Structure

| Field | Type | Description |
|-------|------|-------------|
| `encoding` | string | Character encoding type (e.g., "usc2") |
| `chars_count` | integer | Total character count |
| `segments` | integer | Number of SMS segments required |
| `body` | string | SMS content with all links |
| `mapped_links` | object | Tracked URL mappings in the message |

### Example Response

```json
{
  "encoding": "usc2",
  "chars_count": 206,
  "segments": 4,
  "body": "Hi Maxine, welcome to Ortto...",
  "mapped_links": {
    "https://or.tto.com/wnfskFJs": "https://ortto.com"
  }
}
```

## Constraints & Limitations

- Cannot use `contact_id` with `show_fallbacks` or `raw` set to true

## References

- [Official Documentation](https://help.ortto.com/a-810-retrieve-details-of-sms-asset)
