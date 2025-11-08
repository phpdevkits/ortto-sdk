<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Data;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Represents a push notification to be sent to a contact.
 *
 * @implements Arrayable<string, mixed>
 */
class PushNotificationData implements Arrayable
{
    /**
     * @param  PushAssetData|array<string, mixed>  $asset  Push notification configuration
     * @param  string  $contactId  Recipient identifier
     */
    public function __construct(
        public readonly PushAssetData|array $asset,
        public readonly string $contactId,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'asset' => $this->asset instanceof PushAssetData ? $this->asset->toArray() : $this->asset,
            'contact_id' => $this->contactId,
        ];
    }
}
