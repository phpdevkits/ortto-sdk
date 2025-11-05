# Create Account Custom Field

Creates a new custom field for Account (Organization) entities in Ortto.

## Endpoint

```
POST /v1/accounts/custom-field/create
```

## Authentication

Requires API key authentication via `X-Api-Key` header.

## Request Body

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `type` | string | Yes | Field type code (same types as Person fields) |
| `name` | string | Yes | Field name (must be unique) |
| `values` | array<string> | Conditional | Required for `single_select` and `multi_select` types |

**Note:** Account custom fields do NOT support the `track_changes` parameter. Change tracking is only available for Person fields.

## Response

| Field | Type | Description |
|-------|------|-------------|
| `name` | string | Field name from request |
| `field_id` | string | Generated field ID in format `{type}:oc:{field-name-kebab-case}` |
| `display_type` | string | Field type from request |
| `values` | array<string> | Options array (only for select types) |

## Notes

1. **Field ID Format**: Uses `:oc:` namespace (Organization Custom) instead of `:cm:` (Person Custom)
2. **No Change Tracking**: Account fields do not support `track_changes` parameter

## See Also

- [Update Account Custom Field](./custom-field-update.md)
- [Get Account Custom Fields](./custom-field-get.md)
- [Create Person Custom Field](../person/custom-field-create.md)
