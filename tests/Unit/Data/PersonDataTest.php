<?php

use PhpDevKits\Ortto\Data\PersonData;

test('creates new collection',
    function (): void {
        $person = PersonData::factory()->make();

        $collection = $person->newCollection();

        expect($collection)
            ->toBeInstanceOf(\Illuminate\Support\Collection::class)
            ->and($collection->isEmpty())
            ->toBeTrue();
    });

test('converts to array with ortto field format',
    function (): void {
        $person = PersonData::factory()->make([
            'email' => 'test@example.com',
            'firstName' => 'John',
            'lastName' => 'Doe',
        ]);

        $array = $person->toArray();

        expect($array)
            ->toHaveKey('fields')
            ->and($array['fields'])
            ->toHaveKey('str::email')
            ->and($array['fields']['str::email'])
            ->toBe('test@example.com')
            ->and($array['fields'])
            ->toHaveKey('str::first')
            ->and($array['fields']['str::first'])
            ->toBe('John');
    });
