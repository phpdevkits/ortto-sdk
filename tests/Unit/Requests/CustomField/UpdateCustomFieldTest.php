<?php

use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\CustomField\UpdateCustomField;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('updates custom field with replace values',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            UpdateCustomField::class => MockResponse::fixture('person/custom-field/update_replace_values'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new UpdateCustomField(
                    endpoint: '/person/custom-field',
                    fieldId: 'str:cm:sdk-test-select-field',
                    replaceValues: ['New A', 'New B', 'New C'],
                )
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('field_id')
            ->and($response->json('field_id'))
            ->toBe('str:cm:sdk-test-select-field')
            ->and($response->json())
            ->toHaveKey('values')
            ->and($response->json('values'))
            ->toBe(['New A', 'New B', 'New C'])
            ->and($response->json())
            ->toHaveKey('track_changes')
            ->and($response->json('track_changes'))
            ->toBeFalse();
    });

test('updates custom field with add values',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            UpdateCustomField::class => MockResponse::fixture('person/custom-field/update_add_values'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new UpdateCustomField(
                    endpoint: '/person/custom-field',
                    fieldId: 'str:cm:sdk-test-select-field',
                    addValues: ['New D'],
                )
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('field_id')
            ->and($response->json('field_id'))
            ->toBe('str:cm:sdk-test-select-field')
            ->and($response->json())
            ->toHaveKey('values')
            ->and($response->json('values'))
            ->toBe(['New A', 'New B', 'New C', 'New D'])
            ->and($response->json())
            ->toHaveKey('track_changes')
            ->and($response->json('track_changes'))
            ->toBeFalse();
    });

test('updates custom field with remove values',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            UpdateCustomField::class => MockResponse::fixture('person/custom-field/update_remove_values'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new UpdateCustomField(
                    endpoint: '/person/custom-field',
                    fieldId: 'str:cm:sdk-test-select-field',
                    removeValues: ['New D'],
                )
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('field_id')
            ->and($response->json('field_id'))
            ->toBe('str:cm:sdk-test-select-field')
            ->and($response->json())
            ->toHaveKey('values')
            ->and($response->json('values'))
            ->toBe(['New A', 'New B', 'New C'])
            ->and($response->json())
            ->toHaveKey('track_changes')
            ->and($response->json('track_changes'))
            ->toBeFalse();
    });

test('updates person custom field track changes',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            UpdateCustomField::class => MockResponse::fixture('person/custom-field/update_track_changes'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new UpdateCustomField(
                    endpoint: '/person/custom-field',
                    fieldId: 'str:cm:sdk-test-text-field',
                    trackChanges: false,
                )
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('field_id')
            ->and($response->json('field_id'))
            ->toBe('str:cm:sdk-test-text-field')
            ->and($response->json())
            ->toHaveKey('track_changes')
            ->and($response->json('track_changes'))
            ->toBeFalse();
    });
