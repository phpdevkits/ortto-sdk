<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Data;

use Illuminate\Contracts\Support\Arrayable;
use PhpDevKits\Ortto\Enums\TagSource;
use PhpDevKits\Ortto\Enums\TagType;

/**
 * @implements Arrayable<string, mixed>
 */
class TagData implements Arrayable
{
    public function __construct(
        public readonly int $id,
        public readonly string $instanceId,
        public readonly string $name,
        public readonly TagSource|string|null $source,
        public readonly string $createdById,
        public readonly string $createdByName,
        public readonly string $createdByEmail,
        public readonly string $createdAt,
        public readonly string $lastUsed,
        public readonly int $count,
        public readonly ?int $smsOptedIn,
        public readonly ?int $subscribers,
        public readonly TagType|string $type,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'instance_id' => $this->instanceId,
            'name' => $this->name,
            'source' => $this->source instanceof TagSource ? $this->source->value : $this->source,
            'created_by_id' => $this->createdById,
            'created_by_name' => $this->createdByName,
            'created_by_email' => $this->createdByEmail,
            'created_at' => $this->createdAt,
            'last_used' => $this->lastUsed,
            'count' => $this->count,
            'sms_opted_in' => $this->smsOptedIn,
            'subscribers' => $this->subscribers,
            'type' => $this->type instanceof TagType ? $this->type->value : $this->type,
        ];
    }
}
