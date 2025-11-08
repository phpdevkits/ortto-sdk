# Campaign Reports API - GET

Retrieve campaign and asset reports including performance metrics, analytics, and attribution data.

## Endpoint

```
POST /v1/campaign/reports/get
```

## Authentication

```
X-Api-Key: CUSTOM-PRIVATE-API-KEY
Content-Type: application/json
```

## Request Parameters

All parameters are optional. At least one identifier is typically provided to scope the report.

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `campaign_id` | string | No | Campaign identifier for the report |
| `asset_id` | string | No | Specific asset, shape, or message within campaign |
| `shape_id` | string | No | Journey shape identifier for individual shape reports |
| `message_id` | string | No | Playbook email message identifier |
| `timeframe` | string | No | Report data period (default: "last-month" for journeys/playbooks) |

### Timeframe Values

Timeframes are calculated based on the account's timezone:
- `last-7-days`
- `last-14-days`
- `last-month`
- `last-quarter`

### Example Requests

**Single-send campaign:**
```json
{
  "campaign_id": "660f29e1c6e6a097e7b0ac9f"
}
```

**A/B variant report:**
```json
{
  "campaign_id": "660f29e1c6e6a097e7b0ac9f",
  "asset_id": "65c9d2711de06dcc18b076b3"
}
```

**Journey/playbook with timeframe:**
```json
{
  "campaign_id": "660f29e1c6e6a097e7b0ac9f",
  "timeframe": "last-14-days"
}
```

## Response Structure

### Top-Level Fields

| Field | Type | Description |
|-------|------|-------------|
| `last_updated` | string | ISO 8601 timestamp of last report update |
| `timeframe` | object | Period details including custom date ranges and timezone |
| `precision` | string | Data granularity (e.g., "days") |
| `report_type` | string | Attribution type (e.g., "revenue") |
| `attr_model` | string | Attribution model applied (e.g., "last") |
| `reports` | object | Campaign metrics and analytics data |
| `report_content_type` | string | Graph data type identifier |
| `asset` | object | Asset metadata and content (emails, SMS, push notifications) |
| `instance_id` | string | Account identifier |

### Reports Object Properties

The `reports` object contains various metrics depending on the campaign type:

- `performance` / `variant_a_performance` / `variant_b_performance` - Key metrics (opens, clicks, conversions, sent, deliveries)
- `message_performance` - Time-series line graph data with legend
- `top_links` - Most-clicked URLs with click counts
- `revenue` - Attribution data including conversions and revenue
- `revenue_graph` - Revenue trend visualization
- `android_performance` / `ios_performance` / `web_performance` - Push notification platform metrics
- `email_summary` - Opens, clicks, conversions, invalid, bounced metrics graph
- `reactions` - Engagement reactions data
- `top_locations` - Geographic engagement data
- `survey_responses` - SMS survey analytics
- `redeemed_coupons` - Promotional tracking

### Asset Object

When included, contains asset metadata:
- `id`, `name`, `subject`, `subject_json`
- `folder_id`, `instance_id`
- `state`, `published_at`, `type`
- `campaign_id`, `campaign_type`, `campaign_name`
- `created_at`, `created_by_name`
- `edited_at`, `edited_by_name`
- `draft`, `published`

## Constraints & Limitations

- **Rate limit:** 60 requests per 10 minutes
- **Campaign state:** Only sent campaigns can be retrieved (no scheduled or draft campaigns)
- **Single-send timeframe:** By default retrieves report data for all time
- **Journey/Playbook timeframe:** Defaults to last month if not specified

## Supported Campaign Types

- Single-send: Email, SMS, push notifications
- Journey/Playbook: Email, SMS, push notification shapes
- A/B tested variants (all types)
- Resent email campaigns (fetched separately by asset/resend ID)

## References

- [Official Documentation](https://help.ortto.com/a-686-retrieve-a-campaign-or-asset-report-get)
