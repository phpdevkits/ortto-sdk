<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Requests\Transactional;

use PhpDevKits\Ortto\Data\PushNotificationData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class SendPush extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<int, PushNotificationData|array<string, mixed>>  $pushes  Array of push notification objects
     * @param  bool  $async  Controls synchronous/asynchronous processing
     */
    public function __construct(
        protected array $pushes,
        protected bool $async = false,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/transactional/send-push';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return [
            'async' => $this->async,
            'pushes' => array_map(
                fn (PushNotificationData|array $push): array => $push instanceof PushNotificationData ? $push->toArray() : $push,
                $this->pushes
            ),
        ];
    }
}
