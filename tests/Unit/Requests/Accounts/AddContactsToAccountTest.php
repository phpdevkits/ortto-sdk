<?php

use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Accounts\AddContactsToAccount;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('adds contacts to account',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            AddContactsToAccount::class => MockResponse::fixture('accounts/add_contacts_to_account'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new AddContactsToAccount(
                    accountId: '106905f9252dad244f055800',
                    personIds: ['00690a5033a2e942cb9ffc00'],
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toBeArray();
    });

test('adds multiple contacts to account',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            AddContactsToAccount::class => MockResponse::fixture('accounts/add_multiple_contacts_to_account'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new AddContactsToAccount(
                    accountId: '106905f9252dad244f055800',
                    personIds: ['00690a4feaa17e5f70919700', '00690a4fa7a17e5f70915100'],
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toBeArray();
    });
