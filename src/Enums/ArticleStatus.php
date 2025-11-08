<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Enums;

/**
 * Article status values
 */
enum ArticleStatus: string
{
    case Published = 'on';
    case Unpublished = 'off';
    case All = '';
}
