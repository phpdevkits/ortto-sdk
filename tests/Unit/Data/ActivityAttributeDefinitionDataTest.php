<?php

declare(strict_types=1);

use PhpDevKits\Ortto\Data\ActivityAttributeDefinitionData;
use PhpDevKits\Ortto\Enums\ActivityDisplayType;

test('converts to array with name and display type', function (): void {
    $attribute = new ActivityAttributeDefinitionData(
        name: 'product-name',
        displayType: 'text',
    );

    expect($attribute->toArray())->toBe([
        'name' => 'product-name',
        'display_type' => 'text',
    ]);
});

test('converts to array with display type enum', function (): void {
    $attribute = new ActivityAttributeDefinitionData(
        name: 'quantity',
        displayType: ActivityDisplayType::Integer,
    );

    expect($attribute->toArray())->toBe([
        'name' => 'quantity',
        'display_type' => 'integer',
    ]);
});

test('converts to array with field id', function (): void {
    $attribute = new ActivityAttributeDefinitionData(
        name: 'custom-field',
        displayType: ActivityDisplayType::Text,
        fieldId: 'str:cm:custom-field',
    );

    expect($attribute->toArray())->toBe([
        'name' => 'custom-field',
        'display_type' => 'text',
        'field_id' => 'str:cm:custom-field',
    ]);
});

test('converts to array with do-not-map field id', function (): void {
    $attribute = new ActivityAttributeDefinitionData(
        name: 'unmapped-field',
        displayType: 'text',
        fieldId: 'do-not-map',
    );

    expect($attribute->toArray())->toBe([
        'name' => 'unmapped-field',
        'display_type' => 'text',
        'field_id' => 'do-not-map',
    ]);
});

test('converts to array with empty string field id', function (): void {
    $attribute = new ActivityAttributeDefinitionData(
        name: 'empty-field',
        displayType: ActivityDisplayType::Decimal,
        fieldId: '',
    );

    expect($attribute->toArray())->toBe([
        'name' => 'empty-field',
        'display_type' => 'decimal',
        'field_id' => '',
    ]);
});
