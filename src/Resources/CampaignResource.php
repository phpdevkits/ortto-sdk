<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Resources;

use PhpDevKits\Ortto\Data\CampaignPeriodData;
use PhpDevKits\Ortto\Enums\CampaignSortField;
use PhpDevKits\Ortto\Enums\CampaignState;
use PhpDevKits\Ortto\Enums\CampaignType;
use PhpDevKits\Ortto\Enums\SortOrder;
use PhpDevKits\Ortto\Enums\Timeframe;
use PhpDevKits\Ortto\Requests\Campaign\GetAllCampaigns;
use PhpDevKits\Ortto\Requests\Campaign\GetCampaignCalendar;
use PhpDevKits\Ortto\Requests\Campaign\GetCampaignReports;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Throwable;

class CampaignResource extends BaseResource
{
    /**
     * Export campaign data for auditing and external analysis.
     *
     * Retrieve campaign metadata, performance metrics, and filter by type, state, or folder.
     * Supports pagination, search, and sorting across multiple campaign types.
     *
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
     *
     * @throws Throwable
     */
    public function getAllCampaigns(
        CampaignType|string|null $type = null,
        ?array $types = null,
        CampaignState|string|null $state = null,
        ?string $folderId = null,
        ?array $campaignIds = null,
        ?int $limit = null,
        ?int $offset = null,
        ?string $q = null,
        CampaignSortField|string|null $sort = null,
        SortOrder|string|null $sortOrder = null,
    ): Response {
        return $this->connector->send(
            request: new GetAllCampaigns(
                type: $type,
                types: $types,
                state: $state,
                folderId: $folderId,
                campaignIds: $campaignIds,
                limit: $limit,
                offset: $offset,
                q: $q,
                sort: $sort,
                sortOrder: $sortOrder,
            ),
        );
    }

    /**
     * Retrieve list of sent and scheduled campaigns.
     *
     * Returns campaigns within a specified date range for the given timezone.
     *
     * @param  CampaignPeriodData|array{year: int, month: int}  $start  Start period (year and month)
     * @param  CampaignPeriodData|array{year: int, month: int}  $end  End period (year and month)
     * @param  string  $timezone  Timezone for campaign list (e.g., "Australia/Sydney")
     *
     * @throws Throwable
     */
    public function getCalendar(CampaignPeriodData|array $start, CampaignPeriodData|array $end, string $timezone): Response
    {
        return $this->connector->send(
            request: new GetCampaignCalendar(
                start: $start,
                end: $end,
                timezone: $timezone,
            ),
        );
    }

    /**
     * Retrieve campaign and asset reports.
     *
     * Get performance metrics, analytics, and attribution data for campaigns.
     * Supports single-send campaigns, A/B variants, journeys, and playbooks.
     *
     * @param  string|null  $campaignId  Campaign identifier for the report
     * @param  string|null  $assetId  Specific asset, shape, or message within campaign
     * @param  string|null  $shapeId  Journey shape identifier for individual shape reports
     * @param  string|null  $messageId  Playbook email message identifier
     * @param  Timeframe|string|null  $timeframe  Report data period
     *
     * @throws Throwable
     */
    public function getReports(
        ?string $campaignId = null,
        ?string $assetId = null,
        ?string $shapeId = null,
        ?string $messageId = null,
        Timeframe|string|null $timeframe = null,
    ): Response {
        return $this->connector->send(
            request: new GetCampaignReports(
                campaignId: $campaignId,
                assetId: $assetId,
                shapeId: $shapeId,
                messageId: $messageId,
                timeframe: $timeframe,
            ),
        );
    }
}
