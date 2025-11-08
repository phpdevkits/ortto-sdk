<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Resources;

use PhpDevKits\Ortto\Enums\ArticleStatus;
use PhpDevKits\Ortto\Requests\KnowledgeBase\GetArticle;
use PhpDevKits\Ortto\Requests\KnowledgeBase\GetArticles;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Throwable;

class KnowledgeBaseResource extends BaseResource
{
    /**
     * Retrieve a single knowledge base article by ID.
     *
     * Returns complete article content including HTML.
     *
     * @param  string  $id  Article unique identifier
     *
     * @throws Throwable
     */
    public function getArticle(string $id): Response
    {
        return $this->connector->send(
            request: new GetArticle(id: $id),
        );
    }

    /**
     * Retrieve multiple or all knowledge base articles.
     *
     * Returns paginated list of articles with filtering and search capabilities.
     *
     * @param  ArticleStatus|string|null  $status  Filter by article status
     * @param  string|null  $q  Search term to match against article titles or descriptions
     * @param  int|null  $limit  Number of articles per response (1-50, default: 50)
     * @param  int|null  $offset  Pagination offset for retrieving subsequent pages
     *
     * @throws Throwable
     */
    public function getArticles(
        ArticleStatus|string|null $status = null,
        ?string $q = null,
        ?int $limit = null,
        ?int $offset = null,
    ): Response {
        return $this->connector->send(
            request: new GetArticles(
                status: $status,
                q: $q,
                limit: $limit,
                offset: $offset,
            ),
        );
    }
}
