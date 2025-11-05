# Retrieve Accounts (GET with Filters)

Retrieve one or more account (organization) records with filtering, sorting, and pagination.

## Endpoint

**POST** `/v1/accounts/get`

Regional endpoints:
- **Default (AP3)**: `https://api.ap3api.com/v1/accounts/get`
- **Australia**: `https://api.au.ap3api.com/v1/accounts/get`
- **Europe**: `https://api.eu.ap3api.com/v1/accounts/get`

## Headers

| Header | Value | Required |
|--------|-------|----------|
| `X-Api-Key` | Your Ortto API key | Yes |
| `Content-Type` | `application/json` | Yes |

## Request Body

| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| `limit` | integer | No | 50 | Page size for pagination (1-500) |
| `offset` | integer | No | 0 | Starting position for pagination |
| `cursor_id` | string (UUID) | No | - | For cursor-based pagination |
| `sort_by_field_id` | string | No | - | Field ID to sort results by |
| `sort_order` | string | No | desc | Sort direction: `asc` or `desc` |
| `fields` | array of strings | No | - | Account field IDs to retrieve |
| `filter` | object | No | - | Filtering conditions |
| `q` | string | No | - | Simple text search by account name |
| `type` | string | No | "" | Filter by type: `""` (all), `"account"`, or `"archived_account"` |
| `inclusion_ids` | array of strings | No | - | Specific account IDs to include |
| `exclusion_ids` | array of strings | No | - | Specific account IDs to exclude |

## Filter Operators

Common filter operators:
- `$has_any_value` - Field has any non-empty value
- `$str::contains` - String field contains value
- `$str::eq` - String exact match
- `$int::gt` / `$int::lt` - Integer greater/less than
- `$and` / `$or` - Combine multiple conditions

## Response Format

```json
{
  "accounts": [
    {
      "id": "account-id-123",
      "fields": {
        "str:o:name": "Company Name",
        "int:o:employees": 100
      }
    }
  ],
  "meta": {
    "total_contacts": 1000,
    "total_accounts": 250,
    "total_matches": 10,
    "total_subscribers": 500
  },
  "offset": 0,
  "next_offset": 50,
  "cursor_id": "uuid-here",
  "has_more": true
}
```

## Example: Basic Request

```json
{
  "limit": 100,
  "sort_by_field_id": "str:o:name",
  "sort_order": "asc",
  "offset": 0,
  "fields": ["str:o:name", "int:o:employees", "str:o:industry"]
}
```

## Example: Filtered Request

```json
{
  "limit": 50,
  "fields": ["str:o:name", "geo:o:country"],
  "filter": {
    "$has_any_value": {
      "field_id": "geo:o:country"
    }
  }
}
```

## Example: Search by Name

```json
{
  "q": "Acme",
  "fields": ["str:o:name", "str:o:website"],
  "limit": 20
}
```

## Example: Complex Filter

```json
{
  "limit": 100,
  "fields": ["str:o:name", "int:o:employees"],
  "filter": {
    "$and": [
      {
        "$int::gt": {
          "field_id": "int:o:employees",
          "value": 50
        }
      },
      {
        "$has_any_value": {
          "field_id": "str:o:industry"
        }
      }
    ]
  }
}
```

## Pagination

### Offset-based Pagination
Use `offset` and `limit`:
```json
{
  "offset": 0,
  "limit": 50
}
```

Check `has_more` in response and use `next_offset` for subsequent requests.

### Cursor-based Pagination
Use `cursor_id` from previous response:
```json
{
  "cursor_id": "uuid-from-previous-response",
  "limit": 50
}
```

## Tips

- **Copy complex filters from browser**: Create filters in Ortto's UI, inspect network requests in browser dev tools, copy the filter object
- **Start simple**: Use basic filters first, then add complexity
- **Use cursor pagination**: More efficient for large datasets than offset pagination
- **Specify fields**: Only request fields you need to reduce response size

## Notes

- Maximum limit is 500 accounts per request
- Archived accounts require `type: "archived_account"`
- Empty `type` returns all non-archived accounts
- Some filter parameters shown in browser may be optional and can be excluded for cleaner API calls
