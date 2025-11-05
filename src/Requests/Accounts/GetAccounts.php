<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Requests\Accounts;

use PhpDevKits\Ortto\Enums\SortOrder;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetAccounts extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  int|null  $limit  Page size for pagination (1-500, default 50)
     * @param  int|null  $offset  Starting position for offset-based pagination
     * @param  string|null  $cursorId  Cursor ID for cursor-based pagination
     * @param  string|null  $sortByFieldId  Field ID to sort results by
     * @param  string|SortOrder|null  $sortOrder  Sort direction: asc or desc
     * @param  string[]|null  $fields  Account field IDs to retrieve
     * @param  array<string, mixed>|null  $filter  Filtering conditions
     * @param  string|null  $q  Simple text search by account name
     * @param  string|null  $type  Filter by type: "" (all), "account", or "archived_account"
     * @param  string[]|null  $inclusionIds  Specific account IDs to include
     * @param  string[]|null  $exclusionIds  Specific account IDs to exclude
     */
    public function __construct(
        protected ?int $limit = null,
        protected ?int $offset = null,
        protected ?string $cursorId = null,
        protected ?string $sortByFieldId = null,
        protected string|SortOrder|null $sortOrder = null,
        protected ?array $fields = null,
        protected ?array $filter = null,
        protected ?string $q = null,
        protected ?string $type = null,
        protected ?array $inclusionIds = null,
        protected ?array $exclusionIds = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/accounts/get';
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

        if ($this->offset !== null) {
            $body['offset'] = $this->offset;
        }

        if ($this->cursorId !== null) {
            $body['cursor_id'] = $this->cursorId;
        }

        if ($this->sortByFieldId !== null) {
            $body['sort_by_field_id'] = $this->sortByFieldId;
        }

        if ($this->sortOrder !== null) {
            $body['sort_order'] = is_string($this->sortOrder) ? $this->sortOrder : $this->sortOrder->value;
        }

        if ($this->fields !== null) {
            $body['fields'] = $this->fields;
        }

        if ($this->filter !== null) {
            $body['filter'] = $this->filter;
        }

        if ($this->q !== null) {
            $body['q'] = $this->q;
        }

        if ($this->type !== null) {
            $body['type'] = $this->type;
        }

        if ($this->inclusionIds !== null) {
            $body['inclusion_ids'] = $this->inclusionIds;
        }

        if ($this->exclusionIds !== null) {
            $body['exclusion_ids'] = $this->exclusionIds;
        }

        return $body;
    }
}
