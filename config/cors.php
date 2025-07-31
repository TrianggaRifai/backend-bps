<?php

return [
<<<<<<< HEAD
=======
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'logout'],
    'allowed_methods' => ['*'],
    
    'allowed_origins' => [env('FRONTEND_URL', 'http://localhost:5173')],
>>>>>>> 8eb7c7efae6329de0c937d51391335d44daa5b58

    'paths' => ['api/*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,
<<<<<<< HEAD

    'supports_credentials' => true,

=======
    'supports_credentials' => true, 
>>>>>>> 8eb7c7efae6329de0c937d51391335d44daa5b58
];
