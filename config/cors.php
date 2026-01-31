<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
        'login',
        'logout',
        'register',
        'forgot-password',
        'reset-password',
        'email/verify/*',
        'email/resend',
    ],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    'allowed_origins' => [
        // These will be merged with environment-specific origins below
    ],

    'allowed_origins_patterns' => [
        'https://*.kadikings.co.ke',
    ],

    'allowed_headers' => [
        'Authorization',
        'Content-Type',
        'X-Requested-With',
        'Accept',
        'X-CSRF-TOKEN',
        'X-XSRF-TOKEN',
        'X-API-KEY',
        'Origin',
        'X-Auth-Token',
    ],

    'exposed_headers' => [
        'Authorization',
        'X-RateLimit-Limit',
        'X-RateLimit-Remaining',
        'X-RateLimit-Reset',
    ],

    'max_age' => 86400,

    'supports_credentials' => false,

];
