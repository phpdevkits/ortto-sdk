# Create a custom activity definition (create)

The create endpoint of the activity definitions entity is used to create custom activity definitions in your Ortto account's customer data platform (CDP).

Activity definitions define the schema and behavior of custom activity types before you can create activity events using them.

## HTTP method and request resource

```
POST https://api.ap3api.com/v1/definitions/activity/create
```

> **NOTE:** Ortto customers who have their instance region set to Australia or Europe will need to use specific service endpoints relative to the region:
>
> - Australia: `https://api.au.ap3api.com/v1/definitions/activity/create`
> - Europe: `https://api.eu.ap3api.com/v1/definitions/activity/create`
>
> All other Ortto users will use the default service endpoint (`https://api.ap3api.com/`).

## Path and query parameters

This endpoint takes no additional path and/or query parameters.

## Headers

This endpoint requires a custom API key and content type (`application/json` for the request body) in the header of the request:

- `X-Api-Key: CUSTOM-PRIVATE-API-KEY`
- `Content-Type: application/json`

## Request body

The request body consists of a JSON object defining the activity schema and behavior.

### Example request body - Basic activity definition

```json
{
  "name": "product-purchase",
  "icon_id": "cart-icon",
  "track_conversion_value": true,
  "touch": true,
  "filterable": true,
  "visible_in_feeds": true,
  "display_style": {
    "type": "activity_attribute",
    "attribute": "product-name"
  },
  "attributes": [
    {
      "name": "product-name",
      "display_type": "text"
    },
    {
      "name": "quantity",
      "display_type": "integer"
    }
  ]
}
```

### Example request body - With field mapping

```json
{
  "name": "support-ticket",
  "icon_id": "help-icon",
  "track_conversion_value": false,
  "touch": true,
  "filterable": true,
  "visible_in_feeds": true,
  "display_style": {
    "type": "activity_template",
    "template": "Ticket {{ticket-id}}: {{ticket-subject}}"
  },
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
    }
  ]
}
```

### Example request body - Event registration

```json
{
  "name": "event-registration",
  "icon_id": "calendar-icon",
  "track_conversion_value": false,
  "touch": true,
  "filterable": true,
  "visible_in_feeds": true,
  "display_style": {
    "type": "activity_attribute",
    "attribute": "event-name"
  },
  "attributes": [
    {
      "name": "event-name",
      "display_type": "text"
    },
    {
      "name": "event-date",
      "display_type": "date"
    },
    {
      "name": "registered-at",
      "display_type": "time",
      "field_id": "do-not-map"
    }
  ]
}
```

### Valid request body elements

| Element | Type | Description |
|---------|------|-------------|
| **name** | string | **Required.** Unique identifier for the activity (no duplicates allowed). Used in activity_id as `act:cm:{name}` |
| **icon_id** | string | **Required.** Visual icon ID from predefined set (see icon options below) |
| **track_conversion_value** | boolean | **Required.** Enable tracking conversion value (revenue) for this activity. Set to `true` to use `int::v` field |
| **touch** | boolean | **Required.** Update contact's first/last seen timestamps when activity occurs. Recommended: `true` |
| **filterable** | boolean | **Required.** Allow filtering and reporting on this activity in Ortto UI. Recommended: `true` |
| **visible_in_feeds** | boolean | **Required.** Display activity in contact activity feeds. Set to `false` for background tracking |
| **display_style** | object | **Required.** Controls how activity appears in feeds. See display style options below |
| **attributes** | array of objects | **Required.** Activity-specific data fields. Each attribute defines a data point captured with this activity |

### Display style options

The `display_style` object determines how the activity renders in activity feeds:

**Option 1: Activity name only**
```json
{
  "type": "activity"
}
```
Displays just the activity name (e.g., "Product Purchase")

**Option 2: Activity with single attribute**
```json
{
  "type": "activity_attribute",
  "attribute": "product-name"
}
```
Displays: "Product Purchase: Premium Plan"

**Option 3: Custom template**
```json
{
  "type": "activity_template",
  "template": "Purchased {{product-name}} (qty: {{quantity}})"
}
```
Displays: "Purchased Premium Plan (qty: 2)"

Uses `{{attribute-name}}` syntax to reference attribute values.

### Attribute object elements

| Element | Type | Description |
|---------|------|-------------|
| **name** | string | **Required.** Attribute identifier (used in activity data as `str:cm:{name}`, `int:cm:{name}`, etc.) |
| **display_type** | string | **Required.** Data type for this attribute. See display types below |
| **field_id** | string | Optional. CDP field to map this attribute to. Use `"do-not-map"` to prevent mapping. If omitted, Ortto may auto-map to existing fields |

### Display types

| Type | Description | Example Use Case |
|------|-------------|------------------|
| `text` | Short text string | Product name, order ID |
| `large_text` | Long text/multi-line | Comments, descriptions |
| `email` | Email address | Recipient email |
| `phone` | Phone number | Contact phone |
| `link` | URL | Product page link |
| `integer` | Whole number | Quantity, count |
| `decimal` | Decimal number | Rating, percentage |
| `currency` | Money value | Price (stored as cents) |
| `date` | Date only | Event date, deadline |
| `time` | Date and time | Registration timestamp |
| `bool` | True/false | Is member, has account |
| `single_select` | Single choice | Priority level, status |
| `multi_select` | Multiple choices | Tags, categories |
| `object` | JSON object | Complex nested data |

### Icon options

Available icon IDs (14 options):

| Icon ID | Description |
|---------|-------------|
| `calendar-icon` | Calendar/event |
| `email-icon` | Email/message |
| `phone-icon` | Phone/call |
| `download-icon` | Download |
| `upload-icon` | Upload |
| `cart-icon` | Shopping cart/purchase |
| `help-icon` | Help/support |
| `user-icon` | User/profile |
| `settings-icon` | Settings/configuration |
| `star-icon` | Favorite/rating |
| `tag-icon` | Tag/label |
| `link-icon` | Link/connection |
| `file-icon` | File/document |
| `globe-icon` | Website/global |

## Response structure

### Example response - Successful creation

```json
{
  "custom_activity": {
    "activity_field_id": "act:cm:product-purchase",
    "name": "Product Purchase",
    "state": "awaiting_implementation",
    "icon_id": "cart-icon",
    "track_conversion_value": true,
    "touch": true,
    "filterable": true,
    "visible_in_feeds": true,
    "display_mode": {
      "type": "activity_attribute",
      "attribute": "product-name"
    },
    "attributes": [
      {
        "name": "product-name",
        "display_type": "text",
        "field_id": "str:cm:product-name",
        "liquid_name": "{{activity.cm.product_purchase.product_name}}"
      },
      {
        "name": "quantity",
        "display_type": "integer",
        "field_id": "int:cm:quantity",
        "liquid_name": "{{activity.cm.product_purchase.quantity}}"
      }
    ],
    "created_at": "2025-11-04T19:30:00Z",
    "edited_at": "2025-11-04T19:30:00Z"
  }
}
```

### Response fields

| Field | Type | Description |
|-------|------|-------------|
| **custom_activity** | object | The created activity definition |
| → **activity_field_id** | string | Generated field ID in format `act:cm:{name}`. Use this when creating activity events |
| → **name** | string | Display name of the activity |
| → **state** | string | `"awaiting_implementation"` until first activity event created, then `"active"` |
| → **icon_id** | string | Icon identifier from request |
| → **track_conversion_value** | boolean | Whether conversion tracking is enabled |
| → **touch** | boolean | Whether to update contact timestamps |
| → **filterable** | boolean | Whether activity can be filtered in UI |
| → **visible_in_feeds** | boolean | Whether activity shows in feeds |
| → **display_mode** | object | Display style configuration |
| → **attributes** | array | Attribute definitions with generated field IDs and liquid syntax |
| → **created_at** | string | ISO 8601 timestamp of creation |
| → **edited_at** | string | ISO 8601 timestamp of last edit |

## Important notes

### Activity state lifecycle

1. **Created** - Definition exists but no events yet (`state: "awaiting_implementation"`)
2. **Active** - First activity event created via `/v1/activities/create`
3. Activity remains active and can accumulate events

### Activity field ID format

After creation, your activity is identified by: `act:cm:{name}`

Example:
- Name: `product-purchase`
- Field ID: `act:cm:product-purchase`

Use this field ID when creating activity events.

### Attribute field mapping

**Mapped attributes** (`field_id` specified):
- Updates the CDP field when activity occurs
- Example: `field_id: "str:cm:last-product"` updates contact's "Last Product" field

**Unmapped attributes** (`field_id: "do-not-map"`):
- Stored only with the activity event
- Doesn't update contact record
- Use for event-specific data that shouldn't overwrite contact fields

**Auto-mapped attributes** (`field_id` omitted):
- Ortto may automatically map to existing CDP fields with matching names
- Specify `"do-not-map"` to prevent auto-mapping

### Plan limits

The number of custom activities you can create depends on your Ortto plan. Check your plan limits in Ortto settings.

### Using the activity definition

After creating the definition, create activity events using `/v1/activities/create`:

```json
{
  "activities": [
    {
      "activity_id": "act:cm:product-purchase",
      "attributes": {
        "str:cm:product-name": "Premium Plan",
        "int:cm:quantity": 2
      },
      "fields": {
        "str::email": "customer@example.com"
      },
      "merge_by": ["str::email"]
    }
  ]
}
```

## Error responses

**400 Bad Request** - Invalid parameters or duplicate activity name

**401 Unauthorized** - Invalid or missing API key

**403 Forbidden** - Plan limit reached for custom activities

## Common use cases

### E-commerce activity
Track purchases with product details:
```json
{
  "name": "purchase",
  "track_conversion_value": true,
  "attributes": [
    {"name": "product-name", "display_type": "text"},
    {"name": "product-sku", "display_type": "text"},
    {"name": "quantity", "display_type": "integer"}
  ]
}
```

### Content engagement
Track content views:
```json
{
  "name": "content-view",
  "track_conversion_value": false,
  "attributes": [
    {"name": "content-title", "display_type": "text"},
    {"name": "content-type", "display_type": "single_select"},
    {"name": "time-spent", "display_type": "integer"}
  ]
}
```

### Customer support
Track support interactions:
```json
{
  "name": "support-interaction",
  "track_conversion_value": false,
  "attributes": [
    {"name": "ticket-id", "display_type": "text"},
    {"name": "subject", "display_type": "text"},
    {"name": "priority", "display_type": "single_select"},
    {"name": "resolved", "display_type": "bool"}
  ]
}
```
