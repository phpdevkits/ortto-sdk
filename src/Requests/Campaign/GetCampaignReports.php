<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Requests\Campaign;

use PhpDevKits\Ortto\Enums\CampaignTimeframe;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\PendingRequest;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetCampaignReports extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  string|null  $campaignId  Campaign identifier for the report
     * @param  string|null  $assetId  Specific asset, shape, or message within campaign
     * @param  string|null  $shapeId  Journey shape identifier for individual shape reports
     * @param  string|null  $messageId  Playbook email message identifier
     * @param  CampaignTimeframe|string|null  $timeframe  Report data period
     */
    public function __construct(
        protected ?string $campaignId = null,
        protected ?string $assetId = null,
        protected ?string $shapeId = null,
        protected ?string $messageId = null,
        protected CampaignTimeframe|string|null $timeframe = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/campaign/reports/get';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        $body = [];

        if ($this->campaignId !== null) {
            $body['campaign_id'] = $this->campaignId;
        }

        if ($this->assetId !== null) {
            $body['asset_id'] = $this->assetId;
        }

        if ($this->shapeId !== null) {
            $body['shape_id'] = $this->shapeId;
        }

        if ($this->messageId !== null) {
            $body['message_id'] = $this->messageId;
        }

        if ($this->timeframe !== null) {
            $body['timeframe'] = $this->timeframe instanceof CampaignTimeframe
                ? $this->timeframe->value
                : $this->timeframe;
        }

        return $body;
    }

    public function bootHasJsonBody(PendingRequest $pendingRequest): void
    {
        $pendingRequest->headers()->add('Content-Type', 'application/json');
        $this->body()->setJsonFlags(JSON_THROW_ON_ERROR | JSON_FORCE_OBJECT);
    }
}
