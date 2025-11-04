<?php

use PhpDevKits\Ortto\Data\PersonSubscriptionData;

test('converts to array with only email',
    function (): void {
        $person = new PersonSubscriptionData(
            email: 'test@example.com',
        );

        $array = $person->toArray();

        expect($array)
            ->toHaveKey('email')
            ->and($array['email'])
            ->toBe('test@example.com')
            ->and($array)
            ->not->toHaveKey('person_id');
    });

test('converts to array with subscription flags',
    function (): void {
        $person = new PersonSubscriptionData(
            email: 'test@example.com',
            subscribed: true,
            smsOptedIn: false,
        );

        $array = $person->toArray();

        expect($array)
            ->toHaveKey('email')
            ->and($array)
            ->toHaveKey('subscribed')
            ->and($array['subscribed'])
            ->toBeTrue()
            ->and($array)
            ->toHaveKey('sms_opted_in')
            ->and($array['sms_opted_in'])
            ->toBeFalse();
    });

test('converts to array with all identifiers',
    function (): void {
        $person = new PersonSubscriptionData(
            email: 'test@example.com',
            personId: '123456',
            externalId: 'ext-123',
        );

        $array = $person->toArray();

        expect($array)
            ->toHaveKey('email')
            ->and($array)
            ->toHaveKey('person_id')
            ->and($array)
            ->toHaveKey('external_id');
    });

test('creates instance via factory',
    function (): void {
        $person = PersonSubscriptionData::factory()->make();

        expect($person)
            ->toBeInstanceOf(PersonSubscriptionData::class)
            ->and($person->email)
            ->not->toBeNull();
    });
