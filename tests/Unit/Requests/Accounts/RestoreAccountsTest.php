<?php

use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Accounts\RestoreAccounts;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('restores accounts by ids',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            RestoreAccounts::class => MockResponse::fixture('accounts/restore_accounts'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new RestoreAccounts(
                    accountIds: ['10690b01fc5cce62b18cb700'],
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('restored_organizations')
            ->and($response->json())
            ->toHaveKey('scheduled_organizations')
            ->and($response->json('restored_organizations'))
            ->toBeInt()
            ->toBeGreaterThanOrEqual(0)
            ->and($response->json('scheduled_organizations'))
            ->toBeInt()
            ->toBeGreaterThanOrEqual(0);
    });

test('restores single account',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            RestoreAccounts::class => MockResponse::fixture('accounts/restore_accounts_single'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new RestoreAccounts(
                    accountIds: ['10690b02991c50bfa7010c00'],
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('restored_organizations')
            ->and($response->json())
            ->toHaveKey('scheduled_organizations')
            ->and($response->json('restored_organizations'))
            ->toBeInt()
            ->toBeGreaterThanOrEqual(0)
            ->and($response->json('scheduled_organizations'))
            ->toBeInt()
            ->toBeGreaterThanOrEqual(0);
    });
