<?php

use PhpDevKits\Ortto\Data\PersonData;

test('creates person via factory',
    function (): void {
        $person = PersonData::factory()->make();

        expect($person)
            ->toBeInstanceOf(PersonData::class)
            ->and($person->email)
            ->toBeString()
            ->and($person->id)
            ->not()->toBeEmpty();
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
