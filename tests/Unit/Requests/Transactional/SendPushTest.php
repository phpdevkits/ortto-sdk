<?php

use PhpDevKits\Ortto\Data\PushActionData;
use PhpDevKits\Ortto\Data\PushAssetData;
use PhpDevKits\Ortto\Data\PushNotificationData;
use PhpDevKits\Ortto\Enums\PushPlatform;
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Transactional\SendPush;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('sends push notification',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            SendPush::class => MockResponse::fixture('transactional/send_push'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(new SendPush(
                pushes: [
                    new PushNotificationData(
                        asset: new PushAssetData(
                            pushName: 'order-update',
                            title: 'Your order shipped!',
                            message: 'Your order is on the way',
                            platforms: [PushPlatform::Web, PushPlatform::Ios, PushPlatform::Android],
                        ),
                        contactId: 'contact123',
                    ),
                ],
            ));

        expect($response->status())->toBe(202);
    });

test('sends push notification with actions',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            SendPush::class => MockResponse::fixture('transactional/send_push_with_actions'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->send(new SendPush(
                pushes: [
                    new PushNotificationData(
                        asset: new PushAssetData(
                            pushName: 'order-update',
                            title: 'Your order shipped!',
                            message: 'Your order is on the way',
                            platforms: [PushPlatform::Web],
                            image: 'https://example.com/image.png',
                            primaryAction: new PushActionData(
                                title: 'Track Order',
                                link: 'https://example.com/track',
                            ),
                            secondaryActions: [
                                new PushActionData(
                                    title: 'View Details',
                                    link: 'https://example.com/details',
                                ),
                            ],
                        ),
                        contactId: 'contact456',
                    ),
                ],
                async: true,
            ));

        expect($response->status())->toBe(202);
    });
