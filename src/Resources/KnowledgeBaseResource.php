<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Resources;

use PhpDevKits\Ortto\Requests\KnowledgeBase\GetArticle;
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
}
