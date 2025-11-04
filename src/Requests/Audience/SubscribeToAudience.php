<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Requests\Audience;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class SubscribeToAudience extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param  array<int, array<string, mixed>>  $people
     */
    public function __construct(
        protected string $audienceId,
        protected array $people,
        protected bool $async = false,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/audience/subscribe';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return [
            'audience_id' => $this->audienceId,
            'people' => $this->people,
            'async' => $this->async,
        ];
    }
}
