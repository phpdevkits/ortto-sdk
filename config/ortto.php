<?php

return [

    'api_key' => env('ORTTO_API_KEY', ''),
    'url' => env('ORTTO_API_URL', 'https://api.eu.ap3api.com/v1'),
    'suppression_list_field_id' => env('ORTTO_SUPPRESSION_LIST_FIELD_ID', 'str::email'),

];
