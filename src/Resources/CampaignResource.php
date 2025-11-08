<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Resources;

use PhpDevKits\Ortto\Enums\CampaignTimeframe;
use PhpDevKits\Ortto\Requests\Campaign\GetCampaignReports;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Throwable;

class CampaignResource extends BaseResource
{
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
     * @param  CampaignTimeframe|string|null  $timeframe  Report data period
     *
     * @throws Throwable
     */
    public function getReports(
        ?string $campaignId = null,
        ?string $assetId = null,
        ?string $shapeId = null,
        ?string $messageId = null,
        CampaignTimeframe|string|null $timeframe = null,
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
