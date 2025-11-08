<?php

use PhpDevKits\Ortto\Enums\CampaignTimeframe;
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Campaign\GetCampaignReports;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('gets campaign report with campaign id',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetCampaignReports::class => MockResponse::fixture('campaign/get_reports_campaign_id'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetCampaignReports(campaignId: '690f209a0a625aefed061265'),
            );

        expect($response->status())->toBe(200)
            ->and($response->json())->toBeArray();
    });

test('gets journey campaign report with enum timeframe',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetCampaignReports::class => MockResponse::fixture('campaign/get_reports_journey_enum_timeframe'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetCampaignReports(
                    campaignId: '690f248595fedf82f6732cb3',
                    timeframe: CampaignTimeframe::Last14Days,
                ),
            );

        expect($response->status())->toBe(200);
    });

test('gets journey campaign report with string timeframe',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetCampaignReports::class => MockResponse::fixture('campaign/get_reports_journey_string_timeframe'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetCampaignReports(
                    campaignId: '690f248595fedf82f6732cb3',
                    timeframe: 'last-7-days',
                ),
            );

        expect($response->status())->toBe(200);
    });
