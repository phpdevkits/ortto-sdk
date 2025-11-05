<?php

namespace PhpDevKits\Ortto\Resources;

use PhpDevKits\Ortto\Enums\FindStrategy;
use PhpDevKits\Ortto\Enums\MergeStrategy;
use PhpDevKits\Ortto\Requests\Accounts\MergeAccounts;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Throwable;

class AccountsResource extends BaseResource
{
    /**
     * Create or update one or more organizations (accounts).
     *
     * @param  array<int, array<string, mixed>>  $accounts  Array of account records (1-100 max)
     * @param  string[]  $mergeBy  Field IDs specifying which account fields determine create vs. update logic
     * @param  int|MergeStrategy  $mergeStrategy  Controls how existing values merge (1=Append, 2=Overwrite [default], 3=Ignore)
     * @param  int|FindStrategy  $findStrategy  For dual merge fields: 0=Any match, 1=First field only
     *
     * @throws Throwable
     */
    public function merge(
        array $accounts,
        array $mergeBy,
        int|MergeStrategy $mergeStrategy = MergeStrategy::OverwriteExisting,
        int|FindStrategy $findStrategy = FindStrategy::Any,
        bool $async = false
    ): Response {
        return $this->connector->send(
            request: new MergeAccounts(
                accounts: $accounts,
                mergeBy: $mergeBy,
                mergeStrategy: $mergeStrategy,
                findStrategy: $findStrategy,
                async: $async,
            ),
        );
    }
}
