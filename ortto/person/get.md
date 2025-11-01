# Retrieve one or more people (get)

The get Ortto endpoint of the person entity is used to retrieve data from one or more person records in Ortto's customer data platform (CDP).

This page provides descriptions of this endpoint's:

## HTTP method and request resource

```
POST https://api.ap3api.com/v1/person/get
```

**NOTE**: Ortto customers who have their instance region set to Australia or Europe will need to use specific service endpoints relative to the region:

- For example: `https://api.eu.ap3api.com/v1/<entity/endpoint>`

All other Ortto users will use the default service endpoint (`https://api.ap3api.com/`).

## Path and query parameters

This endpoint takes no additional path and/or query parameters.

## Headers

This endpoint requires a custom API key and content type (`application/json` for the request body) in the header of the request.

## Request body

The request body consists of a JSON object whose valid elements are listed in the table below.

The following JSON object is an example of field and object data that Ortto can recognize to get data from one or more person records in your Ortto account's CDP.

### Example get people request body from Ortto's CDP

```json
{
  "limit": 100,
  "sort_by_field_id": "str::last",
  "sort_order": "asc",
  "offset": 0,
  "fields": [
    "str::first",
    "str::last",
    "str::email",
    "str:cm:job-title"
  ],
  "filter": {
    "$has_any_value": {
      "field_id": "str::first"
    }
  }
}
```

This endpoint also supports the use of AND/OR for filter conditions, as shown below for `$and` (AND).

### Example get people request using `$and` to join filter conditions

```json
{
  "limit": 100,
  "sort_by_field_id": "str::last",
  "sort_order": "asc",
  "offset": 0,
  "fields": [
    "str::id",
    "str::last",
    "str::email"
  ],
  "filter": {
    "$and": [
      {
        "$has_any_value": {
          "field_id": "str::last"
        }
      },
      {
        "$str::is": {
          "field_id": "str::email",
          "value": "contact@email.com"
        }
      }
    ]
  }
}
```

**NOTE**: The `$has_any_value` filter option will return results that have a value of 0 or "". Learn more about empty values.

The AND/OR conditions can also be nested. The following example shows a query for people who:

### Example get people request using nested AND/OR filter conditions

```json
{
  "limit": 100,
  "sort_by_field_id": "str::last",
  "sort_order": "asc",
  "offset": 0,
  "fields": [
    "str::id",
    "str::first",
    "str::last",
    "str::email"
  ],
  "filter": {
    "$and": [
      {
        "$str::is": {
          "field_id": "geo::country",
          "value": "Australia"
        }
      },
      {
        "$or": [
          {
            "$str::is": {
              "field_id": "str:cm:label",
              "value": "VIP"
            }
          },
          {
            "$str::is": {
              "field_id": "str:cm:label",
              "value": "Highly engaged"
            }
          }
        ]
      }
    ]
  }
}
```

## Generating filters using the browser's dev tools

Depending on the number of conditions, fields, or activities in your filter, creating it reliably can become complex. Fortunately, we have a trick that lets you copy an already created filter from the browser's dev tools. You can follow these steps if you're using Google Chrome. If you're using a different browser, you may need to adjust the steps accordingly.

Follow the steps below to do this:

1. Open the browser's dev tools and select the Network tab.
2. Create the filter in-app on the People page.
3. Locate the create request and click on it.
4. In the Preview tab, right-click on the filter and select either Copy value or Copy object.
5. Paste the object into your API call.

Example of where to locate the 'create' request after building the filter in-app.

### Example filter retrieved from the network tab

```json
{
  "$and": [
    {
      "$and": [
        {
          "$str::contains": {
            "label": "Email contains ortto.com",
            "field_id": "str::email",
            "timezone": "Italy",
            "value": "ortto.com"
          }
        },
        {
          "$act::has_occurred": {
            "formData": {
              "mode": "hasOccurred",
              "label": "Opened email has occurred",
              "hasOccurred": {
                "op": "$act::has_occurred",
                "label": "has occurred"
              }
            },
            "label": "Opened email has occurred",
            "field_id": "act::o",
            "timezone": "Italy",
            "name": "Opened email",
            "icon": "audience-newsletter-icon"
          }
        }
      ]
    }
  ]
}
```

**TIP**: When copying the object from the network tab, some non-required fields (e.g. name, timezone, icon) may be included. You can exclude these to keep your API call cleaner, or send the call as is and it will still work.

## Get by IDs

To retrieve Contact records based on their IDs, use the following endpoint:

```
POST https://api.ap3api.com/v1/person/get-by-ids
```

Simply include the IDs of the Contacts you want to retrieve within the `contact_ids` parameter.

### Example get people request body based on Contact ID

```json
{
  "contact_ids": [
    "0066cc4c16dfd264d0e99b00",
    "00668d816ce2d34c550fe300"
  ],
  "fields": [
    "str::email"
  ]
}
```

**NOTE**: You can include a maximum of 150 field IDs in your request.

## Valid request body elements

The following table lists all valid request body elements (arrays, objects, or fields), which are available to this endpoint.

| Element | Type | Description |
|---------|------|-------------|
| `limit` | integer between 1 to 500 (default value is 50) | The limit element value determines the number of people to be returned by Ortto from the request. This value also represents the "page" length of people (for pagination purposes), since potentially many thousands of people records could be retrieved from a single request. This element can be used in conjunction with offset to retrieve subsequent pages of people records. If this limit element is not specified in the request, or an integer value beyond the permitted maximum is specified, then this value is assumed to be 50. |
| `sort_order` | string with value `asc` or `desc` (default value is `desc`) | The sort_order element determines the order in which the people returned are sorted, based on the sort_by_field_id member value. Specifying this sort_order member is only relevant when the sort_by_field_id member is also specified. |
| `sort_by_field_id` | string | The sort_by_field_id element whose field ID value specifies the person field used to sort the people returned from your Ortto account's CDP. |
| `offset` | integer with a default value of 0 | The offset element represents the count of people records in your Ortto account's CDP for pagination purposes, and is the point from which the next page of people (defined by the limit member value) is retrieved. |
| `cursor_id` | string containing a UUID value (e.g. 00609c898a4490c5800a5453) | The cursor_id element is the UUID for the start of the first person record in the next page of people (defined by the limit member value). This value is retrieved from the previous /v1/person/get endpoint request. |
| `fields` | array of string values | The fields array contains person field ID values that define the person fields of the person records returned by Ortto from the request. |
| `q` | string | The q element is a valid text string used to filter the people records returned by Ortto from the request. |
| `type` | string | The type element is a valid text string used to filter the people records returned by Ortto from the request. |
| `filter` | Object containing a person's field_ID (representing a person field) used for filtering | The filter element contains a field member that determines how records are filtered. |

### Type parameter

`type` can accept the following values:

If you are pairing type with archived, you cannot use filters or other elements (only the query string is accepted).

To retrieve a list of archived contacts:

```json
{
  "type": "archived"
}
```

Or to use type with a filter, you can construct the body something like this:

```json
{
  "type": "",
  "filter": {
    "$str::is": {
      "field_id": "str::email",
      "value": "contact@email.com"
    }
  }
}
```

### Filter parameter

The filter element contains a field member that determines how records are filtered.

Therefore, a construct like:

```json
"filter": {
  "$has_no_value": {
    "field_id": "str::first"
  }
}
```

where `str::first` is the person's First name field would filter and return people who do not have their first name defined.

Similarly, the following construct would return people who do have their first name defined:

```json
"filter": {
  "$has_any_value": {
    "field_id": "str::first"
  }
}
```

You can also join filter conditions with AND/OR. The following construct shows an example for using `$or` (OR):

```json
"filter": {
  "$or": [
    {
      "$str::is": {
        "field_id": "str::name",
        "value": "John Smith"
      }
    },
    {
      "$str::is": {
        "field_id": "str::email",
        "value": "jsmith@email.com"
      }
    }
  ]
}
```

#### field_id

The `field_id` element enables you to filter results by a field ID. This returns records that have the specified field populated and can be used in conjunction with value to filter records by specific field values.

#### value

Use `value` to specify a specific field value you wish to return contact records by. For example, this can be helpful if you are looking for a specific contact and filter by the contact's email address:

```json
"filter": {
  "$str::is": {
    "field_id": "str::email",
    "value": "contact@email.com"
  }
}
```

## Person fields

In Ortto, a person field:

- contains the data for a specific piece of information (i.e. field) about each person in Ortto's CDP,
- is referenced via the Ortto API using a specific ID format, and
- could be a built in Ortto field or a custom field you have defined yourself (which also has its own ID format), and
- is returned by Ortto in the response, based on the fields array values submitted in the request to this endpoint.

Data about the following built-in person fields can be obtained through Ortto's API when retrieving people from the CDP.

| Field name (in product) | Field ID | Returns |
|------------------------|----------|---------|
| First name | `str::first` | A string whose value is this person's first name. |
| Last name | `str::last` | A string whose value is this person's last name. |
| Phone number | `phn::phone` | A phone number object of two members consisting of valid country code digits (c) and a phone number (n), representing this person's phone number. |
| Email | `str::email` | A string whose value is this person's email address. |
| City | `geo::city` | A geographical data object consisting of a member name whose string value is this person's current city. |
| Country | `geo::country` | A geographical data object consisting of a member name whose string value is this person's current country. |
| Birthday | `dtz::b` | A date object consisting of members year, month and day whose respective integer values represent this person's date of birth, along with a timezone string representing the person's current time zone. |
| Region | `geo::region` | A geographical data object consisting of a member name whose string value is this person's current region (e.g. state or province) within their country. |
| Postal | `str::postal` | A string whose value is this person's current postal code. |
| Tags | `tags` | An array whose string values are all tags currently specified on this person. |
| Tag IDs | `u4s::t` | An array whose integer values are the internal Ortto ID of all tags currently specified on this person. |
| GDPR | `bol::gdpr` | A boolean value where true flags that GDPR is a requirement for this person, or false if not. |
| External ID | `str::ei` | A string whose value is any ID used to uniquely identify this person. |
| Email subscription permission | `bol::p` | A boolean value where true flags that the person has their Email permission set to true, or Subscribed through the Ortto user interface (UI), and false flags that this person's permission is set to false (or Unsubscribed). |
| Context message for the email unsubscribe action | `str::u-ctx` | A string value representing the activity context message, when the email subscription permission was set to false. These messages appear in people's Activities updates in the Ortto UI. |
| Context message for the email subscribe action | `str::s-ctx` | A string value representing the activity context message, when the email subscription permission was set to true. These messages appear in people's Activities updates in the Ortto UI. |
| SMS subscription permission | `bol::sp` | A boolean value where false flags that the person has their SMS permission set to false, or Unsubscribed through the Ortto UI, and true flags that this person's permission is set to true (or Subscribed). If this person field is not specified in the request, then this value is assumed to be false by default. |
| Context message for the SMS subscribe action | `str::soi-ctx` | A string value representing the activity context message, when the SMS subscription permission was set to true. These messages appear in people's Activities updates in the Ortto UI. |
| Context message for the SMS unsubscribe action | `str::soo-ctx` | A string value representing the activity context message, when the SMS subscription permission was set to false. These messages appear in people's Activities updates in the Ortto UI. |

### Person field ID format

Each person field is referenced by an ID.

Since Ortto integrates with many third-party products, references to most person fields in Ortto's CDP are both strongly-typed and namespace-specific. Therefore, for most person fields, the field's ID is based on the format:

For Ortto's own built-in person fields, the namespace value is unnecessary and is omitted. Hence, these built-in fields are referenced by an ID based on the format.

Data from custom fields can also be retrieved in person records from requests to this endpoint. The field-name for custom fields is typically based on their configured names converted to kebab-case.

### Person field type abbreviations

The following person field type abbreviations are used to form the first part (type) of each person field's ID for built-in fields:

| Field type abbreviation | Type of value |
|------------------------|---------------|
| `bol` | Boolean |
| `dtz` | Date (object) |
| `geo` | Geographical data (object) |
| `int` | Integer. For internal operations and calculations, the Ortto API treats decimal values as integers multiplied by 1,000. This is done to preserve the precision of values resulting from these calculations. |
| `phn` | Phone number (object) |
| `str` | String |

## Supported filters

The following table lists the available filter options that can be used to refine the contact records that are returned.

| Element | Description |
|---------|-------------|
| `$act::first` | Filters for the first occurrence of a specified activity. |
| `$act::has_not_occurred` | Filters records where a specified activity has not occurred. |
| `$act::has_occurred` | Filters records where a specified activity has occurred. |
| `$act::last` | Filters records based on the most recent occurrence of a specified activity. |
| `$andgroup` | Groups multiple conditions with an AND operator. |
| `$bol::is` | Filters records where a boolean field matches a specific value. |
| `$dtz::absolute_after` | Filters records with a date/time field occurring after a specific value. |
| `$dtz::absolute_before` | Filters records with a date/time field occurring before a specific value. |
| `$dtz::absolute_between` | Filters records with a date/time field within a specific range. |
| `$dtz::absolute_on` | Filters records with a date/time field exactly matching a specific value. |
| `$dtz::age_exactly` | Filters records where the age is exactly a specified value. |
| `$dtz::age_less_than` | Filters records where the age is less than a specified value. |
| `$dtz::age_more_than` | Filters records where the age is greater than a specified value. |
| `$dtz::anniversary` | Filters records based on a recurring date or anniversary. |
| `$dtz::occurs_in` | Filters records where a date field will occur within a specific range from the current time. |
| `$dtz::occurs_in_less_than` | Filters records where a date field occurs in less than a specified time range. |
| `$dtz::occurs_in_more_than` | Filters records where a date field occurs in more than a specified time range. |
| `$dtz::relative_exactly` | Filters records where a date field matches an exact relative time. |
| `$dtz::relative_less_than` | Filters records where a date field is less than a relative time. |
| `$dtz::relative_more_than` | Filters records where a date field is more than a relative time. |
| `$geo::is` | Filters records based on a geographic location match. |
| `$geo::is_not` | Filters records excluding a specific geographic location. |
| `$has_any_value` | Filters records where a field has any value. |
| `$has_no_value` | Filters records where a field has no value. |
| `$id_is` | Filters records where an ID field matches a specific value. |
| `$id_is_not` | Filters records where an ID field does not match a specific value. |
| `$id_set_is` | Filters records where an ID field matches any value in a specific set of IDs. |
| `$id_set_is_not` | Filters records where an ID field does not match any value in a specific set of IDs. |
| `$int::between` | Filters records where an integer field is within a specific range. |
| `$int::greater_than` | Filters records where an integer field is greater than a specific value. |
| `$int::is` | Filters records where an integer field matches a specific value. |
| `$int::is_not` | Filters records where an integer field does not match a specific value. |
| `$int::less_than` | Filters records where an integer field is less than a specific value. |
| `$int::not_between` | Filters records where an integer field is outside a specific range. |
| `$is_member_of` | Filters records for contacts that are members of a specified audience. |
| `$is_not_member_of` | Filters records for contacts that are not members of a specified audience. |
| `$is_not_subscriber_of` | Filters records for contacts that are not subscribed to a specified audience. |
| `$is_opted_in` | Filters records for contacts that have opted-in communications. |
| `$is_opted_out` | Filters records for contacts that have not opted-in communications. |
| `$is_push_opted_in` | Filters records for contacts that opted-in to receive push notifications. |
| `$is_push_opted_out` | Filters records for contacts that are not opted-in for push notifications. |
| `$is_subscriber_of` | Filters records for contacts that are subscribed to a specified audience. |
| `$orgroup` | Groups multiple conditions with an OR operator. |
| `$phn::in_iso2_country_codes` | Filters records where the phone number field is within specific ISO2 country codes. |
| `$phn::iis` | Filters records where the phone number field matches a specific value. |
| `$phn::iis_not` | Filters records where the phone number field does not match a specific value. |
| `$phn::starts` | Filters records where the phone number field starts with a specific prefix. |
| `$proximity_is_not_within` | Filters for records not in proximity to a specified Last Location |
| `$proximity_is_within` | Filters records for proximity to a specified Last Location |
| `$str::between` | Filters records where a string field is within a lexicographical range. |
| `$str::contains` | Filters records where a string field contains a specific value. |
| `$str::does_not_contains` | Filters records where a string field does not contain a specific value. |
| `$str::ends` | Filters records where a string field ends with a specific value. |
| `$str::greater_than` | Filters records where a string field is greater than a specific value. |
| `$str::is` | Filters records where a string field matches a specific value. |
| `$str::is_not` | Filters records where a string field does not match a specific value. |
| `$str::less_than` | Filters records where a string field is less than a specific value. |
| `$str::list_contains` | Filters records where a string list contains a specific value. |
| `$str::list_contains_less_than` | Filters records where a string list contains fewer than a specific number of items. |
| `$str::list_contains_more_than` | Filters records where a string list contains more than a specific number of items. |
| `$str::list_does_not_contains` | Filters records where a string list does not contain a specific value. |
| `$str::list_first_item_contains` | Filters records where the first item in a string list contains a specific value. |
| `$str::list_first_item_does_not_contains` | Filters records where the first item in a string list does not contain a specific value. |
| `$str::list_first_item_is` | Filters records where the first item in a string list matches a specific value. |
| `$str::list_first_item_is_not` | Filters records where the first item in a string list does not match a specific value. |
| `$str::list_has_exactly` | Filters records where a string list has exactly a specific number of items. |
| `$str::list_last_item_contains` | Filters records where the last item in a string list contains a specific value. |
| `$str::list_last_item_does_not_contains` | Filters records where the last item in a string list does not contain a specific value. |
| `$str::list_last_item_is` | Filters records where the last item in a string list matches a specific value. |
| `$str::list_last_item_is_not` | Filters records where the last item in a string list does not match a specific value. |
| `$str::set_contains` | Filters records where a string set contains a specific value. |
| `$str::set_contains_any` | Filters records where a string set contains any value from a specific set of values. |
| `$str::set_contains_less_than` | Filters records where a string set contains fewer than a specific number of items. |
| `$str::set_contains_more_than` | Filters records where a string set contains more than a specific number of items. |
| `$str::set_does_not_contains` | Filters records where a string set does not contain a specific value. |
| `$str::set_first_item_contains` | Filters records where the first item in a string set contains a specific value. |
| `$str::set_first_item_does_not_contains` | Filters records where the first item in a string set does not contain a specific value. |
| `$str::set_first_item_is` | Filters records where the first item in a string set matches a specific value. |
| `$str::set_first_item_is_not` | Filters records where the first item in a string set does not match a specific value. |
| `$str::set_has_exactly` | Filters records where a string set has exactly a specific number of items. |
| `$str::set_is` | Filters records where a string set matches a specific set of values. |
| `$str::set_is_not` | Filters records where a string set does not match a specific set of values. |
| `$str::set_last_item_contains` | Filters records where the last item in a string set contains a specific value. |
| `$str::set_last_item_does_not_contains` | Filters records where the last item in a string set does not contain a specific value. |
| `$str::set_last_item_is` | Filters records where the last item in a string set matches a specific value. |
| `$str::set_last_item_is_not` | Filters records where the last item in a string set does not match a specific value. |
| `$str::starts` | Filters records where a string field starts with a specific value. |
| `$tme::absolute_after` | Filters records where a time and date field is after a specific absolute date. |
| `$tme::absolute_before` | Filters records where a time and date field is before a specific absolute date. |
| `$tme::absolute_between` | Filters records where a time and date field is between two specific absolute dates. |
| `$tme::absolute_on` | Filters records where a time and date field matches a specific absolute date. |
| `$tme::age_exactly` | Filters records where the age of a time and date field matches a specific value. |
| `$tme::age_less_than` | Filters records where the age of a time and date field is less than a specific value. |
| `$tme::age_more_than` | Filters records where the age of a time and date field is more than a specific value. |
| `$tme::anniversary` | Filters records where a time and date field matches a specific anniversary. |
| `$tme::occurs_in` | Filters records where a time and date field occurs within a specific time range. |
| `$tme::occurs_in_less_than` | Filters records where a time and date field occurs within less than a specific time range. |
| `$tme::occurs_in_more_than` | Filters records where a time and date field occurs within more than a specific time range. |
| `$tme::relative_exactly` | Filters records where a time and date field is exactly a specific relative duration. |
| `$tme::relative_less_than` | Filters records where a time and date field is less than a specific relative duration. |
| `$tme::relative_more_than` | Filters records where a time and date field is more than a specific relative duration. |
| `$has_started_campaign` | Filters records for those that have started a specific campaign. |
| `$is_member_of_campaign` | Filters records for those that are currently in a specific campaign. |
| `$has_left_campaign` | Filters records for those that have exited a specific campaign. |

## Response payload

The response payload consists of a JSON object whose elements are listed in the table below.

The following JSON object is an example of people's data that Ortto retrieves from your Ortto account's CDP after a request to this endpoint.

### Example get people response payload from Ortto's CDP

```json
{
  "contacts": [
    {
      "id": "0061b02b24f9b6f85dcb1e00",
      "fields": {
        "str::ei": "c533532fe5d16c7d4fa4c7f0",
        "str::email": "alex@example.com",
        "str::first": "Alex"
      }
    },
    {
      "id": "006153c064088217368efb00",
      "fields": {
        "str::email": "chris.hiedler@example.com",
        "str::first": "Chris",
        "str::last": "Hiedler"
      }
    },
    {
      "id": "006153c064088217368ef500",
      "fields": {
        "str::email": "alex.kodjo@example.com",
        "str::first": "Alex",
        "str::last": "Kodjo"
      }
    },
    {
      "id": "006153c064088217368ef800",
      "fields": {
        "str::email": "lana.romeijn@example.com",
        "str::first": "Lana",
        "str::last": "Romeijn"
      }
    },
    {
      "id": "0061b12ed91681ad2bbe4100",
      "fields": {
        "idt::o": "106153c398e0fdf2320c0700",
        "str::email": "chris.smith@example.com",
        "str::first": "Chris",
        "str::last": "Smith",
        "str:cm:job-title": "Technician"
      }
    }
  ],
  "meta": {
    "total_contacts": 5,
    "total_accounts": 0,
    "total_matches": 5,
    "total_subscribers": 5
  },
  "offset": 0,
  "next_offset": 5,
  "cursor_id": "0062299d655c7cf67184e1e0",
  "has_more": false
}
```

### Response payload elements

The following table lists all elements (arrays, objects, or fields) in the response from a request to this endpoint.

| Element | Description |
|---------|-------------|
| `contacts` | An array of contacts with an `id` and a `fields` hash which contacts the list of fields you requested to be returned. Note that if a contact has an associated account it will include a `idt::o` field, which is the ID of the account with which this contact is associated. |
| `meta` | A hash of meta data containing information about the matches to your contacts GET request. |
| `offset` | The offset which was set on this request. |
| `next_offset` | The offset for fetching the next page of contacts. |
| `cursor_id` | When retrieving the next page of contacts, provide this cursor_id. |
| `has_more` | Whether or not there is a next page of contacts. |