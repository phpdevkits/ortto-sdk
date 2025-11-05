# Remove Contacts from Account

Remove the association between one or more contacts (people) and an account (organization). This unlinks the contacts but does not delete them.

## Endpoint

**PUT** `/v1/accounts/contacts/remove`

Regional endpoints:
- **Default (AP3)**: `https://api.ap3api.com/v1/accounts/contacts/remove`
- **Australia**: `https://api.au.ap3api.com/v1/accounts/contacts/remove`
- **Europe**: `https://api.eu.ap3api.com/v1/accounts/contacts/remove`

## Headers

| Header | Value | Required |
|--------|-------|----------|
| `X-Api-Key` | Your Ortto API key | Yes |
| `Content-Type` | `application/json` | Yes |

## Request Body

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `account_id` | string (UUID) | Yes | ID of the account to remove contacts from |
| `person_ids` | array of strings | Yes | Array of person IDs to disassociate from the account |

## Response Format

```json
{
  "removed": 3,
  "not_associated": 1,
  "not_found": 1
}
```

| Field | Type | Description |
|-------|------|-------------|
| `removed` | integer | Number of contacts successfully removed |
| `not_associated` | integer | Number of contacts that weren't associated with this account |
| `not_found` | integer | Number of person IDs that don't exist |

## Example Request

```json
{
  "account_id": "123e4567-e89b-12d3-a456-426614174000",
  "person_ids": [
    "person-id-abc123",
    "person-id-def456",
    "person-id-ghi789"
  ]
}
```

## Example Response

```json
{
  "removed": 3,
  "not_associated": 0,
  "not_found": 0
}
```

## Behavior

### What Happens When Removing Contacts

1. **Association removed**: Link between contact and account is deleted
2. **Contact preserved**: The contact (person) record itself is **not deleted**
3. **Bidirectional**: Both contact and account records reflect the change
4. **Other associations**: Contact may still be associated with other accounts
5. **Idempotent**: Removing a non-associated contact is safe (counted in `not_associated`)

### Data Impact

- **Account data**: Unchanged
- **Contact data**: Unchanged
- **Relationship only**: Only the association is removed

## Constraints

- **Valid IDs**: Both account_id and person_ids must be valid UUIDs
- **Existing records**: Account and contacts must exist
- **No deletion**: Does not delete contact records, only unlinks them

## Use Cases

- **Employee departure**: Remove ex-employees from company account
- **Relationship changes**: Update contacts when they change organizations
- **Data cleanup**: Remove incorrect or outdated associations
- **Account restructuring**: Reorganize contact-account relationships

## Error Handling

### Response Field Interpretation

- `not_found` > 0: Some person IDs don't exist or account_id is invalid
- `not_associated` > 0: Some contacts weren't linked to this account (not an error)

### Best Practices

1. **Verify association**: Check that contacts are actually associated before removing
2. **Handle partial success**: Some contacts may be removed while others fail
3. **Preserve contacts**: Remember this doesn't delete contact records
4. **Batch operations**: Remove multiple contacts in one request for efficiency

## Querying Associations

Before removing, you may want to verify associations using `/accounts/get`:
```json
{
  "account_ids": ["123e4567-e89b-12d3-a456-426614174000"],
  "fields": ["str:o:name"],
  "include_contacts": true
}
```

## Workflow Example

```
1. Query Account Contacts
   POST /v1/accounts/get
   (with include_contacts: true)

2. Remove Specific Contacts
   PUT /v1/accounts/contacts/remove

3. Verify Removal
   POST /v1/accounts/get
   (with include_contacts: true)
```

## Comparison: Remove vs Delete

| Operation | Endpoint | Effect |
|-----------|----------|--------|
| **Remove** | `/contacts/remove` | Unlinks contact from account, preserves both records |
| **Delete Contact** | `/person/delete` | Permanently deletes contact record (and all associations) |
| **Delete Account** | `/organizations/delete` | Permanently deletes account record (and all associations) |

## Tips

- **Idempotent**: Safe to call multiple times with same contact IDs
- **No data loss**: Contact records remain intact, only relationship removed
- **Partial success**: Check all response fields to understand what happened
- **Batch removes**: More efficient than individual contact removals
- **Validation**: Verify associations exist before attempting removal
- **Reversible**: Use `/contacts/add` endpoint to re-link contacts if needed
- **Account deletion**: When account is deleted, all contact associations are automatically removed
