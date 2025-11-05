<?php

use PhpDevKits\Ortto\Enums\AccountNamespace;
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Account\GetAccountSchema;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('getSchema retrieves all namespaces',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAccountSchema::class => MockResponse::fixture('account/get_account_schema_all_namespaces'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->account()
            ->getSchema();

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toBeArray()
            ->not->toBeEmpty();
    });

test('getSchema retrieves specific namespaces',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAccountSchema::class => MockResponse::fixture('account/get_account_schema_specific_namespace'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->account()
            ->getSchema(namespaces: ['o']);

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('namespaces')
            ->and($response->json('namespaces'))
            ->toBeArray();
    });

test('getSchema accepts enum namespace values',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAccountSchema::class => MockResponse::fixture('account/get_account_schema_multiple_namespaces'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->account()
            ->getSchema(namespaces: [AccountNamespace::System, AccountNamespace::Organization]);

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('namespaces');
    });
