<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Requests\Person;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetPeopleSubscriptions extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<int, array<string, string>>  $people
     */
    public function __construct(
        protected array $people,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/person/subscriptions';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return [
            'people' => $this->people,
        ];
    }
}
