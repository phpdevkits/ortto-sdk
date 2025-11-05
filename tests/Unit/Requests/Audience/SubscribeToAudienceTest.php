<?php

use PhpDevKits\Ortto\Data\PersonData;
use PhpDevKits\Ortto\Data\PersonSubscriptionData;
use PhpDevKits\Ortto\Enums\FindStrategy;
use PhpDevKits\Ortto\Enums\MergeStrategy;
use PhpDevKits\Ortto\Enums\PersonField;
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Audience\GetAudiences;
use PhpDevKits\Ortto\Requests\Audience\SubscribeToAudience;
use PhpDevKits\Ortto\Requests\Person\MergePeople;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {

    /** @var Ortto $this ortto */
    $this->ortto = app(Ortto::class);

});

test('subscribes people to audience',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            GetAudiences::class => MockResponse::fixture('audience/subscribe_test_get_audiences'),
            MergePeople::class => MockResponse::fixture('person/subscribe_test_create_people'),
            SubscribeToAudience::class => MockResponse::fixture('audience/subscribe_people'),
        ]);

        // Step 1: Get audiences to get a real audience ID
        $audiencesResponse = $this->ortto
            ->withMockClient($mockClient)
            ->send(new GetAudiences(limit: 1));

        $audiences = $audiencesResponse->json();
        $audienceId = $audiences[0]['id'];

        // Step 2: Create people
        $peopleData = PersonData::factory()->count(2)->make();

        $createResponse = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new MergePeople(
                    people: $peopleData->toArray(),
                    mergeBy: [PersonField::Email],
                    mergeStrategy: MergeStrategy::OverwriteExisting,
                    findStrategy: FindStrategy::All,
                ),
            );

        $peopleIds = collect($createResponse->json('people'))
            ->pluck('person_id')
            ->toArray();

        // Step 3: Subscribe people to audience
        $subscriptionPeople = [
            new PersonSubscriptionData(
                personId: $peopleIds[0],
                subscribed: true,
                smsOptedIn: true,
            )->toArray(),
            new PersonSubscriptionData(
                personId: $peopleIds[1],
                subscribed: false,
                smsOptedIn: false,
            )->toArray(),
        ];

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new SubscribeToAudience(
                    audienceId: $audienceId,
                    people: $subscriptionPeople,
                ),
            );

        expect($peopleIds)
            ->toHaveCount(2)
            ->and($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('people')
            ->and($response->json('people'))
            ->toHaveCount(2)
            ->and($response->json('people.0.person_status'))
            ->toBe('by-id')
            ->and($response->json('people.0.status'))
            ->toBe('subscribed')
            ->and($response->json('people.1.status'))
            ->toBe('unsubscribed');

    });
