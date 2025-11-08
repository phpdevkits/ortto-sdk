<?php

use PhpDevKits\Ortto\Enums\FindStrategy;
use PhpDevKits\Ortto\Enums\MergeStrategy;
use PhpDevKits\Ortto\Enums\SortOrder;
use PhpDevKits\Ortto\Enums\Timeframe;
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Person\ArchivePeople;
use PhpDevKits\Ortto\Requests\Person\DeletePeople;
use PhpDevKits\Ortto\Requests\Person\GetPeople;
use PhpDevKits\Ortto\Requests\Person\GetPeopleByIds;
use PhpDevKits\Ortto\Requests\Person\GetPeopleSubscriptions;
use PhpDevKits\Ortto\Requests\Person\GetPersonActivities;
use PhpDevKits\Ortto\Requests\Person\MergePeople;
use PhpDevKits\Ortto\Requests\Person\RestorePeople;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('archive archives people by inclusion ids',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            ArchivePeople::class => MockResponse::fixture('person/archive_people_by_ids'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->person()
            ->archive(
                inclusionIds: ['0069061b5bda4060a5765300'],
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('archived_contacts')
            ->and($response->json('archived_contacts'))
            ->toBe(1);
    });

test('archive archives all people when all rows selected',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            ArchivePeople::class => MockResponse::fixture('person/archive_people_all_rows'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->person()
            ->archive(
                allRowsSelected: true,
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('archived_contacts');
    });

test('archive archives people with exclusions',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            ArchivePeople::class => MockResponse::fixture('person/archive_people_with_exclusions'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->person()
            ->archive(
                exclusionIds: ['0069061b5bda4060a5765300'],
                allRowsSelected: true,
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('archived_contacts');
    });

test('activities gets person activities with basic parameters',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            GetPersonActivities::class => MockResponse::fixture('person/get_activities_basic'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->person()
            ->activities(
                personId: '0069061b5bda4060a5765300',
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('activities')
            ->and($response->json('activities'))
            ->toBeArray();
    });

test('activities gets person activities with all parameters',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            GetPersonActivities::class => MockResponse::fixture('person/get_activities_with_all_parameters'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->person()
            ->activities(
                personId: '0069061b5bda4060a5765300',
                activities: ['act::page-visited'],
                limit: 10,
                offset: 0,
                timeframe: Timeframe::Last7Days,
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('activities');
    });

test('delete deletes people by inclusion ids',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            DeletePeople::class => MockResponse::fixture('person/delete_people_by_ids'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->person()
            ->delete(
                inclusionIds: ['0069061b5bda4060a5765300'],
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('deleted_contacts')
            ->and($response->json('deleted_contacts'))
            ->toBe(54);
    });

test('delete deletes all archived people when all rows selected',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            DeletePeople::class => MockResponse::fixture('person/delete_people_all_archived'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->person()
            ->delete(
                allRowsSelected: true,
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('deleted_contacts');
    });

test('delete deletes people with exclusions',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            DeletePeople::class => MockResponse::fixture('person/delete_people_with_exclusions'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->person()
            ->delete(
                exclusionIds: ['0069061b5bda4060a5765300'],
                allRowsSelected: true,
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('deleted_contacts');
    });

test('get gets people with basic fields',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            GetPeople::class => MockResponse::fixture('person/get_people_basic'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->person()
            ->get(
                fields: ['str::email', 'str::first', 'str::last'],
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('contacts')
            ->and($response->json('contacts'))
            ->toBeArray();
    });

test('get gets people with limit and sorting',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            GetPeople::class => MockResponse::fixture('person/get_people_with_limit_and_sort'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->person()
            ->get(
                fields: ['str::email', 'str::first'],
                limit: 50,
                sortByFieldId: 'str::email',
                sortOrder: SortOrder::Asc,
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('contacts');
    });

test('getByIds gets people by single contact id',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            GetPeopleByIds::class => MockResponse::fixture('person/get_people_by_ids_single'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->person()
            ->getByIds(
                contactIds: ['0069061b5bda4060a5765300'],
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('contacts')
            ->and($response->json('contacts'))
            ->toBeArray();
    });

test('getByIds gets people by ids with specific fields',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            GetPeopleByIds::class => MockResponse::fixture('person/get_people_by_ids_with_fields'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->person()
            ->getByIds(
                contactIds: ['0069061b5bda4060a5765300'],
                fields: ['str::email', 'str::first', 'str::last'],
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('contacts');
    });

test('subscriptions gets people subscriptions by person id',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            GetPeopleSubscriptions::class => MockResponse::fixture('person/get_subscriptions_by_person_id'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->person()
            ->subscriptions(
                people: [
                    ['person_id' => '0069061b5bda4060a5765300'],
                ],
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('people')
            ->and($response->json('people'))
            ->toBeArray();
    });

test('subscriptions gets people subscriptions by email',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            GetPeopleSubscriptions::class => MockResponse::fixture('person/get_subscriptions_by_email'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->person()
            ->subscriptions(
                people: [
                    ['str::email' => 'test@example.com'],
                ],
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('people');
    });

test('merge creates or updates people',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            MergePeople::class => MockResponse::fixture('person/merge_people_ok'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->person()
            ->merge(
                people: [
                    [
                        'fields' => [
                            'str::email' => 'test@example.com',
                            'str::first' => 'John',
                            'str::last' => 'Doe',
                        ],
                    ],
                ],
                mergeBy: ['str::email'],
                mergeStrategy: MergeStrategy::OverwriteExisting,
                findStrategy: FindStrategy::Any,
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('people')
            ->and($response->json('people'))
            ->toBeArray();
    });

test('restore restores people by inclusion ids',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            RestorePeople::class => MockResponse::fixture('person/restore_people_by_ids'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->person()
            ->restore(
                inclusionIds: ['0069061b5bda4060a5765300'],
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('restored_contacts')
            ->and($response->json('restored_contacts'))
            ->toBe(54);
    });

test('restore restores all archived people when all rows selected',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            RestorePeople::class => MockResponse::fixture('person/restore_people_all_archived'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->person()
            ->restore(
                allRowsSelected: true,
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('restored_contacts');
    });

test('restore restores people with exclusions',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            RestorePeople::class => MockResponse::fixture('person/restore_people_with_exclusions'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->person()
            ->restore(
                exclusionIds: ['0069061b5bda4060a5765300'],
                allRowsSelected: true,
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('restored_contacts');
    });
