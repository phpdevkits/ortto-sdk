# Get Person Custom Fields

Retrieves all custom fields defined for Person entities in Ortto.

## Endpoint

```
POST /v1/person/custom-field/get
```

## Authentication

Requires API key authentication via `X-Api-Key` header.

## Request Body

No request body parameters required. Send an empty body or empty JSON object `{}`.

## Response

| Field | Type | Description |
|-------|------|-------------|
| `fields` | array | Collection of custom field definitions |

### Field Object Structure

| Field | Type | Description |
|-------|------|-------------|
| `field` | object | Field definition object |
| `field.id` | string | Field ID in format `{type}:cm:{field-name}` |
| `field.name` | string | User-defined field name |
| `field.display_type` | string | Field type (text, integer, single_select, etc.) |
| `field.liquid_name` | string | Templating variable for the field (format: `people.custom.{field-name}`) |
| `field.dic_items` | array<string> | Available values for select-type fields (optional) |
| `tracked_value` | boolean | Whether field updates create custom activities (change tracking enabled) |

## Examples

### Example 1: Get All Person Custom Fields

**Request:**
```json
{}
```

**Response:**
```json
{
  "fields": [
    {
      "field": {
        "id": "str:cm:job-title",
        "name": "Job Title",
        "display_type": "text",
        "liquid_name": "people.custom.job-title"
      },
      "tracked_value": true
    },
    {
      "field": {
        "id": "int:cm:loyalty-points",
        "name": "Loyalty Points",
        "display_type": "integer",
        "liquid_name": "people.custom.loyalty-points"
      },
      "tracked_value": false
    },
    {
      "field": {
        "id": "bol:cm:is-vip",
        "name": "Is VIP",
        "display_type": "bool",
        "liquid_name": "people.custom.is-vip"
      },
      "tracked_value": true
    }
  ]
}
```

### Example 2: Single Select Field with Options

**Request:**
```json
{}
```

**Response:**
```json
{
  "fields": [
    {
      "field": {
        "id": "str:cm:customer-type",
        "name": "Customer Type",
        "display_type": "single_select",
        "liquid_name": "people.custom.customer-type",
        "dic_items": ["Enterprise", "SMB", "Startup", "Individual"]
      },
      "tracked_value": false
    }
  ]
}
```

## See Also

- [Create Person Custom Field](./custom-field-create.md)
- [Update Person Custom Field](./custom-field-update.md)
- [Merge People](./merge.md)
