<?php

declare(strict_types=1);

namespace Tests\Factories;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PhpDevKits\Ortto\Data\PersonData;

use function fake;

/**
 * @extends Factory<PersonData>
 */
final class PersonFactory extends Factory
{
    /**
     * The name of the factory's corresponding data object.
     *
     * @var class-string<PersonData>
     */
    protected $model = PersonData::class;

    /**
     * @var array<string, mixed>
     */
    private array $extraAttributes = [];

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

    /**
     * Override make to handle non-Eloquent PersonData.
     *
     * @param  array<string, mixed>  $attributes
     * @return PersonData|Collection<int, PersonData>
     */
    public function make($attributes = [], ?Model $parent = null): PersonData|Collection
    {
        $this->extraAttributes = $attributes;

        if ($this->count === null) {
            return $this->makeInstance($parent);
        }

        $instances = [];

        for ($i = 0; $i < $this->count; $i++) {
            $instances[] = $this->makeInstance($parent);
        }

        return collect($instances);
    }

    /**
     * Make a single instance.
     */
    protected function makeInstance(?Model $parent = null): PersonData
    {
        $attributes = array_merge(
            $this->getRawAttributes($parent),
            $this->extraAttributes
        );

        return new PersonData(...$attributes);
    }

    public function newModel(array $attributes = []): PersonData
    {
        $attributes = array_merge($this->definition(), $attributes);

        return new PersonData(...$attributes);
    }
}
