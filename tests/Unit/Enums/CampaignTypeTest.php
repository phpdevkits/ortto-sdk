<?php

use PhpDevKits\Ortto\Enums\CampaignType;

test('has correct all value', function (): void {
    expect(CampaignType::All->value)->toBe('all');
});

test('has correct email value', function (): void {
    expect(CampaignType::Email->value)->toBe('email');
});

test('has correct playbook value', function (): void {
    expect(CampaignType::Playbook->value)->toBe('playbook');
});

test('has correct sms value', function (): void {
    expect(CampaignType::Sms->value)->toBe('sms');
});

test('has correct journey value', function (): void {
    expect(CampaignType::Journey->value)->toBe('journey');
});

test('has correct push value', function (): void {
    expect(CampaignType::Push->value)->toBe('push');
});

test('has correct whatsapp value', function (): void {
    expect(CampaignType::Whatsapp->value)->toBe('whatsapp');
});
