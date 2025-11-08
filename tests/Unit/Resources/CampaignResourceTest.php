<?php

use PhpDevKits\Ortto\Enums\CampaignTimeframe;
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Campaign\GetCampaignReports;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('getReports retrieves campaign report',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetCampaignReports::class => MockResponse::fixture('campaign/get_reports_campaign_id'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->campaign()
            ->getReports(campaignId: '690f209a0a625aefed061265');

        expect($response->status())->toBe(200)
            ->and($response->json())->toBeArray();
    });

test('getReports retrieves campaign report with all parameters',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetCampaignReports::class => MockResponse::fixture('campaign/get_reports_campaign_id'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->campaign()
            ->getReports(
                campaignId: '690f209a0a625aefed061265',
                assetId: 'asset123',
                shapeId: 'shape123',
                messageId: 'message123',
                timeframe: CampaignTimeframe::Last14Days,
            );

        expect($response->status())->toBe(200);
    });
