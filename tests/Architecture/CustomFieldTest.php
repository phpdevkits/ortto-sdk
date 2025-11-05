<?php

declare(strict_types=1);

use PhpDevKits\Ortto\Requests\CustomField\CreateCustomField;
use PhpDevKits\Ortto\Requests\CustomField\GetCustomFields;
use PhpDevKits\Ortto\Requests\CustomField\UpdateCustomField;

test('custom field requests')
    ->expect('PhpDevKits\Ortto\Requests\CustomField')
    ->toBeSaloonRequest()
    ->toUseStrictTypes();

test('CreateCustomField request')
    ->expect(CreateCustomField::class)
    ->toSendPostRequest()
    ->toHaveJsonBody();

test('GetCustomFields request')
    ->expect(GetCustomFields::class)
    ->toSendPostRequest()
    ->toHaveJsonBody();

test('UpdateCustomField request')
    ->expect(UpdateCustomField::class)
    ->toSendPutRequest()
    ->toHaveJsonBody();
