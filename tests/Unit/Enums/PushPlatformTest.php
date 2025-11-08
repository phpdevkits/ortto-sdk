<?php

use PhpDevKits\Ortto\Enums\PushPlatform;

test('has correct web value', function (): void {
    expect(PushPlatform::Web->value)->toBe('web');
});

test('has correct ios value', function (): void {
    expect(PushPlatform::Ios->value)->toBe('ios');
});

test('has correct android value', function (): void {
    expect(PushPlatform::Android->value)->toBe('android');
});
