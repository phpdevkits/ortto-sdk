<?php

use PhpDevKits\Ortto\Enums\ActivityDisplayType;

test('has correct values', function (): void {
    expect(ActivityDisplayType::Text->value)->toBe('text')
        ->and(ActivityDisplayType::LargeText->value)->toBe('large_text')
        ->and(ActivityDisplayType::Email->value)->toBe('email')
        ->and(ActivityDisplayType::Phone->value)->toBe('phone')
        ->and(ActivityDisplayType::Link->value)->toBe('link')
        ->and(ActivityDisplayType::Integer->value)->toBe('integer')
        ->and(ActivityDisplayType::Decimal->value)->toBe('decimal')
        ->and(ActivityDisplayType::Currency->value)->toBe('currency')
        ->and(ActivityDisplayType::Date->value)->toBe('date')
        ->and(ActivityDisplayType::Time->value)->toBe('time')
        ->and(ActivityDisplayType::Bool->value)->toBe('bool')
        ->and(ActivityDisplayType::SingleSelect->value)->toBe('single_select')
        ->and(ActivityDisplayType::MultiSelect->value)->toBe('multi_select')
        ->and(ActivityDisplayType::Object->value)->toBe('object');
});
