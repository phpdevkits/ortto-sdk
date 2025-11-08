<?php

use PhpDevKits\Ortto\Data\PushActionData;

test('converts to array', function (): void {
    $action = new PushActionData(
        title: 'Track Order',
        link: 'https://example.com/track',
    );

    expect($action->toArray())->toBe([
        'title' => 'Track Order',
        'link' => 'https://example.com/track',
    ]);
});
