<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Enums;

/**
 * Activity attribute display types
 *
 * These types control how activity attribute values are displayed and formatted in Ortto.
 * Used when creating custom activity definitions to specify attribute data types.
 *
 * @see https://help.ortto.com/a-272-activity
 */
enum ActivityDisplayType: string
{
    /**
     * Plain text field (up to 500 characters)
     */
    case Text = 'text';

    /**
     * Extended text field (over 500 characters)
     */
    case LargeText = 'large_text';

    /**
     * Email address field
     */
    case Email = 'email';

    /**
     * Phone number field
     */
    case Phone = 'phone';

    /**
     * URL/link field
     */
    case Link = 'link';

    /**
     * Whole number field
     */
    case Integer = 'integer';

    /**
     * Floating-point number field
     */
    case Decimal = 'decimal';

    /**
     * Currency value field
     */
    case Currency = 'currency';

    /**
     * Date field (day/month/year)
     */
    case Date = 'date';

    /**
     * Timestamp field (date + time)
     */
    case Time = 'time';

    /**
     * Boolean field (true/false)
     */
    case Bool = 'bool';

    /**
     * Single choice dropdown field
     */
    case SingleSelect = 'single_select';

    /**
     * Multiple choice dropdown field
     */
    case MultiSelect = 'multi_select';

    /**
     * JSON object field
     */
    case Object = 'object';
}
