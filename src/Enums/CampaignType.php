<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Enums;

/**
 * Campaign type values
 */
enum CampaignType: string
{
    case All = 'all';
    case Email = 'email';
    case Playbook = 'playbook';
    case Sms = 'sms';
    case Journey = 'journey';
    case Push = 'push';
    case Whatsapp = 'whatsapp';
}
