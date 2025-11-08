<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Resources;

use PhpDevKits\Ortto\Requests\Tag\GetTags;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Throwable;

class TagResource extends BaseResource
{
    /**
     * Retrieve a list of tags.
     *
     * Returns all tags or filters by search term using token-based AND logic.
     * All tokens must match anywhere in tag names (case-insensitive).
     *
     * @param  string|null  $q  Search term for filtering tags
     *
     * @throws Throwable
     */
    public function get(?string $q = null): Response
    {
        return $this->connector->send(
            request: new GetTags(
                q: $q,
            ),
        );
    }
}
