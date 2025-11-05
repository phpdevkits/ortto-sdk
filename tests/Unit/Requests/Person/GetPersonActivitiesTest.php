<?php

use PhpDevKits\Ortto\Data\PersonData;
use PhpDevKits\Ortto\Enums\ActivityTimeframe;
use PhpDevKits\Ortto\Enums\FindStrategy;
use PhpDevKits\Ortto\Enums\MergeStrategy;
use PhpDevKits\Ortto\Enums\PersonField;
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Person\GetPersonActivities;
use PhpDevKits\Ortto\Requests\Person\MergePeople;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {

    /** @var Ortto $this ortto */
    $this->ortto = app(Ortto::class);

});

test('gets activities with basic request',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            MergePeople::class => MockResponse::fixture('person/activity_test_create_person'),
            GetPersonActivities::class => MockResponse::fixture('person/get_activities_basic'),
        ]);

        // Create a person to get activities for
        $person = PersonData::factory()->make();

        $createResponse = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new MergePeople(
                    people: [$person->toArray()],
                    mergeBy: [PersonField::Email],
                    mergeStrategy: MergeStrategy::OverwriteExisting,
                    findStrategy: FindStrategy::All,
                ),
            );

        $personId = $createResponse->json('people.0.person_id');

        // Get activities for the person
        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetPersonActivities(
                    personId: $personId,
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('activities')
            ->and($response->json('activities'))
            ->toBeArray()
            ->and($response->json('activities'))
            ->toHaveCount(3)
            ->and($response->json('activities.0'))
            ->toHaveKey('id')
            ->and($response->json('activities.0'))
            ->toHaveKey('field_id')
            ->and($response->json('activities.0'))
            ->toHaveKey('created_at')
            ->and($response->json('meta.total_activities'))
            ->toBe(3)
            ->and($response->json('meta.has_more'))
            ->toBeFalse()
            ->and($response->json('offset'))
            ->toBe(0)
            ->and($response->json('next_offset'))
            ->toBe(3);

    });

test('gets activities with all parameters',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            MergePeople::class => MockResponse::fixture('person/activity_all_params_test_create_person'),
            GetPersonActivities::class => MockResponse::fixture('person/get_activities_with_all_parameters'),
        ]);

        // Create a person
        $person = PersonData::factory()->make();

        $createResponse = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new MergePeople(
                    people: [$person->toArray()],
                    mergeBy: [PersonField::Email],
                    mergeStrategy: MergeStrategy::OverwriteExisting,
                    findStrategy: FindStrategy::All,
                ),
            );

        $personId = $createResponse->json('people.0.person_id');

        // Get activities with all parameters
        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetPersonActivities(
                    personId: $personId,
                    activities: ['act::o', 'act::c'],
                    limit: 20,
                    offset: 0,
                    timeframe: ActivityTimeframe::Last7Days,
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json('activities'))
            ->toBeNull()
            ->and($response->json('meta.total_activities'))
            ->toBe(0)
            ->and($response->json('meta.field_ids'))
            ->toBe(['act::o', 'act::c'])
            ->and($response->json('meta.has_more'))
            ->toBeFalse()
            ->and($response->json('meta.retention.type'))
            ->toBe('time')
            ->and($response->json('meta.retention.max_value'))
            ->toBe(90)
            ->and($response->json('offset'))
            ->toBe(0)
            ->and($response->json('next_offset'))
            ->toBe(0);

    });

test('gets activities with timeframe filter using enum',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            MergePeople::class => MockResponse::fixture('person/activity_timeframe_enum_test_create_person'),
            GetPersonActivities::class => MockResponse::fixture('person/get_activities_with_timeframe_enum'),
        ]);

        // Create a person
        $person = PersonData::factory()->make();

        $createResponse = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new MergePeople(
                    people: [$person->toArray()],
                    mergeBy: [PersonField::Email],
                    mergeStrategy: MergeStrategy::OverwriteExisting,
                    findStrategy: FindStrategy::All,
                ),
            );

        $personId = $createResponse->json('people.0.person_id');

        // Get activities with timeframe filter using enum
        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetPersonActivities(
                    personId: $personId,
                    timeframe: ActivityTimeframe::Last30Days,
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json('activities'))
            ->toBeArray()
            ->and($response->json('activities'))
            ->toHaveCount(3)
            ->and($response->json('meta.total_activities'))
            ->toBe(3)
            ->and($response->json('meta.has_more'))
            ->toBeFalse()
            ->and($response->json('offset'))
            ->toBe(0)
            ->and($response->json('next_offset'))
            ->toBe(3);

    });

test('gets activities with timeframe filter using string',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            MergePeople::class => MockResponse::fixture('person/activity_timeframe_string_test_create_person'),
            GetPersonActivities::class => MockResponse::fixture('person/get_activities_with_timeframe_string'),
        ]);

        // Create a person
        $person = PersonData::factory()->make();

        $createResponse = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new MergePeople(
                    people: [$person->toArray()],
                    mergeBy: [PersonField::Email],
                    mergeStrategy: MergeStrategy::OverwriteExisting,
                    findStrategy: FindStrategy::All,
                ),
            );

        $personId = $createResponse->json('people.0.person_id');

        // Get activities with timeframe filter using string
        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetPersonActivities(
                    personId: $personId,
                    timeframe: 'today',
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json('activities'))
            ->toBeArray()
            ->and($response->json('activities'))
            ->toHaveCount(3)
            ->and($response->json('activities.0.field_id'))
            ->toBeString()
            ->and($response->json('activities.0'))
            ->toHaveKeys(['id', 'field_id', 'created_at', 'attr', 'render_cmds'])
            ->and($response->json('meta.total_activities'))
            ->toBe(3)
            ->and($response->json('meta.has_more'))
            ->toBeFalse()
            ->and($response->json('meta.retention.type'))
            ->toBe('time')
            ->and($response->json('meta.retention.max_value'))
            ->toBe(90)
            ->and($response->json('offset'))
            ->toBe(0)
            ->and($response->json('next_offset'))
            ->toBe(3);

    });
