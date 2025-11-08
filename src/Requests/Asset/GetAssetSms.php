<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Requests\Asset;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetAssetSms extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  string  $assetId  Unique identifier for the SMS asset
     * @param  string|null  $contactId  Populates merge tags using specific contact data
     * @param  bool|null  $showFallbacks  Returns merge tag fallbacks (default: false)
     * @param  bool|null  $raw  Includes full liquid syntax for merge tags (default: false)
     * @param  bool|null  $usePublished  Returns published version if true, draft if false
     */
    public function __construct(
        protected string $assetId,
        protected ?string $contactId = null,
        protected ?bool $showFallbacks = null,
        protected ?bool $raw = null,
        protected ?bool $usePublished = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/assets/get-sms';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return array_filter([
            'asset_id' => $this->assetId,
            'contact_id' => $this->contactId,
            'show_fallbacks' => $this->showFallbacks,
            'raw' => $this->raw,
            'use_published' => $this->usePublished,
        ], fn (string|bool|null $value): bool => $value !== null);
    }
}
