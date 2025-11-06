<?php

declare(strict_types=1);

use PhpDevKits\Ortto\Enums\ActivityIcon;

test('has correct calendar icon value', function (): void {
    expect(ActivityIcon::Calendar->value)->toBe('calendar-illustration-icon');
});

test('has correct caution icon value', function (): void {
    expect(ActivityIcon::Caution->value)->toBe('caution-illustration-icon');
});

test('has correct clicked icon value', function (): void {
    expect(ActivityIcon::Clicked->value)->toBe('clicked-illustration-icon');
});

test('has correct coupon icon value', function (): void {
    expect(ActivityIcon::Coupon->value)->toBe('coupon-illustration-icon');
});

test('has correct download icon value', function (): void {
    expect(ActivityIcon::Download->value)->toBe('download-illustration-icon');
});

test('has correct email icon value', function (): void {
    expect(ActivityIcon::Email->value)->toBe('email-illustration-icon');
});

test('has correct eye icon value', function (): void {
    expect(ActivityIcon::Eye->value)->toBe('eye-illustration-icon');
});

test('has correct flag icon value', function (): void {
    expect(ActivityIcon::Flag->value)->toBe('flag-activities-illustration-icon');
});

test('has correct happy icon value', function (): void {
    expect(ActivityIcon::Happy->value)->toBe('happy-illustration-icon');
});

test('has correct money icon value', function (): void {
    expect(ActivityIcon::Money->value)->toBe('moneys-illustration-icon');
});

test('has correct page icon value', function (): void {
    expect(ActivityIcon::Page->value)->toBe('page-illustration-icon');
});

test('has correct phone icon value', function (): void {
    expect(ActivityIcon::Phone->value)->toBe('phone-illustration-icon');
});

test('has correct reload icon value', function (): void {
    expect(ActivityIcon::Reload->value)->toBe('reload-illustration-icon');
});

test('has correct tag icon value', function (): void {
    expect(ActivityIcon::Tag->value)->toBe('tag-illustration-icon');
});

test('has correct time icon value', function (): void {
    expect(ActivityIcon::Time->value)->toBe('time-illustration-icon');
});

test('has all 15 icon cases', function (): void {
    expect(ActivityIcon::cases())->toHaveCount(15);
});
