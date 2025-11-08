<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Enums;

/**
 * Push notification platform values
 */
enum PushPlatform: string
{
    case Web = 'web';
    case Ios = 'ios';
    case Android = 'android';
}
