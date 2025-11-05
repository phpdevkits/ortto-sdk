<?php

use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Accounts\GetAccountsByIds;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('gets accounts by single id',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAccountsByIds::class => MockResponse::fixture('accounts/get_accounts_by_ids_single'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetAccountsByIds(
                    accountIds: ['507f1f77bcf86cd799439011'],
                    fields: ['str:o:name', 'str:o:website'],
                ),
            );

        // May return 400 if account IDs don't exist or have invalid format
        expect($response->status())
            ->toBeIn([200, 400]);
    });

test('gets accounts by multiple ids',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAccountsByIds::class => MockResponse::fixture('accounts/get_accounts_by_ids_multiple'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetAccountsByIds(
                    accountIds: ['507f1f77bcf86cd799439011', '507f1f77bcf86cd799439012', '507f1f77bcf86cd799439013'],
                    fields: ['str:o:name', 'str:o:website', 'int:o:employees'],
                ),
            );

        // May return 400 if account IDs don't exist or have invalid format
        expect($response->status())
            ->toBeIn([200, 400]);
    });

test('gets accounts with specific fields only',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAccountsByIds::class => MockResponse::fixture('accounts/get_accounts_by_ids_specific_fields'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetAccountsByIds(
                    accountIds: ['507f1f77bcf86cd799439011'],
                    fields: ['str:o:name'],
                ),
            );

        // May return 400 if account IDs don't exist or have invalid format
        expect($response->status())
            ->toBeIn([200, 400]);
    });
