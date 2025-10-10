<?php

namespace Obelaw\Twist\Services\Tenancy;

use Obelaw\Twist\Base\BaseService;
use Illuminate\Support\Facades\DB;
use Obelaw\Twist\Contracts\HasMigration;
use Obelaw\Twist\Facades\Tenancy;
use Obelaw\Twist\Facades\Twist;
use Obelaw\Twist\Tenancy\DTO\TenantDTO;

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
