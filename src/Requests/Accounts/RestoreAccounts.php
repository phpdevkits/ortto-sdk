<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Requests\Accounts;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class RestoreAccounts extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param  array<string>  $accountIds
     */
    public function __construct(
        protected array $accountIds,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/accounts/restore';
    }

    /**
     * @return array<string, array<string>>
     */
    protected function defaultBody(): array
    {
        return [
            'inclusion_ids' => $this->accountIds,
        ];
    }
}
