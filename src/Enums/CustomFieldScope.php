<?php

namespace PhpDevKits\Ortto\Enums;

/**
 * Ortto custom field scope
 *
 * Determines whether the custom field is for Person or Account entities.
 *
 * @see https://help.ortto.com/developer/latest/api-reference/custom-field/create.html
 */
enum CustomFieldScope: string
{
    /**
     * Person-level custom field
     * Field ID format: {type}:cm:{field-name}
     * Example: str:cm:job-title
     */
    case Person = 'person';

    /**
     * Account-level custom field (Organizations)
     * Field ID format: {type}:oc:{field-name}
     * Example: str:oc:industry
     */
    case Account = 'account';
}
