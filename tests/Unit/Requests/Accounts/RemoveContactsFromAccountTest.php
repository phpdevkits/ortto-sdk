<?php

use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Accounts\RemoveContactsFromAccount;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('removes contacts from account',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            RemoveContactsFromAccount::class => MockResponse::fixture('accounts/remove_contacts_from_account'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new RemoveContactsFromAccount(
                    accountId: '106905f9252dad244f055800',
                    personIds: ['00690a5033a2e942cb9ffc00'],
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toBeArray();
    });

test('removes multiple contacts from account',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            RemoveContactsFromAccount::class => MockResponse::fixture('accounts/remove_multiple_contacts_from_account'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new RemoveContactsFromAccount(
                    accountId: '106905f9252dad244f055800',
                    personIds: ['00690a4feaa17e5f70919700', '00690a4fa7a17e5f70915100'],
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toBeArray();
    });
