<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Data;

use Illuminate\Contracts\Support\Arrayable;
use Tests\Factories\PersonFactory;

/**
 * @implements Arrayable<string, mixed>
 */
class Person implements Arrayable
{
    /**
     * @param  array<string, mixed>  $fields
     */
    public function __construct(
        public array $fields
    ) {}

    /**
     * Create a new factory instance.
     */
    public static function factory(): PersonFactory
    {
        return PersonFactory::new();
    }

    /**
     * Get the instance as an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return ['fields' => $this->fields];
    }
}
