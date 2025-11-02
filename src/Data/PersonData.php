<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Data;

use Carbon\CarbonImmutable;
use Illuminate\Contracts\Support\Arrayable;
use Tests\Factories\PersonFactory;

/**
 * @implements Arrayable<string, mixed>
 */
class PersonData implements Arrayable
{
    public function __construct(
        public string|int $id,
        public string $email,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $name = null,
        public ?string $city = null,
        public ?string $country = null,
        public ?string $postalCode = null,
        public ?CarbonImmutable $birthdate = null,
        public bool $emailPermission = false,
        public bool $smsPermission = false,
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

        return [
            'fields' => [
                'str::ei' => (string) $this->id,
                'str::email' => $this->email,
                'str::first' => $this->firstName,
                'str::last' => $this->lastName,
                'str::name' => $this->name,
                'geo::city' => [
                    'name' => $this->city,
                ],
                'geo::country' => [
                    'name' => $this->country,
                ],
                'str::postal' => $this->postalCode,
                'dtz::b' => [
                    'year' => $this->birthdate?->year,
                    'month' => $this->birthdate?->month,
                    'day' => $this->birthdate?->day,
                    'timezone' => $this->birthdate?->getTimezone()->getName(),
                ],
                'bol::p' => $this->emailPermission,
                'bol::sp' => $this->smsPermission,
            ]];
    }
}
