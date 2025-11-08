<?php

use PhpDevKits\Ortto\Enums\ArticleStatus;
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\KnowledgeBase\GetArticle;
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
            ->knowledgeBase()
            ->getArticles();

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
            GetArticles::class => MockResponse::fixture('knowledge-base/get_articles_filtered'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->knowledgeBase()
            ->getArticles(status: ArticleStatus::Published, q: 'first', limit: 10, offset: 0);

        expect($response->status())->toBe(200)
            ->and($response->json())->toBeArray();
    });

test('gets articles with status string',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetArticles::class => MockResponse::fixture('knowledge-base/get_articles_filtered'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->knowledgeBase()
            ->getArticles(status: 'on', q: 'first', limit: 10, offset: 0);

        expect($response->status())->toBe(200)
            ->and($response->json())->toBeArray();
    });

test('gets knowledge base article by id',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetArticle::class => MockResponse::fixture('knowledge-base/get_article'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->knowledgeBase()
            ->getArticle(id: '690f2b469159dd51dee50960');

        expect($response->status())->toBe(200)
            ->and($response->json())->toBeArray()
            ->and($response->json())->toHaveKeys(['id', 'title', 'description', 'html']);
    });
