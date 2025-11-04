# Retrieve account instance schema

The get Ortto endpoint of the instance-schema entity is used to retrieve schema data within Ortto's customer data platform (CDP).

## HTTP method and request resource

```
POST https://api.ap3api.com/v1/instance-schema/get
```

**NOTE**: Ortto customers who have their instance region set to Australia or Europe will need to use specific service endpoints relative to the region:

- Australia: `https://api.au.ap3api.com/v1/instance-schema/get`
- Europe: `https://api.eu.ap3api.com/v1/instance-schema/get`

All other Ortto users will use the default service endpoint (`https://api.ap3api.com/`).

## Path and query parameters

This endpoint takes no additional path and/or query parameters.

## Headers

This endpoint requires a custom API key and content type (`application/json` for the request body) in the header of the request:

- `X-Api-Key: CUSTOM-PRIVATE-API-KEY`
- `Content-Type: application/json`

## Request body

The request body consists of a JSON object whose valid elements are listed in the table below.

### Example request body - All namespaces

```json
{
  "namespaces": []
}
```

### Example request body - Specific namespace

```json
{
  "namespaces": ["cm"]
}
```

## Valid request body elements

| Element | Type | Description |
|---------|------|-------------|
| **namespaces** | array of strings | Array of namespace IDs to retrieve schema for. Empty array returns all namespaces. |

## Supported Namespaces

The endpoint supports 40+ namespace IDs including:

- `cm` - Custom activities, attributes, and fields
- `a2` - Autopilot Journeys
- `sf` - Salesforce (various object types)
- `sh` - Shopify
- `st` - Stripe
- `zd` - Zendesk
- `ghl` - GoHighLevel
- `hs` - HubSpot
- `itbl` - Iterable
- `klaviyo` - Klaviyo
- `pipedrive` - Pipedrive
- And many other integration-specific namespaces

## Response structure

The response returns a JSON object with nested namespace data containing schema definitions.

### Response fields

| Field | Type | Description |
|-------|------|-------------|
| **fields** | object | Schema definitions indexed by field ID |
| **id** | string | Namespace identifier |
| **display_type** | string | Field classification (activity, text, geo, bool, date, etc.) |
| **attributes** | object | Sub-fields with liquid variable names for template usage |
| **triggers** | array | Conditional logic operations (set, set_if_greater, set_if_less) |

### Example response structure

```json
{
  "cm": {
    "fields": {
      "str:cm:custom-field": {
        "id": "str:cm:custom-field",
        "display_type": "text",
        "name": "Custom Field",
        "attributes": {
          "value": {
            "liquid_variable": "{{contact.field.cm.custom_field}}"
          }
        }
      }
    }
  }
}
```

## Notes

- Response structure varies based on requested namespace and account configuration
- Use empty namespaces array to retrieve all available schemas
- Field definitions include liquid template variables for use in campaigns
- Useful for discovering available custom fields and integration-specific data structures
