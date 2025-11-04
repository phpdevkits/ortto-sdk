# Archive, restore and delete people

The archive, restore and delete Ortto endpoints of the person entity are used to archive, restore and delete one or more people records in Ortto's customer data platform (CDP).

Learn more about archiving, restoring and deleting people under [Archiving and deleting people](https://help.ortto.com/user/latest/data-management/people/archiving-and-deleting-people.html).

This page provides descriptions of these endpoints.

## HTTP method and request resources

### Archive endpoint

```
PUT https://api.ap3api.com/v1/person/archive
```

### Restore endpoint

```
PUT https://api.ap3api.com/v1/person/restore
```

### Delete endpoint

```
DELETE https://api.ap3api.com/v1/person/delete
```

**NOTE**: Contacts must be archived before they can be restored or deleted.

**NOTE**: Ortto customers who have their instance region set to Australia or Europe will need to use specific service endpoints relative to the region:

- For example: `https://api.eu.ap3api.com/v1/<entity/endpoint>`

All other Ortto users will use the default service endpoint (`https://api.ap3api.com/`).

## Path and query parameters

This endpoint takes no additional path and/or query parameters.

## Headers

This endpoint requires a custom API key and content type (`application/json` for the request body) in the header of the request.

## Request body

The request body consists of a JSON object whose valid elements are listed in the table below.

The following JSON object is an example of field and object data that Ortto can recognize to archive, restore or delete one or more contact records in your Ortto account's CDP.

### Example archive people request body from Ortto's CDP

```json
{
  "inclusion_ids": [
    "00609384fa6de6e7a8d89d01"
  ]
}
```

## Valid request body elements

The following table lists all valid request body elements (arrays, objects, or fields), which are available to these endpoints.

| Element | Type | Description |
|---------|------|-------------|
| `inclusion_ids` | array of string values | A list of contacts to archive/restore/delete. You can obtain the contact ID using the get endpoint to retrieve one or more people. Or, find the contact ID in the Ortto app by viewing the contact's profile and copying the ID from the URL (e.g. `https://ortto.app/myinstance/cdp/people/00626f6dfba865ba45d9c200/activities?from=cdp-filter&key=people-all-`). |
| `exclusion_ids` | array of string values | A list of contacts to exclude from the archive/restore/delete request. |
| `all_rows_selected` | boolean | Setting `all_rows_selected` to `true` allows you to bulk archive, restore and delete contact records. When used to archive people, `"all_rows_selected": true` will select all rows in your main people list. However, when restoring and deleting people, `"all_rows_selected": true` will select all rows from your archived people list. |

### Bulk archive/restore/delete example

A request body to bulk archive/restore/delete looks like:

```json
{
  "all_rows_selected": true
}
```

`all_rows_selected` can be used in conjunction with `exclusion_ids` to make it easier to manage many and exclude few:

```json
{
  "all_rows_selected": true,
  "exclusion_ids": [
    "006281d8ff95c3ed70b70800",
    "00641a4bba87757d86750f00"
  ]
}
```

## Response payload

The response payload consists of a JSON object with the elements listed in the table below.

The following JSON object is an example of the person data that Ortto retrieves from your Ortto account's CDP after a request to these endpoints.

### Example archive people response payload from Ortto's CDP

```json
{
  "scheduled_contacts": 0,
  "archived_contacts": 35
}
```

## Response payload elements

The following table lists all elements (arrays, objects, or fields) in the response from a request to these endpoints.

| Element | Type | Description |
|---------|------|-------------|
| `scheduled_contacts` | integer | The value indicates the number of contacts that are scheduled to be archived/restored/deleted shortly after the API call. |
| `archived_contacts` | integer | The value indicates the number of contacts that were archived during the API call. |
| `restored_contacts` | integer | The value indicates the number of contacts that were restored during the API call. |
| `deleted_contacts` | integer | The value indicates the number of contacts that were deleted during the API call. |