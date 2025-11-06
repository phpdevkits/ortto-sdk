<?php

use PhpDevKits\Ortto\Enums\PersonField;
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Activity\CreateActivities;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('createDefinition creates activity definition',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            \PhpDevKits\Ortto\Requests\Activity\CreateActivityDefinition::class => MockResponse::fixture('activity/create_definition_resource'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->activity()
            ->createDefinition(
                definition: [
                    'name' => 'sdk-test-simple',
                    'icon_id' => 'flag-activities-illustration-icon',
                    'track_conversion_value' => false,
                    'touch' => true,
                    'filterable' => true,
                    'visible_in_feeds' => true,
                    'display_style' => ['type' => 'activity'],
                    'attributes' => [],
                ],
            );

        expect($response->status())
            ->toBe(200);
    });

test('createDefinition creates with data object',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            \PhpDevKits\Ortto\Requests\Activity\CreateActivityDefinition::class => MockResponse::fixture('activity/create_definition_resource_data'),
        ]);

        $definition = new \PhpDevKits\Ortto\Data\ActivityDefinitionData(
            name: 'test-activity-data-obj',
            iconId: \PhpDevKits\Ortto\Enums\ActivityIcon::Happy,
            trackConversionValue: false,
            touch: true,
            filterable: true,
            visibleInFeeds: true,
            displayStyle: ['type' => 'activity'],
            attributes: [],
        );

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->activity()
            ->createDefinition(definition: $definition);

        expect($response->status())
            ->toBe(200);
    });

test('createDefinition creates with array',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            \PhpDevKits\Ortto\Requests\Activity\CreateActivityDefinition::class => MockResponse::fixture('activity/create_definition_resource_array'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->activity()
            ->createDefinition(
                definition: [
                    'name' => 'support-ticket',
                    'icon_id' => 'phone-illustration-icon',
                    'track_conversion_value' => false,
                    'touch' => true,
                    'filterable' => true,
                    'visible_in_feeds' => true,
                    'display_style' => [
                        'type' => 'activity',
                    ],
                    'attributes' => [],
                ],
            );

        expect($response->status())
            ->toBe(200);
    });

test('create creates activity',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            CreateActivities::class => MockResponse::fixture('activity/create_activities_with_person_id'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->activity()
            ->create(
                activities: [
                    [
                        'activity_id' => 'act::c',
                        'person_id' => '00690a5033a2e942cb9ffc00',
                        'attributes' => [
                            PersonField::FirstName->value => 'John',
                        ],
                    ],
                ],
            );

        expect($response->status())
            ->toBe(200);
    });

test('create creates activity with async mode',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            CreateActivities::class => MockResponse::fixture('activity/create_activities_async'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->activity()
            ->create(
                activities: [
                    [
                        'activity_id' => 'act::c',
                        'person_id' => '00690a5033a2e942cb9ffc00',
                    ],
                ],
                async: true,
            );

        expect($response->status())
            ->toBe(200);
    });
