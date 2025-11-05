<?php

use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Accounts\ArchiveAccounts;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('archives accounts by ids',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            ArchiveAccounts::class => MockResponse::fixture('accounts/archive_accounts'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new ArchiveAccounts(
                    accountIds: ['10690b01fc5cce62b18cb700'],
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('archived_organizations')
            ->and($response->json())
            ->toHaveKey('scheduled_organizations')
            ->and($response->json('archived_organizations'))
            ->toBeInt()
            ->toBeGreaterThanOrEqual(0)
            ->and($response->json('scheduled_organizations'))
            ->toBeInt()
            ->toBeGreaterThanOrEqual(0);
    });

test('archives single account',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            ArchiveAccounts::class => MockResponse::fixture('accounts/archive_accounts_single'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new ArchiveAccounts(
                    accountIds: ['10690b02991c50bfa7010c00'],
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('archived_organizations')
            ->and($response->json())
            ->toHaveKey('scheduled_organizations')
            ->and($response->json('archived_organizations'))
            ->toBeInt()
            ->toBeGreaterThanOrEqual(0)
            ->and($response->json('scheduled_organizations'))
            ->toBeInt()
            ->toBeGreaterThanOrEqual(0);
    });
