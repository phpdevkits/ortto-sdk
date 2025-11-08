<?php

use PhpDevKits\Ortto\Enums\TagType;

test('has correct person type value', function (): void {
    expect(TagType::Person->value)->toBe('');
});

test('has correct organization type value', function (): void {
    expect(TagType::Organization->value)->toBe('organization');
});

test('person type uses empty string', function (): void {
    expect(TagType::Person->value)
        ->toBeEmpty()
        ->and(TagType::Person->value)->toBe('');
});
