<?php

use PhpDevKits\Ortto\Data\EmailAssetData;
use PhpDevKits\Ortto\Data\EmailAttachmentData;

test('converts to array with required fields only', function (): void {
    $asset = new EmailAssetData(
        fromEmail: 'sender@example.com',
        fromName: 'Test Sender',
        subject: 'Test Subject',
        emailName: 'test-email',
        htmlBody: '<html><body>Test</body></html>',
    );

    expect($asset->toArray())->toBe([
        'from_email' => 'sender@example.com',
        'from_name' => 'Test Sender',
        'subject' => 'Test Subject',
        'email_name' => 'test-email',
        'html_body' => '<html><body>Test</body></html>',
    ]);
});

test('converts to array with reply_to', function (): void {
    $asset = new EmailAssetData(
        fromEmail: 'sender@example.com',
        fromName: 'Test Sender',
        subject: 'Test Subject',
        emailName: 'test-email',
        htmlBody: '<html><body>Test</body></html>',
        replyTo: 'reply@example.com',
    );

    expect($asset->toArray())->toHaveKey('reply_to')
        ->and($asset->toArray()['reply_to'])->toBe('reply@example.com');
});

test('converts to array with cc', function (): void {
    $asset = new EmailAssetData(
        fromEmail: 'sender@example.com',
        fromName: 'Test Sender',
        subject: 'Test Subject',
        emailName: 'test-email',
        htmlBody: '<html><body>Test</body></html>',
        cc: ['cc1@example.com', 'cc2@example.com'],
    );

    expect($asset->toArray())->toHaveKey('cc')
        ->and($asset->toArray()['cc'])->toBe(['cc1@example.com', 'cc2@example.com']);
});

test('converts to array with attachments', function (): void {
    $asset = new EmailAssetData(
        fromEmail: 'sender@example.com',
        fromName: 'Test Sender',
        subject: 'Test Subject',
        emailName: 'test-email',
        htmlBody: '<html><body>Test</body></html>',
        attachments: [
            new EmailAttachmentData(
                filename: 'test.pdf',
                content: base64_encode('pdf content'),
                mimeType: 'application/pdf',
            ),
        ],
    );

    $array = $asset->toArray();

    expect($array)->toHaveKey('attachments')
        ->and($array['attachments'])->toBeArray()
        ->and($array['attachments'][0])->toHaveKey('filename')
        ->and($array['attachments'][0]['filename'])->toBe('test.pdf');
});

test('converts to array with all optional fields', function (): void {
    $asset = new EmailAssetData(
        fromEmail: 'sender@example.com',
        fromName: 'Test Sender',
        subject: 'Test Subject',
        emailName: 'test-email',
        htmlBody: '<html><body>Test</body></html>',
        replyTo: 'reply@example.com',
        cc: ['cc@example.com'],
        liquidSyntaxEnabled: false,
        noClickTracks: true,
        noOpenTracks: true,
    );

    $array = $asset->toArray();

    expect($array)->toHaveKey('reply_to')
        ->and($array)->toHaveKey('cc')
        ->and($array)->toHaveKey('liquid_syntax_enabled')
        ->and($array['liquid_syntax_enabled'])->toBe(false)
        ->and($array)->toHaveKey('no_click_tracks')
        ->and($array['no_click_tracks'])->toBe(true)
        ->and($array)->toHaveKey('no_open_tracks')
        ->and($array['no_open_tracks'])->toBe(true);
});
