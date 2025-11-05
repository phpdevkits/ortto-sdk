<?php

use PhpDevKits\Ortto\Data\ActivityData;
use PhpDevKits\Ortto\Data\ActivityLocationData;
use PhpDevKits\Ortto\Enums\MergeStrategy;
use PhpDevKits\Ortto\Enums\PersonField;

test('creates activity with person id', function (): void {
    $activity = new ActivityData(
        activityId: 'act::c',
        attributes: [
            PersonField::FirstName->value => 'John',
        ],
        personId: '0069061b5bda4060a5765300'
    );

    expect($activity->toArray())
        ->toBe([
            'activity_id' => 'act::c',
            'attributes' => [
                PersonField::FirstName->value => 'John',
            ],
            'person_id' => '0069061b5bda4060a5765300',
        ]);
});

test('creates activity with fields and merge by', function (): void {
    $activity = new ActivityData(
        activityId: 'act:cm:webinar-attended',
        attributes: [
            'str:cm:webinar-name' => 'Product Demo',
        ],
        fields: [
            PersonField::Email->value => 'john@example.com',
        ],
        mergeBy: [PersonField::Email->value]
    );

    expect($activity->toArray())
        ->toBe([
            'activity_id' => 'act:cm:webinar-attended',
            'attributes' => [
                'str:cm:webinar-name' => 'Product Demo',
            ],
            'fields' => [
                PersonField::Email->value => 'john@example.com',
            ],
            'merge_by' => [PersonField::Email->value],
        ]);
});

test('creates activity with backdate timestamp', function (): void {
    $activity = new ActivityData(
        activityId: 'act::c',
        personId: '0069061b5bda4060a5765300',
        created: '2024-01-15T10:30:00Z'
    );

    expect($activity->toArray())
        ->toHaveKey('created')
        ->and($activity->toArray()['created'])
        ->toBe('2024-01-15T10:30:00Z');
});

test('creates activity with location data', function (): void {
    $activity = new ActivityData(
        activityId: 'act::c',
        personId: '0069061b5bda4060a5765300',
        location: ActivityLocationData::fromIp('203.123.45.67')
    );

    expect($activity->toArray())
        ->toHaveKey('location')
        ->and($activity->toArray()['location'])
        ->toBe([
            'ip' => '203.123.45.67',
        ]);
});

test('creates activity with merge strategy enum', function (): void {
    $activity = new ActivityData(
        activityId: 'act::c',
        personId: '0069061b5bda4060a5765300',
        mergeStrategy: MergeStrategy::OverwriteExisting
    );

    expect($activity->toArray())
        ->toHaveKey('merge_strategy')
        ->and($activity->toArray()['merge_strategy'])
        ->toBe(2);
});

test('creates activity with merge strategy integer', function (): void {
    $activity = new ActivityData(
        activityId: 'act::c',
        personId: '0069061b5bda4060a5765300',
        mergeStrategy: 1
    );

    expect($activity->toArray())
        ->toHaveKey('merge_strategy')
        ->and($activity->toArray()['merge_strategy'])
        ->toBe(1);
});

test('creates activity with unique key', function (): void {
    $activity = new ActivityData(
        activityId: 'act::c',
        personId: '0069061b5bda4060a5765300',
        created: '2024-01-15T10:30:00Z',
        key: 'order-12345'
    );

    expect($activity->toArray())
        ->toHaveKey('key')
        ->and($activity->toArray()['key'])
        ->toBe('order-12345');
});

test('creates minimal activity', function (): void {
    $activity = new ActivityData(
        activityId: 'act::c',
        personId: '0069061b5bda4060a5765300'
    );

    expect($activity->toArray())
        ->toBe([
            'activity_id' => 'act::c',
            'person_id' => '0069061b5bda4060a5765300',
        ]);
});
