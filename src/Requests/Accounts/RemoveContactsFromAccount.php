<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Requests\Accounts;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class RemoveContactsFromAccount extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param  array<string>  $personIds
     */
    public function __construct(
        protected string $accountId,
        protected array $personIds,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/accounts/contacts/remove';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return [
            'account_id' => $this->accountId,
            'person_ids' => $this->personIds,
        ];
    }
}
