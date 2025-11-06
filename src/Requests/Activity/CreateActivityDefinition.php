<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Requests\Activity;

use PhpDevKits\Ortto\Data\ActivityDefinitionData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreateActivityDefinition extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>|ActivityDefinitionData  $definition  Activity definition data
     */
    public function __construct(
        protected array|ActivityDefinitionData $definition,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/definitions/activity/create';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->definition instanceof ActivityDefinitionData
            ? $this->definition->toArray()
            : $this->definition;
    }
}
