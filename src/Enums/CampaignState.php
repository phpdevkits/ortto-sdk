<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Enums;

/**
 * Campaign state values
 */
enum CampaignState: string
{
    case Draft = 'draft';
    case Scheduled = 'scheduled';
    case Sending = 'sending';
    case Sent = 'sent';
    case Cancelled = 'cancelled';
    case On = 'on';
    case Off = 'off';
}
