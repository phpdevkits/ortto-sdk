<?php

use PhpDevKits\Ortto\Enums\PersonField;
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Activity\CreateActivities;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
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
