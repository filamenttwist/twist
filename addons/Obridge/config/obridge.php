<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Configuration
    |--------------------------------------------------------------------------
    |
    | These settings control the rate limiting for Obridge authentication
    | attempts to prevent brute force attacks.
    |
    */

    'rate_limit' => [
        'max_attempts' => env('OBRIDGE_MAX_ATTEMPTS', 10),
        'decay_minutes' => env('OBRIDGE_DECAY_MINUTES', 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for caching authenticated Obridge instances to improve
    | performance and reduce database queries.
    |
    */

    'cache' => [
        'ttl_minutes' => env('OBRIDGE_CACHE_TTL', 15),
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Settings for logging Obridge authentication attempts and activities.
    |
    */

    'logging' => [
        'channel' => env('OBRIDGE_LOG_CHANNEL', 'obridge'),
        'log_attempts' => env('OBRIDGE_LOG_ATTEMPTS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    |
    | Additional security configurations for Obridge.
    |
    */

    'security' => [
        'hash_secrets' => env('OBRIDGE_HASH_SECRETS', false),
        'require_https' => env('OBRIDGE_REQUIRE_HTTPS', false),
    ],

];
