<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Requests\KnowledgeBase;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\PendingRequest;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetArticles extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  string|null  $status  Filter by article status: "on" (published), "off" (unpublished), or "" (all)
     * @param  string|null  $q  Search term to match against article titles or descriptions
     * @param  int|null  $limit  Number of articles per response (1-50, default: 50)
     * @param  int|null  $offset  Pagination offset for retrieving subsequent pages
     */
    public function __construct(
        protected ?string $status = null,
        protected ?string $q = null,
        protected ?int $limit = null,
        protected ?int $offset = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/kb/get-articles';
    }

    public function bootHasJsonBody(PendingRequest $pendingRequest): void
    {
        $pendingRequest->headers()->add('Content-Type', 'application/json');
        $this->body()->setJsonFlags(JSON_THROW_ON_ERROR | JSON_FORCE_OBJECT);
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return array_filter([
            'status' => $this->status,
            'q' => $this->q,
            'limit' => $this->limit,
            'offset' => $this->offset,
        ], fn (string|int|null $value): bool => $value !== null);
    }
}
