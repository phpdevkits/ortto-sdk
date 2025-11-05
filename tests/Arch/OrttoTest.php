<?php

declare(strict_types=1);

use PhpDevKits\Ortto\Ortto;

test('connector')
    ->expect(Ortto::class)
    ->toBeSaloonConnector()
    ->toHaveDefaultHeaders()
    ->toHaveDefaultConfig()
    ->toHaveBaseUrl()
    ->toUseAcceptsJsonTrait();

test('can be instantiated', function (): void {
    $ortto = new Ortto;

    expect($ortto)
        ->toBeInstanceOf(Ortto::class);
});

test('resolves base URL', function (): void {
    $ortto = new Ortto;

    expect($ortto->resolveBaseUrl())
        ->toBe(config()->string('ortto.url'));
});

test('throws exception when ortto url is null', function (): void {

    config()->set('ortto.url');

    $ortto = new Ortto;

    $ortto->resolveBaseUrl();

})->throws(InvalidArgumentException::class);

test('throws exception when ortto url is empty string', function (): void {

    config()->set('ortto.url', '');

    $ortto = new Ortto;

    $ortto->resolveBaseUrl();

})->throws(InvalidArgumentException::class);

test('returns default headers with api key', function (): void {
    config()->set('ortto.api_key', 'test-api-key');

    $ortto = new Ortto;

    $headers = $ortto->headers()->all();

    expect($headers)
        ->toHaveKey('x-api-key', 'test-api-key')
        ->toHaveKey('content-type', 'application/json');
});

test('returns default headers with empty api key when not set', function (): void {
    config()->set('ortto.api_key', '');

    $ortto = new Ortto;

    $headers = $ortto->headers()->all();

    expect($headers)
        ->toHaveKey('x-api-key', '')
        ->toHaveKey('content-type', 'application/json');
});

test('returns empty default config', function (): void {
    $ortto = new Ortto;

    $config = $ortto->config()->all();

    expect($config)->toBe([]);
});
