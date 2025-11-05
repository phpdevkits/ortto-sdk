<?php

namespace PhpDevKits\Ortto;

use InvalidArgumentException;
use PhpDevKits\Ortto\Resources\AccountResource;
use PhpDevKits\Ortto\Resources\AccountsResource;
use PhpDevKits\Ortto\Resources\PersonResource;
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
     */
    protected function defaultHeaders(): array
    {
        return [
            'x-api-key' => config()->string('ortto.api_key', ''),
            'content-type' => 'application/json',
        ];
    }

    public function person(): PersonResource
    {
        /** @var class-string<PersonResource> $class */
        $class = config()->string('ortto.resources.person', PersonResource::class);

        return new $class(connector: $this);
    }

    public function account(): AccountResource
    {
        /** @var class-string<AccountResource> $class */
        $class = config()->string('ortto.resources.account', AccountResource::class);

        return new $class(connector: $this);
    }

    public function accounts(): AccountsResource
    {
        /** @var class-string<AccountsResource> $class */
        $class = config()->string('ortto.resources.accounts', AccountsResource::class);

        return new $class(connector: $this);
    }
}
