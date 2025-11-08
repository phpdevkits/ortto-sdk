# Transactional - Send Push Notification API

Send transactional push notifications to users for important updates like order status changes or system alerts.

## Endpoint

```
POST /v1/transactional/send-push
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
| `async` | boolean | No | Controls synchronous/asynchronous processing |
| `pushes` | array | Yes | Array of push notification objects |

### Push Object Structure

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `pushes[].asset.push_name` | string | Yes | Campaign identifier; recipient-invisible |
| `pushes[].asset.title` | string | Yes | Publicly visible notification title; supports templating (e.g., `{{ people.first-name }}`) |
| `pushes[].asset.message` | string | Yes | Notification body content |
| `pushes[].asset.image` | string | No | Valid image URL for display |
| `pushes[].asset.primary_action` | object | No | Single action triggered on notification click |
| `pushes[].asset.primary_action.title` | string | Yes | Action button title |
| `pushes[].asset.primary_action.link` | string | Yes | Action URL |
| `pushes[].asset.secondary_actions` | array | No | Up to 4 additional clickable actions |
| `pushes[].asset.platforms` | array | Yes | Target delivery platforms: "web", "ios", "android" |
| `pushes[].contact_id` | string | Yes | Recipient identifier |

### Example Request

```json
{
  "async": false,
  "pushes": [
    {
      "asset": {
        "push_name": "order-update",
        "title": "Hi {{ people.first-name }}, your order shipped!",
        "message": "Your order is on the way",
        "image": "https://example.com/image.png",
        "primary_action": {
          "title": "Track Order",
          "link": "https://example.com/track"
        },
        "platforms": ["web", "ios", "android"]
      },
      "contact_id": "contact123"
    }
  ]
}
```

## Response Structure

Response structure not specified in documentation. Success indicated by HTTP 200 status.

## Constraints & Limitations

- `push_name`, `title`, and `message` must not be empty
- Platform values limited to: web, ios, android (unsupported values ignored)
- Image URLs must be valid
- Primary action requires valid link for each specified platform
- Each secondary action requires valid links for all platforms
- Mobile push features available only on selected pricing plans
- Supports template variables in title field
- Maximum 4 secondary actions per notification

## References

- [Official Documentation](https://help.ortto.com/a-847-using-api-to-send-transactional-push-notifications)
