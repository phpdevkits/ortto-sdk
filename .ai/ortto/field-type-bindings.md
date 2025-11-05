# Ortto Field Type Bindings

This document maps Ortto's field types to their internal prefixes and API type codes.

## Field Type Prefixes (for Field IDs)

These prefixes are used in field IDs when referencing fields in API requests:

### Built-in Person Fields
- `str::` - String/Text fields (e.g., `str::email`, `str::first`, `str::name`)
- `bol::` - Boolean fields (e.g., `bol::p` for email permission, `bol::sp` for SMS permission)
- `int::` - Integer/Number fields
- `dec::` - Decimal number fields
- `phn::` - Phone number fields (e.g., `phn::phone`)
- `geo::` - Geographical fields (e.g., `geo::city`, `geo::country`)
- `dtz::` - Date/Time fields (e.g., `dtz::b` for birthdate)
- `cur::` - Currency fields
- `tim::` - Time-only fields
- `dat::` - Date-only fields
- `lnk::` - Link/URL fields
- `obj::` - Object/JSON fields

### Custom Person Fields
Format: `{prefix}:cm:{field-name}`
- `str:cm:` - Custom text fields (e.g., `str:cm:job-title`)
- `bol:cm:` - Custom boolean fields (e.g., `bol:cm:is-vip`)
- `int:cm:` - Custom integer fields (e.g., `int:cm:loyalty-points`)
- `dec:cm:` - Custom decimal fields (e.g., `dec:cm:rating`)
- `cur:cm:` - Custom currency fields (e.g., `cur:cm:lifetime-value`)
- `phn:cm:` - Custom phone fields (e.g., `phn:cm:work-phone`)
- `dat:cm:` - Custom date fields (e.g., `dat:cm:anniversary`)
- `tim:cm:` - Custom time fields (e.g., `tim:cm:preferred-call-time`)
- `lnk:cm:` - Custom link fields (e.g., `lnk:cm:linkedin-profile`)
- `obj:cm:` - Custom object fields (e.g., `obj:cm:metadata`)

### Built-in Account Fields (Organizations)
- `str:o:` - String fields (e.g., `str:o:name`, `str:o:website`)
- `int:o:` - Integer fields (e.g., `int:o:employees`)
- `geo:o:` - Geographical fields

### Custom Account Fields (Organizations)
Format: `{prefix}:oc:{field-name}`
- `str:oc:` - Custom text fields
- `int:oc:` - Custom integer fields
- `dec:oc:` - Custom decimal fields
- `cur:oc:` - Custom currency fields
- etc.

## API Type Codes (for Create/Update Custom Field)

These are the type values used when creating or updating custom fields via the API:

| Type Code | Description | Field ID Prefix | Notes |
|-----------|-------------|-----------------|-------|
| `text` | Plain text (≤500 chars) | `str:` | Default text field |
| `large_text` | Extended text (>500 chars) | `str:` | Large text field |
| `integer` | Whole numbers | `int:` | Integers only |
| `decimal` | Floating-point numbers | `dec:` | Up to 2 decimal places |
| `currency` | Decimal with currency symbol | `cur:` | Uses workspace currency |
| `price` | Decimal with ISO currency codes | `cur:` | Allows per-value currency |
| `date` | Day/month/year | `dat:` | Date only, no time |
| `time` | Timestamp | `tim:` | Date + time |
| `bool` | True/false values | `bol:` | Boolean field |
| `phone` | Phone numbers | `phn:` | Local/international format |
| `single_select` | Single choice dropdown | `str:` | Stored as string |
| `multi_select` | Multiple choice dropdown | `str:` | Cannot track changes |
| `link` | URL/webpage | `lnk:` | Web links |
| `object` | JSON object | `obj:` | Max 15,000 bytes |

## Examples

### Creating Custom Field (API Request)
```json
{
  "name": "Job Title",
  "type": "text",
  "scope": "person"
}
```
**Response Field ID**: `str:cm:job-title`

### Using Custom Field in Merge Request
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

## Special Notes

1. **Namespace Identifiers**:
   - `:cm:` = Custom fields (Person)
   - `:oc:` = Custom fields (Organizations/Accounts)
   - `:sf:` = Salesforce integration fields
   - `:sh:` = Shopify integration fields
   - `:st:` = Stripe integration fields

2. **Field Name Conversion**:
   - API automatically converts field names to kebab-case
   - Example: "Job Title" → `str:cm:job-title`

3. **Type Prefix Inference**:
   - The prefix is determined by the `type` value
   - Example: `type: "text"` → prefix `str:`
   - Example: `type: "bool"` → prefix `bol:`

4. **Select Fields**:
   - Both `single_select` and `multi_select` use `str:` prefix
   - Values are stored as strings

5. **Change Tracking**:
   - Only Person fields support change tracking
   - Multi-select fields cannot enable change tracking

## References

- [Ortto API Documentation](https://help.ortto.com/developer/latest/)
- [Supported Field Data Types](https://help.ortto.com/a-702-supported-field-data-types)
- [Custom Field API](https://help.ortto.com/a-264-custom-field)
