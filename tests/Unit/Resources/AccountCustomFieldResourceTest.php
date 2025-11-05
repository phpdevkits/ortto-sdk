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

test('updates account custom field via resource',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            UpdateCustomField::class => MockResponse::fixture('accounts/custom-field/resource_update'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->account()
            ->customField()
            ->update(
                fieldId: 'str:oc:sdk-test-account-field',
                trackChanges: false,
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('field_id')
            ->and($response->json('field_id'))
            ->toBe('str:oc:sdk-test-account-field')
            ->and($response->json())
            ->toHaveKey('track_changes')
            ->and($response->json('track_changes'))
            ->toBeFalse();
    });

test('gets account custom fields via resource',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            GetCustomFields::class => MockResponse::fixture('accounts/custom-field/get'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->account()
            ->customField()
            ->get();

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('fields')
            ->and($response->json('fields'))
            ->toBeArray();
    });

test('creates account custom field via resource with CustomFieldData',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            CreateCustomField::class => MockResponse::fixture('accounts/custom-field/resource_create'),
        ]);

        $fieldData = new CustomFieldData(
            name: 'Account Field '.time(),
            type: CustomFieldType::Text,
            scope: CustomFieldScope::Account,
        );

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->account()
            ->customField()
            ->create($fieldData);

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('field_id')
            ->and($response->json('field_id'))
            ->toContain(':oc:')
            ->and($response->json())
            ->toHaveKey('display_type')
            ->and($response->json('display_type'))
            ->toBe('text');
    });

test('creates account custom field via resource',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            CreateCustomField::class => MockResponse::fixture('accounts/custom-field/resource_create'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->account()
            ->customField()
            ->create([
                'type' => CustomFieldType::Text->value,
                'name' => 'Test Account Field '.time(),
            ]);

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('field_id')
            ->and($response->json('field_id'))
            ->toContain(':oc:');
    });
