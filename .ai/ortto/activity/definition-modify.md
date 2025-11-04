# Modify a custom activity definition (modify)

The modify endpoint of the activity definitions entity is used to update existing custom activity definitions in your Ortto account's customer data platform (CDP).

**Note:** The API documentation specifies this as a PUT endpoint, though the semantic operation is a PATCH (partial update).

## HTTP method and request resource

```
PUT https://api.ap3api.com/v1/definitions/activity/modify
```

> **NOTE:** Ortto customers who have their instance region set to Australia or Europe will need to use specific service endpoints relative to the region:
>
> - Australia: `https://api.au.ap3api.com/v1/definitions/activity/modify`
> - Europe: `https://api.eu.ap3api.com/v1/definitions/activity/modify`
>
> All other Ortto users will use the default service endpoint (`https://api.ap3api.com/`).

## Path and query parameters

This endpoint takes no additional path and/or query parameters.

## Headers

This endpoint requires a custom API key and content type (`application/json` for the request body) in the header of the request:

- `X-Api-Key: CUSTOM-PRIVATE-API-KEY`
- `Content-Type: application/json`

## Request body

The request body consists of a JSON object with the fields to modify. The `name` field identifies which activity to modify.

### Example request body - Update display and icon

```json
{
  "name": "product-purchase",
  "icon_id": "star-icon",
  "display_style": {
    "type": "activity_template",
    "template": "Purchased {{product-name}} for ${{price}}"
  }
}
```

### Example request body - Add new attributes

```json
{
  "name": "support-ticket",
  "attributes": [
    {
      "name": "ticket-id",
      "display_type": "text",
      "field_id": "do-not-map"
    },
    {
      "name": "ticket-subject",
      "display_type": "text",
      "field_id": "str:cm:last-ticket-subject"
    },
    {
      "name": "priority",
      "display_type": "single_select",
      "field_id": "do-not-map"
    },
    {
      "name": "resolution-time",
      "display_type": "integer",
      "field_id": "int:cm:avg-resolution-time"
    }
  ]
}
```

### Example request body - Update visibility settings

```json
{
  "name": "background-sync",
  "visible_in_feeds": false,
  "filterable": true,
  "touch": false
}
```

### Valid request body elements

| Element | Type | Description |
|---------|------|-------------|
| **name** | string | **Required.** The name of the activity definition to modify. This identifies which activity to update. Duplicate names not allowed |
| **icon_id** | string | Optional. Update the visual icon ID from predefined set |
| **track_conversion_value** | boolean | Optional. Enable/disable conversion value (revenue) tracking |
| **touch** | boolean | Optional. Update whether to update contact's first/last seen timestamps |
| **filterable** | boolean | Optional. Update whether activity can be filtered/reported on in UI |
| **visible_in_feeds** | boolean | Optional. Update whether activity appears in contact feeds |
| **display_style** | object | Optional. Update how activity renders in feeds |
| **attributes** | array of objects | Optional. Update activity-specific data fields |

### Display style object

Controls how the activity appears in activity feeds:

| Field | Type | Description |
|-------|------|-------------|
| **type** | string | Display type: `"activity"`, `"activity_attribute"`, or `"activity_template"` |
| **attribute** | string | Required if type is `"activity_attribute"`. The attribute name to display |
| **template** | string | Required if type is `"activity_template"`. Custom template with `{{attribute-name}}` variables |

**Display type examples:**

```json
// Activity name only
{
  "type": "activity"
}

// Activity with single attribute
{
  "type": "activity_attribute",
  "attribute": "product-name"
}

// Custom template
{
  "type": "activity_template",
  "template": "Order {{order-id}}: {{product-name}} (qty: {{quantity}})"
}
```

### Attribute object elements

| Element | Type | Description |
|---------|------|-------------|
| **name** | string | **Required.** Attribute identifier |
| **display_type** | string | **Required.** Data type: text, large_text, email, phone, link, integer, decimal, currency, date, time, bool, single_select, multi_select, object |
| **field_id** | string | Optional. CDP field to map attribute to, or `"do-not-map"` to prevent mapping |

## Response structure

### Example response - Successful modification

```json
{
  "custom_activity": {
    "activity_field_id": "act:cm:product-purchase",
    "name": "Product Purchase",
    "state": "live",
    "icon_id": "star-icon",
    "track_conversion_value": true,
    "touch": true,
    "filterable": true,
    "visible_in_feeds": true,
    "display_mode": {
      "type": "activity_template",
      "template": "Purchased {{product-name}} for ${{price}}"
    },
    "attributes": [
      {
        "name": "product-name",
        "display_type": "text",
        "field_id": "str:cm:product-name",
        "liquid_name": "{{activity.cm.product_purchase.product_name}}"
      },
      {
        "name": "price",
        "display_type": "currency",
        "field_id": "int:cm:last-purchase-price",
        "liquid_name": "{{activity.cm.product_purchase.price}}"
      },
      {
        "name": "quantity",
        "display_type": "integer",
        "field_id": "int:cm:quantity",
        "liquid_name": "{{activity.cm.product_purchase.quantity}}"
      }
    ],
    "created_at": "2025-11-01T10:00:00Z",
    "edited_at": "2025-11-04T19:45:00Z"
  }
}
```

### Response fields

| Field | Type | Description |
|-------|------|-------------|
| **custom_activity** | object | The modified activity definition |
| → **activity_field_id** | string | Activity field ID (format: `act:cm:{name}`) |
| → **name** | string | Display name of the activity |
| → **state** | string | `"live"` if events exist, `"awaiting_implementation"` if no events yet |
| → **icon_id** | string | Current icon identifier |
| → **track_conversion_value** | boolean | Whether conversion tracking is enabled |
| → **touch** | boolean | Whether to update contact timestamps |
| → **filterable** | boolean | Whether activity can be filtered |
| → **visible_in_feeds** | boolean | Whether activity shows in feeds |
| → **display_mode** | object | Current display configuration |
| → **attributes** | array | Attribute definitions with field IDs and liquid syntax |
| → **created_at** | string | ISO 8601 timestamp of original creation |
| → **edited_at** | string | ISO 8601 timestamp of last modification |

## Important notes

### Identifying the activity to modify

The `name` field in the request body identifies which activity definition to modify. This should match the name used when creating the activity (not the `activity_field_id`).

**Important:** Duplicate names are not allowed across your account.

### Partial updates

You only need to include fields you want to change. Omitted fields retain their current values.

Example - Only update icon:
```json
{
  "name": "product-purchase",
  "icon_id": "cart-icon"
}
```

### Adding vs replacing attributes

When you include the `attributes` array, it **replaces** all existing attributes. To add a new attribute while keeping existing ones:

1. Retrieve current activity definition first
2. Include all existing attributes plus new ones in your modification request

### Field mapping constraints

Once an attribute is mapped to a CDP field (`field_id`), changing the mapping may affect:
- Historical data visualization
- Automation triggers
- Segment filters

Test carefully in a development environment before modifying production activities.

### Activity state

The `state` field indicates:
- `"awaiting_implementation"` - Activity defined but no events created yet
- `"live"` - Activity has received at least one event

You cannot change an activity back to "awaiting_implementation" once it's live.

## Error responses

**400 Bad Request** - Invalid parameters or name doesn't exist

**401 Unauthorized** - Invalid or missing API key

**404 Not Found** - Activity definition with specified name doesn't exist

**409 Conflict** - Attempting to rename to a name that already exists

## Common use cases

### Update activity appearance

Change how an activity displays in feeds:

```json
{
  "name": "purchase",
  "icon_id": "cart-icon",
  "display_style": {
    "type": "activity_template",
    "template": "💰 Purchased {{product-name}} - ${{price}}"
  }
}
```

### Enable conversion tracking

Add revenue tracking to an existing activity:

```json
{
  "name": "event-registration",
  "track_conversion_value": true
}
```

### Hide activity from feeds

Make an activity invisible while keeping it filterable:

```json
{
  "name": "background-sync",
  "visible_in_feeds": false,
  "filterable": true
}
```

### Add new tracking fields

Extend an activity with additional attributes:

```json
{
  "name": "content-view",
  "attributes": [
    {
      "name": "content-title",
      "display_type": "text",
      "field_id": "str:cm:last-content-viewed"
    },
    {
      "name": "content-type",
      "display_type": "single_select",
      "field_id": "do-not-map"
    },
    {
      "name": "time-spent",
      "display_type": "integer",
      "field_id": "int:cm:avg-time-on-content"
    },
    {
      "name": "completion-rate",
      "display_type": "decimal",
      "field_id": "do-not-map"
    }
  ]
}
```

## Related endpoints

- **POST /v1/definitions/activity/create** - Create new activity definition
- **DELETE /v1/definitions/activity/delete** - Archive activity definition
- **POST /v1/activities/create** - Create activity events

## Best practices

1. **Test first** - Modify in test environment before production
2. **Document changes** - Keep records of why modifications were made
3. **Check dependencies** - Review automations and segments using the activity
4. **Preserve attributes** - When adding attributes, include existing ones to avoid data loss
5. **Gradual rollout** - If changing display significantly, communicate to team members
6. **Version naming** - Consider versioning in activity names (e.g., "purchase-v2") for major changes
