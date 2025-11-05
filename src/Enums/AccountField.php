<?php

namespace PhpDevKits\Ortto\Enums;

enum AccountField: string
{
    // String fields (str:o:)
    case Name = 'str:o:name';
    case Website = 'str:o:website';
    case Industry = 'str:o:industry';
    case Address = 'str:o:address';
    case PostalCode = 'str:o:postal';
    case Source = 'str:o:source';

    // Integer fields (int:o:)
    case Employees = 'int:o:employees';

    // Geo fields (geo:o:)
    case City = 'geo:o:city';
    case Country = 'geo:o:country';
    case Region = 'geo:o:region';
}
