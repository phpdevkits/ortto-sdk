<?php

namespace PhpDevKits\Ortto\Enums;

enum PersonField: string
{
    // String fields (str::)
    case ExternalId = 'str::ei';
    case Email = 'str::email';
    case FirstName = 'str::first';
    case LastName = 'str::last';
    case FullName = 'str::name';
    case PhoneNumber = 'str::phone';
    case PostalCode = 'str::postal';

    // Boolean fields (bol::)
    case EmailPermission = 'bol::p';
    case SmsPermission = 'bol::sp';

    // Geo fields (geo::)
    case City = 'geo::city';
    case Country = 'geo::country';

    // DateTime fields (dtz::)
    case Birthdate = 'dtz::b';
}
