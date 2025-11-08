<?php

use PhpDevKits\Ortto\Data\EmailRecipientData;
use PhpDevKits\Ortto\Enums\PersonField;

test('converts to array with only fields', function (): void {
    $recipient = new EmailRecipientData(
        fields: [
            PersonField::Email->value => 'test@example.com',
            PersonField::FirstName->value => 'John',
        ],
    );

    expect($recipient->toArray())->toBe([
        'fields' => [
            PersonField::Email->value => 'test@example.com',
            PersonField::FirstName->value => 'John',
        ],
    ]);
});

test('converts to array with fields and location', function (): void {
    $recipient = new EmailRecipientData(
        fields: [
            PersonField::Email->value => 'test@example.com',
        ],
        location: [
            'ip' => '192.168.1.1',
        ],
    );

    expect($recipient->toArray())->toBe([
        'fields' => [
            PersonField::Email->value => 'test@example.com',
        ],
        'location' => [
            'ip' => '192.168.1.1',
        ],
    ]);
});

test('converts to array with all fields', function (): void {
    $recipient = new EmailRecipientData(
        fields: [
            PersonField::Email->value => 'test@example.com',
        ],
        location: [
            'ip' => '192.168.1.1',
        ],
        asset: [
            'from_email' => 'custom@example.com',
            'subject' => 'Custom Subject',
        ],
    );

    expect($recipient->toArray())->toBe([
        'fields' => [
            PersonField::Email->value => 'test@example.com',
        ],
        'location' => [
            'ip' => '192.168.1.1',
        ],
        'asset' => [
            'from_email' => 'custom@example.com',
            'subject' => 'Custom Subject',
        ],
    ]);
});
