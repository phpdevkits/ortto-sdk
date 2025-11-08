<?php

namespace PhpDevKits\Ortto;

use InvalidArgumentException;
use PhpDevKits\Ortto\Resources\AccountResource;
use PhpDevKits\Ortto\Resources\AccountsResource;
use PhpDevKits\Ortto\Resources\ActivityResource;
use PhpDevKits\Ortto\Resources\AssetResource;
use PhpDevKits\Ortto\Resources\CampaignResource;
use PhpDevKits\Ortto\Resources\KnowledgeBaseResource;
use PhpDevKits\Ortto\Resources\PersonResource;
use PhpDevKits\Ortto\Resources\TagResource;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;

class Ortto extends Connector
{
    use AcceptsJson;

    /**
     * The Base URL of the API
     *
     * @throws InvalidArgumentException
     */
    public function resolveBaseUrl(): string
    {
        $url = config()->string('ortto.url');

        if (empty($url)) {
            throw new InvalidArgumentException('Ortto URL not found');
        }

        return $url;
    }

    /**
     * Default headers for every request
     *
     * @throws InvalidArgumentException
     */
    protected function defaultHeaders(): array
    {
        return [
            'x-api-key' => config()->string('ortto.api_key', ''),
            'content-type' => 'application/json',
        ];
    }

    /**
     * @throws InvalidArgumentException
     */
    public function person(): PersonResource
    {
        /** @var class-string<PersonResource> $class */
        $class = config()->string('ortto.resources.person', PersonResource::class);

        return new $class(connector: $this);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function account(): AccountResource
    {
        /** @var class-string<AccountResource> $class */
        $class = config()->string('ortto.resources.account', AccountResource::class);

        return new $class(connector: $this);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function accounts(): AccountsResource
    {
        /** @var class-string<AccountsResource> $class */
        $class = config()->string('ortto.resources.accounts', AccountsResource::class);

        return new $class(connector: $this);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function activity(): ActivityResource
    {
        /** @var class-string<ActivityResource> $class */
        $class = config()->string('ortto.resources.activity', ActivityResource::class);

        return new $class(connector: $this);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function asset(): AssetResource
    {
        /** @var class-string<AssetResource> $class */
        $class = config()->string('ortto.resources.asset', AssetResource::class);

        return new $class(connector: $this);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function campaign(): CampaignResource
    {
        /** @var class-string<CampaignResource> $class */
        $class = config()->string('ortto.resources.campaign', CampaignResource::class);

        return new $class(connector: $this);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function knowledgeBase(): KnowledgeBaseResource
    {
        /** @var class-string<KnowledgeBaseResource> $class */
        $class = config()->string('ortto.resources.knowledge_base', KnowledgeBaseResource::class);

        return new $class(connector: $this);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function tag(): TagResource
    {
        /** @var class-string<TagResource> $class */
        $class = config()->string('ortto.resources.tag', TagResource::class);

        return new $class(connector: $this);
    }
}
