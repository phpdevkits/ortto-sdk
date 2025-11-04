<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Requests\Account;

use PhpDevKits\Ortto\Enums\AccountNamespace;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetAccountSchema extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<int, AccountNamespace|string>  $namespaces  Array of namespace IDs to retrieve. Empty array returns all namespaces.
     */
    public function __construct(
        protected array $namespaces = [],
    ) {}

    public function resolveEndpoint(): string
    {
        return '/instance-schema/get';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return [
            'namespaces' => array_map(
                fn (AccountNamespace|string $namespace) => $namespace instanceof AccountNamespace ? $namespace->value : $namespace,
                $this->namespaces
            ),
        ];
    }
}
