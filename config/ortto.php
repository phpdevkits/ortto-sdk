<?php

use PhpDevKits\Ortto\Resources\AccountResource;
use PhpDevKits\Ortto\Resources\AccountsResource;
use PhpDevKits\Ortto\Resources\AssetResource;
use PhpDevKits\Ortto\Resources\CampaignResource;
use PhpDevKits\Ortto\Resources\KnowledgeBaseResource;
use PhpDevKits\Ortto\Resources\PersonResource;
use PhpDevKits\Ortto\Resources\TagResource;

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
        'account' => AccountResource::class,
        'accounts' => AccountsResource::class,
        'asset' => AssetResource::class,
        'campaign' => CampaignResource::class,
        'knowledge_base' => KnowledgeBaseResource::class,
        'tag' => TagResource::class,
    ],

];
