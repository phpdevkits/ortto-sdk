<?php

namespace PhpDevKits\Ortto\Resources;

/**
 * Person custom field resource
 *
 * Handles custom field operations for Person entities
 */
class PersonCustomFieldResource extends CustomFieldResource
{
    protected function getBaseEndpoint(): string
    {
        return '/person/custom-field';
    }
}
