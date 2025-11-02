<?php

namespace Tests\Factories;

use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use PhpDevKits\Ortto\Data\PersonData;

class PersonFactory
{
    /**
     * @var array<string, mixed>
     */
    protected array $state = [];

    /**
     * Create a new factory instance.
     */
    public static function new(): self
    {
        return new self;
    }

    /**
     * Set the state of the factory.
     *
     * @param  array<string, mixed>  $state
     */
    public function state(array $state): self
    {
        $this->state = array_merge($this->state, $state);

        return $this;
    }

    /**
     * Create a single instance.
     */
    public function make(): PersonData
    {
        $firstName = fake()->firstName();
        $lastName = fake()->lastName();

        return new PersonData(
            id: $this->state['id'] ?? Str::uuid()->toString(),
            email: fake()->unique()->email(),
            firstName: $this->state['first_name'] ?? $firstName,
            lastName: $this->state['last_name'] ?? $lastName,
            name: $this->state['name'] ?? "{$firstName} {$lastName}",
            city: $this->state['city'] ?? fake()->city(),
            country: $this->state['country'] ?? fake()->country(),
            postalCode: $this->state['postal_code'] ?? fake()->postcode(),
            birthdate: $this->state['birthdate'] ?? CarbonImmutable::now(),
            emailPermission: $this->state['email_permission'] ?? fake()->boolean(),
            smsPermission: $this->state['sms_permission'] ?? fake()->boolean(),
        );
    }

    /**
     * Create multiple instances.
     *
     * @return array<int, PersonData>
     */
    public function count(int $count): array
    {
        $instances = [];

        for ($i = 0; $i < $count; $i++) {
            $instances[] = $this->make();
        }

        return $instances;
    }
}
