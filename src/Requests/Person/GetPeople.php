<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Requests\Person;

use PhpDevKits\Ortto\Enums\SortOrder;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetPeople extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  string[]|null  $fields
     * @param  array<string, mixed>|null  $filter
     */
    public function __construct(
        protected array $fields,
        protected ?int $limit = 100,
        protected ?string $sortByFieldId = null,
        protected string|SortOrder|null $sortOrder = null,
        protected ?int $offset = null,
        protected ?string $cursorId = null,
        protected ?string $q = null,
        protected ?string $type = null,
        protected ?array $filter = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/person/get';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        $body = [];

        if ($this->limit !== null) {
            $body['limit'] = $this->limit;
        }

        if ($this->sortByFieldId !== null) {
            $body['sort_by_field_id'] = $this->sortByFieldId;
        }

        if ($this->sortOrder !== null) {
            $body['sort_order'] = is_string($this->sortOrder) ? $this->sortOrder : $this->sortOrder->value;
        }

        if ($this->offset !== null) {
            $body['offset'] = $this->offset;
        }

        if ($this->cursorId !== null) {
            $body['cursor_id'] = $this->cursorId;
        }

        if ($this->fields !== null) {
            $body['fields'] = $this->fields;
        }

        if ($this->q !== null) {
            $body['q'] = $this->q;
        }

        if ($this->type !== null) {
            $body['type'] = $this->type;
        }

        if ($this->filter !== null) {
            $body['filter'] = $this->filter;
        }

        return $body;
    }
}
