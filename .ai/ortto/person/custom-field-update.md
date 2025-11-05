# Update Person Custom Field

Updates an existing custom field for Person entities in Ortto. This endpoint can modify select field options and change tracking settings.

## Endpoint

```
PUT /v1/person/custom-field/update
```

## Authentication

Requires API key authentication via `X-Api-Key` header.

## Request Body

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `field_id` | string | Yes | Field ID to update (format: `{type}:cm:{field-name}`) |
| `replace_values` | array<string> | No | Removes all existing options and replaces with new values |
| `add_values` | array<string> | No | Appends new values to existing options |
| `remove_values` | array<string> | No | Removes specified values from existing options |
| `track_changes` | boolean | No | Enable/disable field change tracking |

### Operation Priority

If multiple value modification parameters are provided, they are processed in this priority order:

1. **`replace_values`** - If present, executes and ignores `add_values` and `remove_values`
2. **`add_values`** - If `replace_values` is absent; ignores `remove_values` if present
3. **`remove_values`** - Only processed if both `replace_values` and `add_values` are absent

## Response

| Field | Type | Description |
|-------|------|-------------|
| `field_id` | string | Field ID that was updated |
| `values` | array<string> | Current options for the field (only for select types) |
| `track_changes` | boolean | Current change tracking status |

## Constraints

- **Value modifications only apply to `single_select` or `multi_select` fields**
- Multi-select fields **cannot enable change tracking**
- Field `name` and `type` cannot be changed after creation

## Examples

### Example 1: Replace Select Field Options

**Request:**
```json
{
  "field_id": "str:cm:customer-type",
  "replace_values": ["Enterprise", "Mid-Market", "SMB", "Startup"]
}
```

**Response:**
```json
{
  "field_id": "str:cm:customer-type",
  "values": ["Enterprise", "Mid-Market", "SMB", "Startup"],
  "track_changes": false
}
```

### Example 2: Enable Change Tracking

**Request:**
```json
{
  "field_id": "str:cm:job-title",
  "track_changes": true
}
```

**Response:**
```json
{
  "field_id": "str:cm:job-title",
  "track_changes": true
}
```

## See Also

- [Create Person Custom Field](./custom-field-create.md)
- [Get Person Custom Fields](./custom-field-get.md)
- [Merge People](./merge.md)
