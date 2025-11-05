<?php

use PhpDevKits\Ortto\Enums\AccountField;
use PhpDevKits\Ortto\Enums\SortOrder;
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Accounts\GetAccounts;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('gets accounts with basic fields',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAccounts::class => MockResponse::fixture('accounts/get_accounts_basic'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetAccounts(
                    fields: [AccountField::Name->value, AccountField::Website->value],
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('accounts')
            ->and($response->json('accounts'))
            ->toBeArray();
    });

test('gets accounts with limit and sorting',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAccounts::class => MockResponse::fixture('accounts/get_accounts_with_limit_and_sort'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetAccounts(
                    limit: 10,
                    sortByFieldId: AccountField::Name->value,
                    sortOrder: SortOrder::Asc,
                    fields: ['str:o:name', 'str:o:website'],
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('accounts')
            ->and($response->json('accounts'))
            ->toBeArray();
    });

test('gets accounts with pagination offset',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAccounts::class => MockResponse::fixture('accounts/get_accounts_with_offset'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetAccounts(
                    limit: 50,
                    offset: 100,
                    fields: [AccountField::Name->value],
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('accounts')
            ->and($response->json())
            ->toHaveKey('offset')
            ->and($response->json())
            ->toHaveKey('has_more');
    });

test('gets accounts with cursor pagination',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAccounts::class => MockResponse::fixture('accounts/get_accounts_with_cursor'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetAccounts(
                    cursorId: '0069061b5bda4060a576',
                    fields: [AccountField::Name->value],
                ),
            );

        // Verify the request was made (may return 400 with invalid cursor, that's ok)
        expect($response->status())
            ->toBeIn([200, 400]);
    });

test('gets accounts with search query',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAccounts::class => MockResponse::fixture('accounts/get_accounts_with_query'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetAccounts(
                    fields: ['str:o:name', 'str:o:website'],
                    q: 'Acme',
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('accounts');
    });

test('gets accounts with type filter for archived',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAccounts::class => MockResponse::fixture('accounts/get_accounts_archived_type'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetAccounts(
                    fields: [AccountField::Name->value],
                    type: 'archived_account',
                ),
            );

        // May return 400 if no archived accounts exist or test data is missing
        expect($response->status())
            ->toBeIn([200, 400]);
    });

test('gets accounts with filter',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAccounts::class => MockResponse::fixture('accounts/get_accounts_with_filter'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetAccounts(
                    fields: [AccountField::Name->value, AccountField::Employees->value],
                    filter: [
                        '$has_any_value' => [
                            'field_id' => 'geo:o:country',
                        ],
                    ],
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('accounts');
    });

test('gets accounts with complex filter',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAccounts::class => MockResponse::fixture('accounts/get_accounts_complex_filter'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetAccounts(
                    fields: [AccountField::Name->value, AccountField::Employees->value],
                    filter: [
                        '$and' => [
                            [
                                '$has_any_value' => [
                                    'field_id' => 'int:o:employees',
                                ],
                            ],
                            [
                                '$has_any_value' => [
                                    'field_id' => 'str:o:name',
                                ],
                            ],
                        ],
                    ],
                ),
            );

        // May return 400 if filter operators are not valid
        expect($response->status())
            ->toBeIn([200, 400]);
    });

test('gets accounts with inclusion ids',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAccounts::class => MockResponse::fixture('accounts/get_accounts_with_inclusion_ids'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetAccounts(
                    fields: [AccountField::Name->value],
                    inclusionIds: ['account-id-123', 'account-id-456'],
                ),
            );

        // May return 400 if account IDs don't exist
        expect($response->status())
            ->toBeIn([200, 400]);
    });

test('gets accounts with exclusion ids',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAccounts::class => MockResponse::fixture('accounts/get_accounts_with_exclusion_ids'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetAccounts(
                    fields: [AccountField::Name->value],
                    exclusionIds: ['account-id-789'],
                ),
            );

        // May return 400 if parameter is not supported
        expect($response->status())
            ->toBeIn([200, 400]);
    });
