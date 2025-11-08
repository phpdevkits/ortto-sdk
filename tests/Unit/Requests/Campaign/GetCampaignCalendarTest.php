<?php

use PhpDevKits\Ortto\Data\CampaignPeriodData;
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Campaign\GetCampaignCalendar;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('gets campaign calendar for date range with arrays',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetCampaignCalendar::class => MockResponse::fixture('campaign/get_calendar'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetCampaignCalendar(
                    start: ['year' => 2024, 'month' => 3],
                    end: ['year' => 2024, 'month' => 4],
                    timezone: 'Australia/Sydney',
                ),
            );

        expect($response->status())->toBe(200)
            ->and($response->json())->toBeArray()
            ->and($response->json())->toHaveKey('campaigns');
    });

test('gets campaign calendar with CampaignPeriodData',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            GetCampaignCalendar::class => MockResponse::fixture('campaign/get_calendar'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(
                new GetCampaignCalendar(
                    start: new CampaignPeriodData(year: 2024, month: 3),
                    end: new CampaignPeriodData(year: 2024, month: 4),
                    timezone: 'Australia/Sydney',
                ),
            );

        expect($response->status())->toBe(200);
    });
