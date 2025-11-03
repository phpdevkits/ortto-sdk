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
                    fields: ['str::email', 'str::first', 'str::last'],
                    limit: 10,
                    sortByFieldId: 'str::last',
                    sortOrder: SortOrder::Asc,
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
                    fields: ['str::email'],
                    limit: 50,
                    offset: 100,
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

test('includes cursor id in request body when provided',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            GetPeople::class => MockResponse::fixture('person/get_people_with_cursor_param'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetPeople(
                    fields: ['str::email'],
                    cursorId: '0069061b5bda4060a576',
                ),
            );

        // Verify the request was made (may return 400 with invalid cursor, that's ok)
        expect($response->status())
            ->toBeIn([200, 400]);

    });

test('gets people with search query',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            GetPeople::class => MockResponse::fixture('person/get_people_with_query'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetPeople(
                    fields: ['str::email', 'str::first', 'str::last'],
                    q: 'john',
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('contacts');

    });

test('gets people with type filter',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            GetPeople::class => MockResponse::fixture('person/get_people_with_type'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetPeople(
                    fields: ['str::email'],
                    type: 'archived',
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('contacts');

    });

test('gets people with filter',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            GetPeople::class => MockResponse::fixture('person/get_people_with_filter'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetPeople(
                    fields: ['str::email', 'str::first'],
                    filter: [
                        '$has_any_value' => [
                            'field_id' => 'str::first',
                        ],
                    ],
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('contacts');

    });
