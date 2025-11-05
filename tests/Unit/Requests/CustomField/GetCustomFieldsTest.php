<?php

use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\CustomField\GetCustomFields;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('gets account custom fields',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            GetCustomFields::class => MockResponse::fixture('accounts/custom-field/get'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetCustomFields(endpoint: '/accounts/custom-field')
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('fields')
            ->and($response->json('fields'))
            ->toBeArray();
    });

test('gets person custom fields',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            GetCustomFields::class => MockResponse::fixture('person/custom-field/get'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetCustomFields(endpoint: '/person/custom-field')
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('fields')
            ->and($response->json('fields'))
            ->toBeArray();
    });
