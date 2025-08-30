<?php

return [


    // Isolation drivers mapping (name => class)
    'drivers' => [
        'multi' => \Obelaw\Twist\Tenancy\Drivers\MultiTenantDriver::class,
    ],

    // Default driver name
    // Switch default to single tenant by default; override via env if desired.
    'default_driver' => env('OBELAW_TENANCY_DRIVER', 'multi'),

    // Tenant resolver callback (publish & override). Should return a Tenant model instance or null.
    'tenant_resolver' => null,
];
