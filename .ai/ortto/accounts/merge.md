# Account/Organization Merge Endpoint

Create or update one or more organizations (accounts) in Ortto.

## Endpoint

**POST** `/v1/accounts/merge`

Regional endpoints:
- **Default (AP3)**: `https://api.ap3api.com/v1/accounts/merge`
- **Australia**: `https://api.au.ap3api.com/v1/accounts/merge`
- **Europe**: `https://api.eu.ap3api.com/v1/accounts/merge`

## Headers

| Header | Value | Required |
|--------|-------|----------|
| `X-Api-Key` | Your Ortto API key | Yes |
| `Content-Type` | `application/json` | Yes |

## Request Body

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `accounts` | array | Yes | Array of account records (1-100 max) |
| `merge_by` | array of strings | Yes | Field IDs specifying which account fields determine create vs. update logic. Unlike person endpoints, no defaults exist. |
| `merge_strategy` | integer | No | Controls how existing values merge: `1` = Append Only, `2` = Overwrite Existing (default), `3` = Ignore |
| `find_strategy` | integer | No | For dual merge fields: `0` = Any match (default), `1` = First field only |
| `async` | boolean | No | Queue ingestion when true; receive immediate confirmation |

### Account Object Structure

Each account object in the `accounts` array contains:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `fields` | object | Yes | Account field data using field ID format |
| `tags` | array of strings | No | Tags to apply to the account |
| `unset_tags` | array of strings | No | Tags to remove from the account |

### Field ID Format

Account fields use the format: `type:namespace:field-name`

**Built-in fields (namespace `o`):**
- `str:o:name` - Organization name
- `int:o:employees` - Number of employees
- `str:o:industry` - Industry
- `str:o:website` - Website URL
- `geo:o:city` - City (requires `name` member)
- `str:o:address` - Street address
- `geo:o:region` - Region/State (requires `name` member)
- `geo:o:country` - Country (requires `name` member)
- `str:o:postal` - Postal code
- `str:o:source` - Source

**Custom fields (namespace `oc`):** Up to 100 custom fields supported

### Field Types

- `str` - String value
- `int` - Integer value
- `geo` - Geographical object (requires `name` member)

## Merge Behavior

- **Mandatory merge_by:** Unlike person endpoints, no defaults exist; at least one field required
- **Account ID constraint:** If merging by an account's ID, then `accounts_id` must be the only field in `merge_by`
- **Null handling:** Set values to `null` to exclude from searches; use `0` or `""` for empty inclusions
- **Tags:** Applied regardless of create/update status; can use `unset_tags` to remove existing tags

### Merge Strategies

| Value | Name | Behavior |
|-------|------|----------|
| `1` | Append Only | Add new values but don't overwrite existing ones |
| `2` | Overwrite Existing | Replace existing values with new ones (default) |
| `3` | Ignore | Keep existing values, ignore new ones |

### Find Strategies

| Value | Name | Behavior |
|-------|------|----------|
| `0` | Any | Match if any of the merge_by fields match (default) |
| `1` | First Only | Match only if first field matches; check second field only if first is empty |

## Request Example

```json
{
  "accounts": [
    {
      "fields": {
        "str:o:name": "Acme Corporation",
        "str:o:website": "https://acme.com",
        "int:o:employees": 150,
        "str:o:industry": "Technology",
        "geo:o:city": {"name": "San Francisco"},
        "geo:o:region": {"name": "California"},
        "geo:o:country": {"name": "United States"}
      },
      "tags": ["enterprise", "tech"]
    },
    {
      "fields": {
        "str:o:name": "Beta Inc",
        "str:o:website": "https://beta.com"
      },
      "tags": ["startup"]
    }
  ],
  "merge_by": ["str:o:website"],
  "merge_strategy": 2,
  "find_strategy": 0,
  "async": false
}
```

## Response Example

```json
{
  "accounts": [
    {
      "status": "created",
      "account_id": "507f1f77bcf86cd799439011"
    },
    {
      "status": "merged",
      "account_id": "507f1f77bcf86cd799439012"
    }
  ]
}
```

## Response Status Values

- `"created"` - New account was created
- `"merged"` - Existing account was updated

## Notes

- Maximum 100 accounts per request
- Custom fields must be created in Ortto CDP before use
- Geographical fields require an object with a `name` member
- Tags are applied regardless of whether the account was created or merged
