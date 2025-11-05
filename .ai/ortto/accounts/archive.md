# Archive Accounts

Archive one or more account records. Archived accounts are hidden from normal queries but can be restored or permanently deleted.

## Endpoint

**PUT** `/v1/organizations/archive`

**Note**: May also work with `/v1/accounts/archive` (Ortto renamed Organizations to Accounts in June 2025)

Regional endpoints:
- **Default (AP3)**: `https://api.ap3api.com/v1/organizations/archive`
- **Australia**: `https://api.au.ap3api.com/v1/organizations/archive`
- **Europe**: `https://api.eu.ap3api.com/v1/organizations/archive`

## Headers

| Header | Value | Required |
|--------|-------|----------|
| `X-Api-Key` | Your Ortto API key | Yes |
| `Content-Type` | `application/json` | Yes |

## Request Body

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `account_ids` | array of strings | Yes | Array of account IDs to archive (UUID format) |

## Response Format

```json
{
  "archived": 3,
  "not_found": 0
}
```

| Field | Type | Description |
|-------|------|-------------|
| `archived` | integer | Number of accounts successfully archived |
| `not_found` | integer | Number of account IDs that didn't exist |

## Example Request

```json
{
  "account_ids": [
    "123e4567-e89b-12d3-a456-426614174000",
    "987fcdeb-51a2-43f7-9abc-123456789def",
    "456e7890-a12b-34c5-d678-901234567890"
  ]
}
```

## Example Response

```json
{
  "archived": 3,
  "not_found": 0
}
```

## Behavior

### What Happens When Archiving

1. **Visibility**: Account is hidden from normal `/get` queries
2. **Relationships**: Associated contacts remain linked
3. **Restoration**: Can be restored using `/restore` endpoint
4. **Deletion**: Must be archived before permanent deletion
5. **Idempotency**: Archiving an already-archived account is safe (no error)

### Retrieving Archived Accounts

To see archived accounts in `/get` queries, use:
```json
{
  "type": "archived_account"
}
```

## Constraints

- **Required first step**: Accounts must be archived before they can be deleted
- **No validation errors**: Invalid account IDs are counted in `not_found`, not errors
- **Bulk operations**: Can archive multiple accounts in one request

## Use Cases

- **Soft delete**: Remove accounts from active use without losing data
- **Compliance**: Hide inactive or outdated accounts
- **Data cleanup**: Prepare accounts for eventual deletion
- **Reversible removal**: Archive instead of delete to allow restoration

## Workflow: Archive → Restore OR Delete

```
Active Account
     ↓ (archive)
Archived Account
     ↓ (restore)          ↓ (delete)
Active Account    →   Permanently Deleted
```

## Tips

- **Archive before delete**: Always archive first, then delete if needed
- **Check not_found**: Review `not_found` count to identify invalid IDs
- **Bulk archive**: More efficient than archiving one at a time
- **Reversible**: Unlike delete, archive can be undone with restore
- **Test both URLs**: Try both `/organizations/archive` and `/accounts/archive` to see which works for your Ortto instance
