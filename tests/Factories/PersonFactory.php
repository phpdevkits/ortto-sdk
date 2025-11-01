<?php

namespace Tests\Factories;

use Illuminate\Support\Str;
use PhpDevKits\Ortto\Data\Person;

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
    public function make(): Person
    {
        $firstName = fake()->firstName();
        $lastName = fake()->lastName();

        $fields = array_merge([
            'str::ei' => Str::uuid()->toString(),
            'str::email' => fake()->email(),
            'str::first' => $firstName,
            'str::last' => $lastName,
            'str::name' => "{$firstName} {$lastName}",
        ], $this->state);

        return new Person($fields);
    }

    /**
     * Create multiple instances.
     *
     * @return array<int, Person>
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
