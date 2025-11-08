<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Enums;

/**
 * Tag entity type.
 *
 * Indicates whether a tag is associated with people or organizations.
 * Note: Person tags use an empty string as the value (Ortto API convention).
 */
enum TagType: string
{
    /**
     * Tag associated with people/contacts
     * Note: Uses empty string as per Ortto API specification
     */
    case Person = '';

    /**
     * Tag associated with organizations/accounts
     */
    case Organization = 'organization';
}
