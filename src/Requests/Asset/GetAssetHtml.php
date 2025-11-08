<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Requests\Asset;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetAssetHtml extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  string  $assetId  The unique identifier for the asset to retrieve
     */
    public function __construct(
        protected string $assetId,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/assets/get-html';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return [
            'asset_id' => $this->assetId,
        ];
    }
}
