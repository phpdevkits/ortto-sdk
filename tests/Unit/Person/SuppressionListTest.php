<?php

use PhpDevKits\Ortto\Data\PersonData;
use PhpDevKits\Ortto\Enums\FindStrategy;
use PhpDevKits\Ortto\Enums\MergeStrategy;
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Person\MergePeople;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('suppressed email is blocked when creating new contact',
    /**
     * @throws Throwable
     */
    function (): void {

        $person = PersonData::factory()
            ->state(['email' => 'test.suppressed@example.com'])
            ->make();

        $mockClient = new MockClient([
            MergePeople::class => MockResponse::fixture('person/merge_people_suppressed_blocked'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new MergePeople(
                    people: [
                        $person->toArray(),
                    ],
                    mergeBy: ['str::email'],
                    mergeStrategy: MergeStrategy::OverwriteExisting->value,
                    findStrategy: FindStrategy::All->value,
                    suppressionListFieldId: 'str::email',
                ),
            );

        expect($response->status())
            ->toBe(400)
            ->and($response->json())
            ->toHaveKey('details')
            ->and($response->json('details'))
            ->toBeArray()
            ->and($response->json('details.0.status'))
            ->toBe('suppressed')
            ->and($response->json('details.0.error'))
            ->toBe('Email is suppressed');

    });

test('suppressed email is merged when contact already exists',
    /**
     * @throws Throwable
     */
    function (): void {

        $person = PersonData::factory()
            ->state(['email' => 'francisco.barrento@gmail.com'])
            ->make();

        $mockClient = new MockClient([
            MergePeople::class => MockResponse::fixture('person/merge_people_suppressed_existing'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new MergePeople(
                    people: [
                        $person->toArray(),
                    ],
                    mergeBy: ['str::email'],
                    mergeStrategy: MergeStrategy::OverwriteExisting->value,
                    findStrategy: FindStrategy::All->value,
                    suppressionListFieldId: 'str::email',
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('people')
            ->and($response->json('people'))
            ->toBeArray()
            ->and($response->json('people.0'))
            ->toHaveKey('status')
            ->and($response->json('people.0.status'))
            ->toBe('merged')
            ->and($response->json('people.0'))
            ->toHaveKey('person_id');

    });

test('merge with suppressed email succeeds when suppression check skipped',
    /**
     * @throws Throwable
     */
    function (): void {

        $person = PersonData::factory()
            ->state(['email' => 'francisco.barrento@gmail.com'])
            ->make();

        $mockClient = new MockClient([
            MergePeople::class => MockResponse::fixture('person/merge_people_suppressed_email_without_check'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new MergePeople(
                    people: [
                        $person->toArray(),
                    ],
                    mergeBy: ['str::email'],
                    mergeStrategy: MergeStrategy::OverwriteExisting->value,
                    findStrategy: FindStrategy::All->value,
                    skipSuppressionCheck: true,
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('people')
            ->and($response->json('people'))
            ->toBeArray()
            ->and($response->json('people.0'))
            ->toHaveKey('status')
            ->and($response->json('people.0.status'))
            ->toBe('merged')
            ->and($response->json('people.0'))
            ->toHaveKey('person_id');

    });
