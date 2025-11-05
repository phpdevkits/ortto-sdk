<?php

namespace PhpDevKits\Ortto\Resources;

/**
 * Account custom field resource
 *
 * Handles custom field operations for Account (Organization) entities
 */
class AccountCustomFieldResource extends CustomFieldResource
{
    protected function getBaseEndpoint(): string
    {
        return '/accounts/custom-field';
    }
}
