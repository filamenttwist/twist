<?php

namespace Twist\Services\Tenancy;

use Twist\Base\BaseService;
use Illuminate\Support\Facades\DB;
use Twist\Contracts\HasMigration;
use Twist\Facades\Tenancy;
use Twist\Facades\Twist;
use Twist\Tenancy\DTO\TenantDTO;

class MigrateTenancyService extends BaseService
{

    public function migrateAddons($tenant)
    {
        $dbName = config('database.connections.mysql.database') . '_' . $tenant->id;

        DB::statement("CREATE DATABASE IF NOT EXISTS {$dbName}");

        $migratePaths = [
            '\obelaw\permit\database\migrations'
        ];

        foreach (Twist::getAddons() as $addon) {
            if ($addon instanceof HasMigration) {
                array_push($migratePaths, $addon->pathMigrations());
            }
        }

        $tenantDTO = new TenantDTO(database: $dbName, id: $tenant->id);
        Tenancy::migrate($tenantDTO, $migratePaths);
    }
}
