# Create or update one or more people (merge)

16 min. read | [View original](https://help.ortto.com/developer/latest/api-reference/v1-person-merge.html)

The merge Ortto endpoint of the person entity is used to create or update one or more person records in Ortto's customer data platform (CDP).

This page provides descriptions of this endpoint's:

## HTTP method and request resource

```
POST https://api.ap3api.com/v1/person/merge
```

> **NOTE:** Ortto customers who have their instance region set to Australia or Europe will need to use specific service endpoints relative to the region:
>
> For example: `https://api.eu.ap3api.com/v1/<entity/endpoint>`
>
> All other Ortto users will use the default service endpoint (`https://api.ap3api.com/`).

## Path and query parameters

This endpoint takes no additional path and/or query parameters.

## Headers

This endpoint requires a custom API key and content type (`application/json` for the request body) in the header of the request.

## Request body

The request body consists of a JSON object whose valid elements are listed in the table below.

The following JSON object is an example of field and object data that Ortto can recognize to create or update one or more person records in your Ortto account's CDP.

### Example create/update people request body in Ortto's CDP

```json
{
  "people": [
    {
      "fields": {
        "str::first": "Chris",
        "str::last": "Smith",
        "str::email": "chris.smith@example.com",
        "str:cm:job-title": "Technician"
      },
      "location": {
        "source_ip": "119.18.0.218"
      }
    },
    {
      "fields": {
        "str::first": "Alex",
        "str::email": "alex@example.com"
      },
      "location": {
        "source_ip": "119.18.0.218"
      }
    }
  ],
  "async": true,
  "merge_by": ["str::email"],
  "merge_strategy": 2,
  "find_strategy": 0,
  "suppression_list_field_id": "str::email"
}
```

> **IMPORTANT:** If you are sending a large number of synchronous (`"async": false`) API updates using a merge key (e.g. `"merge_by": ["str::email"]`) this can end up hitting a concurrency limit and your API requests may start to fail.
>
> To avoid this, and to speed up the processing of the API requests, we recommend using asynchronous (`"async": true`) updates where possible, or if the person ID of the contact is known, merging by person_id instead, as it's a guaranteed unique identifier of the contact and so no lookup request is needed to search through all contacts. See below for an example of merging by person ID.

> **TIP:** You can provide up to three fields in the merge_by array.

### Merge_by array

If you provide any fields in the merge_by array, only those fields will be used for merging.

If you do not send any fields in the merge_by array, we will fall back to using the unique identifier list defined in your Custom API data source.

#### Check against other field

You can replicate the **Check against another field** option from the account unique identifiers in your API payload by using the following syntax:

```json
"merge_by_alt_fields": {
  "str::email": ["{your alt email field id}"]
}
```

Learn more about the [check against another field option](https://help.ortto.com/developer/latest/).

### Merging person records using a person's ID

If merging by a person's ID, then `person_id` must be the only field in the merge_by array.

#### Example create/update people and merge by a person's ID

```json
{
  "people": [
    {
      "fields": {
        "str::first": "Jack",
        "str::last": "Skellington",
        "str::person_id": "00647687d2e43b25a0261f00"
      }
    },
    {
      "fields": {
        "str::first": "Sally",
        "str::last": "O'Hara",
        "str::person_id": "00647687d2f43b25b0261f01"
      }
    }
  ],
  "async": false,
  "merge_by": ["str::person_id"],
  "merge_strategy": 2,
  "find_strategy": 1
}
```

## Valid request body elements

The following table lists all valid request body elements (arrays, objects, or fields), which are available to this endpoint.

| Element | Type | Description |
|---------|------|-------------|
| **people** | array of objects | The people array consists of an array of objects, where each object contains data associated with a person being created or updated in your Ortto account's CDP. Each of these objects can contain:<br><br>Between 1 to 100 people (each as individual objects of this array) can be created and/or updated in a single request body call to this endpoint. |
| → **fields** | Object containing person field members | The object containing the fields for a person being created or updated in your Ortto account's CDP. This person is either created or updated in Ortto's CDP based on these criteria:<br><br>If this fields object contains a person field member whose value matches the merge_by member's value submitted in this request, and this person field's value does not match that of an existing person in Ortto's CDP, then Ortto creates this person as a new record.<br><br>Otherwise, if the person field member's value does already match that of an existing person in Ortto's CDP, then Ortto updates the fields of that person's record in the Ortto CDP, based on the merge_strategy value of this request. |
| → **location** | Object containing location field data | The location object either accepts a single IP address (as a source_ip field member), or a full address (in either a custom or address object). The location object provides more flexible options for specifying a person's location and address details rather than having to specify this information via geo-type person fields in a fields object (above). |
| → **tags** | array of string values | Each string value in the tags array represents a tag that is applied to this person in this request. Tags can be applied to a person, regardless of whether their record is being created or updated in your Ortto account's CDP. If a specified tag already exists in the CDP, then that tag is re-used when applied to this person. Otherwise, a new tag is automatically created in the CDP and applied to this person. |
| → **unset_tags** | array of string values | Each string value in the unset_tags array represents a tag that is removed from this person in this request. Be aware that if the unset_tags array:<br><br>is used in conjunction with the tags array in a single request, avoid specifying the same tag in both arrays, since the processing order of these arrays may differ from one request to the next, resulting in unpredictable tagging outcomes.<br><br>contains tags which were not applied to this person, then specifying them in this array has no effect.<br><br>Therefore, a construct like:<br><br>```json\n"people": [\n  {\n    "fields": {\n      "str::email": "chris.smith@example.com"\n    },\n    "tags": ["Tag2", "Tag3"],\n    "unset_tags": ["Tag1"]\n  }\n]\n```<br><br>would result in Tag2 and Tag3 being applied to this person. Tag1 would be removed if it had already been applied to this person. Otherwise, if Tag1 were not applied to this person, the tag's explicit removal (as depicted in this code example), has no effect. |
| → **clear_fields** | Object containing person field members | The clear_fields object enables you to determine which person field values will be overwritten by the data provided in the payload at fields (when the merge_strategy is set to 2). Learn more about clearing and setting a person's field values. |
| **async** | boolean | When set to true, the async element enables you to queue the ingestion of merged person data. You will receive an immediate response confirming the queued ingestion.<br><br>**IMPORTANT:** If you are sending a large number of synchronous (`"async": false`) API updates using a merge key (e.g. `"merge_by": ["str::email"]`) this can end up hitting a concurrency limit and your API requests may start to fail.<br><br>To avoid this, and to speed up the processing of the API requests, we recommend using asynchronous (`"async": true`) updates where possible, or if the person ID of the contact is known, merging by person_id instead, as it's a guaranteed unique identifier of the contact and so no lookup request is needed to search through all contacts. See Request body for an example of merging by person ID. |
| **merge_by** | array of one or two string values | The merge_by element's array allows up to three field ID values that specify the person fields used to determine whether the people's records are either created or updated in your Ortto account's CDP.<br><br>When the value of the person field member (determined by the relevant merge_by member value), submitted in this request matches that of an existing person in Ortto's CDP, then this person's record is updated in the CDP and where appropriate, existing field values are merged according to the strategy below. Otherwise, Ortto creates a new person's record in the CDP.<br><br>These values respectively override the default person fields associated with the custom API key submitted in this request. These default field values are defined by the Merge strategy associations configured for this custom API key. If a merge_by element is not specified in the request, then these default person field values are utilized instead. The first of these values determines the main merge_by person field utilized by Ortto in the request, whereas the second (optional) value determines the fallback merge_by person field (which behaves as a backup should the first field - e.g. a custom field - not be available within the person's record of Ortto's CDP).<br><br>**NOTE:** If merging by a person's ID, then `person_id` must be the only field in the merge_by array, i.e. `"merge_by": ["str::person_id"]` |
| **merge_strategy** | integer (default value is 2 Overwrite existing) | When the merge_by member value (and its corresponding person field member value) submitted in this request determines that an existing person's record in Ortto's CDP will be updated, then this merge_strategy member value determines how the person's existing field values are merged. Learn more about this value in Merge strategy below. If merge_strategy is not specified in the request, then the default value of 2 (Overwrite existing) is used. |
| **find_strategy** | integer (default value is 0 [any]) | The Find strategy determines how the merge_by fields are used in finding existing people to merge with. Learn about the different options in Find strategy below. If `find_strategy` is not specified in the request, then the default value of `0` ([any]) is used. |
| **skip_non_existing** | boolean | The skip_non_existing flag enables you to perform updates to existing contacts and not add contacts that do not already exist in your CDP.<br><br>For example, this is useful when you want to update the email address for a contact by their data source ID (in this example, a Chargebee customer ID):<br><br>```json\n"people": [\n  {\n    "fields": {\n      "str::email": "customer@email.com",\n      "str:cb:c_id": "123456789"\n    }\n  }\n],\n"async": false,\n"merge_by": ["str:cb:c_id"],\n"merge_strategy": 1,\n"find_strategy": 0,\n"skip_non_existing": true\n```<br><br>skip_non_existing works at the record level, not the field level. As such, where the person exists in the CDP, the merge strategy comes into play. Using the example above, when used in conjunction with merge-strategy:<br><br>with a value of 1 (`"merge_strategy": 1`; append), the API will only add email addresses to contact records if the email address does not already exist (it will not add new contacts).<br><br>with a value of 2 (`"merge_strategy": 2`; overwrite), the API will only overwrite exiting contact records to update existing email addresses.<br><br>**NOTE:** When using a merge key that is read-only (such as the Chargebee customer ID above: `"str:cb:c_id"`), you must use `"merge strategy": 2` (with skip_non_existing), as the API cannot write updates to a new contact based on a read-only merge key. |
| **suppression_list_field_id** | string | The suppression_list_field_id enables you to skip creating new contacts who have an email address that already exists on your Email suppression list, so you don't create a contact you won't be able to send emails too.<br><br>The value of this setting should be the field that contains the email address you want to compare against the suppression list, which in most cases will be the default email address, for example:<br><br>`"suppression_list_field_id": "str::email"`<br><br>When a contact is skipped because their email address is suppressed, you will get this response:<br><br>```json\n{\n  "status": "suppressed",\n  "error": "Email is suppressed"\n}\n``` |

### About empty values

When you use a filter to search for people, the **Has any value** filter option will find matches for activity attribute and field values that have a value of `0` or `""` (empty string). However, **Has any value** won't find attribute or field values that are `null`.

You can set values according to your needs by updating a person's data using this API endpoint (v1/person/merge). To:

For example, updating a person's field value to exclude it from search can look like this:

```json
"people": [
  {
    "fields": {
      "str::first": "John",
      "str::last": "Apple",
      "str::email": "japple@email.com",
      "str:cm:job-title": null
    }
  }
]
```

## Person fields

In Ortto, a person field:

- contains the data for a specific piece of information (i.e. field) about each person in Ortto's CDP,
- is referenced via the Ortto API using a specific ID format,
- could be a built in Ortto field or a custom field you have defined yourself (which also has its own ID format), and
- is defined as a member for each person (within their respective `fields : { … }` object) submitted in the request to this endpoint.

The following built-in person fields are accessible through Ortto's API when creating or updating people in the CDP.

| Field name | Example | Description |
|------------|---------|-------------|
| **First name** | `"str::first": "Chris"` | A string whose value is this person's first name. |
| **Last name** | `"str::last": "Smith"` | A string whose value is this person's last name. |
| **Person ID** | `"str::person_id": "00647687d2f43b25b0261f00"` | A string value representing a unique identifier for the person's CDP record. |
| **Phone number** | ```json\n"phn::phone": {\n  "c": "61",\n  "n": "401234567"\n}\n```<br>or<br>```json\n"phn::phone": {\n  "phone": "61401234567",\n  "parse_with_country_code": true\n}\n``` | A phone number field can be provided in one of two ways:<br><br>1 - an object of two members consisting of valid country code digits (c) and a phone number (n), representing this person's phone number. For the phone number, omit the initial trunk prefix/digit (e.g. 0) that is typically dialed when calling the number locally.<br><br>2 - An object of two members consisting of the phone number (phone) and whether that number should be considered to start with a country code or not (parse_with_country_code). Use this object to provide the full phone number in one field, not split out between the number and the country code. |
| **Email** | `"str::email": "chris.smith@example.com"` | A string whose value is this person's email address. This person field and its value is commonly used as the main merge_by field that determines whether a person's record in Ortto's CDP is either created or updated. This field is mandatory if the External ID field is not provided in the containing fields object. |
| **City** | ```json\n"geo::city": {\n  "name": "Melbourne"\n}\n``` | A geographical data object consisting of a member name whose string value is this person's current city. |
| **Country** | ```json\n"geo::country": {\n  "name": "Australia"\n}\n``` | A geographical data object consisting of a member name whose string value is this person's current country. |
| **Birthday** | ```json\n"dtz::b": {\n  "year": 1980,\n  "month": 3,\n  "day": 4,\n  "timezone": "Australia/Sydney"\n}\n``` | A date object consisting of members year, month and day whose respective integer values represent this person's date of birth, along with a timezone string representing the person's current time zone. |
| **Region** | ```json\n"geo::region": {\n  "name": "Victoria"\n}\n``` | A geographical data object consisting of a member name whose string value is this person's current region (e.g. state or province) within their country. |
| **Postal** | `"str::postal": "90210"` | A string whose value is this person's current postal code. |
| **External ID** | `"str::ei": "c533532fe5d16c7d4fa4c7f0"` | A string whose value is any ID used to uniquely identify this person. This value is mandatory if the email field is not provided in the containing fields object. |
| **GDPR** | `"bol::gdpr": true` | A boolean value where true flags that GDPR is a requirement for this person, or false if not. |
| **Email subscription permission** | `"bol::p": false` (default value is true) | A boolean value where true flags that the person has their Email permission set to true, or Subscribed through the Ortto user interface (UI), and false flags that this person's permission is set to false (or Unsubscribed). If this person field is not specified in the request, then this value is assumed to be true by default. |
| **Custom context message for the email unsubscribe action** | `"str::u-ctx": "Unsubscribed from email using a custom context message"` (default value is "Unsubscribed via API") | A string value that allows you to customize the default activity context message from Unsubscribed via API to something else, when setting the email subscription permission to false. These messages appear in people's Activities updates in the Ortto UI. |
| **Custom context message for the email subscribe action** | `"str::s-ctx": "Subscribed to email using a custom context message"` (default value is "Subscribed via API") | A string value that allows you to customize the default activity context message from Subscribed via API to something else, when setting the email subscription permission to true. These messages appear in people's Activities updates in the Ortto UI. |
| **SMS subscription permission** | `"bol::sp": true` (default value is false) | A boolean value where false flags that the person has their SMS permission set to false, or Unsubscribed through the Ortto UI, and true flags that this person's permission is set to true (or Subscribed). If this person field is not specified in the request, then this value is assumed to be false by default. |
| **Custom context message for the SMS subscribe action** | `"str::soi-ctx": "Subscribed to SMS using a custom context message"` (default value is "Subscribed via API") | A string value that allows you to customize the default activity context message from Subscribed via API to something else, when setting the SMS subscription permission to true. These messages appear in people's Activities updates in the Ortto UI. |
| **Custom context message for the SMS unsubscribe action** | `"str::soo-ctx": "Unsubscribed from SMS using a custom context message"` (default value is "Unsubscribed via API") | A string value that allows you to customize the default activity context message from Unsubscribed via API to something else, when setting the SMS subscription permission to false. These messages appear in people's Activities updates in the Ortto UI. |
| **Language** | `"str::language": "de"` | A string which determines the person's preferred language. This can be used to present email campaigns in the person's preferred language (where supported) using Ortto's multi-language feature.<br><br>See a list of available language values at List of languages. |
| **FCM iOS push notification token** | `"str::fcm_ios_token": "my-token"` | If a user has already given push permission to your mobile app before implementing Ortto's SDK, you can use these fields to submit the notification token to Ortto so it can be re-used for sending Ortto's push notifications without having to ask the customer for permission again. |
| **APN iOS push notification token** | `"str::apn_ios_token": "my-token"` | |
| **Android push notification token** | `"str::android_token": "my-token"` | |

### Person field ID format

Each person field is referenced by an ID.

Since Ortto integrates with many third-party products, references to person fields in Ortto's CDP are both strongly-typed and namespace-specific. Therefore, each person field's ID is based on the format:

For:

Ortto's own built-in person fields, the namespace value is unnecessary and is omitted. Hence, these built-in fields are referenced by an ID based on the format:

Up to 100 custom fields can be added to an Ortto account/instance. The field-name for custom fields is typically based on their configured names converted to kebab-case.

**NOTE:**

### Person field type abbreviations

The following person field type abbreviations are used to form the first part (type) of each person field's ID for built-in fields:

| Field type abbreviation | Type of value |
|------------------------|---------------|
| **bol** | Boolean |
| **dtz** | Date (object) |
| **geo** | Geographical data (object) |
| **int** | Integer. For internal operations and calculations, the Ortto API treats decimal values as integers multiplied by 1,000. This is done to preserve the precision of values resulting from these calculations.<br><br>**Note:** Integers are processed as int64 |
| **phn** | Phone number (object) |
| **str** | String |

## Merge strategy

The merge strategy determines how a person's existing field values are merged.

When the merge_by member value (and its corresponding person field member value) submitted in this request determines that an existing person's record in Ortto's CDP will be updated, then one of the following merge_strategy values in the request determines how the person's existing field values are merged:

| merge_strategy (integer) | Strategy | Description |
|--------------------------|----------|-------------|
| **1** | Append only | Using this strategy, all fields with existing values in Ortto's CDP are not changed. Ortto only adds new data (for fields without a value). For example, assuming you have a custom field `str:cm:place-of-birth`, and the request contains the person field value: `"str:cm:place-of-birth": "Oslo"`,<br><br>If this person's existing `str:cm:place-of-birth` value is "Sydney" in the CDP, then this existing value would not be changed after the request is submitted, and the value would remain "Sydney" in the CDP.<br><br>If, however, this person's existing `str:cm:place-of-birth` value is empty in the CDP, then this empty field would be updated to "Oslo" in the CDP. |
| **2** | Overwrite existing (default) | Using this strategy, any person fields specified in the request are updated in Ortto's CDP, even when existing values are present, and hence are overwritten. A person's field in the CDP can be cleared by specifying the corresponding person field's value in the request as null. For example, assuming you have a custom field `str:cm:place-of-birth` and this person's existing `str:cm:place-of-birth` value in the CDP is "Sydney", then a request containing the person field value:<br><br>Any person fields which are not specified in the request are not cleared (and retain their value) in the person's CDP record. |
| **3** | Ignore | Using this strategy, no updates are applied to the existing person's record in Ortto's CDP, but a new person will be created if it doesn't exist. If you do not wish to create a new person you need to provide the skip_non_existing to true.<br><br>**TIP:** Use this merge strategy to enforce only adding new people to the CDP, leaving existing people's records untouched. |

## Find strategy

The find strategy determines how the merge_by fields are used to detect an existing person match.

The find strategy is only relevant if you have 2 or more merge_by fields provided. When you have only 1 field, this setting makes no difference to the outcome. When 2 or more merge_by fields are provided, the find_strategy value determines how we utilise the fields in detecting an existing person match:

| find_strategy (integer) | Strategy | Description |
|-------------------------|----------|-------------|
| **0** | Any (default) | Using this strategy, all merge_by fields are used in detecting an existing person to merge with. For example, assuming you have `str::email` and `phn::phone` as your two merge_by fields, and provide both fields in your request:<br><br>Starting with the first merge_by field (in this case, `str::email`), try to find an existing person match using that field, and if a match is found, merge.<br><br>If a match is not found using the first field, the second field is then used (in this case, `phn::phone`) to try and find a match, and if found, merge.<br><br>If neither of the provided values find a match, then a new record would be created (depending on your merge_strategy) |
| **1** | Next only if previous empty | Using this strategy, the first merge_by field is prioritized, and the second field is only used if the first field had no value for the existing contact. For example, assuming you have `str::email` and `phn::phone` as your two merge_by fields, and provide both fields in your request:<br><br>If a match is not found using the first field, there are 2 scenarios that can happen:<br><br>There are no existing contacts who do not have a value for the first merge key (`str::email`). In this case the second field (`phn::phone`) is ignored and we do not check for a match.<br><br>There are existing contacts who do not have a value for the first merge key (`str::email`). In this case, because the first value is empty, we check the second merge key (`phn::phone`) for a match, and if a contact with no email address does match on phone number, we merge. |
| **2** | All | Using this strategy, all merge_by fields are used in detecting an existing person to merge with. For example, assuming you have `str::email` and `phn::phone` as your two merge_by fields, and provide both fields in your request:<br><br>If only one of the merge_by fields are provided for the contact in the request, then we will just match on that single field against the existing contacts. |

## Key combinations to achieve different merge strategies

When you use Ortto's user interface (UI) to import contacts, such as when you connect a data source like Salesforce or Segment, or perform a CSV import, you will be presented with a number of options for the merge strategy and merge key strategies.

If you are creating or merging contacts via the v1/person/merge endpoint, the merge strategies presented in the UI are achieved according to the merge_strategy and skip_non_existing values you use.

The equivalent combinations are:

- **Import and merge new data only:** `"merge_strategy": 1`, `"skip_non_existing": false`
- **Import and merge new data for existing records only:** `"merge_strategy": 1`, `"skip_non_existing": true`
- **Import and overwrite any data that exist (recommended):** `"merge_strategy": 2`, `"skip_non_existing": false`
- **Import and overwrite any data that exist for existing records:** `"merge_strategy": 2`, `"skip_non_existing": true`
- **Import new records only:** `"merge_strategy": 3`, `"skip_non_existing": false`

The merge key strategy is determined by the identifiers you set at merge_by and the find_strategy value.

The equivalent merge key strategies are:

- **Match only if previous merge key is empty:** `"find_strategy": 1`
- **Merge with any key match:** `"find_strategy": 0`