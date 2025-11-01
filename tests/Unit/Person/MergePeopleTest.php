<?php

use PhpDevKits\Ortto\Data\Person;
use PhpDevKits\Ortto\Enums\FindStrategy;
use PhpDevKits\Ortto\Enums\MergeStrategy;
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Person\MergePeople;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('person is created when email does not exist',
    /**
     * @throws Throwable
     */
    function (): void {

        $person = Person::factory()
            ->state([
                'str::email' => 'test.create@example.com',
                'str::first' => 'John',
                'str::last' => 'Doe',
            ])
            ->make();

        $mockClient = new MockClient([
            MergePeople::class => MockResponse::fixture('person/merge_people_created'),
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
            ->toBe('created')
            ->and($response->json('people.0'))
            ->toHaveKey('person_id');

    });

test('person is merged when email already exists',
    /**
     * @throws Throwable
     */
    function (): void {

        $person = Person::factory()
            ->state([
                'str::email' => 'test.update@example.com',
                'str::first' => 'Jane',
                'str::last' => 'Smith',
            ])
            ->make();

        $mockClient = new MockClient([
            MergePeople::class => MockResponse::fixture('person/merge_people_merged'),
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

        $person = Person::factory()
            ->state(['str::email' => 'francisco.barrento@gmail.com'])
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
                    skipSuppressionCheck: true
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

test('suppressed email is blocked when creating new contact',
    /**
     * @throws Throwable
     */
    function (): void {

        $person = Person::factory()
            ->state(['str::email' => 'test.suppressed@example.com'])
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
                    suppressionListFieldId: 'str::email'
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

        $person = Person::factory()
            ->state(['str::email' => 'francisco.barrento@gmail.com'])
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
                    suppressionListFieldId: 'str::email'
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

test('merge with invalid field returns error',
    /**
     * @throws Throwable
     */
    function (): void {

        $person = Person::factory()
            ->state(['str::invalid_field_that_does_not_exist' => 'test value'])
            ->make();

        $mockClient = new MockClient([
            MergePeople::class => MockResponse::fixture('person/merge_people_with_invalid_field'),
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
                ),
            );

        expect($response->status())
            ->toBe(400)
            ->and($response->json())
            ->toHaveKey('error')
            ->and($response->json('code'))
            ->toBe(400)
            ->and($response->json('error'))
            ->toContain('No valid contacts provided')
            ->toContain('str::invalid_field_that_does_not_exist')
            ->and($response->json('details'))
            ->toBeArray()
            ->and($response->json('details.0.status'))
            ->toBe('invalid');

    });

test('people are merged successfully',
    /**
     * @throws Throwable
     */
    function (): void {

        $person = Person::factory()->make();

        $mockClient = new MockClient([
            MergePeople::class => MockResponse::fixture('person/merge_people_ok'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)->send(
                new MergePeople(
                    people: [
                        $person->toArray(),
                    ],
                    mergeBy: ['str::email'],
                    mergeStrategy: MergeStrategy::OverwriteExisting->value,
                    findStrategy: FindStrategy::All->value,
                    suppressionListFieldId: 'str::email'
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('people')
            ->and($response->json('people'))
            ->toBeArray();

    });
