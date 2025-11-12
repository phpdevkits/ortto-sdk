<?php

declare(strict_types=1);

namespace Tests\Factories;

use DateTimeImmutable;
use FBarrento\DataFactory\Factory;
use Illuminate\Support\Str;
use PhpDevKits\Ortto\Data\PersonData;

/**
 * @extends Factory<PersonData>
 */
final class PersonFactory extends Factory
{
    protected string $dataObject = PersonData::class;

    public function definition(): array
    {
        return [
            'id' => fn () => Str::uuid()->toString(),
            'email' => fn () => $this->fake->unique()->email(),
            'firstName' => fn () => $this->fake->firstName(),
            'lastName' => fn () => $this->fake->lastName(),
            'name' => fn () => $this->fake->name(),
            'city' => fn () => $this->fake->city(),
            'country' => fn () => $this->fake->country(),
            'postalCode' => fn () => $this->fake->postcode(),
            'birthdate' => fn () => new DateTimeImmutable,
            'emailPermission' => fn () => $this->fake->boolean(),
            'smsPermission' => fn () => $this->fake->boolean(),
        ];
    }
}
