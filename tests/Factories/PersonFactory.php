<?php

declare(strict_types=1);

namespace Tests\Factories;

use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use PhpDevKits\Ortto\Data\PersonData;

use function fake;

/**
 * @extends BaseFactory<PersonData>
 */
final class PersonFactory extends BaseFactory
{
    /**
     * The name of the factory's corresponding data object.
     *
     * @var class-string<PersonData>
     */
    protected $model = PersonData::class;

    public function definition(): array
    {
        return [
            'id' => Str::uuid()->toString(),
            'email' => fake()->unique()->email(),
            'firstName' => fake()->firstName(),
            'lastName' => fake()->lastName(),
            'name' => fake()->name(),
            'city' => fake()->city(),
            'country' => fake()->country(),
            'postalCode' => fake()->postcode(),
            'birthdate' => CarbonImmutable::now(),
            'emailPermission' => fake()->boolean(),
            'smsPermission' => fake()->boolean(),
        ];
    }

    protected function createDataObject(array $attributes): PersonData
    {
        return new PersonData(...$attributes);
    }
}
