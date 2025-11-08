<?php

use PhpDevKits\Ortto\Enums\ArticleStatus;
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\KnowledgeBase\GetArticles;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('gets all articles',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetArticles::class => MockResponse::fixture('knowledge-base/get_articles_all'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(new GetArticles);

        expect($response->status())->toBe(200)
            ->and($response->json())->toBeArray()
            ->and($response->json())->toHaveKeys(['articles', 'total', 'offset', 'next_offset', 'has_more']);
    });

test('gets articles with status enum',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetArticles::class => MockResponse::fixture('knowledge-base/get_articles_status_on'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(new GetArticles(status: ArticleStatus::Published));

        expect($response->status())->toBe(200)
            ->and($response->json())->toBeArray();
    });

test('gets articles with status string',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetArticles::class => MockResponse::fixture('knowledge-base/get_articles_status_on'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(new GetArticles(status: 'on'));

        expect($response->status())->toBe(200)
            ->and($response->json())->toBeArray();
    });

test('gets articles with search query',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetArticles::class => MockResponse::fixture('knowledge-base/get_articles_search'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(new GetArticles(q: 'first'));

        expect($response->status())->toBe(200)
            ->and($response->json())->toBeArray();
    });

test('gets articles with pagination',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetArticles::class => MockResponse::fixture('knowledge-base/get_articles_pagination'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(new GetArticles(limit: 10, offset: 0));

        expect($response->status())->toBe(200)
            ->and($response->json())->toBeArray();
    });
