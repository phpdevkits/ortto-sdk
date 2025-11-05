<?php

use PhpDevKits\Ortto\Enums\AccountField;
use PhpDevKits\Ortto\Enums\FindStrategy;
use PhpDevKits\Ortto\Enums\MergeStrategy;
use PhpDevKits\Ortto\Enums\SortOrder;
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Accounts\AddContactsToAccount;
use PhpDevKits\Ortto\Requests\Accounts\ArchiveAccounts;
use PhpDevKits\Ortto\Requests\Accounts\DeleteAccounts;
use PhpDevKits\Ortto\Requests\Accounts\GetAccounts;
use PhpDevKits\Ortto\Requests\Accounts\GetAccountsByIds;
use PhpDevKits\Ortto\Requests\Accounts\MergeAccounts;
use PhpDevKits\Ortto\Requests\Accounts\RemoveContactsFromAccount;
use PhpDevKits\Ortto\Requests\Accounts\RestoreAccounts;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('merge creates new account',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            MergeAccounts::class => MockResponse::fixture('accounts/merge_accounts_create'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->accounts()
            ->merge(
                accounts: [
                    [
                        'fields' => [
                            'str:o:name' => 'New Company Inc',
                            'str:o:website' => 'https://newcompany.com',
                        ],
                    ],
                ],
                mergeBy: ['str:o:website'],
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json('accounts.0.status'))
            ->toBe('created');
    });

test('merge updates existing account',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            MergeAccounts::class => MockResponse::fixture('accounts/merge_accounts_merge'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->accounts()
            ->merge(
                accounts: [
                    [
                        'fields' => [
                            'str:o:name' => 'Existing Company',
                            'str:o:website' => 'https://existing.com',
                        ],
                    ],
                ],
                mergeBy: ['str:o:website'],
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json('accounts.0.status'))
            ->toBe('merged');
    });

test('merge accepts merge strategy enums',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            MergeAccounts::class => MockResponse::fixture('accounts/merge_accounts_merge_strategy'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->accounts()
            ->merge(
                accounts: [
                    [
                        'fields' => [
                            'str:o:name' => 'Gamma Ltd',
                            'str:o:website' => 'https://gamma.com',
                        ],
                    ],
                ],
                mergeBy: ['str:o:website'],
                mergeStrategy: MergeStrategy::AppendOnly,
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('accounts');
    });

test('merge accepts find strategy enums',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            MergeAccounts::class => MockResponse::fixture('accounts/merge_accounts_find_strategy'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->accounts()
            ->merge(
                accounts: [
                    [
                        'fields' => [
                            'str:o:name' => 'Beta Inc',
                            'str:o:website' => 'https://beta.com',
                        ],
                    ],
                ],
                mergeBy: ['str:o:website', 'str:o:name'],
                findStrategy: FindStrategy::NextOnlyIfPreviousEmpty,
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('accounts');
    });

test('merge handles bulk operations',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            MergeAccounts::class => MockResponse::fixture('accounts/merge_accounts_bulk'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->accounts()
            ->merge(
                accounts: [
                    [
                        'fields' => [
                            'str:o:name' => 'Delta Corp',
                            'str:o:website' => 'https://delta.com',
                        ],
                    ],
                    [
                        'fields' => [
                            'str:o:name' => 'Epsilon LLC',
                            'str:o:website' => 'https://epsilon.com',
                        ],
                    ],
                    [
                        'fields' => [
                            'str:o:name' => 'Zeta Group',
                            'str:o:website' => 'https://zeta.com',
                        ],
                    ],
                ],
                mergeBy: ['str:o:website'],
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json('accounts'))
            ->toHaveCount(3);
    });

test('get retrieves accounts with basic fields',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAccounts::class => MockResponse::fixture('accounts/get_accounts_basic'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->accounts()
            ->get(
                fields: [AccountField::Name->value, AccountField::Website->value],
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('accounts');
    });

test('get retrieves accounts with limit and sorting',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAccounts::class => MockResponse::fixture('accounts/get_accounts_with_limit_and_sort'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->accounts()
            ->get(
                fields: [AccountField::Name->value, AccountField::Website->value],
                limit: 10,
                sortByFieldId: AccountField::Name->value,
                sortOrder: SortOrder::Asc,
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('accounts');
    });

test('getByIds retrieves specific accounts',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAccountsByIds::class => MockResponse::fixture('accounts/get_accounts_by_ids_single'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->accounts()
            ->getByIds(
                accountIds: ['106905f9252dad244f055800'],
                fields: [AccountField::Name->value, AccountField::Website->value],
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('accounts');
    });

test('archive archives accounts',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            ArchiveAccounts::class => MockResponse::fixture('accounts/archive_accounts'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->accounts()
            ->archive(
                accountIds: ['10690b01fc5cce62b18cb700'],
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('archived_organizations');
    });

test('restore restores archived accounts',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            RestoreAccounts::class => MockResponse::fixture('accounts/restore_accounts'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->accounts()
            ->restore(
                accountIds: ['10690b01fc5cce62b18cb700'],
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('restored_organizations');
    });

test('delete deletes archived accounts',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            DeleteAccounts::class => MockResponse::fixture('accounts/delete_accounts'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->accounts()
            ->delete(
                accountIds: ['10690b01fc5cce62b18cb700'],
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('deleted_accounts');
    });

test('addContacts associates contacts with account',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            AddContactsToAccount::class => MockResponse::fixture('accounts/add_contacts_to_account'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->accounts()
            ->addContacts(
                accountId: '106905f9252dad244f055800',
                personIds: ['00690a5033a2e942cb9ffc00'],
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toBeArray();
    });

test('removeContacts removes contact associations from account',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            RemoveContactsFromAccount::class => MockResponse::fixture('accounts/remove_contacts_from_account'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->accounts()
            ->removeContacts(
                accountId: '106905f9252dad244f055800',
                personIds: ['00690a5033a2e942cb9ffc00'],
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toBeArray();
    });
