<?php

namespace PhpDevKits\Ortto\Resources;

use PhpDevKits\Ortto\Enums\AccountNamespace;
use PhpDevKits\Ortto\Requests\Account\GetAccountSchema;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Throwable;

class AccountResource extends BaseResource
{
    /**
     * Get instance schema for one or more namespaces.
     *
     * @param  array<int, AccountNamespace|string>  $namespaces  Array of namespace IDs. Empty array returns all namespaces.
     *
     * @throws Throwable
     */
    public function getSchema(
        array $namespaces = [],
    ): Response {
        return $this->connector->send(
            request: new GetAccountSchema(
                namespaces: $namespaces,
            ),
        );
    }
}
