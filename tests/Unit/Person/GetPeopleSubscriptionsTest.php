<?php

use PhpDevKits\Ortto\Data\PersonData;
use PhpDevKits\Ortto\Data\PersonSubscriptionData;
use PhpDevKits\Ortto\Enums\FindStrategy;
use PhpDevKits\Ortto\Enums\MergeStrategy;
use PhpDevKits\Ortto\Enums\PersonField;
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Audience\SubscribeToAudience;
use PhpDevKits\Ortto\Requests\Person\GetPeopleSubscriptions;
use PhpDevKits\Ortto\Requests\Person\MergePeople;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {

    /** @var Ortto $this ortto */
    $this->ortto = app(Ortto::class);

});

test('gets subscriptions by person id',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            MergePeople::class => MockResponse::fixture('person/subscription_test_create_people'),
            SubscribeToAudience::class => MockResponse::fixture('audience/subscription_test_subscribe_to_audiences'),
            GetPeopleSubscriptions::class => MockResponse::fixture('person/get_subscriptions_by_person_id'),
        ]);

        // Create a person with SMS permission true (auto-subscribes to SMS subscribers audience)
        $person = PersonData::factory()->make([
            'emailPermission' => false,
            'smsPermission' => true,
        ]);

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

        // Subscribe person to "My team" and "Engaged subscribers"
        $subscriptionPerson = new PersonSubscriptionData(
            personId: $personId,
            subscribed: true,
        );

        // Subscribe to My team
        $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new SubscribeToAudience(
                    audienceId: '6904f4fbf0cc08364094a968',
                    people: [$subscriptionPerson->toArray()],
                ),
            );

        // Subscribe to Engaged subscribers
        $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new SubscribeToAudience(
                    audienceId: '6904f4fbf0cc08364094a966',
                    people: [$subscriptionPerson->toArray()],
                ),
            );

        // Get subscription status
        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetPeopleSubscriptions(
                    people: [
                        ['person_id' => $personId],
                    ],
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('people')
            ->and($response->json('people.0.person_status'))
            ->toBe('by-id')
            ->and($response->json('people.0.person_id'))
            ->toBe($personId)
            ->and($response->json('people.0'))
            ->toHaveKey('subscriptions')
            ->and($response->json('people.0.subscriptions'))
            ->toBeArray()
            ->and($response->json('people.0.subscriptions'))
            ->not->toBeEmpty()
            ->and($response->json('people.0.email_permissions'))
            ->toBeFalse()
            ->and($response->json('people.0.sms_permissions'))
            ->toBeTrue();

    });

test('gets subscriptions by email',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            MergePeople::class => MockResponse::fixture('person/subscription_email_test_create_people'),
            SubscribeToAudience::class => MockResponse::fixture('audience/subscription_email_test_subscribe_to_audiences'),
            GetPeopleSubscriptions::class => MockResponse::fixture('person/get_subscriptions_by_email'),
        ]);

        // Create a person with both email and SMS permissions
        // This auto-subscribes them to "Subscribers" and "SMS subscribers" audiences
        $person = PersonData::factory()->make([
            'email' => 'subscription.test@example.com',
            'emailPermission' => true,
            'smsPermission' => true,
        ]);

        $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new MergePeople(
                    people: [$person->toArray()],
                    mergeBy: [PersonField::Email],
                    mergeStrategy: MergeStrategy::OverwriteExisting,
                    findStrategy: FindStrategy::All,
                ),
            );

        // Subscribe to My team and Engaged subscribers
        $subscriptionPerson = new PersonSubscriptionData(
            email: 'subscription.test@example.com',
            subscribed: true,
        );

        // Subscribe to My team
        $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new SubscribeToAudience(
                    audienceId: '6904f4fbf0cc08364094a968',
                    people: [$subscriptionPerson->toArray()],
                ),
            );

        // Subscribe to Engaged subscribers
        $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new SubscribeToAudience(
                    audienceId: '6904f4fbf0cc08364094a966',
                    people: [$subscriptionPerson->toArray()],
                ),
            );

        // Step 4: Get subscription status by email
        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetPeopleSubscriptions(
                    people: [
                        ['email' => 'subscription.test@example.com'],
                    ],
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('people')
            ->and($response->json('people.0.person_status'))
            ->toBe('merged')
            ->and($response->json('people.0.email'))
            ->toBe('subscription.test@example.com')
            ->and($response->json('people.0'))
            ->toHaveKey('subscriptions')
            ->and($response->json('people.0.subscriptions'))
            ->toBeArray()
            ->and($response->json('people.0.subscriptions'))
            ->toHaveCount(2)
            ->and($response->json('people.0.email_permissions'))
            ->toBeTrue()
            ->and($response->json('people.0.sms_permissions'))
            ->toBeTrue();

        // Verify person is in the 2 auto-subscribed audiences
        // Note: SubscribeToAudience calls above don't add to "My team" because person doesn't match audience filter
        $audienceNames = collect($response->json('people.0.subscriptions'))
            ->pluck('audience_name')
            ->toArray();

        expect($audienceNames)
            ->toContain('Subscribers')
            ->toContain('SMS subscribers');

    });
