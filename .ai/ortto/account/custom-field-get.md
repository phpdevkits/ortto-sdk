# Get Account Custom Fields

Retrieves all custom fields defined for Account (Organization) entities in Ortto.

## Endpoint

```
POST /v1/accounts/custom-field/get
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

**Note:** Account fields have a flat structure (no nested `field` property) and no `tracked_value` property.

| Field | Type | Description |
|-------|------|-------------|
| `id` | string | Field ID in format `{type}:oc:{field-name}` |
| `name` | string | User-defined field name |
| `display_type` | string | Field type |
| `liquid_name` | string | Templating variable (format: `account.custom.{field-name}`) |
| `dic_items` | array<string> | Available values for select-type fields (optional) |

## See Also

- [Create Account Custom Field](./custom-field-create.md)
- [Update Account Custom Field](./custom-field-update.md)
- [Get Person Custom Fields](../person/custom-field-get.md)
