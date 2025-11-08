<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Requests\Transactional;

use PhpDevKits\Ortto\Data\EmailAssetData;
use PhpDevKits\Ortto\Data\EmailRecipientData;
use PhpDevKits\Ortto\Enums\FindStrategy;
use PhpDevKits\Ortto\Enums\MergeStrategy;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class SendEmail extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<int, EmailRecipientData|array<string, mixed>>  $emails  Recipients with fields and optional overrides
     * @param  array<int, string>  $mergeBy  Up to 3 field IDs for create/update logic
     * @param  int|MergeStrategy  $mergeStrategy  Merge behavior (1=AppendOnly, 2=OverwriteExisting, 3=Ignore)
     * @param  EmailAssetData|array<string, mixed>|null  $asset  Inline email definition (Option 1)
     * @param  string|null  $campaignId  Campaign template ID (Option 2)
     * @param  string|null  $assetId  Asset template ID (Option 3)
     * @param  int|FindStrategy|null  $findStrategy  Record finding strategy (0=Any, 1=NextOnlyIfPreviousEmpty, 2=All)
     * @param  bool  $skipNonExisting  Skip records without existing people
     */
    public function __construct(
        protected array $emails,
        protected array $mergeBy,
        protected int|MergeStrategy $mergeStrategy,
        protected EmailAssetData|array|null $asset = null,
        protected ?string $campaignId = null,
        protected ?string $assetId = null,
        protected int|FindStrategy|null $findStrategy = null,
        protected bool $skipNonExisting = false,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/transactional/send';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        $body = [
            'emails' => array_map(
                fn (EmailRecipientData|array $email): array => $email instanceof EmailRecipientData ? $email->toArray() : $email,
                $this->emails
            ),
            'merge_by' => $this->mergeBy,
            'merge_strategy' => is_int($this->mergeStrategy) ? $this->mergeStrategy : $this->mergeStrategy->value,
            'skip_non_existing' => $this->skipNonExisting,
        ];

        // Add find_strategy if provided
        if ($this->findStrategy !== null) {
            $body['find_strategy'] = is_int($this->findStrategy) ? $this->findStrategy : $this->findStrategy->value;
        }

        // Option 2: Campaign Reference
        if ($this->campaignId !== null) {
            $body['campaign_id'] = $this->campaignId;

            return $body;
        }

        // Option 3: Asset Reference
        if ($this->assetId !== null) {
            $body['asset_id'] = $this->assetId;

            return $body;
        }

        // Option 1: Inline Email Definition
        if ($this->asset !== null) {
            $assetData = $this->asset instanceof EmailAssetData ? $this->asset->toArray() : $this->asset;
            $body = array_merge($body, $assetData);
        }

        return $body;
    }
}
