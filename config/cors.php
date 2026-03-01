<?php

// return [
//     'paths' => ['*'],
//     'allowed_methods' => ['*'],
//     'allowed_origins' => ['*'],
//     'allowed_headers' => ['*'],
//     'max_age' => 0,
// ];
return [
    'paths' => ['*'],
    'allowed_methods' => ['GET','POST','PUT','DELETE','OPTIONS'],
    'allowed_origins' => ['http://localhost:4200'], // your Angular dev URL
    'allowed_headers' => ['Content-Type','Authorization','apikey','Apikey'],
    'supports_credentials' => true,
    'max_age' => 3600,
];
