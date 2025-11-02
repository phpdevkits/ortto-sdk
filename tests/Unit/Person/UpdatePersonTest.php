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

test('person is merged when email already exists',
    /**
     * @throws Throwable
     */
    function (): void {

        $person = PersonData::factory()->make([
            'email' => 'test.update@example.com',
            'firstName' => 'Jane',
            'lastName' => 'Smith',
        ]);

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

test('people are merged successfully',
    /**
     * @throws Throwable
     */
    function (): void {

        $person = PersonData::factory()->make();

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
                    suppressionListFieldId: 'str::email',
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('people')
            ->and($response->json('people'))
            ->toBeArray();

    });
