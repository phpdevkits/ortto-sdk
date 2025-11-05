<?php

use PhpDevKits\Ortto\Data\ActivityLocationData;

test('creates from ip address', function (): void {
    $location = ActivityLocationData::fromIp('203.123.45.67');

    expect($location->toArray())
        ->toBe([
            'ip' => '203.123.45.67',
        ]);
});

test('creates from coordinates', function (): void {
    $location = ActivityLocationData::fromCoordinates(-33.8688, 151.2093);

    expect($location->toArray())
        ->toBe([
            'custom' => [
                'latitude' => -33.8688,
                'longitude' => 151.2093,
            ],
        ]);
});

test('creates from postal address', function (): void {
    $location = ActivityLocationData::fromAddress([
        'city' => 'Sydney',
        'region' => 'NSW',
        'country' => 'Australia',
        'postal_code' => '2000',
    ]);

    expect($location->toArray())
        ->toBe([
            'custom' => [
                'city' => 'Sydney',
                'region' => 'NSW',
                'country' => 'Australia',
                'postal_code' => '2000',
            ],
        ]);
});
