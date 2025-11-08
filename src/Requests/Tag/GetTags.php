<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Requests\Tag;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\PendingRequest;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetTags extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  string|null  $q  Search term for filtering tags (token-based AND logic)
     */
    public function __construct(
        protected ?string $q = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/tags/get';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        $body = [];

        if ($this->q !== null) {
            $body['q'] = $this->q;
        }

        return $body;
    }

    public function bootHasJsonBody(PendingRequest $pendingRequest): void
    {
        // Set Content-Type header
        $pendingRequest->headers()->add('Content-Type', 'application/json');

        // Force empty arrays to encode as JSON objects
        $this->body()->setJsonFlags(JSON_THROW_ON_ERROR | JSON_FORCE_OBJECT);
    }
}
