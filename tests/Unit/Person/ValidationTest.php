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

test('merge with invalid field returns error',
    /**
     * @throws Throwable
     */
    function (): void {

        $person = PersonData::factory()
            ->make();

        $mockClient = new MockClient([
            MergePeople::class => MockResponse::fixture('person/merge_people_with_invalid_field'),
        ]);

        $payload = $person->toArray();
        $payload['fields']['str::invalid_field_that_does_not_exist'] = 'Some';

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new MergePeople(
                    people: [
                        $payload,
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
