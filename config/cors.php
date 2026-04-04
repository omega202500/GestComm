<?php
return [


    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,

];



// return [
//     'paths' => ['api/*', 'sanctum/csrf-cookie'],
//     'allowed_methods' => ['*'],
//     'allowed_origins' => ['*'], // Pour développement, en production limitez aux domaines spécifiques
//     'allowed_origins_patterns' => [],
//     'allowed_headers' => ['*'],
//     'exposed_headers' => [],
//     'max_age' => 0,
//     'supports_credentials' => false,
// ];
// return [

//     'paths' => ['api/*', 'sanctum/csrf-cookie'],

//     'allowed_methods' => ['*'],

//     'allowed_origins' => [
//         'http://localhost:8000',
//         'http://127.0.0.1:8000',
//         'http://192.168.1.106:8000',
//     ],

//     'allowed_origins_patterns' => [],

//     'allowed_headers' => ['*'],

//     'exposed_headers' => [],

//     'max_age' => 0,

//     'supports_credentials' => false,

// ];
