<?php

use Carbon\CarbonImmutable;
use PhpDevKits\Ortto\Data\CampaignPeriodData;

test('creates from year and month', function (): void {
    $period = new CampaignPeriodData(year: 2024, month: 3);

    expect($period->year)->toBe(2024)
        ->and($period->month)->toBe(3);
});

test('converts to array', function (): void {
    $period = new CampaignPeriodData(year: 2024, month: 3);

    expect($period->toArray())->toBe([
        'year' => 2024,
        'month' => 3,
    ]);
});

test('creates from CarbonImmutable', function (): void {
    $date = CarbonImmutable::create(2024, 3, 15);
    $period = CampaignPeriodData::fromCarbon($date);

    expect($period->year)->toBe(2024)
        ->and($period->month)->toBe(3)
        ->and($period->toArray())->toBe([
            'year' => 2024,
            'month' => 3,
        ]);
});
