<?php

use PhpDevKits\Ortto\Enums\TagSource;

test('has correct values', function (): void {
    expect(TagSource::Csv->value)->toBe('csv')
        ->and(TagSource::Api->value)->toBe('api')
        ->and(TagSource::Manual->value)->toBe('manual')
        ->and(TagSource::Zapier->value)->toBe('zapier');
});
