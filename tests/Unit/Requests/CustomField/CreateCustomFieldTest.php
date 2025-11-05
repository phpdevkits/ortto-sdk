<?php

use PhpDevKits\Ortto\Enums\CustomFieldType;
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\CustomField\CreateCustomField;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('creates account text custom field',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            CreateCustomField::class => MockResponse::fixture('accounts/custom-field/create'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new CreateCustomField(
                    endpoint: '/accounts/custom-field',
                    data: [
                        'type' => CustomFieldType::Text->value,
                        'name' => 'Test Account Field '.time(),
                    ]
                )
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('field_id')
            ->and($response->json('field_id'))
            ->toContain(':oc:')
            ->and($response->json())
            ->toHaveKey('display_type');
    });

test('creates person single select custom field',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            CreateCustomField::class => MockResponse::fixture('person/custom-field/create_select'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new CreateCustomField(
                    endpoint: '/person/custom-field',
                    data: [
                        'type' => CustomFieldType::SingleSelect->value,
                        'name' => 'Test Select '.time(),
                        'values' => ['Option A', 'Option B', 'Option C'],
                        'track_changes' => false,
                    ]
                )
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('field_id')
            ->and($response->json('field_id'))
            ->toContain(':cm:')
            ->and($response->json())
            ->toHaveKey('values')
            ->and($response->json('values'))
            ->toBeArray();
    });

test('creates person text custom field',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            CreateCustomField::class => MockResponse::fixture('person/custom-field/create'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new CreateCustomField(
                    endpoint: '/person/custom-field',
                    data: [
                        'type' => CustomFieldType::Text->value,
                        'name' => 'Test Field '.time(),
                        'track_changes' => true,
                    ]
                )
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('field_id')
            ->and($response->json('field_id'))
            ->toContain(':cm:')
            ->and($response->json())
            ->toHaveKey('display_type');
    });
