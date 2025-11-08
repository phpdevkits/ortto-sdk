<?php

use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\KnowledgeBase\GetArticle;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
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
            ->send(
                new GetArticle(id: '690f2b469159dd51dee50960'),
            );

        expect($response->status())->toBe(200)
            ->and($response->json())->toBeArray()
            ->and($response->json())->toHaveKeys(['id', 'title', 'description', 'html']);
    });
