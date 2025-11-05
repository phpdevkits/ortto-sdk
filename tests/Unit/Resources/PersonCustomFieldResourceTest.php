<?php

use PhpDevKits\Ortto\Data\CustomFieldData;
use PhpDevKits\Ortto\Enums\CustomFieldScope;
use PhpDevKits\Ortto\Enums\CustomFieldType;
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\CustomField\CreateCustomField;
use PhpDevKits\Ortto\Requests\CustomField\GetCustomFields;
use PhpDevKits\Ortto\Requests\CustomField\UpdateCustomField;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('updates person custom field with add values via resource',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            UpdateCustomField::class => MockResponse::fixture('person/custom-field/resource_update'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->person()
            ->customField()
            ->update(
                fieldId: 'str:cm:sdk-test-select-field',
                addValues: ['New D'],
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('field_id')
            ->and($response->json('field_id'))
            ->toBe('str:cm:sdk-test-select-field')
            ->and($response->json())
            ->toHaveKey('track_changes')
            ->and($response->json('track_changes'))
            ->toBeFalse();
    });

test('updates person custom field via resource',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            UpdateCustomField::class => MockResponse::fixture('person/custom-field/update_track_changes'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->person()
            ->customField()
            ->update(
                fieldId: 'str:cm:test-field',
                trackChanges: false,
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('field_id');
    });

test('gets person custom fields via resource',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            GetCustomFields::class => MockResponse::fixture('person/custom-field/get'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->person()
            ->customField()
            ->get();

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('fields')
            ->and($response->json('fields'))
            ->toBeArray();
    });

test('creates person custom field via resource with CustomFieldData',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            CreateCustomField::class => MockResponse::fixture('person/custom-field/resource_create'),
        ]);

        $fieldData = new CustomFieldData(
            name: 'Test Field '.time(),
            type: CustomFieldType::Text,
            scope: CustomFieldScope::Person,
            trackChanges: true,
        );

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->person()
            ->customField()
            ->create($fieldData);

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('field_id')
            ->and($response->json('field_id'))
            ->toContain(':cm:')
            ->and($response->json())
            ->toHaveKey('display_type');
    });

test('creates person custom field via resource',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            CreateCustomField::class => MockResponse::fixture('person/custom-field/resource_create'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->person()
            ->customField()
            ->create([
                'type' => CustomFieldType::Text->value,
                'name' => 'Test Field '.time(),
                'track_changes' => true,
            ]);

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('field_id')
            ->and($response->json('field_id'))
            ->toContain(':cm:')
            ->and($response->json())
            ->toHaveKey('display_type');
    });
