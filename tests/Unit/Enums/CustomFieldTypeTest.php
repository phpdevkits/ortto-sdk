<?php

declare(strict_types=1);

use PhpDevKits\Ortto\Enums\CustomFieldType;

test('has correct text type value', function (): void {
    expect(CustomFieldType::Text->value)->toBe('text');
});

test('has correct large_text type value', function (): void {
    expect(CustomFieldType::LargeText->value)->toBe('large_text');
});

test('has correct integer type value', function (): void {
    expect(CustomFieldType::Integer->value)->toBe('integer');
});

test('has correct decimal type value', function (): void {
    expect(CustomFieldType::Decimal->value)->toBe('decimal');
});

test('has correct currency type value', function (): void {
    expect(CustomFieldType::Currency->value)->toBe('currency');
});

test('has correct price type value', function (): void {
    expect(CustomFieldType::Price->value)->toBe('price');
});

test('has correct date type value', function (): void {
    expect(CustomFieldType::Date->value)->toBe('date');
});

test('has correct time type value', function (): void {
    expect(CustomFieldType::Time->value)->toBe('time');
});

test('has correct bool type value', function (): void {
    expect(CustomFieldType::Bool->value)->toBe('bool');
});

test('has correct phone type value', function (): void {
    expect(CustomFieldType::Phone->value)->toBe('phone');
});

test('has correct single_select type value', function (): void {
    expect(CustomFieldType::SingleSelect->value)->toBe('single_select');
});

test('has correct multi_select type value', function (): void {
    expect(CustomFieldType::MultiSelect->value)->toBe('multi_select');
});

test('has correct link type value', function (): void {
    expect(CustomFieldType::Link->value)->toBe('link');
});

test('has correct object type value', function (): void {
    expect(CustomFieldType::Object->value)->toBe('object');
});

test('has all 14 field types', function (): void {
    $cases = CustomFieldType::cases();
    expect($cases)->toHaveCount(14);
});
