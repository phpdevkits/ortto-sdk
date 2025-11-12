<?php

use PhpDevKits\Ortto\Data\PersonData;
use PhpDevKits\Ortto\Enums\FindStrategy;
use PhpDevKits\Ortto\Enums\MergeStrategy;
use PhpDevKits\Ortto\Enums\PersonField;
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Person\ArchivePeople;
use PhpDevKits\Ortto\Requests\Person\DeletePeople;
use PhpDevKits\Ortto\Requests\Person\MergePeople;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {

    /** @var Ortto $this ortto */
    $this->ortto = app(Ortto::class);

});

test('deletes archived people with inclusion ids',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            MergePeople::class => MockResponse::fixture('person/delete_create_for_for_delete_people_test'),
            ArchivePeople::class => MockResponse::fixture('person/delete_archive_for_for_delete_people_test'),
            DeletePeople::class => MockResponse::fixture('person/delete_people_by_ids'),
        ]);

        $peopleData = PersonData::factory()
            ->count(54)
            ->make();

        $peopleResponse = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new MergePeople(
                    people: array_map(fn (PersonData $person): array => $person->toArray(), $peopleData),
                    mergeBy: [PersonField::Email],
                    mergeStrategy: MergeStrategy::OverwriteExisting,
                    findStrategy: FindStrategy::All
                )
            );

        $newPeopleIds = collect($peopleResponse->json('people'))
            ->pluck('person_id')
            ->toArray();

        // Let's archive the people first
        $archiveResponse = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new ArchivePeople(
                    inclusionIds: $newPeopleIds,
                )
            );

        $deletedPeople = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new DeletePeople(
                    inclusionIds: $newPeopleIds
                )
            );

        expect($newPeopleIds)
            ->toHaveCount(54)
            ->and($archiveResponse->json('archived_contacts'))
            ->toBe(54)
            ->and($deletedPeople->json('deleted_contacts'))
            ->toBe(54);

    });

test('deletes all archived contacts with bulk flag',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            MergePeople::class => MockResponse::fixture('person/delete_bulk_create'),
            ArchivePeople::class => MockResponse::fixture('person/delete_bulk_archive'),
            DeletePeople::class => MockResponse::fixture('person/delete_people_all_archived'),
        ]);

        // Create people
        $peopleData = PersonData::factory()->count(10)->make();

        $peopleResponse = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new MergePeople(
                    people: array_map(fn (PersonData $person): array => $person->toArray(), $peopleData),
                    mergeBy: [PersonField::Email],
                    mergeStrategy: MergeStrategy::OverwriteExisting,
                    findStrategy: FindStrategy::All,
                ),
            );

        $newPeopleIds = collect($peopleResponse->json('people'))
            ->pluck('person_id')
            ->toArray();

        // Archive people
        $archiveResponse = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new ArchivePeople(
                    inclusionIds: $newPeopleIds,
                ),
            );

        // Delete all archived
        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new DeletePeople(
                    allRowsSelected: true,
                ),
            );

        expect($newPeopleIds)
            ->toHaveCount(10)
            ->and($archiveResponse->json('archived_contacts'))
            ->toBe(10)
            ->and($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('deleted_contacts')
            ->and($response->json('deleted_contacts'))
            ->toBe(10)
            ->and($response->json())
            ->toHaveKey('scheduled_contacts')
            ->and($response->json('scheduled_contacts'))
            ->toBe(0);

    });

test('deletes with exclusions',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            MergePeople::class => MockResponse::fixture('person/delete_exclusion_create'),
            ArchivePeople::class => MockResponse::fixture('person/delete_exclusion_archive'),
            DeletePeople::class => MockResponse::fixture('person/delete_people_with_exclusions'),
        ]);

        // Create people
        $peopleData = PersonData::factory()->count(5)->make();

        $peopleResponse = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new MergePeople(
                    people: array_map(fn (PersonData $person): array => $person->toArray(), $peopleData),
                    mergeBy: [PersonField::Email],
                    mergeStrategy: MergeStrategy::OverwriteExisting,
                    findStrategy: FindStrategy::All,
                ),
            );

        $newPeopleIds = collect($peopleResponse->json('people'))
            ->pluck('person_id')
            ->toArray();

        // Archive people
        $archiveResponse = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new ArchivePeople(
                    inclusionIds: $newPeopleIds,
                ),
            );

        // Delete all except first one
        $excludedId = $newPeopleIds[0];

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new DeletePeople(
                    exclusionIds: [$excludedId],
                    allRowsSelected: true,
                ),
            );

        expect($newPeopleIds)
            ->toHaveCount(5)
            ->and($archiveResponse->json('archived_contacts'))
            ->toBe(5)
            ->and($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('deleted_contacts')
            ->and($response->json('deleted_contacts'))
            ->toBe(4)
            ->and($response->json())
            ->toHaveKey('scheduled_contacts')
            ->and($response->json('scheduled_contacts'))
            ->toBe(0);

    });
