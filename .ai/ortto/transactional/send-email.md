# Transactional - Send Email API

Submit requests to deliver transactional email messages through Ortto's customer data platform.

## Endpoint

```
POST /v1/transactional/send
```

## Authentication

```
X-Api-Key: CUSTOM-PRIVATE-API-KEY
Content-Type: application/json
```

## Request Parameters

### Email Asset (Required - One of three options)

**Option 1: Inline Email Definition**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `from_email` | string | Yes | Sender email address; ideally from configured custom domain |
| `from_name` | string | Yes | Display name for sender |
| `reply_to` | string | No | Reply-to email address |
| `cc` | array | No | Carbon copy addresses (max 5) |
| `subject` | string | Yes | Email subject line |
| `email_name` | string | Yes | Identifier for filtering and reporting |
| `html_body` | string | Yes | Full HTML email content |
| `liquid_syntax_enabled` | boolean | No | Enable Liquid templating (default: true) |
| `no_click_tracks` | boolean | No | Disable URL rewriting for click tracking (default: false) |
| `no_open_tracks` | boolean | No | Disable tracking pixel (default: false) |
| `attachments` | array | No | Up to 5 base64-encoded files with MIME types |

**Option 2: Campaign Reference**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `campaign_id` | string | Yes | Draft/sent campaign template ID |

**Option 3: Asset Reference**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `asset_id` | string | Yes | Draft/published asset template ID |

### Recipients (Required)

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `emails` | array | Yes | Recipient objects containing fields and optional asset overrides |
| `emails[].fields` | object | Yes | Person field data (uses Ortto field naming like `str::email`, `str::first`) |
| `emails[].location` | object | No | Geographic location data |
| `emails[].asset` | object | No | Per-recipient asset overrides |

### Merge Configuration (Required)

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `merge_by` | array | Yes | Up to 3 field IDs determining create/update logic |
| `merge_strategy` | integer | Yes | Merge behavior strategy (1=AppendOnly, 2=OverwriteExisting, 3=Ignore) |
| `find_strategy` | integer | No | How merge_by fields find existing records (0=Any, 1=NextOnlyIfPreviousEmpty, 2=All) |
| `skip_non_existing` | boolean | No | Skip records without existing people |

### Example Request (Inline Email)

```json
{
  "from_email": "sender@example.com",
  "from_name": "Example Team",
  "subject": "Welcome!",
  "email_name": "welcome-email",
  "html_body": "<html><body>Welcome!</body></html>",
  "emails": [
    {
      "fields": {
        "str::email": "recipient@example.com",
        "str::first": "John"
      }
    }
  ],
  "merge_by": ["str::email"],
  "merge_strategy": 2
}
```

### Example Request (Campaign Reference)

```json
{
  "campaign_id": "63f3f1d0ae7e17e725033fe3",
  "emails": [
    {
      "fields": {
        "str::email": "recipient@example.com",
        "str::first": "John"
      }
    }
  ],
  "merge_by": ["str::email"],
  "merge_strategy": 2
}
```

### Example Request (Asset Reference)

```json
{
  "asset_id": "63f3f1d0ae7e17e725033fe3",
  "emails": [
    {
      "fields": {
        "str::email": "recipient@example.com",
        "str::first": "John"
      }
    }
  ],
  "merge_by": ["str::email"],
  "merge_strategy": 2
}
```

## Response Structure

**Status:** 200 OK (successful submission)

```json
{
  "emails": [
    {
      "person_id": "string",
      "contact_status": "merged",
      "email_status": "queued",
      "message_id": "string",
      "email_name": "string"
    }
  ]
}
```

| Field | Type | Description |
|-------|------|-------------|
| `person_id` | string | Unique person identifier |
| `contact_status` | string | "merged" or "created" |
| `email_status` | string | "queued" indicates successful submission |
| `message_id` | string | Unique message identifier |
| `email_name` | string | Email identifier for reporting |

## Constraints & Limitations

- Maximum 5 attachments per email
- Maximum 5 CC/BCC addresses combined
- All attachment content must be base64-encoded with correct MIME type
- merge_by array limited to 3 field IDs
- Transactional emails don't require unsubscribe links (optional inclusion allowed)
- Per-recipient asset overrides supported for sending different content to individual recipients

## References

- [Official Documentation](https://help.ortto.com/a-827-send-emails-via-api)
