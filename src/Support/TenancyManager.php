<?php

namespace Twist\Support;

use Twist\Tenancy\Contracts\IsolationDriver;
use Twist\Tenancy\Drivers\DriverFactory;
use Twist\Tenancy\DTO\TenantDTO;


class TenancyManager
{
    protected DriverFactory $factory;
    protected ?IsolationDriver $active = null;
    protected ?TenantDTO $tenant = null;

    public function __construct(DriverFactory $factory)
    {
        $this->factory = $factory;
    }

    public function driver(?string $name = null): IsolationDriver
    {
        if ($this->active && ($name === null || $name === $this->factory->default())) {
            return $this->active;
        }
        return $this->active = $this->factory->make($name);
    }

    public function currentTenant(): ?TenantDTO
    {
        return $this->tenant;
    }

    public function initialize(TenantDTO $tenant, ?string $driverName = null): void
    {
        $driver = $this->driver($driverName);
        $driver->boot($tenant);
        $this->tenant = $tenant;
    }

    public function migrate(TenantDTO $tenant, array $paths = [], ?string $driverName = null): void
    {
        $driver = $this->driver($driverName);
        $driver->migrate($tenant, $paths);
        $this->tenant = $tenant;
    }

    public function end(): void
    {
        if ($this->active) {
            $this->active->end();
        }
        $this->active = null;
        $this->tenant = null;
    }
}
