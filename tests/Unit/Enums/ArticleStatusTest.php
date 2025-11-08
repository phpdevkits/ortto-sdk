<?php

use PhpDevKits\Ortto\Enums\ArticleStatus;

test('has correct published value', function (): void {
    expect(ArticleStatus::Published->value)->toBe('on');
});

test('has correct unpublished value', function (): void {
    expect(ArticleStatus::Unpublished->value)->toBe('off');
});

test('has correct all value', function (): void {
    expect(ArticleStatus::All->value)->toBe('');
});
