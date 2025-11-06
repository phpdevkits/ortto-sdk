<?php

declare(strict_types=1);

use PhpDevKits\Ortto\Data\ActivityDisplayStyleData;

test('converts to array with type only', function (): void {
    $style = new ActivityDisplayStyleData(
        type: 'activity',
    );

    expect($style->toArray())->toBe([
        'type' => 'activity',
    ]);
});

test('converts to array with activity_attribute type', function (): void {
    $style = new ActivityDisplayStyleData(
        type: 'activity_attribute',
        attributeName: 'product-name',
    );

    expect($style->toArray())->toBe([
        'type' => 'activity_attribute',
        'attribute_name' => 'product-name',
    ]);
});

test('converts to array with activity_template type', function (): void {
    $style = new ActivityDisplayStyleData(
        type: 'activity_template',
        title: 'Purchased {{product-name}} (qty: {{quantity}})',
    );

    expect($style->toArray())->toBe([
        'type' => 'activity_template',
        'title' => 'Purchased {{product-name}} (qty: {{quantity}})',
    ]);
});

test('converts to array with all fields', function (): void {
    $style = new ActivityDisplayStyleData(
        type: 'activity_attribute',
        title: 'Product Purchase',
        attributeName: 'product-name',
        attributeFieldId: 'str:cm:product-name',
    );

    expect($style->toArray())->toBe([
        'type' => 'activity_attribute',
        'title' => 'Product Purchase',
        'attribute_name' => 'product-name',
        'attribute_field_id' => 'str:cm:product-name',
    ]);
});
