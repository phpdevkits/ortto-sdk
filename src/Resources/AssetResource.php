<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Resources;

use PhpDevKits\Ortto\Requests\Asset\GetAssetHtml;
use PhpDevKits\Ortto\Requests\Asset\GetAssetSms;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Throwable;

class AssetResource extends BaseResource
{
    /**
     * Retrieve the HTML content and email metadata for a specified asset.
     *
     * Returns complete HTML content including from_email, from_name, subject, preview, and reply_to fields.
     *
     * @param  string  $assetId  The unique identifier for the asset to retrieve
     *
     * @throws Throwable
     */
    public function getHtml(string $assetId): Response
    {
        return $this->connector->send(
            request: new GetAssetHtml(assetId: $assetId),
        );
    }

    /**
     * Retrieve content and details from an SMS asset.
     *
     * Returns message body, character count, encoding type, segment count, and mapped links.
     *
     * @param  string  $assetId  Unique identifier for the SMS asset
     * @param  string|null  $contactId  Populates merge tags using specific contact data
     * @param  bool|null  $showFallbacks  Returns merge tag fallbacks (default: false)
     * @param  bool|null  $raw  Includes full liquid syntax for merge tags (default: false)
     * @param  bool|null  $usePublished  Returns published version if true, draft if false
     *
     * @throws Throwable
     */
    public function getSms(
        string $assetId,
        ?string $contactId = null,
        ?bool $showFallbacks = null,
        ?bool $raw = null,
        ?bool $usePublished = null,
    ): Response {
        return $this->connector->send(
            request: new GetAssetSms(
                assetId: $assetId,
                contactId: $contactId,
                showFallbacks: $showFallbacks,
                raw: $raw,
                usePublished: $usePublished,
            ),
        );
    }
}
