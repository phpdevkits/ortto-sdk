<?php

use PhpDevKits\Ortto\Data\CustomFieldData;
use PhpDevKits\Ortto\Enums\CustomFieldScope;
use PhpDevKits\Ortto\Enums\CustomFieldType;

test('creates CustomFieldData with all fields', function (): void {
    $data = new CustomFieldData(
        name: 'Test Field',
        type: CustomFieldType::Text,
        scope: CustomFieldScope::Person,
        fieldId: 'str:cm:test-field',
        trackChanges: true,
    );

    expect($data->name)->toBe('Test Field');
    expect($data->type)->toBe(CustomFieldType::Text);
    expect($data->scope)->toBe(CustomFieldScope::Person);
    expect($data->fieldId)->toBe('str:cm:test-field');
    expect($data->trackChanges)->toBe(true);
    expect($data->options)->toBeNull();
});

test('toArray converts enum types to values', function (): void {
    $data = new CustomFieldData(
        name: 'Test Field',
        type: CustomFieldType::SingleSelect,
        scope: CustomFieldScope::Account,
        options: ['Option A', 'Option B'],
    );

    $array = $data->toArray();

    expect($array)->toBe([
        'name' => 'Test Field',
        'type' => 'single_select',
        'scope' => 'account',
        'options' => ['Option A', 'Option B'],
    ]);
});

test('toArray includes optional fields when provided', function (): void {
    $data = new CustomFieldData(
        name: 'Test Field',
        type: 'text',
        scope: 'person',
        fieldId: 'str:cm:test',
        trackChanges: true,
    );

    $array = $data->toArray();

    expect($array)->toHaveKey('field_id');
    expect($array)->toHaveKey('track_changes');
    expect($array['field_id'])->toBe('str:cm:test');
    expect($array['track_changes'])->toBe(true);
});

test('toArray works with string type and scope', function (): void {
    $data = new CustomFieldData(
        name: 'Test',
        type: 'integer',
        scope: 'person',
    );

    $array = $data->toArray();

    expect($array['type'])->toBe('integer');
    expect($array['scope'])->toBe('person');
});
