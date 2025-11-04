<?php

declare(strict_types=1);

namespace Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Base factory for non-Eloquent data classes.
 *
 * @template TData
 *
 * @extends Factory<TData>
 */
abstract class BaseFactory extends Factory
{
    /**
     * @var array<string, mixed>
     */
    private array $extraAttributes = [];

    /**
     * Override make to handle non-Eloquent data objects.
     *
     * @param  array<string, mixed>  $attributes
     * @return TData|Collection<int, TData>
     */
    public function make($attributes = [], ?Model $parent = null): mixed
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
     *
     * @return TData
     */
    protected function makeInstance(?Model $parent = null): mixed
    {
        $attributes = array_merge(
            $this->getRawAttributes($parent),
            $this->extraAttributes
        );

        return $this->createDataObject($attributes);
    }

    /**
     * Create the data object from attributes.
     *
     * Subclasses must implement this to instantiate their specific data class.
     *
     * @param  array<string, mixed>  $attributes
     * @return TData
     */
    abstract protected function createDataObject(array $attributes): mixed;

    /**
     * Create a new model instance (required by parent Factory).
     *
     * @param  array<string, mixed>  $attributes
     * @return TData
     */
    public function newModel(array $attributes = []): mixed
    {
        $attributes = array_merge($this->definition(), $attributes);

        return $this->createDataObject($attributes);
    }
}
