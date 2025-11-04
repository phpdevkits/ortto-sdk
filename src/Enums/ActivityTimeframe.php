<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Enums;

enum ActivityTimeframe: string
{
    case Last24Hours = 'last-24-hours';
    case Last7Days = 'last-7-days';
    case Last30Days = 'last-30-days';
    case Today = 'today';
    case Yesterday = 'yesterday';
    case ThisWeek = 'this-week';
    case ThisMonth = 'this-month';
    case ThisQuarter = 'this-quarter';
    case ThisYear = 'this-year';
    case All = 'all';
}
