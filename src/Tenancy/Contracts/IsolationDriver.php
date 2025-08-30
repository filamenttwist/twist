<?php

namespace Obelaw\Twist\Tenancy\Contracts;

use Obelaw\Twist\Tenancy\DTO\TenantDTO;


interface IsolationDriver
{
    public function boot(TenantDTO $tenant): void;
    public function end(): void;
    public function migrate(TenantDTO $tenant, array $paths = []): void;
    public function seed(TenantDTO $tenant, array $seeders = []): void;
}
