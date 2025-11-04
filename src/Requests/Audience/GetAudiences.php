<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Requests\Audience;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetAudiences extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected ?string $searchTerm = null,
        protected ?bool $withFilter = null,
        protected ?int $limit = null,
        protected ?int $offset = null,
        protected ?bool $archived = null,
        protected ?bool $retention = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/audiences/get';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        $body = [];

        if ($this->searchTerm !== null) {
            $body['search_term'] = $this->searchTerm;
        }

        if ($this->withFilter !== null) {
            $body['with_filter'] = $this->withFilter;
        }

        if ($this->limit !== null) {
            $body['limit'] = $this->limit;
        }

        if ($this->offset !== null) {
            $body['offset'] = $this->offset;
        }

        if ($this->archived !== null) {
            $body['archived'] = $this->archived;
        }

        if ($this->retention !== null) {
            $body['retention'] = $this->retention;
        }

        return $body;
    }
}
