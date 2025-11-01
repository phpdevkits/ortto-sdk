<?php

namespace PhpDevKits\Ortto\Enums;

enum FindStrategy: int
{
    case Any = 0;
    case NextOnlyIfPreviousEmpty = 1;
    case All = 2;

}
