<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Requests\Activity;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreateActivities extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<int, array<string, mixed>>  $activities  Array of activity records (1-100 max)
     * @param  bool  $async  Process activities asynchronously for bulk operations
     */
    public function __construct(
        protected array $activities,
        protected bool $async = false,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/activities/create';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        $body = [
            'activities' => $this->activities,
        ];

        if ($this->async) {
            $body['async'] = true;
        }

        return $body;
    }
}
