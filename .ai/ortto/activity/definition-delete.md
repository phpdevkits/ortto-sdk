# Delete a custom activity definition (delete)

The delete endpoint of the activity definitions entity is used to archive custom activity definitions in your Ortto account's customer data platform (CDP).

**Important:** This endpoint archives the activity definition. Once archived via the API, you can permanently delete it in the Ortto app.

## HTTP method and request resource

```
DELETE https://api.ap3api.com/v1/definitions/activity/delete
```

> **NOTE:** Ortto customers who have their instance region set to Australia or Europe will need to use specific service endpoints relative to the region:
>
> - Australia: `https://api.au.ap3api.com/v1/definitions/activity/delete`
> - Europe: `https://api.eu.ap3api.com/v1/definitions/activity/delete`
>
> All other Ortto users will use the default service endpoint (`https://api.ap3api.com/`).

## Path and query parameters

This endpoint takes no additional path and/or query parameters.

## Headers

This endpoint requires a custom API key and content type (`application/json` for the request body) in the header of the request:

- `X-Api-Key: CUSTOM-PRIVATE-API-KEY`
- `Content-Type: application/json`

## Request body

The request body consists of a JSON object specifying which activity definition to archive.

### Example request body

```json
{
  "activity_field_id": "act:cm:product-purchase"
}
```

### Valid request body elements

| Element | Type | Description |
|---------|------|-------------|
| **activity_field_id** | string | **Required.** The field ID of the custom activity definition to archive. Format: `act:cm:{activity-name}` |

### Finding your activity field ID

The activity field ID is returned when you create an activity definition, or you can find it in the Ortto CDP:

1. Navigate to CDP > Activities
2. Click on the custom activity
3. The ID is shown in the format `act:cm:{name}`

## Response structure

### Example response - Successful archival

```json
{
  "archived_activity": "act:cm:product-purchase"
}
```

### Response fields

| Field | Type | Description |
|-------|------|-------------|
| **archived_activity** | string | The field ID of the archived custom activity definition |

## Two-step deletion process

Deleting a custom activity is a two-step process to prevent accidental data loss:

### Step 1: Archive via API (this endpoint)

Call `DELETE /v1/definitions/activity/delete` with the activity field ID:

```bash
curl -X DELETE https://api.ap3api.com/v1/definitions/activity/delete \
  -H "X-Api-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "activity_field_id": "act:cm:product-purchase"
  }'
```

This archives the activity definition. The activity:
- No longer appears in active activity lists
- Can still be viewed in archived activities
- Can be restored if needed

### Step 2: Permanent deletion via Ortto app

After archiving via API:

1. Log into Ortto app
2. Navigate to CDP > Activities
3. Filter to show archived activities
4. Find the archived activity
5. Click the permanent delete option

**Warning:** Permanent deletion cannot be undone. All historical activity data will be lost.

## Important notes

### What happens when you archive an activity

**Archived activities:**
- Are removed from active activity lists in the UI
- No longer trigger in automations or campaigns
- Historical data remains in the system
- Can be restored before permanent deletion

**Activity events:**
- Existing activity events remain on contact records
- New activity events cannot be created for archived activities
- Attempting to create events for archived activities will fail

### Before archiving

Consider these factors before archiving an activity definition:

1. **Automations** - Check if any active playbooks use this activity as a trigger
2. **Segments** - Verify no audiences filter on this activity
3. **Campaigns** - Ensure no active campaigns rely on this activity
4. **Reports** - Review dashboards that may include this activity
5. **Historical data** - Confirm you don't need to create new events of this type

### Restoration

Archived activities can be restored before permanent deletion:

1. Navigate to CDP > Activities in Ortto app
2. Filter to show archived activities
3. Select the archived activity
4. Click "Restore" option

The activity returns to active status and can receive new events.

## Error responses

**400 Bad Request** - Invalid activity field ID format

**401 Unauthorized** - Invalid or missing API key

**404 Not Found** - Activity definition doesn't exist or is already archived

**403 Forbidden** - Insufficient permissions to archive activities

## Common use cases

### Deprecating old activity types

When replacing an old activity definition with a new one:

```json
{
  "activity_field_id": "act:cm:old-purchase-v1"
}
```

### Cleaning up test activities

Remove test activities created during development:

```json
{
  "activity_field_id": "act:cm:test-activity"
}
```

### Removing unused integrations

Archive activities from disconnected integrations:

```json
{
  "activity_field_id": "act:cm:shopify-order-old"
}
```

## Related endpoints

- **POST /v1/definitions/activity/create** - Create new activity definition
- **PATCH /v1/definitions/activity/modify** - Modify existing activity definition
- **POST /v1/activities/create** - Create activity events

## Best practices

1. **Audit first** - Check all automations, segments, and campaigns before archiving
2. **Document reason** - Keep internal records of why activities were archived
3. **Backup data** - Export activity data reports before permanent deletion
4. **Test impact** - Archive in test environment first if available
5. **Gradual rollout** - If replacing activities, ensure new ones work before archiving old ones
