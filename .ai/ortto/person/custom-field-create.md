# Create Person Custom Field

Creates a new custom field for Person entities in Ortto.

## Endpoint

```
POST /v1/person/custom-field/create
```

## Authentication

Requires API key authentication via `X-Api-Key` header.

## Request Body

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `type` | string | Yes | Field type code (see Field Types below) |
| `name` | string | Yes | Field name (must be unique) |
| `values` | array<string> | Conditional | Required for `single_select` and `multi_select` types |
| `track_changes` | boolean | No | Enable field change tracking (default: false). Cannot be enabled for `multi_select` type |

### Field Types

| Type Code | Description | Field ID Prefix | Notes |
|-----------|-------------|-----------------|-------|
| `text` | Plain text (≤500 chars) | `str:` | - |
| `large_text` | Extended text (>500 chars) | `str:` | - |
| `integer` | Whole numbers | `int:` | - |
| `decimal` | Floating-point numbers | `dec:` | Up to 2 decimal places |
| `currency` | Decimal with currency symbol | `cur:` | Uses workspace default currency |
| `price` | Decimal with ISO currency codes | `cur:` | Allows per-value currency specification |
| `date` | Day/month/year | `dat:` | Date only, no time |
| `time` | Timestamp | `tim:` | Date + time |
| `bool` | True/false values | `bol:` | Boolean field |
| `phone` | Phone numbers | `phn:` | Local/international format |
| `single_select` | Single choice dropdown | `str:` | Requires `values` array |
| `multi_select` | Multiple choice dropdown | `str:` | Requires `values` array. Cannot track changes |
| `link` | URL/webpage | `lnk:` | Web links |
| `object` | JSON object | `obj:` | Max 15,000 bytes |

**Note:** The `aggregate` field type is not supported when creating custom fields.

## Response

| Field | Type | Description |
|-------|------|-------------|
| `name` | string | Field name from request |
| `field_id` | string | Generated field ID in format `{type}:cm:{field-name-kebab-case}` |
| `display_type` | string | Field type from request |
| `values` | array<string> | Options array (only for select types) |
| `track_changes` | boolean | Change tracking status |

## Examples

### Example 1: Create Text Field

**Request:**
```json
{
  "type": "text",
  "name": "Job Title",
  "track_changes": true
}
```

**Response:**
```json
{
  "name": "Job Title",
  "field_id": "str:cm:job-title",
  "display_type": "text",
  "track_changes": true
}
```

### Example 2: Create Single Select Field

**Request:**
```json
{
  "type": "single_select",
  "name": "Customer Type",
  "values": ["Enterprise", "SMB", "Startup", "Individual"],
  "track_changes": false
}
```

**Response:**
```json
{
  "name": "Customer Type",
  "field_id": "str:cm:customer-type",
  "display_type": "single_select",
  "values": ["Enterprise", "SMB", "Startup", "Individual"],
  "track_changes": false
}
```

### Example 3: Create Boolean Field

**Request:**
```json
{
  "type": "bool",
  "name": "Is VIP",
  "track_changes": true
}
```

**Response:**
```json
{
  "name": "Is VIP",
  "field_id": "bol:cm:is-vip",
  "display_type": "bool",
  "track_changes": true
}
```

### Example 4: Create Integer Field

**Request:**
```json
{
  "type": "integer",
  "name": "Loyalty Points"
}
```

**Response:**
```json
{
  "name": "Loyalty Points",
  "field_id": "int:cm:loyalty-points",
  "display_type": "integer",
  "track_changes": false
}
```

## Constraints

- **Maximum 100 custom fields** per Ortto account
- Field names must be **unique** (duplicates are rejected)
- Field names are automatically converted to **kebab-case** for field IDs
- `multi_select` fields **cannot enable change tracking**
- `single_select` and `multi_select` types **require** the `values` array

## Error Responses

### 400 Bad Request
```json
{
  "error": "Duplicate field name"
}
```

### 401 Unauthorized
```json
{
  "error": "Invalid API key"
}
```

### 403 Forbidden
```json
{
  "error": "Maximum custom field limit reached (100)"
}
```

## Notes

1. **Field ID Format**: The generated field_id follows the pattern `{type-prefix}:cm:{field-name}` where:
   - `type-prefix` is determined by the field type (e.g., `str`, `bol`, `int`)
   - `cm` indicates this is a custom field (Person)
   - `field-name` is the kebab-case version of the name

2. **Change Tracking**: Only available for Person fields (not Account fields). Tracks field value changes over time.

3. **Select Field Values**: Once created, select field values can be modified using the Update endpoint with `add`, `remove`, or `replace` operations.

4. **Using Custom Fields**: After creation, use the `field_id` when merging or retrieving people:
   ```json
   {
     "people": [
       {
         "fields": {
           "str::email": "user@example.com",
           "str:cm:job-title": "Senior Developer"
         }
       }
     ],
     "merge_by": ["str::email"]
   }
   ```

## See Also

- [Update Person Custom Field](./custom-field-update.md)
- [Get Person Custom Fields](./custom-field-get.md)
- [Merge People](./merge.md)
- [Supported Field Data Types](https://help.ortto.com/a-702-supported-field-data-types)
