<?php

declare(strict_types=1);

use PhpDevKits\Ortto\Data\ActivityAttributeDefinitionData;
use PhpDevKits\Ortto\Data\ActivityDefinitionData;
use PhpDevKits\Ortto\Data\ActivityDisplayStyleData;
use PhpDevKits\Ortto\Enums\ActivityDisplayType;
use PhpDevKits\Ortto\Enums\ActivityIcon;
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Activity\CreateActivityDefinition;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('creates basic activity definition',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            CreateActivityDefinition::class => MockResponse::fixture('activity/create_definition_basic'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new CreateActivityDefinition(
                    definition: [
                        'name' => 'sdk-test-basic-def',
                        'icon_id' => 'moneys-illustration-icon',
                        'track_conversion_value' => false,
                        'touch' => true,
                        'filterable' => true,
                        'visible_in_feeds' => true,
                        'display_style' => [
                            'type' => 'activity',
                        ],
                        'attributes' => [],
                    ],
                ),
            );

        expect($response->status())->toBe(200);
    });

test('creates definition with all fields',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            CreateActivityDefinition::class => MockResponse::fixture('activity/create_definition_full'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new CreateActivityDefinition(
                    definition: [
                        'name' => 'sdk-test-all-fields',
                        'icon_id' => 'moneys-illustration-icon',
                        'track_conversion_value' => true,
                        'touch' => true,
                        'filterable' => true,
                        'visible_in_feeds' => true,
                        'display_style' => [
                            'type' => 'activity',
                        ],
                        'attributes' => [],
                    ],
                ),
            );

        expect($response->status())->toBe(200);
    });

test('creates definition with display style activity only',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            CreateActivityDefinition::class => MockResponse::fixture('activity/create_definition_display_activity'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new CreateActivityDefinition(
                    definition: [
                        'name' => 'sdk-test-display-act',
                        'icon_id' => 'eye-illustration-icon',
                        'track_conversion_value' => false,
                        'touch' => true,
                        'filterable' => true,
                        'visible_in_feeds' => true,
                        'display_style' => [
                            'type' => 'activity',
                        ],
                        'attributes' => [],
                    ],
                ),
            );

        expect($response->status())->toBe(200);
    });

test('creates definition with display style activity attribute',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            CreateActivityDefinition::class => MockResponse::fixture('activity/create_definition_display_attribute'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new CreateActivityDefinition(
                    definition: [
                        'name' => 'sdk-test-display-attr',
                        'icon_id' => 'moneys-illustration-icon',
                        'track_conversion_value' => false,
                        'touch' => true,
                        'filterable' => true,
                        'visible_in_feeds' => true,
                        'display_style' => [
                            'type' => 'activity_attribute',
                            'attribute_name' => 'product-name',
                        ],
                        'attributes' => [
                            [
                                'name' => 'product-name',
                                'display_type' => 'text',
                            ],
                        ],
                    ],
                ),
            );

        expect($response->status())->toBe(200);
    });

test('creates definition with display style activity template',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            CreateActivityDefinition::class => MockResponse::fixture('activity/create_definition_display_template'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new CreateActivityDefinition(
                    definition: [
                        'name' => 'sdk-test-template-v3',
                        'icon_id' => 'download-illustration-icon',
                        'track_conversion_value' => false,
                        'touch' => true,
                        'filterable' => true,
                        'visible_in_feeds' => true,
                        'display_style' => [
                            'type' => 'activity',
                        ],
                        'attributes' => [
                            [
                                'name' => 'item-name',
                                'display_type' => 'text',
                            ],
                            [
                                'name' => 'item-count',
                                'display_type' => 'integer',
                            ],
                        ],
                    ],
                ),
            );

        expect($response->status())->toBe(200);
    });

test('creates definition with icon enum',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            CreateActivityDefinition::class => MockResponse::fixture('activity/create_definition_icon_enum'),
        ]);

        $definition = new ActivityDefinitionData(
            name: 'sdk-test-icon-enum',
            iconId: ActivityIcon::Calendar,
            trackConversionValue: false,
            touch: true,
            filterable: true,
            visibleInFeeds: true,
            displayStyle: ['type' => 'activity'],
            attributes: [],
        );

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(new CreateActivityDefinition(definition: $definition));

        expect($response->status())->toBe(200);
    });

test('creates definition with attributes',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            CreateActivityDefinition::class => MockResponse::fixture('activity/create_definition_with_attributes'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new CreateActivityDefinition(
                    definition: [
                        'name' => 'sdk-test-with-attrs',
                        'icon_id' => 'moneys-illustration-icon',
                        'track_conversion_value' => false,
                        'touch' => true,
                        'filterable' => true,
                        'visible_in_feeds' => true,
                        'display_style' => [
                            'type' => 'activity',
                        ],
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
                    ],
                ),
            );

        expect($response->status())->toBe(200);
    });

test('creates definition with data object',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            CreateActivityDefinition::class => MockResponse::fixture('activity/create_definition_data_object'),
        ]);

        $definition = new ActivityDefinitionData(
            name: 'sdk-test-data-object',
            iconId: ActivityIcon::Money,
            trackConversionValue: false,
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

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(new CreateActivityDefinition(definition: $definition));

        expect($response->status())->toBe(200);
    });
