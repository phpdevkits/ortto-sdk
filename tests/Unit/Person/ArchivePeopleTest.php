<?php

use PhpDevKits\Ortto\Enums\PersonField;
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Person\ArchivePeople;
use PhpDevKits\Ortto\Requests\Person\GetPeople;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('archives specific contacts by ids',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            GetPeople::class => MockResponse::fixture('person/get_people_for_archiving'),
            ArchivePeople::class => MockResponse::fixture('person/archive_people_by_ids'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetPeople(
                    fields: [
                        PersonField::Email, PersonField::FirstName, PersonField::LastName, PersonField::PhoneNumber,
                    ],
                    limit: 1
                ),
            );

        $contacts = collect($response->json()['contacts']);

        $firstContactId = $contacts->first()['id'];

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new ArchivePeople(
                    inclusionIds: [$firstContactId],
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('archived_contacts')
            ->and($response->json('archived_contacts'))
            ->toBeInt()
            ->toBe(1);

    });

test('archives all contacts with bulk flag',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            ArchivePeople::class => MockResponse::fixture('person/archive_people_all_rows'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new ArchivePeople(
                    allRowsSelected: true,
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('archived_contacts')
            ->and($response->json())
            ->toHaveKey('scheduled_contacts');

    });

test('archives with exclusions',
    /**
     * @throws Throwable
     */
    function (): void {

        $mockClient = new MockClient([
            ArchivePeople::class => MockResponse::fixture('person/archive_people_with_exclusions'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new ArchivePeople(
                    exclusionIds: ['0069061b5bda4060a5765300'],
                    allRowsSelected: true,
                ),
            );

        expect($response->status())
            ->toBe(200)
            ->and($response->json())
            ->toHaveKey('archived_contacts')
            ->and($response->json('archived_contacts'))
            ->toBeInt();

    });
