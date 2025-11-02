<?php

declare(strict_types=1);

namespace Tests\Factories;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PhpDevKits\Ortto\Data\PersonData;

use function collect;
use function fake;

final class PersonFactory
{
    /**
     * @var array<string, mixed>
     */
    protected array $state = [];

    protected int $count = 0;

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
     *
     * @return PersonData|Collection<int, PersonData>
     */
    public function make(): PersonData|Collection
    {
        if ($this->count === 0) {
            return $this->createPerson();
        }

        $instances = collect([]);

        foreach (range(0, $this->count - 1) as $_) {
            $instances->add($this->createPerson());
        }

        return $instances;
    }

    private function createPerson(): PersonData
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
     * @return Collection<int, PersonData>
     */
    public function all(): Collection
    {
        return collect($this->instances);
    }

    /**
     * Create multiple instances.
     */
    public function count(int $count): PersonFactory
    {

        $this->count = $count;

        return $this;
    }
}
