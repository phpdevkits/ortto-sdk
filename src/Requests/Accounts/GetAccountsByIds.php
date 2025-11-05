<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Requests\Accounts;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetAccountsByIds extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  string[]  $accountIds  Array of account IDs to retrieve (UUID format)
     * @param  string[]  $fields  Account field IDs to retrieve (max 20 fields, required)
     */
    public function __construct(
        protected array $accountIds,
        protected array $fields,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/accounts/get-by-ids';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return [
            'account_ids' => $this->accountIds,
            'fields' => $this->fields,
        ];
    }
}
