<?php

use PhpDevKits\Ortto\Enums\SortOrder;
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Person\GetPeople;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('gets people with basic fields',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            GetPeople::class => MockResponse::fixture('person/get_people_basic'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetPeople(
                    fields: ['str::email', 'str::first', 'str::last'],
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('contacts')
            ->and($response->json('contacts'))
            ->toBeArray();

    });

test('gets people with limit and sorting',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            GetPeople::class => MockResponse::fixture('person/get_people_with_limit_and_sort'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetPeople(
                    limit: 10,
                    sortByFieldId: 'str::last',
                    sortOrder: SortOrder::Asc,
                    fields: ['str::email', 'str::first', 'str::last'],
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('contacts')
            ->and($response->json('contacts'))
            ->toBeArray();

    });

test('gets people with specific fields',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            GetPeople::class => MockResponse::fixture('person/get_people_with_fields'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetPeople(
                    fields: ['str::email', 'str::first', 'str::last'],
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('contacts')
            ->and($response->json('contacts'))
            ->toBeArray();

    });

test('gets people with pagination offset',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            GetPeople::class => MockResponse::fixture('person/get_people_with_offset'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetPeople(
                    limit: 50,
                    offset: 100,
                    fields: ['str::email'],
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('contacts')
            ->and($response->json())
            ->toHaveKey('offset')
            ->and($response->json())
            ->toHaveKey('has_more');

    });
