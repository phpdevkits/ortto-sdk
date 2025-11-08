<?php

use PhpDevKits\Ortto\Enums\TagSource;
use PhpDevKits\Ortto\Enums\TagType;
use Tests\Factories\TagDataFactory;

test('converts to array with all fields', function (): void {
    $tag = TagDataFactory::new()->make([
        'id' => 12345,
        'instance_id' => 'instance-uuid-123',
        'name' => 'VIP Customer',
        'source' => TagSource::Api,
        'created_by_id' => 'user-uuid-456',
        'created_by_name' => 'John Doe',
        'created_by_email' => 'john@example.com',
        'created_at' => '2024-01-15T10:30:00Z',
        'last_used' => '2024-12-01T15:45:00Z',
        'count' => 150,
        'sms_opted_in' => 75,
        'subscribers' => 120,
        'type' => TagType::Person,
    ]);

    expect($tag->toArray())->toBe([
        'id' => 12345,
        'instance_id' => 'instance-uuid-123',
        'name' => 'VIP Customer',
        'source' => 'api',
        'created_by_id' => 'user-uuid-456',
        'created_by_name' => 'John Doe',
        'created_by_email' => 'john@example.com',
        'created_at' => '2024-01-15T10:30:00Z',
        'last_used' => '2024-12-01T15:45:00Z',
        'count' => 150,
        'sms_opted_in' => 75,
        'subscribers' => 120,
        'type' => '',
    ]);
});

test('converts TagSource enum to string value', function (): void {
    $tag = TagDataFactory::new()->make([
        'source' => TagSource::Csv,
    ]);

    expect($tag->toArray()['source'])->toBe('csv')
        ->and($tag->toArray()['source'])->not->toBeInstanceOf(TagSource::class);
});

test('converts TagType enum to string value', function (): void {
    $tag = TagDataFactory::new()->make([
        'type' => TagType::Organization,
    ]);

    expect($tag->toArray()['type'])->toBe('organization')
        ->and($tag->toArray()['type'])->not->toBeInstanceOf(TagType::class);
});

test('handles person type as empty string', function (): void {
    $tag = TagDataFactory::new()->make([
        'type' => TagType::Person,
    ]);

    expect($tag->toArray()['type'])
        ->toBe('')
        ->and($tag->toArray()['type'])->toBeEmpty();
});

test('handles integer id correctly', function (): void {
    $tag = TagDataFactory::new()->make([
        'id' => 999999,
        'source' => TagSource::Zapier,
    ]);

    expect($tag->toArray()['id'])
        ->toBeInt()
        ->and($tag->toArray()['id'])->toBe(999999);
});

test('accepts string source value', function (): void {
    $tag = TagDataFactory::new()->make([
        'source' => 'manual',
    ]);

    expect($tag->toArray()['source'])->toBe('manual');
});

test('accepts string type value', function (): void {
    $tag = TagDataFactory::new()->make([
        'type' => 'account',
    ]);

    expect($tag->toArray()['type'])->toBe('account');
});
