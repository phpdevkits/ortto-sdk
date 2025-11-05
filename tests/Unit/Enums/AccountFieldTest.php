<?php

use PhpDevKits\Ortto\Enums\AccountField;

test('has correct values', function (): void {
    expect(AccountField::Name->value)->toBe('str:o:name')
        ->and(AccountField::Website->value)->toBe('str:o:website')
        ->and(AccountField::Industry->value)->toBe('str:o:industry')
        ->and(AccountField::Address->value)->toBe('str:o:address')
        ->and(AccountField::PostalCode->value)->toBe('str:o:postal')
        ->and(AccountField::Source->value)->toBe('str:o:source')
        ->and(AccountField::Employees->value)->toBe('int:o:employees')
        ->and(AccountField::City->value)->toBe('geo:o:city')
        ->and(AccountField::Country->value)->toBe('geo:o:country')
        ->and(AccountField::Region->value)->toBe('geo:o:region');
});
