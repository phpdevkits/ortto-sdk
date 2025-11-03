<?php

namespace PhpDevKits\Ortto\Enums;

enum PersonField: string
{
    case Email = 'str::email';
    case FirstName = 'str::first';
    case LastName = 'str::last';
    case FullName = 'str::name';
    case PhoneNumber = 'str::phone';

}
