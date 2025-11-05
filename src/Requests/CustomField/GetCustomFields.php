<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Requests\CustomField;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetCustomFields extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $endpoint,
    ) {}

    public function resolveEndpoint(): string
    {
        return $this->endpoint.'/get';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return [];
    }
}
