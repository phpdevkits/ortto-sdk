<?php

use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Tag\GetTags;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('get retrieves all tags',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetTags::class => MockResponse::fixture('tag/get_tags_all'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->tag()
            ->get();

        expect($response->status())->toBe(200);
    });

test('get retrieves tags with search term',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetTags::class => MockResponse::fixture('tag/get_tags_with_search'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->tag()
            ->get(q: 'team');

        expect($response->status())->toBe(200);
    });
