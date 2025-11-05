# Add Contacts to Account

Associate one or more contacts (people) with an account (organization). Each account can have up to 3,000 associated contacts.

## Endpoint

**PUT** `/v1/accounts/contacts/add`

Regional endpoints:
- **Default (AP3)**: `https://api.ap3api.com/v1/accounts/contacts/add`
- **Australia**: `https://api.au.ap3api.com/v1/accounts/contacts/add`
- **Europe**: `https://api.eu.ap3api.com/v1/accounts/contacts/add`

## Headers

| Header | Value | Required |
|--------|-------|----------|
| `X-Api-Key` | Your Ortto API key | Yes |
| `Content-Type` | `application/json` | Yes |

## Request Body

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `account_id` | string (UUID) | Yes | ID of the account to add contacts to |
| `person_ids` | array of strings | Yes | Array of person IDs to associate with the account |

## Response Format

```json
{
  "added": 5,
  "already_associated": 2,
  "not_found": 1,
  "limit_exceeded": 0
}
```

| Field | Type | Description |
|-------|------|-------------|
| `added` | integer | Number of contacts successfully added |
| `already_associated` | integer | Number of contacts already linked to this account |
| `not_found` | integer | Number of person IDs that don't exist |
| `limit_exceeded` | integer | Number of contacts that couldn't be added due to 3,000 limit |

## Example Request

```json
{
  "account_id": "123e4567-e89b-12d3-a456-426614174000",
  "person_ids": [
    "person-id-abc123",
    "person-id-def456",
    "person-id-ghi789",
    "person-id-jkl012"
  ]
}
```

## Example Response

```json
{
  "added": 4,
  "already_associated": 0,
  "not_found": 0,
  "limit_exceeded": 0
}
```

## Behavior

### What Happens When Adding Contacts

1. **Association created**: Contact becomes associated with the account
2. **Bidirectional**: Both contact and account records reflect the relationship
3. **Multiple accounts**: A contact can be associated with multiple accounts
4. **Idempotent**: Adding an already-associated contact is safe (counted in `already_associated`)

### Account Limit

- **Maximum**: 3,000 contacts per account
- **Enforcement**: Attempts to exceed limit counted in `limit_exceeded`
- **No error**: Exceeding limit doesn't cause request to fail

## Constraints

- **Max contacts per account**: 3,000
- **Valid IDs**: Both account_id and person_ids must be valid UUIDs
- **Existing records**: Account and contacts must exist before association

## Use Cases

- **B2B relationships**: Link employees to their company
- **Team management**: Associate team members with an organization
- **Account-based marketing**: Group contacts by organization
- **Hierarchy tracking**: Maintain organizational relationships

## Error Handling

### Response Field Interpretation

- `not_found` > 0: Some person IDs don't exist or account_id is invalid
- `limit_exceeded` > 0: Account has reached 3,000 contact limit
- `already_associated` > 0: Some contacts were already linked (not an error)

### Best Practices

1. **Check limits**: Query account first to see current contact count
2. **Validate IDs**: Ensure person IDs exist before attempting to add
3. **Handle partial success**: Some contacts may be added while others fail
4. **Batch operations**: Add contacts in batches rather than one at a time

## Querying Associated Contacts

After adding contacts, query them using `/accounts/get`:
```json
{
  "account_ids": ["123e4567-e89b-12d3-a456-426614174000"],
  "fields": ["str:o:name"],
  "include_contacts": true
}
```

## Workflow Example

```
1. Create/Update Account
   POST /v1/accounts/merge

2. Add Contacts to Account
   PUT /v1/accounts/contacts/add

3. Verify Association
   POST /v1/accounts/get
   (with include_contacts: true)
```

## Tips

- **Idempotent**: Safe to call multiple times with same contact IDs
- **Partial success**: Check all response fields to understand what happened
- **Limit monitoring**: Track `limit_exceeded` to know when approaching 3,000 limit
- **Batch adds**: More efficient than individual contact additions
- **Validation**: Verify person IDs exist before attempting association
- **Reversible**: Use `/contacts/remove` endpoint to unlink contacts
