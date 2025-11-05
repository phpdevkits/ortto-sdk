<?php

use PhpDevKits\Ortto\Enums\AccountField;
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
                    accountIds: ['106905f9252dad244f055800'],
                    fields: [AccountField::Name->value, AccountField::Website->value],
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('accounts')
            ->and($response->json('accounts'))
            ->toBeArray()
            ->and($response->json('accounts.106905f9252dad244f055800'))
            ->toBeArray()
            ->toHaveKey('id')
            ->and($response->json('accounts.106905f9252dad244f055800.id'))
            ->toBe('106905f9252dad244f055800');
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
                    accountIds: ['106905f9252dad244f055800', '106904f4fd2dad244ea03100'],
                    fields: [AccountField::Name->value],
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('accounts')
            ->and($response->json('accounts'))
            ->toBeArray()
            ->toHaveCount(2);
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
                    accountIds: ['106905f9252dad244f055800'],
                    fields: [AccountField::Name->value],
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('accounts')
            ->and($response->json('accounts.106905f9252dad244f055800.fields'))
            ->toHaveKey(AccountField::Name->value);
    });
