<?php

use PhpDevKits\Ortto\Enums\CampaignState;

test('has correct draft value', function (): void {
    expect(CampaignState::Draft->value)->toBe('draft');
});

test('has correct scheduled value', function (): void {
    expect(CampaignState::Scheduled->value)->toBe('scheduled');
});

test('has correct sending value', function (): void {
    expect(CampaignState::Sending->value)->toBe('sending');
});

test('has correct sent value', function (): void {
    expect(CampaignState::Sent->value)->toBe('sent');
});

test('has correct cancelled value', function (): void {
    expect(CampaignState::Cancelled->value)->toBe('cancelled');
});

test('has correct on value', function (): void {
    expect(CampaignState::On->value)->toBe('on');
});

test('has correct off value', function (): void {
    expect(CampaignState::Off->value)->toBe('off');
});
