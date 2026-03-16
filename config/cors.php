<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Caminhos cobertos pelo CORS
    |--------------------------------------------------------------------------
    */
    'paths' => ['api/*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        env('FRONTEND_URL', 'http://localhost:3000'),
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // false porque usamos tokens Bearer (sem cookies de sessão cross-origin)
    'supports_credentials' => false,

];
