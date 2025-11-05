<?php

namespace PhpDevKits\Ortto\Enums;

/**
 * Ortto custom field types
 *
 * These are the type codes used when creating or updating custom fields via the API.
 * Each type maps to a specific field ID prefix when the field is created.
 *
 * @see https://help.ortto.com/a-702-supported-field-data-types
 * @see https://help.ortto.com/developer/latest/api-reference/custom-field/create.html
 */
enum CustomFieldType: string
{
    /**
     * Plain text field (up to 500 characters)
     * Field ID prefix: str:
     */
    case Text = 'text';

    /**
     * Extended text field (over 500 characters)
     * Field ID prefix: str:
     */
    case LargeText = 'large_text';

    /**
     * Whole number field
     * Field ID prefix: int:
     */
    case Integer = 'integer';

    /**
     * Floating-point number field (up to 2 decimal places)
     * Field ID prefix: dec:
     */
    case Decimal = 'decimal';

    /**
     * Decimal field with workspace currency symbol
     * Field ID prefix: cur:
     */
    case Currency = 'currency';

    /**
     * Decimal field with ISO currency codes
     * Field ID prefix: cur:
     */
    case Price = 'price';

    /**
     * Date field (day/month/year only, no time)
     * Field ID prefix: dat:
     */
    case Date = 'date';

    /**
     * Timestamp field (date + time)
     * Field ID prefix: tim:
     */
    case Time = 'time';

    /**
     * Boolean field (true/false)
     * Field ID prefix: bol:
     */
    case Bool = 'bool';

    /**
     * Phone number field (local/international format)
     * Field ID prefix: phn:
     */
    case Phone = 'phone';

    /**
     * Single choice dropdown field
     * Field ID prefix: str:
     */
    case SingleSelect = 'single_select';

    /**
     * Multiple choice dropdown field
     * Field ID prefix: str:
     * Note: Cannot enable change tracking for multi-select fields
     */
    case MultiSelect = 'multi_select';

    /**
     * URL/webpage field
     * Field ID prefix: lnk:
     */
    case Link = 'link';

    /**
     * JSON object field (max 15,000 bytes)
     * Field ID prefix: obj:
     */
    case Object = 'object';
}
