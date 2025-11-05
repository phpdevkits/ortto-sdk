# Restore Archived Accounts

Restore one or more previously archived account records back to active status.

## Endpoint

**PUT** `/v1/organizations/restore`

**Note**: May also work with `/v1/accounts/restore` (Ortto renamed Organizations to Accounts in June 2025)

Regional endpoints:
- **Default (AP3)**: `https://api.ap3api.com/v1/organizations/restore`
- **Australia**: `https://api.au.ap3api.com/v1/organizations/restore`
- **Europe**: `https://api.eu.ap3api.com/v1/organizations/restore`

## Headers

| Header | Value | Required |
|--------|-------|----------|
| `X-Api-Key` | Your Ortto API key | Yes |
| `Content-Type` | `application/json` | Yes |

## Request Body

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `account_ids` | array of strings | Yes | Array of archived account IDs to restore (UUID format) |

## Response Format

```json
{
  "restored": 2,
  "not_found": 1
}
```

| Field | Type | Description |
|-------|------|-------------|
| `restored` | integer | Number of accounts successfully restored |
| `not_found` | integer | Number of account IDs that didn't exist or weren't archived |

## Example Request

```json
{
  "account_ids": [
    "123e4567-e89b-12d3-a456-426614174000",
    "987fcdeb-51a2-43f7-9abc-123456789def"
  ]
}
```

## Example Response

```json
{
  "restored": 2,
  "not_found": 0
}
```

## Behavior

### What Happens When Restoring

1. **Visibility**: Account becomes visible in normal `/get` queries again
2. **Status**: Account returns to active status
3. **Relationships**: All associated contacts remain linked (unchanged)
4. **Data**: All field values preserved from before archival
5. **Idempotency**: Restoring an already-active account is safe (no error)

### Finding Archived Accounts

Before restoring, you may need to find archived account IDs using `/get`:
```json
{
  "type": "archived_account",
  "fields": ["str:o:name"]
}
```

## Constraints

- **Must be archived**: Can only restore accounts that are currently archived
- **No validation errors**: Invalid or non-archived IDs counted in `not_found`, not errors
- **Bulk operations**: Can restore multiple accounts in one request

## Use Cases

- **Undo archive**: Reverse an accidental or premature archive
- **Reactivate**: Bring back previously inactive accounts
- **Data recovery**: Restore accounts that were archived but still needed
- **Workflow correction**: Fix incorrect archive operations

## Workflow: Archive ↔ Restore

```
Active Account
     ↓ (archive)
Archived Account
     ↓ (restore)
Active Account (fully restored)
```

## Error Handling

The `not_found` count includes:
- Account IDs that don't exist
- Account IDs that exist but are not archived (already active)
- Invalid UUID formats

## Tips

- **Check not_found**: Review `not_found` count to identify issues
- **Bulk restore**: More efficient than restoring one at a time
- **No data loss**: Restoring preserves all original data
- **Query first**: Use `/get` with `type: "archived_account"` to find accounts to restore
- **Test both URLs**: Try both `/organizations/restore` and `/accounts/restore` to see which works for your Ortto instance
- **Safe operation**: Restoring an already-active account won't cause errors
