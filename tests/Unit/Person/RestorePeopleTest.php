<?php

use PhpDevKits\Ortto\Data\PersonData;
use PhpDevKits\Ortto\Enums\FindStrategy;
use PhpDevKits\Ortto\Enums\MergeStrategy;
use PhpDevKits\Ortto\Enums\PersonField;
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Person\ArchivePeople;
use PhpDevKits\Ortto\Requests\Person\MergePeople;
use PhpDevKits\Ortto\Requests\Person\RestorePeople;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {

    /** @var Ortto $this ortto */
    $this->ortto = app(Ortto::class);

});

test('restores archived people with inclusion ids',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            MergePeople::class => MockResponse::fixture('person/restore_create'),
            ArchivePeople::class => MockResponse::fixture('person/restore_archive'),
            RestorePeople::class => MockResponse::fixture('person/restore_people_by_ids'),
        ]);

        $peopleData = PersonData::factory()
            ->count(54)
            ->make();

        $peopleResponse = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new MergePeople(
                    people: $peopleData->toArray(),
                    mergeBy: [PersonField::Email],
                    mergeStrategy: MergeStrategy::OverwriteExisting,
                    findStrategy: FindStrategy::All,
                ),
            );

        $newPeopleIds = collect($peopleResponse->json('people'))
            ->pluck('person_id')
            ->toArray();

        // Archive the people first
        $archiveResponse = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new ArchivePeople(
                    inclusionIds: $newPeopleIds,
                ),
            );

        // Restore them
        $restoreResponse = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new RestorePeople(
                    inclusionIds: $newPeopleIds,
                ),
            );

        expect($newPeopleIds)
            ->toHaveCount(54)
            ->and($archiveResponse->json('archived_contacts'))
            ->toBe(54)
            ->and($restoreResponse->json('restored_contacts'))
            ->toBe(54);

    });

test('restores all archived contacts with bulk flag',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            MergePeople::class => MockResponse::fixture('person/restore_bulk_create'),
            ArchivePeople::class => MockResponse::fixture('person/restore_bulk_archive'),
            RestorePeople::class => MockResponse::fixture('person/restore_people_all_archived'),
        ]);

        // Create people
        $peopleData = PersonData::factory()->count(10)->make();

        $peopleResponse = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new MergePeople(
                    people: $peopleData->toArray(),
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

        // Restore all archived
        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new RestorePeople(
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
            ->toHaveKey('restored_contacts')
            ->and($response->json('restored_contacts'))
            ->toBeGreaterThanOrEqual(10)
            ->and($response->json())
            ->toHaveKey('scheduled_contacts')
            ->and($response->json('scheduled_contacts'))
            ->toBe(0);

    });

test('restores with exclusions',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            MergePeople::class => MockResponse::fixture('person/restore_exclusion_create'),
            ArchivePeople::class => MockResponse::fixture('person/restore_exclusion_archive'),
            RestorePeople::class => MockResponse::fixture('person/restore_people_with_exclusions'),
        ]);

        // Create people
        $peopleData = PersonData::factory()->count(5)->make();

        $peopleResponse = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new MergePeople(
                    people: $peopleData->toArray(),
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

        // Restore all except first one
        $excludedId = $newPeopleIds[0];

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new RestorePeople(
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
            ->toHaveKey('restored_contacts')
            ->and($response->json('restored_contacts'))
            ->toBe(4)
            ->and($response->json())
            ->toHaveKey('scheduled_contacts')
            ->and($response->json('scheduled_contacts'))
            ->toBe(0);

    });
