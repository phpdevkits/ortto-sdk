<?php

use PhpDevKits\Ortto\Resources\PersonResource;

return [

    'api_key' => env('ORTTO_API_KEY', ''),
    'url' => env('ORTTO_API_URL', 'https://api.eu.ap3api.com/v1'),
    'suppression_list_field_id' => env('ORTTO_SUPPRESSION_LIST_FIELD_ID', 'str::email'),

    /*
    |--------------------------------------------------------------------------
    | Resource Class Overrides
    |--------------------------------------------------------------------------
    |
    | Override default resource classes with your custom implementations.
    | This allows you to extend resources with custom behavior such as
    | caching, logging, rate limiting, or custom validation.
    |
    | Example:
    |   'person' => \App\Ortto\CustomPersonResource::class,
    |
    */

    'resources' => [
        'person' => PersonResource::class,
    ],

];
