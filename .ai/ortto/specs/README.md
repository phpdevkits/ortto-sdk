# Ortto API OpenAPI Specification

This directory contains the OpenAPI 3.1 specification for the Ortto CDP REST API.

## Files

- **openapi.yaml** - Complete OpenAPI specification covering Person, Audience, and Account endpoints

## Coverage

The OpenAPI specification currently includes:

### Person Endpoints
- `POST /v1/person/merge` - Create or update people
- `POST /v1/person/get` - Retrieve people with filters
- `POST /v1/person/get-by-ids` - Retrieve people by IDs
- `PUT /v1/person/archive` - Archive people
- `PUT /v1/person/restore` - Restore archived people
- `DELETE /v1/person/delete` - Delete archived people
- `POST /v1/person/subscriptions` - Get people's subscription statuses

### Audience Endpoints
- `POST /v1/audiences/get` - Get list of audiences
- `PUT /v1/audience/subscribe` - Subscribe/unsubscribe people to/from audience

### Account Endpoints (Instance Schema)
- `POST /v1/instance-schema/get` - Retrieve instance schema and field definitions

### Accounts Endpoints (Organizations)
- `POST /v1/accounts/merge` - Create or update organizations

## Key Features

### Authentication
All endpoints require API key authentication via the `X-Api-Key` header.

### Regional Servers
The spec defines three server endpoints:
- **AP3 (Default)**: `https://api.ap3api.com/v1`
- **Australia**: `https://api.au1api.com/v1`
- **Europe**: `https://api.eu1api.com/v1`

### Request/Response Examples
Each endpoint includes:
- Detailed parameter descriptions
- Request body schemas with examples
- Response schemas with examples
- Error response formats

### Field Types

**Person Fields:**
- String fields (`str::`)
- Boolean fields (`bol::`)
- Integer fields (`int::`)
- Phone number fields (`phn::`)
- Geographical fields (`geo::`)
- Date/time fields (`dtz::`)

**Account Fields (Organizations):**
- Built-in fields (`str:o:`, `int:o:`, `geo:o:`)
  - Examples: `str:o:name`, `str:o:website`, `int:o:employees`
- Custom fields (`str:oc:`, `int:oc:`, etc.)
  - Up to 100 custom fields supported

## Usage

### Validation
You can validate the OpenAPI spec using:

```bash
# Using openapi-spec-validator
pip install openapi-spec-validator
openapi-spec-validator .ai/ortto/specs/openapi.yaml

# Using Swagger CLI
npm install -g @apidevtools/swagger-cli
swagger-cli validate .ai/ortto/specs/openapi.yaml
```

### Code Generation
Generate client SDKs using OpenAPI Generator:

```bash
# Generate PHP client
openapi-generator-cli generate \
  -i .ai/ortto/specs/openapi.yaml \
  -g php \
  -o generated/php-client

# Generate TypeScript client
openapi-generator-cli generate \
  -i .ai/ortto/specs/openapi.yaml \
  -g typescript-fetch \
  -o generated/typescript-client
```

### Documentation
Generate interactive API documentation:

```bash
# Using Redoc
npx redoc-cli bundle .ai/ortto/specs/openapi.yaml -o docs/api.html

# Using Swagger UI
docker run -p 8080:8080 \
  -e SWAGGER_JSON=/spec/openapi.yaml \
  -v $(pwd)/.ai/ortto/specs:/spec \
  swaggerapi/swagger-ui
```

## Future Endpoints

The following endpoints are known to exist but lack detailed documentation:

### Account/Organization Management (Pending Documentation)
- `POST /v1/accounts/get` - Retrieve one or more accounts
- `POST /v1/accounts/get-by-ids` - Retrieve accounts by their IDs
- `POST /v1/accounts/merge` - Create or update multiple accounts
- `PUT /v1/accounts/archive` - Archive accounts
- `PUT /v1/accounts/restore` - Restore archived accounts
- `DELETE /v1/accounts/delete` - Delete accounts
- `POST /v1/accounts/contacts/add` - Add contacts to an account
- `POST /v1/accounts/contacts/remove` - Remove contacts from an account

### Other Entities (Planned)
- Activity tracking endpoints
- Campaign management endpoints
- Custom fields management endpoints
- Tags management endpoints

## Resources

- [Ortto API Documentation](https://help.ortto.com/developer/latest/)
- [OpenAPI 3.1 Specification](https://spec.openapis.org/oas/v3.1.0)
- [OpenAPI Generator](https://openapi-generator.tech/)

## Maintenance

This OpenAPI specification is maintained alongside the local Ortto API documentation in `.ai/ortto/`. When updating:

1. Check corresponding markdown documentation in `.ai/ortto/{resource}/`
2. Review test fixtures in `tests/Fixtures/Saloon/{resource}/`
3. Validate changes against actual SDK implementation in `src/Requests/`
4. Update this README if new endpoints are added

## Related Documentation

- **Implementation Plan**: `.ai/specs/account-api-implementation-plan.md` - Detailed plan for Account API implementation
- **Task Checklist**: `.ai/specs/account-api-tasks.md` - Comprehensive checklist for Account API tasks
- **Account Docs**: `.ai/ortto/account/` - Markdown documentation for account endpoints

## Notes

- All timestamps use ISO 8601 format
- Field IDs follow format: `{type}::{field}` (built-in) or `{type}:cm:{field}` (custom)
- Merge strategies: 1=Append, 2=Overwrite (default), 3=Ignore
- Find strategies: 0=Any (default), 1=Next only if previous empty, 2=All
- Person records support batch operations (typically 1-100 per request)
- Namespace IDs: `cm` (custom), `sf` (Salesforce), `sh` (Shopify), `st` (Stripe), etc.
