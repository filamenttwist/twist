<?php

namespace Twist\Tenancy\Drivers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Twist\Facades\Twist;
use Twist\Tenancy\Contracts\IsolationDriver;
use Twist\Tenancy\DTO\TenantDTO;

/**
 * Simple database-per-tenant driver.
 * Assumes tenant has connection details stored (database name, optionally host, username, password).
 */
class MultiTenantDriver implements IsolationDriver
{
    protected ?string $previousConnection = null;
    protected ?string $tenantConnectionName = null;

    public function boot(TenantDTO $tenant): void
    {
        $this->end();

        $base = Config::get('database.connections.mysql');
        $connectionName = 'tenant_' . ($tenant->id ?? uniqid());
        $database = $tenant->database ?? ($base['database'] . '_' . $tenant->id);

        $config = array_merge($base ?? [], [
            'database' => $database,
        ]);

        Config::set("database.connections.{$connectionName}", $config);

        Twist::setConnection($connectionName);

        $this->previousConnection = DB::getDefaultConnection();
        // DB::setDefaultConnection($connectionName);
        $this->tenantConnectionName = $connectionName;
    }

    public function end(): void
    {
        if ($this->previousConnection) {
            DB::setDefaultConnection($this->previousConnection);
        }
        if ($this->tenantConnectionName) {
            // Optionally forget connection.
            DB::purge($this->tenantConnectionName);
        }
        $this->previousConnection = null;
        $this->tenantConnectionName = null;
    }

    public function migrate(TenantDTO $tenant, array $paths = []): void
    {
        $this->boot($tenant);

        $parameters = ['--database' => $this->tenantConnectionName, '--force' => true];

        if ($paths) {
            $parameters['--path'] = $paths; // multiple allowed
        }

        Artisan::call('migrate', $parameters);

        $migrator = app('migrator');
        $migrator->setConnection($this->tenantConnectionName);
        $migrator->run($paths, $parameters);
    }

    public function seed(TenantDTO $tenant, array $seeders = []): void
    {
        $this->boot($tenant);
        if (empty($seeders)) {
            Artisan::call('db:seed', ['--database' => DB::getDefaultConnection(), '--force' => true]);
            return;
        }
        foreach ($seeders as $seeder) {
            Artisan::call('db:seed', [
                '--class' => $seeder,
                '--database' => DB::getDefaultConnection(),
                '--force' => true,
            ]);
        }
    }
}
