<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Data;

use Illuminate\Contracts\Support\Arrayable;
use PhpDevKits\Ortto\Enums\PushPlatform;

/**
 * Represents a push notification asset configuration.
 *
 * @implements Arrayable<string, mixed>
 */
class PushAssetData implements Arrayable
{
    /**
     * @param  string  $pushName  Campaign identifier (recipient-invisible)
     * @param  string  $title  Notification title (supports templating)
     * @param  string  $message  Notification body content
     * @param  array<int, PushPlatform|string>  $platforms  Target platforms: web, ios, android
     * @param  string|null  $image  Valid image URL for display
     * @param  PushActionData|array<string, mixed>|null  $primaryAction  Action triggered on notification click
     * @param  array<int, PushActionData|array<string, mixed>>|null  $secondaryActions  Up to 4 additional actions
     */
    public function __construct(
        public readonly string $pushName,
        public readonly string $title,
        public readonly string $message,
        public readonly array $platforms,
        public readonly ?string $image = null,
        public readonly PushActionData|array|null $primaryAction = null,
        public readonly ?array $secondaryActions = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [
            'push_name' => $this->pushName,
            'title' => $this->title,
            'message' => $this->message,
            'platforms' => array_map(
                fn (PushPlatform|string $platform): string => $platform instanceof PushPlatform ? $platform->value : $platform,
                $this->platforms
            ),
        ];

        if ($this->image !== null) {
            $data['image'] = $this->image;
        }

        if ($this->primaryAction !== null) {
            $data['primary_action'] = $this->primaryAction instanceof PushActionData ? $this->primaryAction->toArray() : $this->primaryAction;
        }

        if ($this->secondaryActions !== null) {
            $data['secondary_actions'] = array_map(
                fn (PushActionData|array $action): array => $action instanceof PushActionData ? $action->toArray() : $action,
                $this->secondaryActions
            );
        }

        return $data;
    }
}
