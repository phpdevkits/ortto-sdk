# Retrieve Accounts by IDs

Retrieve one or more account records by their account IDs.

## Endpoint

**POST** `/v1/accounts/get-by-ids`

Regional endpoints:
- **Default (AP3)**: `https://api.ap3api.com/v1/accounts/get-by-ids`
- **Australia**: `https://api.au.ap3api.com/v1/accounts/get-by-ids`
- **Europe**: `https://api.eu.ap3api.com/v1/accounts/get-by-ids`

## Headers

| Header | Value | Required |
|--------|-------|----------|
| `X-Api-Key` | Your Ortto API key | Yes |
| `Content-Type` | `application/json` | Yes |

## Request Body

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `account_ids` | array of strings | Yes | Array of account IDs to retrieve (UUID format) |
| `fields` | array of strings | Yes | Account field IDs to retrieve (max 20 fields) |

## Response Format

Returns an **object** (not array) with accounts keyed by their account ID:

```json
{
  "accounts": {
    "account-id-123": {
      "id": "account-id-123",
      "fields": {
        "str:o:name": "Company Name",
        "str:o:website": "https://company.com"
      }
    },
    "account-id-456": {
      "id": "account-id-456",
      "fields": {
        "str:o:name": "Another Company",
        "str:o:website": "https://another.com"
      }
    }
  },
  "meta": {
    "total_contacts": 1000,
    "total_accounts": 250,
    "total_matches": 2,
    "total_subscribers": 500
  }
}
```

## Example Request

```json
{
  "account_ids": [
    "123e4567-e89b-12d3-a456-426614174000",
    "987fcdeb-51a2-43f7-9abc-123456789def"
  ],
  "fields": [
    "str:o:name",
    "str:o:website",
    "int:o:employees",
    "geo:o:country"
  ]
}
```

## Response Structure Notes

**Important**: Unlike the `/get` endpoint which returns an array of accounts, this endpoint returns an **object** where:
- Keys are the account IDs
- Values are the account records
- This allows for efficient lookups when you know the IDs

## Constraints

- **Maximum fields**: 20 per request
- **Account IDs**: Must be valid UUIDs
- **Missing accounts**: If an account ID doesn't exist, it simply won't appear in the response

## Use Cases

- **Direct lookup**: When you have specific account IDs from another source
- **Bulk retrieval**: Fetch multiple specific accounts efficiently
- **Relationship resolution**: Get account details for contacts that belong to accounts

## Tips

- **Validate UUIDs**: Ensure account IDs are valid UUID format before sending
- **Handle missing accounts**: Check if each requested ID exists in response
- **Limit fields**: Only request fields you need (20 max vs 500 max for `/get` endpoint)
- **Response iteration**: Remember to iterate over object keys, not array indices
