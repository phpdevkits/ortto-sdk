<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Requests\Accounts;

use PhpDevKits\Ortto\Enums\FindStrategy;
use PhpDevKits\Ortto\Enums\MergeStrategy;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class MergeAccounts extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<int, array<string, mixed>>  $accounts  Array of account records (1-100 max)
     * @param  string[]  $mergeBy  Field IDs specifying which account fields determine create vs. update logic
     * @param  int|MergeStrategy  $mergeStrategy  Controls how existing values merge (1=Append, 2=Overwrite [default], 3=Ignore)
     * @param  int|FindStrategy  $findStrategy  For dual merge fields: 0=Any match, 1=First field only
     */
    public function __construct(
        protected array $accounts,
        protected array $mergeBy,
        protected int|MergeStrategy $mergeStrategy = MergeStrategy::OverwriteExisting,
        protected int|FindStrategy $findStrategy = FindStrategy::Any,
        protected bool $async = false
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v1/accounts/merge';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return [
            'accounts' => $this->accounts,
            'merge_by' => $this->mergeBy,
            'merge_strategy' => is_int($this->mergeStrategy) ? $this->mergeStrategy : $this->mergeStrategy->value,
            'find_strategy' => is_int($this->findStrategy) ? $this->findStrategy : $this->findStrategy->value,
            'async' => $this->async,
        ];
    }
}
