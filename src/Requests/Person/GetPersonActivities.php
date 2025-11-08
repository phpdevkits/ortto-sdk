<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Requests\Person;

use PhpDevKits\Ortto\Enums\Timeframe;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetPersonActivities extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<int, string>|null  $activities
     */
    public function __construct(
        protected string $personId,
        protected ?array $activities = null,
        protected ?int $limit = null,
        protected ?int $offset = null,
        protected string|Timeframe|null $timeframe = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/person/get/activities';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        $body = [
            'person_id' => $this->personId,
        ];

        if ($this->activities !== null) {
            $body['activities'] = $this->activities;
        }

        if ($this->limit !== null) {
            $body['limit'] = $this->limit;
        }

        if ($this->offset !== null) {
            $body['offset'] = $this->offset;
        }

        if ($this->timeframe !== null) {
            $body['timeframe'] = [
                'type' => is_string($this->timeframe)
                    ? $this->timeframe
                    : $this->timeframe->value,
            ];
        }

        return $body;
    }
}
