<?php

use Carbon\CarbonImmutable;
use PhpDevKits\Ortto\Enums\MergeStrategy;
use PhpDevKits\Ortto\Enums\PersonField;
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Activity\CreateActivities;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('creates activity with person id',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            CreateActivities::class => MockResponse::fixture('activity/create_activities_with_person_id'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new CreateActivities(
                    activities: [
                        [
                            'activity_id' => 'act::c',
                            'person_id' => '00690a5033a2e942cb9ffc00',
                            'attributes' => [
                                PersonField::FirstName->value => 'John',
                            ],
                        ],
                    ],
                ),
            );

        expect($response->status())
            ->toBe(200);
    });

test('creates activity with fields and merge by',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            CreateActivities::class => MockResponse::fixture('activity/create_activities_with_merge'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new CreateActivities(
                    activities: [
                        [
                            'activity_id' => 'act::c',
                            'fields' => [
                                PersonField::Email->value => 'john@example.com',
                                PersonField::FirstName->value => 'John',
                            ],
                            'merge_by' => [PersonField::Email->value],
                            'attributes' => [
                                PersonField::FirstName->value => 'John',
                            ],
                        ],
                    ],
                ),
            );

        expect($response->status())
            ->toBe(200);
    });

test('creates activity with backdate timestamp',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            CreateActivities::class => MockResponse::fixture('activity/create_activities_backdate'),
        ]);

        $backdateTimestamp = CarbonImmutable::now()->subDays(30)->toIso8601String();

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new CreateActivities(
                    activities: [
                        [
                            'activity_id' => 'act::c',
                            'person_id' => '00690a5033a2e942cb9ffc00',
                            'created' => $backdateTimestamp,
                        ],
                    ],
                ),
            );

        expect($response->status())
            ->toBe(200);
    });

test('creates activity with location from ip',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            CreateActivities::class => MockResponse::fixture('activity/create_activities_location_ip'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new CreateActivities(
                    activities: [
                        [
                            'activity_id' => 'act::c',
                            'person_id' => '00690a5033a2e942cb9ffc00',
                            'location' => [
                                'ip' => '203.123.45.67',
                            ],
                        ],
                    ],
                ),
            );

        expect($response->status())
            ->toBe(200);
    });

test('creates activity with location from coordinates',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            CreateActivities::class => MockResponse::fixture('activity/create_activities_location_coords'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new CreateActivities(
                    activities: [
                        [
                            'activity_id' => 'act::c',
                            'person_id' => '00690a5033a2e942cb9ffc00',
                            'location' => [
                                'custom' => [
                                    'latitude' => -33.8688,
                                    'longitude' => 151.2093,
                                ],
                            ],
                        ],
                    ],
                ),
            );

        expect($response->status())
            ->toBe(200);
    });

test('creates activity with location from address',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            CreateActivities::class => MockResponse::fixture('activity/create_activities_location_address'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new CreateActivities(
                    activities: [
                        [
                            'activity_id' => 'act::c',
                            'person_id' => '00690a5033a2e942cb9ffc00',
                            'location' => [
                                'custom' => [
                                    'city' => 'Sydney',
                                    'region' => 'NSW',
                                    'country' => 'Australia',
                                    'postal_code' => '2000',
                                ],
                            ],
                        ],
                    ],
                ),
            );

        expect($response->status())
            ->toBe(200);
    });

test('creates activity with async mode',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            CreateActivities::class => MockResponse::fixture('activity/create_activities_async'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new CreateActivities(
                    activities: [
                        [
                            'activity_id' => 'act::c',
                            'person_id' => '00690a5033a2e942cb9ffc00',
                        ],
                    ],
                    async: true,
                ),
            );

        expect($response->status())
            ->toBe(200);
    });

test('creates bulk activities',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            CreateActivities::class => MockResponse::fixture('activity/create_activities_bulk'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new CreateActivities(
                    activities: [
                        [
                            'activity_id' => 'act::c',
                            'person_id' => '00690a5033a2e942cb9ffc00',
                        ],
                        [
                            'activity_id' => 'act::c',
                            'person_id' => '00690a4feaa17e5f70919700',
                        ],
                        [
                            'activity_id' => 'act::c',
                            'person_id' => '00690a4fa7a17e5f70915100',
                        ],
                    ],
                ),
            );

        expect($response->status())
            ->toBe(200);
    });

test('creates activity with merge strategy',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            CreateActivities::class => MockResponse::fixture('activity/create_activities_merge_strategy'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new CreateActivities(
                    activities: [
                        [
                            'activity_id' => 'act::c',
                            'fields' => [
                                PersonField::Email->value => 'john@example.com',
                            ],
                            'merge_by' => [PersonField::Email->value],
                            'merge_strategy' => MergeStrategy::AppendOnly->value,
                        ],
                    ],
                ),
            );

        expect($response->status())
            ->toBe(200);
    });
