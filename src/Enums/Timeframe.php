<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Enums;

/**
 * Timeframe periods for activities and campaigns
 *
 * @see https://help.ortto.com/a-686-retrieve-a-campaign-or-asset-report-get
 */
enum Timeframe: string
{
    case Last24Hours = 'last-24-hours';
    case Last7Days = 'last-7-days';
    case Last14Days = 'last-14-days';
    case Last30Days = 'last-30-days';
    case LastMonth = 'last-month';
    case LastQuarter = 'last-quarter';
    case Today = 'today';
    case Yesterday = 'yesterday';
    case ThisWeek = 'this-week';
    case ThisMonth = 'this-month';
    case ThisQuarter = 'this-quarter';
    case ThisYear = 'this-year';
    case All = 'all';
}
