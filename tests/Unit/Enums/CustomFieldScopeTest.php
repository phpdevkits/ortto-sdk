<?php

declare(strict_types=1);

use PhpDevKits\Ortto\Enums\CustomFieldScope;

test('has correct person scope value', function (): void {
    expect(CustomFieldScope::Person->value)->toBe('person');
});

test('has correct account scope value', function (): void {
    expect(CustomFieldScope::Account->value)->toBe('account');
});

test('has both scope cases', function (): void {
    $cases = CustomFieldScope::cases();
    expect($cases)->toHaveCount(2);
});
