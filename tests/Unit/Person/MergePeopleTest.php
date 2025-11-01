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
                    mergedBy: ['str::email'],
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
                    mergedBy: ['str::email'],
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
