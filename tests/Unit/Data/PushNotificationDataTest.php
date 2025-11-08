<?php

use PhpDevKits\Ortto\Data\PushAssetData;
use PhpDevKits\Ortto\Data\PushNotificationData;
use PhpDevKits\Ortto\Enums\PushPlatform;

test('converts to array with PushAssetData', function (): void {
    $notification = new PushNotificationData(
        asset: new PushAssetData(
            pushName: 'test-push',
            title: 'Test Title',
            message: 'Test Message',
            platforms: [PushPlatform::Web],
        ),
        contactId: 'contact123',
    );

    $array = $notification->toArray();

    expect($array)->toHaveKey('asset')
        ->and($array)->toHaveKey('contact_id')
        ->and($array['contact_id'])->toBe('contact123')
        ->and($array['asset'])->toBeArray()
        ->and($array['asset']['push_name'])->toBe('test-push');
});

test('converts to array with array asset', function (): void {
    $notification = new PushNotificationData(
        asset: [
            'push_name' => 'test',
            'title' => 'Title',
            'message' => 'Message',
            'platforms' => ['web'],
        ],
        contactId: 'contact456',
    );

    $array = $notification->toArray();

    expect($array['asset'])->toBe([
        'push_name' => 'test',
        'title' => 'Title',
        'message' => 'Message',
        'platforms' => ['web'],
    ])
        ->and($array['contact_id'])->toBe('contact456');
});
