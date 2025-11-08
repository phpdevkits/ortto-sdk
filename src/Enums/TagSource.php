<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Enums;

/**
 * Tag creation source types.
 *
 * Indicates how a tag was originally created in the Ortto CDP.
 */
enum TagSource: string
{
    /**
     * Tag created via CSV import
     */
    case Csv = 'csv';

    /**
     * Tag created via API
     */
    case Api = 'api';

    /**
     * Tag created manually in the Ortto UI
     */
    case Manual = 'manual';

    /**
     * Tag created via Zapier integration
     */
    case Zapier = 'zapier';
}
