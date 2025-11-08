<?php

use PhpDevKits\Ortto\Data\EmailAttachmentData;

test('converts to array with all fields', function (): void {
    $attachment = new EmailAttachmentData(
        filename: 'document.pdf',
        content: base64_encode('PDF content here'),
        mimeType: 'application/pdf',
    );

    $array = $attachment->toArray();

    expect($array)->toHaveKey('filename')
        ->and($array['filename'])->toBe('document.pdf')
        ->and($array)->toHaveKey('content')
        ->and($array['content'])->toBe(base64_encode('PDF content here'))
        ->and($array)->toHaveKey('mime_type')
        ->and($array['mime_type'])->toBe('application/pdf');
});

test('converts to array with image attachment', function (): void {
    $attachment = new EmailAttachmentData(
        filename: 'image.png',
        content: base64_encode('PNG image data'),
        mimeType: 'image/png',
    );

    expect($attachment->toArray()['filename'])->toBe('image.png')
        ->and($attachment->toArray()['mime_type'])->toBe('image/png');
});
