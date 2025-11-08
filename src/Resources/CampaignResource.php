<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Resources;

use PhpDevKits\Ortto\Data\CampaignPeriodData;
use PhpDevKits\Ortto\Enums\Timeframe;
use PhpDevKits\Ortto\Requests\Campaign\GetCampaignCalendar;
use PhpDevKits\Ortto\Requests\Campaign\GetCampaignReports;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Throwable;

class CampaignResource extends BaseResource
{
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
