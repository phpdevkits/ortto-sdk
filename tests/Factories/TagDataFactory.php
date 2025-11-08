<?php

declare(strict_types=1);

namespace Tests\Factories;

use PhpDevKits\Ortto\Data\TagData;
use PhpDevKits\Ortto\Enums\TagSource;
use PhpDevKits\Ortto\Enums\TagType;

/**
 * @extends BaseFactory<TagData>
 */
class TagDataFactory extends BaseFactory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->randomNumber(),
            'instance_id' => $this->faker->uuid(),
            'name' => $this->faker->words(2, true),
            'source' => $this->faker->randomElement(TagSource::cases()),
            'created_by_id' => $this->faker->uuid(),
            'created_by_name' => $this->faker->name(),
            'created_by_email' => $this->faker->email(),
            'created_at' => $this->faker->iso8601(),
            'last_used' => $this->faker->iso8601(),
            'count' => $this->faker->numberBetween(0, 1000),
            'sms_opted_in' => $this->faker->numberBetween(0, 500),
            'subscribers' => $this->faker->numberBetween(0, 800),
            'type' => $this->faker->randomElement(TagType::cases()),
        ];
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    protected function createDataObject(array $attributes): TagData
    {
        return new TagData(
            id: $attributes['id'],
            instanceId: $attributes['instance_id'],
            name: $attributes['name'],
            source: $attributes['source'],
            createdById: $attributes['created_by_id'],
            createdByName: $attributes['created_by_name'],
            createdByEmail: $attributes['created_by_email'],
            createdAt: $attributes['created_at'],
            lastUsed: $attributes['last_used'],
            count: $attributes['count'],
            smsOptedIn: $attributes['sms_opted_in'],
            subscribers: $attributes['subscribers'],
            type: $attributes['type'],
        );
    }
}
