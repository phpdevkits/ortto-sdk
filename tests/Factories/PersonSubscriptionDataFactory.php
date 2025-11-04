<?php

declare(strict_types=1);

namespace Tests\Factories;

use PhpDevKits\Ortto\Data\PersonSubscriptionData;

use function fake;

/**
 * @extends BaseFactory<PersonSubscriptionData>
 */
final class PersonSubscriptionDataFactory extends BaseFactory
{
    /**
     * The name of the factory's corresponding data object.
     *
     * @var class-string<PersonSubscriptionData>
     */
    protected $model = PersonSubscriptionData::class;

    public function definition(): array
    {
        return [
            'email' => fake()->unique()->email(),
            'personId' => null,
            'externalId' => null,
            'subscribed' => fake()->boolean(),
            'smsOptedIn' => fake()->boolean(),
        ];
    }

    protected function createDataObject(array $attributes): PersonSubscriptionData
    {
        return new PersonSubscriptionData(...$attributes);
    }
}
