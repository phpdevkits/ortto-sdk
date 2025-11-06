<?php

declare(strict_types=1);

use PhpDevKits\Ortto\Data\ActivityAttributeDefinitionData;
use PhpDevKits\Ortto\Data\ActivityDefinitionData;
use PhpDevKits\Ortto\Data\ActivityDisplayStyleData;
use PhpDevKits\Ortto\Enums\ActivityDisplayType;
use PhpDevKits\Ortto\Enums\ActivityIcon;

test('converts to array with required fields only', function (): void {
    $definition = new ActivityDefinitionData(
        name: 'product-purchase',
        iconId: 'moneys-illustration-icon',
    );

    expect($definition->toArray())->toBe([
        'name' => 'product-purchase',
        'icon_id' => 'moneys-illustration-icon',
    ]);
});

test('converts to array with all optional fields', function (): void {
    $definition = new ActivityDefinitionData(
        name: 'product-purchase',
        iconId: 'moneys-illustration-icon',
        trackConversionValue: true,
        touch: true,
        filterable: true,
        visibleInFeeds: true,
    );

    expect($definition->toArray())->toBe([
        'name' => 'product-purchase',
        'icon_id' => 'moneys-illustration-icon',
        'track_conversion_value' => true,
        'touch' => true,
        'filterable' => true,
        'visible_in_feeds' => true,
    ]);
});

test('converts to array with icon enum', function (): void {
    $definition = new ActivityDefinitionData(
        name: 'product-purchase',
        iconId: ActivityIcon::Money,
    );

    expect($definition->toArray())->toBe([
        'name' => 'product-purchase',
        'icon_id' => 'moneys-illustration-icon',
    ]);
});

test('converts to array with icon string', function (): void {
    $definition = new ActivityDefinitionData(
        name: 'event-registration',
        iconId: 'calendar-illustration-icon',
    );

    expect($definition->toArray())->toBe([
        'name' => 'event-registration',
        'icon_id' => 'calendar-illustration-icon',
    ]);
});

test('converts to array with display style object', function (): void {
    $displayStyle = new ActivityDisplayStyleData(
        type: 'activity_attribute',
        attributeName: 'product-name',
    );

    $definition = new ActivityDefinitionData(
        name: 'product-purchase',
        iconId: ActivityIcon::Money,
        displayStyle: $displayStyle,
    );

    expect($definition->toArray())->toBe([
        'name' => 'product-purchase',
        'icon_id' => 'moneys-illustration-icon',
        'display_style' => [
            'type' => 'activity_attribute',
            'attribute_name' => 'product-name',
        ],
    ]);
});

test('converts to array with display style array', function (): void {
    $definition = new ActivityDefinitionData(
        name: 'product-purchase',
        iconId: ActivityIcon::Money,
        displayStyle: [
            'type' => 'activity_template',
            'title' => 'Purchased {{product-name}}',
        ],
    );

    expect($definition->toArray())->toBe([
        'name' => 'product-purchase',
        'icon_id' => 'moneys-illustration-icon',
        'display_style' => [
            'type' => 'activity_template',
            'title' => 'Purchased {{product-name}}',
        ],
    ]);
});

test('converts to array with attributes array', function (): void {
    $definition = new ActivityDefinitionData(
        name: 'product-purchase',
        iconId: ActivityIcon::Money,
        attributes: [
            new ActivityAttributeDefinitionData(
                name: 'product-name',
                displayType: ActivityDisplayType::Text,
            ),
            new ActivityAttributeDefinitionData(
                name: 'quantity',
                displayType: ActivityDisplayType::Integer,
                fieldId: 'do-not-map',
            ),
        ],
    );

    expect($definition->toArray())->toBe([
        'name' => 'product-purchase',
        'icon_id' => 'moneys-illustration-icon',
        'attributes' => [
            [
                'name' => 'product-name',
                'display_type' => 'text',
            ],
            [
                'name' => 'quantity',
                'display_type' => 'integer',
                'field_id' => 'do-not-map',
            ],
        ],
    ]);
});

test('handles nested data objects', function (): void {
    $definition = new ActivityDefinitionData(
        name: 'product-purchase',
        iconId: ActivityIcon::Money,
        trackConversionValue: true,
        touch: true,
        filterable: true,
        visibleInFeeds: true,
        displayStyle: new ActivityDisplayStyleData(
            type: 'activity_attribute',
            attributeName: 'product-name',
        ),
        attributes: [
            new ActivityAttributeDefinitionData(
                name: 'product-name',
                displayType: ActivityDisplayType::Text,
            ),
        ],
    );

    $result = $definition->toArray();

    expect($result)->toHaveKey('display_style')
        ->and($result['display_style'])->toBeArray()
        ->and($result)->toHaveKey('attributes')
        ->and($result['attributes'])->toBeArray()
        ->and($result['attributes'][0])->toBeArray();
});
