<?php

use PhpDevKits\Ortto\Data\EmailAssetData;
use PhpDevKits\Ortto\Data\EmailRecipientData;
use PhpDevKits\Ortto\Enums\MergeStrategy;
use PhpDevKits\Ortto\Enums\PersonField;
use PhpDevKits\Ortto\Ortto;
use PhpDevKits\Ortto\Requests\Transactional\SendEmail;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function (): void {
    $this->ortto = new Ortto;
});

test('sends email via transactional resource with inline definition',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            SendEmail::class => MockResponse::fixture('transactional/send_email_inline'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->transactional()
            ->sendEmail(
                emails: [
                    new EmailRecipientData(
                        fields: [
                            PersonField::Email->value => 'recipient@example.com',
                            PersonField::FirstName->value => 'John',
                        ],
                    ),
                ],
                mergeBy: [PersonField::Email->value],
                mergeStrategy: MergeStrategy::OverwriteExisting,
                asset: new EmailAssetData(
                    fromEmail: 'sender@example.com',
                    fromName: 'Test Sender',
                    subject: 'Test Subject',
                    emailName: 'test-email',
                    htmlBody: '<html><body>Welcome!</body></html>',
                ),
            );

        expect($response->status())->toBe(200)
            ->and($response->json())->toBeArray()
            ->and($response->json())->toHaveKey('emails');
    });

test('sends email via transactional resource with campaign reference',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            SendEmail::class => MockResponse::fixture('transactional/send_email_campaign'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->transactional()
            ->sendEmail(
                emails: [
                    [
                        'fields' => [
                            PersonField::Email->value => 'recipient@example.com',
                            PersonField::FirstName->value => 'Jane',
                        ],
                    ],
                ],
                mergeBy: [PersonField::Email->value],
                mergeStrategy: MergeStrategy::OverwriteExisting,
                campaignId: '63f3f1d0ae7e17e725033fe3',
            );

        expect($response->status())->toBe(200)
            ->and($response->json())->toBeArray()
            ->and($response->json())->toHaveKey('emails');
    });

test('sends email via transactional resource with asset reference',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            SendEmail::class => MockResponse::fixture('transactional/send_email_asset'),
        ]);

        $response = $this->ortto
            ->withMockClient($mockClient)
            ->transactional()
            ->sendEmail(
                emails: [
                    [
                        'fields' => [
                            PersonField::Email->value => 'recipient@example.com',
                        ],
                    ],
                ],
                mergeBy: [PersonField::Email->value],
                mergeStrategy: 2,
                assetId: '690f250ebe8b42033b352de2',
            );

        expect($response->status())->toBe(200)
            ->and($response->json())->toBeArray()
            ->and($response->json())->toHaveKey('emails');
    });
