<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Requests\CustomField;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreateCustomField extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $data  Custom field data (type, name, values, track_changes)
     */
    public function __construct(
        protected string $endpoint,
        protected array $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return $this->endpoint.'/create';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }
}
