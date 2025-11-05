# Update Account Custom Field

Updates an existing custom field for Account (Organization) entities in Ortto.

## Endpoint

```
PUT /v1/accounts/custom-field/update
```

## Authentication

Requires API key authentication via `X-Api-Key` header.

## Request Body

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `field_id` | string | Yes | Field ID to update (format: `{type}:oc:{field-name}`) |
| `replace_values` | array<string> | No | Removes all existing options and replaces with new values |
| `add_values` | array<string> | No | Appends new values to existing options |
| `remove_values` | array<string> | No | Removes specified values from existing options |

**Note:** Account custom fields do NOT support the `track_changes` parameter.

## Response

| Field | Type | Description |
|-------|------|-------------|
| `field_id` | string | Field ID that was updated |
| `values` | array<string> | Current options for the field (only for select types) |

## See Also

- [Create Account Custom Field](./custom-field-create.md)
- [Get Account Custom Fields](./custom-field-get.md)
- [Update Person Custom Field](../person/custom-field-update.md)
