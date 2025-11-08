<?php

use PhpDevKits\Ortto\Data\PushActionData;
use PhpDevKits\Ortto\Data\PushAssetData;
use PhpDevKits\Ortto\Enums\PushPlatform;

test('converts to array with required fields only', function (): void {
    $asset = new PushAssetData(
        pushName: 'order-update',
        title: 'Your order shipped!',
        message: 'Your order is on the way',
        platforms: [PushPlatform::Web, PushPlatform::Ios],
    );

    expect($asset->toArray())->toBe([
        'push_name' => 'order-update',
        'title' => 'Your order shipped!',
        'message' => 'Your order is on the way',
        'platforms' => ['web', 'ios'],
    ]);
});

test('converts platforms from enums to strings', function (): void {
    $asset = new PushAssetData(
        pushName: 'test',
        title: 'Test',
        message: 'Test message',
        platforms: [PushPlatform::Web, 'ios', PushPlatform::Android],
    );

    expect($asset->toArray()['platforms'])->toBe(['web', 'ios', 'android']);
});

test('converts to array with image', function (): void {
    $asset = new PushAssetData(
        pushName: 'test',
        title: 'Test',
        message: 'Test',
        platforms: [PushPlatform::Web],
        image: 'https://example.com/image.png',
    );

    expect($asset->toArray())->toHaveKey('image')
        ->and($asset->toArray()['image'])->toBe('https://example.com/image.png');
});

test('converts to array with primary action', function (): void {
    $asset = new PushAssetData(
        pushName: 'test',
        title: 'Test',
        message: 'Test',
        platforms: [PushPlatform::Web],
        primaryAction: new PushActionData(
            title: 'Click Here',
            link: 'https://example.com',
        ),
    );

    $array = $asset->toArray();

    expect($array)->toHaveKey('primary_action')
        ->and($array['primary_action'])->toBe([
            'title' => 'Click Here',
            'link' => 'https://example.com',
        ]);
});

test('converts to array with secondary actions', function (): void {
    $asset = new PushAssetData(
        pushName: 'test',
        title: 'Test',
        message: 'Test',
        platforms: [PushPlatform::Web],
        secondaryActions: [
            new PushActionData(title: 'Action 1', link: 'https://example.com/1'),
            new PushActionData(title: 'Action 2', link: 'https://example.com/2'),
        ],
    );

    $array = $asset->toArray();

    expect($array)->toHaveKey('secondary_actions')
        ->and($array['secondary_actions'])->toBeArray()
        ->and($array['secondary_actions'])->toHaveCount(2)
        ->and($array['secondary_actions'][0]['title'])->toBe('Action 1');
});

test('converts to array with all fields', function (): void {
    $asset = new PushAssetData(
        pushName: 'test',
        title: 'Test',
        message: 'Test message',
        platforms: [PushPlatform::Web, PushPlatform::Ios],
        image: 'https://example.com/image.png',
        primaryAction: new PushActionData(title: 'Main', link: 'https://example.com'),
        secondaryActions: [
            new PushActionData(title: 'Secondary', link: 'https://example.com/s'),
        ],
    );

    $array = $asset->toArray();

    expect($array)->toHaveKey('push_name')
        ->and($array)->toHaveKey('title')
        ->and($array)->toHaveKey('message')
        ->and($array)->toHaveKey('platforms')
        ->and($array)->toHaveKey('image')
        ->and($array)->toHaveKey('primary_action')
        ->and($array)->toHaveKey('secondary_actions');
});
