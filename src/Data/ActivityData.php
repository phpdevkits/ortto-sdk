<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Data;

use Illuminate\Contracts\Support\Arrayable;
use PhpDevKits\Ortto\Enums\MergeStrategy;

/**
 * @implements Arrayable<string, mixed>
 */
class ActivityData implements Arrayable
{
    /**
     * @param  string  $activityId  Activity field ID (e.g., 'act::c', 'act:cm:custom-event')
     * @param  array<string, mixed>  $attributes  Activity-specific attributes
     * @param  string|null  $personId  Existing person ID (use this OR fields+mergeBy)
     * @param  array<string, mixed>|null  $fields  Person fields (use with mergeBy if personId not provided)
     * @param  array<string>|null  $mergeBy  Field IDs to determine create vs update (use with fields)
     * @param  string|null  $created  ISO 8601 timestamp for backdating (up to 90 days)
     * @param  string|null  $key  Unique key for duplicate prevention (combined with created)
     * @param  ActivityLocationData|null  $location  Location data
     * @param  int|MergeStrategy|null  $mergeStrategy  Override merge strategy for this activity
     */
    public function __construct(
        private readonly string $activityId,
        private readonly array $attributes = [],
        private readonly ?string $personId = null,
        private readonly ?array $fields = null,
        private readonly ?array $mergeBy = null,
        private readonly ?string $created = null,
        private readonly ?string $key = null,
        private readonly ?ActivityLocationData $location = null,
        private readonly int|MergeStrategy|null $mergeStrategy = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [
            'activity_id' => $this->activityId,
        ];

        if ($this->attributes !== []) {
            $data['attributes'] = $this->attributes;
        }

        // Use person_id OR (fields + merge_by), not both
        if ($this->personId !== null) {
            $data['person_id'] = $this->personId;
        } elseif ($this->fields !== null && $this->mergeBy !== null) {
            $data['fields'] = $this->fields;
            $data['merge_by'] = $this->mergeBy;
        }

        if ($this->created !== null) {
            $data['created'] = $this->created;
        }

        if ($this->key !== null) {
            $data['key'] = $this->key;
        }

        if ($this->location instanceof \PhpDevKits\Ortto\Data\ActivityLocationData) {
            $data['location'] = $this->location->toArray();
        }

        if ($this->mergeStrategy !== null) {
            $data['merge_strategy'] = is_int($this->mergeStrategy)
                ? $this->mergeStrategy
                : $this->mergeStrategy->value;
        }

        return $data;
    }
}
