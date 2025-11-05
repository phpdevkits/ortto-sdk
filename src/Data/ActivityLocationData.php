<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Data;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
class ActivityLocationData implements Arrayable
{
    /**
     * @param  array<string, mixed>  $data
     */
    private function __construct(
        private readonly array $data
    ) {}

    /**
     * Create location data from an IP address.
     * Ortto will automatically geocode the IP to determine location.
     */
    public static function fromIp(string $ip): self
    {
        return new self([
            'ip' => $ip,
        ]);
    }

    /**
     * Create location data from custom coordinates.
     *
     * @param  float  $latitude  Latitude coordinate
     * @param  float  $longitude  Longitude coordinate
     */
    public static function fromCoordinates(float $latitude, float $longitude): self
    {
        return new self([
            'custom' => [
                'latitude' => $latitude,
                'longitude' => $longitude,
            ],
        ]);
    }

    /**
     * Create location data from a postal address.
     *
     * @param  array<string, string>  $address  Address components (city, region, country, postal_code)
     */
    public static function fromAddress(array $address): self
    {
        return new self([
            'custom' => $address,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->data;
    }
}
