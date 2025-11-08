<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Enums;

/**
 * Campaign report timeframe periods
 *
 * @see https://help.ortto.com/a-686-retrieve-a-campaign-or-asset-report-get
 */
enum CampaignTimeframe: string
{
    case Last7Days = 'last-7-days';
    case Last14Days = 'last-14-days';
    case LastMonth = 'last-month';
    case LastQuarter = 'last-quarter';
}
