<?php

use PhpDevKits\Ortto\Enums\CampaignSortField;

test('has correct name value', function (): void {
    expect(CampaignSortField::Name->value)->toBe('name');
});

test('has correct state value', function (): void {
    expect(CampaignSortField::State->value)->toBe('state');
});

test('has correct edited_at value', function (): void {
    expect(CampaignSortField::EditedAt->value)->toBe('edited_at');
});

test('has correct created_at value', function (): void {
    expect(CampaignSortField::CreatedAt->value)->toBe('created_at');
});

test('has correct delivered value', function (): void {
    expect(CampaignSortField::Delivered->value)->toBe('delivered');
});

test('has correct opens value', function (): void {
    expect(CampaignSortField::Opens->value)->toBe('opens');
});

test('has correct clicks value', function (): void {
    expect(CampaignSortField::Clicks->value)->toBe('clicks');
});

test('has correct conversions value', function (): void {
    expect(CampaignSortField::Conversions->value)->toBe('conversions');
});

test('has correct revenue value', function (): void {
    expect(CampaignSortField::Revenue->value)->toBe('revenue');
});
