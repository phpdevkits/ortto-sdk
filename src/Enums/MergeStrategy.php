<?php

namespace PhpDevKits\Ortto\Enums;

enum MergeStrategy: int
{
    case AppendOnly = 1;
    case OverwriteExisting = 2;
    case Ignore = 3;

}
