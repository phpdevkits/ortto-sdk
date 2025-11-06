<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Enums;

/**
 * Activity icon identifiers
 *
 * These icons are displayed in the Ortto app for custom activity definitions.
 * Used when creating or modifying activity definitions.
 *
 * @see https://help.ortto.com/developer/latest/api-reference/activity/create.html
 */
enum ActivityIcon: string
{
    case Calendar = 'calendar-illustration-icon';
    case Caution = 'caution-illustration-icon';
    case Clicked = 'clicked-illustration-icon';
    case Coupon = 'coupon-illustration-icon';
    case Download = 'download-illustration-icon';
    case Email = 'email-illustration-icon';
    case Eye = 'eye-illustration-icon';
    case Flag = 'flag-activities-illustration-icon';
    case Happy = 'happy-illustration-icon';
    case Money = 'moneys-illustration-icon';
    case Page = 'page-illustration-icon';
    case Phone = 'phone-illustration-icon';
    case Reload = 'reload-illustration-icon';
    case Tag = 'tag-illustration-icon';
    case Time = 'time-illustration-icon';
}
