<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Enums;

/**
 * Campaign sort field values
 */
enum CampaignSortField: string
{
    case Name = 'name';
    case State = 'state';
    case EditedAt = 'edited_at';
    case CreatedAt = 'created_at';
    case Delivered = 'delivered';
    case Opens = 'opens';
    case Clicks = 'clicks';
    case Conversions = 'conversions';
    case Revenue = 'revenue';
}
