<?php

use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Asset\GetAssetSms;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('gets sms asset by id',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAssetSms::class => MockResponse::fixture('asset/get_sms'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(new GetAssetSms(assetId: '690f37fa0ebf85582e1f98b4'));

        expect($response->status())->toBe(200)
            ->and($response->json())->toBeArray()
            ->and($response->json())->toHaveKeys(['encoding', 'chars_count', 'segments', 'body', 'mapped_links']);
    });

test('gets sms asset with all parameters',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAssetSms::class => MockResponse::fixture('asset/get_sms_with_params'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(new GetAssetSms(
                assetId: '690f37fa0ebf85582e1f98b4',
                contactId: null,
                showFallbacks: true,
                raw: false,
                usePublished: false,
            ));

        expect($response->status())->toBe(200)
            ->and($response->json())->toBeArray();
    });
