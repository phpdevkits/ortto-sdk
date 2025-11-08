<?php

use PhpDevKits\Ortto\Data\TagData;
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Tag\GetTags;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('gets all tags without search',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetTags::class => MockResponse::fixture('tag/get_tags_all'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetTags,
            );

        expect($response->status())->toBe(200);

        $tags = $response->json();
        expect($tags)->toBeArray()
            ->and($tags)->toHaveCount(2);
    });

test('gets tags with search term',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetTags::class => MockResponse::fixture('tag/get_tags_with_search'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetTags(q: 'team'),
            );

        expect($response->status())->toBe(200);

        $tags = $response->json();
        expect($tags)->toBeArray()
            ->and($tags)->toHaveCount(1);
    });

test('parses response into TagData objects',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetTags::class => MockResponse::fixture('tag/get_tags_all'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetTags,
            );

        $tags = collect($response->json())->map(fn (array $tag): TagData => new TagData(
            id: $tag['id'],
            instanceId: $tag['instance_id'],
            name: $tag['name'],
            source: $tag['source'] ?? null,
            createdById: $tag['created_by_id'],
            createdByName: $tag['created_by_name'],
            createdByEmail: $tag['created_by_email'],
            createdAt: $tag['created_at'],
            lastUsed: $tag['last_used'],
            count: $tag['count'],
            smsOptedIn: $tag['sms_opted_in'] ?? null,
            subscribers: $tag['subscribers'] ?? null,
            type: $tag['type'],
        ));

        expect($tags)->toHaveCount(2)
            ->and($tags->first())
            ->toBeInstanceOf(TagData::class)->not->toBeNull()
            ->and($tags->last())->toBeInstanceOf(TagData::class)->not->toBeNull()
            ->and($tags->first())->name->toBe('My team')
            ->and($tags->first())->type->toBe('')
            ->and($tags->first())->subscribers->toBe(1)
            ->and($tags->last())->name->toBe('My organization')
            ->and($tags->last()->type)->toBe('organization')
            ->and($tags->last()->subscribers)->toBeNull();
    });
