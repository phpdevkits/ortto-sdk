<?php

use PhpDevKits\Ortto\Enums\CampaignSortField;
use PhpDevKits\Ortto\Enums\CampaignState;
use PhpDevKits\Ortto\Enums\CampaignType;
use PhpDevKits\Ortto\Enums\SortOrder;
use PhpDevKits\Ortto\Enums\Timeframe;
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Campaign\GetAllCampaigns;
use PhpDevKits\Ortto\Requests\Campaign\GetCampaignCalendar;
use PhpDevKits\Ortto\Requests\Campaign\GetCampaignReports;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('getAllCampaigns retrieves campaign list',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetAllCampaigns::class => MockResponse::fixture('campaign/get_all_campaigns'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->campaign()
            ->getAllCampaigns(
                type: CampaignType::Journey,
                state: CampaignState::On,
                folderId: '6842c82de2f490232b196392',
                limit: 5,
                q: 'welcome',
                sort: CampaignSortField::Name,
                sortOrder: SortOrder::Desc,
            );

        expect($response->status())->toBe(200)
            ->and($response->json())->toBeArray()
            ->and($response->json())->toHaveKey('campaigns');
    });

test('getCalendar retrieves campaign calendar',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetCampaignCalendar::class => MockResponse::fixture('campaign/get_calendar'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->campaign()
            ->getCalendar(
                start: ['year' => 2024, 'month' => 3],
                end: ['year' => 2024, 'month' => 4],
                timezone: 'Australia/Sydney',
            );

        expect($response->status())->toBe(200);
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
                timeframe: Timeframe::Last14Days,
            );

        expect($response->status())->toBe(200);
    });
