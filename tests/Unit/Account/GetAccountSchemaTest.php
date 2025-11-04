<?php

use PhpDevKits\Ortto\Enums\AccountNamespace;
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Account\GetAccountSchema;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = app(Ortto::class);
});

test('gets multiple namespace schemas',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAccountSchema::class => MockResponse::fixture('account/get_account_schema_multiple_namespaces'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(new GetAccountSchema(namespaces: ['', 'o']));

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toBeArray()
            ->and($response->json())
            ->toHaveKey('namespaces')
            ->and($response->json('namespaces'))
            ->toBeArray();
    });

test('gets specific namespace schema',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAccountSchema::class => MockResponse::fixture('account/get_account_schema_specific_namespace'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(new GetAccountSchema(namespaces: ['o']));

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toBeArray()
            ->and($response->json())
            ->toHaveKey('namespaces')
            ->and($response->json('namespaces'))
            ->toBeArray();
    });

test('gets all namespaces when empty array provided',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAccountSchema::class => MockResponse::fixture('account/get_account_schema_all_namespaces'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(new GetAccountSchema);

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toBeArray()
            ->not->toBeEmpty();
    });

test('accepts namespace enum values',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAccountSchema::class => MockResponse::fixture('account/get_account_schema_multiple_namespaces'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(new GetAccountSchema(namespaces: [AccountNamespace::System, AccountNamespace::Organization]));

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toBeArray()
            ->and($response->json())
            ->toHaveKey('namespaces');
    });
