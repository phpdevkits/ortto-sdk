<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Requests\Person;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class DeletePeople extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::DELETE;

    /**
     * @param  string[]|null  $inclusionIds
     * @param  string[]|null  $exclusionIds
     */
    public function __construct(
        protected ?array $inclusionIds = null,
        protected ?array $exclusionIds = null,
        protected bool $allRowsSelected = false,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/person/delete';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        $body = [];

        if ($this->inclusionIds !== null) {
            $body['inclusion_ids'] = $this->inclusionIds;
        }

        if ($this->exclusionIds !== null) {
            $body['exclusion_ids'] = $this->exclusionIds;
        }

        if ($this->allRowsSelected) {
            $body['all_rows_selected'] = $this->allRowsSelected;
        }

        return $body;
    }
}
