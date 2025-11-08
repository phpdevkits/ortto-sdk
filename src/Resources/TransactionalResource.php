<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Resources;

use PhpDevKits\Ortto\Data\EmailAssetData;
use PhpDevKits\Ortto\Data\EmailRecipientData;
use PhpDevKits\Ortto\Enums\FindStrategy;
use PhpDevKits\Ortto\Enums\MergeStrategy;
use PhpDevKits\Ortto\Requests\Transactional\SendEmail;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Throwable;

class TransactionalResource extends BaseResource
{
    /**
     * Send transactional emails using inline email definition, campaign reference, or asset reference.
     *
     * Supports three approaches:
     * 1. Inline: Use EmailAssetData with full email definition (from_email, subject, html_body, etc.)
     * 2. Campaign Reference: Use campaign_id to reference a campaign template
     * 3. Asset Reference: Use asset_id to reference an asset template
     *
     * @param  array<int, EmailRecipientData|array<string, mixed>>  $emails  Recipients with fields and optional overrides
     * @param  array<int, string>  $mergeBy  Up to 3 field IDs for create/update logic
     * @param  int|MergeStrategy  $mergeStrategy  Merge behavior (1=AppendOnly, 2=OverwriteExisting, 3=Ignore)
     * @param  EmailAssetData|array<string, mixed>|null  $asset  Inline email definition (Option 1)
     * @param  string|null  $campaignId  Campaign template ID (Option 2)
     * @param  string|null  $assetId  Asset template ID (Option 3)
     * @param  int|FindStrategy|null  $findStrategy  Record finding strategy (0=Any, 1=NextOnlyIfPreviousEmpty, 2=All)
     * @param  bool  $skipNonExisting  Skip records without existing people
     *
     * @throws Throwable
     */
    public function sendEmail(
        array $emails,
        array $mergeBy,
        int|MergeStrategy $mergeStrategy,
        EmailAssetData|array|null $asset = null,
        ?string $campaignId = null,
        ?string $assetId = null,
        int|FindStrategy|null $findStrategy = null,
        bool $skipNonExisting = false,
    ): Response {
        return $this->connector->send(
            request: new SendEmail(
                emails: $emails,
                mergeBy: $mergeBy,
                mergeStrategy: $mergeStrategy,
                asset: $asset,
                campaignId: $campaignId,
                assetId: $assetId,
                findStrategy: $findStrategy,
                skipNonExisting: $skipNonExisting,
            ),
        );
    }
}
