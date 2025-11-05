<?php

use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Accounts\DeleteAccounts;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('deletes accounts by ids',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            DeleteAccounts::class => MockResponse::fixture('accounts/delete_accounts'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new DeleteAccounts(
                    accountIds: ['10690b01fc5cce62b18cb700'],
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('deleted_accounts')
            ->and($response->json())
            ->toHaveKey('scheduled_accounts')
            ->and($response->json('deleted_accounts'))
            ->toBe(1)
            ->and($response->json('scheduled_accounts'))
            ->toBe(0);
    });

test('deletes single account',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            DeleteAccounts::class => MockResponse::fixture('accounts/delete_accounts_single'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new DeleteAccounts(
                    accountIds: ['10690b02991c50bfa7010c00'],
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('deleted_accounts')
            ->and($response->json())
            ->toHaveKey('scheduled_accounts')
            ->and($response->json('deleted_accounts'))
            ->toBe(1)
            ->and($response->json('scheduled_accounts'))
            ->toBe(0);
    });
