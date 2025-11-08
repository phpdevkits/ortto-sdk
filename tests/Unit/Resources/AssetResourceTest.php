<?php

use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Asset\GetAssetHtml;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('gets asset html',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAssetHtml::class => MockResponse::fixture('asset/get_html'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->asset()
            ->getHtml(assetId: '690f250ebe8b42033b352de2');

        expect($response->status())->toBe(200)
            ->and($response->json())->toBeArray()
            ->and($response->json())->toHaveKeys(['html', 'from_email', 'from_name', 'subject', 'preview', 'reply_to']);
    });
