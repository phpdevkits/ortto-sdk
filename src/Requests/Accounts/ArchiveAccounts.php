<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Requests\Accounts;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class ArchiveAccounts extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param  string[]  $accountIds  Array of account IDs to archive (UUID format)
     */
    public function __construct(
        protected array $accountIds,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/accounts/archive';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return [
            'inclusion_ids' => $this->accountIds,
        ];
    }
}
