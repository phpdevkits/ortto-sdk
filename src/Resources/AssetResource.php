<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Resources;

use PhpDevKits\Ortto\Requests\Asset\GetAssetHtml;
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
}
