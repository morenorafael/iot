<?php

return [

    'default' => env('IOT_CONTROLLER', 'tuya'),

    'controllers' => [

        'tuya' => [
            'driver' => 'tuya',
            'url' => env('TUYA_URL', 'https://openapi.tuyaus.com'),
            'client_id' => env('TUYA_CLIENT_ID'),
            'access_token' => env('TUYA_ACCESS_TOKEN'),
        ],

    ],

];