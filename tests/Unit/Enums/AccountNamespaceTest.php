<?php

use PhpDevKits\Ortto\Enums\AccountNamespace;

test('has correct values', function (): void {
    expect(AccountNamespace::System->value)->toBe('')
        ->and(AccountNamespace::Organization->value)->toBe('o')
        ->and(AccountNamespace::Custom->value)->toBe('cm')
        ->and(AccountNamespace::Shopify->value)->toBe('sh')
        ->and(AccountNamespace::Stripe->value)->toBe('st')
        ->and(AccountNamespace::Zendesk->value)->toBe('zd')
        ->and(AccountNamespace::SalesforceContact->value)->toBe('sfc');
});
