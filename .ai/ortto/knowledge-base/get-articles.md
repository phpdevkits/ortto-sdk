# Knowledge Base - Get Articles API

Retrieve multiple or all knowledge base articles within Ortto's customer data platform (CDP).

## Endpoint

```
POST /v1/kb/get-articles
```

## Authentication

```
X-Api-Key: CUSTOM-PRIVATE-API-KEY
Content-Type: application/json
```

## Request Parameters

No path or query parameters required. All filtering and pagination via request body.

## Request Body

All parameters are optional.

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `status` | string | No | Filter by article status: `"on"` (published), `"off"` (unpublished), or `""` (all) |
| `q` | string | No | Search term to match against article titles or descriptions |
| `limit` | integer | No | Number of articles per response (1-50, default: 50) |
| `offset` | integer | No | Pagination offset for retrieving subsequent pages |

### Example Requests

**Get all articles:**
```json
{}
```

**Filter by status:**
```json
{
  "status": "on"
}
```

**Search with pagination:**
```json
{
  "q": "getting started",
  "limit": 10,
  "offset": 0
}
```

## Response Structure

| Field | Type | Description |
|-------|------|-------------|
| `articles` | array | Collection of article objects |
| `total` | integer | Total number of articles in account |
| `offset` | integer | Current pagination position |
| `next_offset` | integer | Starting point for next page of results |
| `has_more` | boolean | Indicates if additional articles exist beyond current page |

### Article Object

| Field | Type | Description |
|-------|------|-------------|
| `id` | string | Unique article identifier |
| `title` | string | Article title |
| `description` | string | Article description (optional) |

### Example Response

```json
{
  "articles": [
    {
      "id": "650cf26b8bdeb4e9fbb12567",
      "title": "Getting Started Guide",
      "description": "Learn how to get started with our platform"
    }
  ],
  "total": 150,
  "offset": 0,
  "next_offset": 50,
  "has_more": true
}
```

## Constraints & Limitations

- **Default limit:** 50 articles per request
- **Maximum limit:** 50 articles per request
- **Pagination:** Use `offset` increments matching the `limit` value
- **Status values:** Only `"on"`, `"off"`, or `""` (empty string for all)

## Use Cases

- Retrieve all published articles: `{"status": "on"}`
- Search knowledge base: `{"q": "search term"}`
- Paginate through articles: Increment `offset` by `limit` value
- Get article IDs for use with `/kb/get-one-article` endpoint

## References

- [Official Documentation](https://help.ortto.com/a-805-retrieve-knowledge-base-articles-get-articles)
