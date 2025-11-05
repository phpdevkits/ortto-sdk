<?php

use PhpDevKits\Ortto\Enums\FindStrategy;
use PhpDevKits\Ortto\Enums\MergeStrategy;
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Accounts\MergeAccounts;
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
