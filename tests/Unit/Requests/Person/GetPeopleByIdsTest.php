<?php

use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Person\GetPeopleByIds;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('gets people by single id',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            GetPeopleByIds::class => MockResponse::fixture('person/get_people_by_ids_single'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetPeopleByIds(
                    contactIds: ['0069061b5bda4060a5765300'],
                    fields: ['str::email', 'str::first', 'str::last'],
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('contacts')
            ->and($response->json('contacts.0069061b5bda4060a5765300'))
            ->toHaveKey('fields')
            ->and($response->json('contacts.0069061b5bda4060a5765300.fields'))
            ->toHaveKeys(['str::email', 'str::first', 'str::last']);

    });

test('gets people by ids with specific fields',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            GetPeopleByIds::class => MockResponse::fixture('person/get_people_by_ids_with_fields'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetPeopleByIds(
                    contactIds: ['0069061b5bda4060a5765300'],
                    fields: ['str::email', 'str::first', 'str::last'],
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('contacts')
            ->and($response->json('contacts.0069061b5bda4060a5765300'))
            ->toHaveKey('id')
            ->and($response->json('contacts.0069061b5bda4060a5765300.id'))
            ->toBe('0069061b5bda4060a5765300')
            ->and($response->json('contacts.0069061b5bda4060a5765300'))
            ->toHaveKey('fields')
            ->and($response->json('contacts.0069061b5bda4060a5765300.fields'))
            ->toHaveKeys(['str::email', 'str::first', 'str::last']);

    });
