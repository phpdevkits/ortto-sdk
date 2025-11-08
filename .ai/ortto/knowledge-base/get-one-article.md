# Knowledge Base - Get One Article API

Retrieve a single knowledge base article by ID within Ortto's customer data platform (CDP).

## Endpoint

```
POST /v1/kb/get-one-article
```

## Authentication

```
X-Api-Key: CUSTOM-PRIVATE-API-KEY
Content-Type: application/json
```

## Request Parameters

No path or query parameters required.

## Request Body

All fields in the request body are required.

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | string | Yes | Article unique identifier |

### Example Request

```json
{
  "id": "650cf26b8bdeb4e9fbb12567"
}
```

## Response Structure

| Field | Type | Description |
|-------|------|-------------|
| `id` | string | Unique article identifier |
| `title` | string | Article title |
| `description` | string | Article description content |
| `html` | string | Full HTML content including head, title, body elements |

### Example Response

```json
{
  "id": "650cf26b8bdeb4e9fbb12567",
  "title": "Title",
  "description": "Testing",
  "html": "<full html>"
}
```

## Notes

- Article IDs can be located through the Ortto UI
- Article IDs can also be retrieved via the `/kb/get-articles` endpoint
- Returns complete HTML document including all containing elements

## References

- [Official Documentation](https://help.ortto.com/a-811-retrieve-one-knowledge-base-article-get-one-article)
