<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Requests\Person;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetPeopleByIds extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  string[]  $contactIds
     * @param  string[]|null  $fields
     */
    public function __construct(
        protected array $contactIds,
        protected ?array $fields = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/person/get-by-ids';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        $body = [
            'contact_ids' => $this->contactIds,
        ];

        if ($this->fields !== null) {
            $body['fields'] = $this->fields;
        }

        return $body;
    }
}
