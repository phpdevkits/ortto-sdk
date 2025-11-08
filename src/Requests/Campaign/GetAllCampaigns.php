<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Requests\Campaign;

use PhpDevKits\Ortto\Enums\CampaignSortField;
use PhpDevKits\Ortto\Enums\CampaignState;
use PhpDevKits\Ortto\Enums\CampaignType;
use PhpDevKits\Ortto\Enums\SortOrder;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetAllCampaigns extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  CampaignType|string|null  $type  Single campaign type filter
     * @param  array<int, CampaignType|string>|null  $types  Multiple campaign types filter
     * @param  CampaignState|string|null  $state  Campaign state filter
     * @param  string|null  $folderId  Filter campaigns by folder ID
     * @param  array<int, string>|null  $campaignIds  Specific campaign IDs to retrieve
     * @param  int|null  $limit  Results per page (1-50, default: 50)
     * @param  int|null  $offset  Pagination offset
     * @param  string|null  $q  Search query for campaign names
     * @param  CampaignSortField|string|null  $sort  Sort field
     * @param  SortOrder|string|null  $sortOrder  Sort direction (asc/desc)
     */
    public function __construct(
        protected CampaignType|string|null $type = null,
        protected ?array $types = null,
        protected CampaignState|string|null $state = null,
        protected ?string $folderId = null,
        protected ?array $campaignIds = null,
        protected ?int $limit = null,
        protected ?int $offset = null,
        protected ?string $q = null,
        protected CampaignSortField|string|null $sort = null,
        protected SortOrder|string|null $sortOrder = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/campaign/get-all';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        $body = [];

        if ($this->type !== null) {
            $body['type'] = $this->type instanceof CampaignType
                ? $this->type->value
                : $this->type;
        }

        if ($this->types !== null) {
            $body['types'] = array_map(
                fn (CampaignType|string $type): string => $type instanceof CampaignType ? $type->value : $type,
                $this->types
            );
        }

        if ($this->state !== null) {
            $body['state'] = $this->state instanceof CampaignState
                ? $this->state->value
                : $this->state;
        }

        if ($this->folderId !== null) {
            $body['folder_id'] = $this->folderId;
        }

        if ($this->campaignIds !== null) {
            $body['campaign_ids'] = $this->campaignIds;
        }

        if ($this->limit !== null) {
            $body['limit'] = $this->limit;
        }

        if ($this->offset !== null) {
            $body['offset'] = $this->offset;
        }

        if ($this->q !== null) {
            $body['q'] = $this->q;
        }

        if ($this->sort !== null) {
            $body['sort'] = $this->sort instanceof CampaignSortField
                ? $this->sort->value
                : $this->sort;
        }

        if ($this->sortOrder !== null) {
            $body['sort_order'] = $this->sortOrder instanceof SortOrder
                ? $this->sortOrder->value
                : $this->sortOrder;
        }

        return $body;
    }
}
