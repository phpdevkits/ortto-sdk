<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Data;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Represents a push notification action button.
 *
 * @implements Arrayable<string, mixed>
 */
class PushActionData implements Arrayable
{
    /**
     * @param  string  $title  Action button title
     * @param  string  $link  Action URL
     */
    public function __construct(
        public readonly string $title,
        public readonly string $link,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'link' => $this->link,
        ];
    }
}
