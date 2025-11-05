# Delete Archived Accounts

Permanently delete one or more archived account records. This operation is **irreversible**.

## Endpoint

**DELETE** `/v1/organizations/delete`

**Note**: May also work with `/v1/accounts/delete` (Ortto renamed Organizations to Accounts in June 2025)

Regional endpoints:
- **Default (AP3)**: `https://api.ap3api.com/v1/organizations/delete`
- **Australia**: `https://api.au.ap3api.com/v1/organizations/delete`
- **Europe**: `https://api.eu.ap3api.com/v1/organizations/delete`

## Headers

| Header | Value | Required |
|--------|-------|----------|
| `X-Api-Key` | Your Ortto API key | Yes |
| `Content-Type` | `application/json` | Yes |

## Request Body

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `account_ids` | array of strings | Yes | Array of **archived** account IDs to delete (UUID format) |

## Response Format

```json
{
  "deleted": 2,
  "not_found": 1
}
```

| Field | Type | Description |
|-------|------|-------------|
| `deleted` | integer | Number of accounts successfully deleted |
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
  "deleted": 2,
  "not_found": 0
}
```

## Behavior

### What Happens When Deleting

1. **Permanent removal**: Account data is permanently deleted
2. **No recovery**: Cannot be restored after deletion
3. **Relationships**: Associated contacts are unlinked
4. **Must be archived**: Only archived accounts can be deleted

### Pre-Deletion Requirement

Accounts **must be archived first** before deletion:

```
Active Account
     ↓ (archive first)
Archived Account
     ↓ (then delete)
Permanently Deleted ❌ Cannot restore
```

Attempting to delete a non-archived account will result in it being counted in `not_found`.

## Constraints

- **Archive required**: Accounts must be archived before they can be deleted
- **Irreversible**: No way to recover deleted accounts
- **No validation errors**: Invalid or non-archived IDs counted in `not_found`, not errors
- **Bulk operations**: Can delete multiple accounts in one request

## Use Cases

- **Permanent cleanup**: Remove accounts that will never be needed again
- **Data compliance**: Delete accounts to meet data retention policies
- **Storage management**: Free up storage by removing archived accounts
- **GDPR/Privacy**: Comply with data deletion requests

## Error Handling

The `not_found` count includes:
- Account IDs that don't exist
- Account IDs that exist but are **not archived** (active accounts)
- Invalid UUID formats

**Important**: If you try to delete an active (non-archived) account, it will be counted in `not_found` and NOT deleted. You must archive it first.

## Workflow: Complete Deletion Process

```
Step 1: Archive
POST /v1/organizations/archive
{
  "account_ids": ["account-id-123"]
}

Step 2: Delete (irreversible)
DELETE /v1/organizations/delete
{
  "account_ids": ["account-id-123"]
}
```

## Safety Recommendations

1. **Double-check IDs**: Verify account IDs before deletion
2. **Export data**: Back up account data before deleting
3. **Archive first**: Always archive and verify before deleting
4. **Small batches**: Delete in small batches to minimize mistakes
5. **Audit trail**: Log all deletion operations for compliance

## Tips

- **Two-step process**: Always archive → then delete
- **No undo**: Unlike archive/restore, deletion cannot be reversed
- **Check not_found**: High `not_found` count may indicate non-archived accounts
- **Bulk delete**: More efficient than deleting one at a time
- **Test both URLs**: Try both `/organizations/delete` and `/accounts/delete` to see which works for your Ortto instance
- **Compliance**: Document deletion reasons for audit purposes
