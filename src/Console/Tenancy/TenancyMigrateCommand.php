<?php

declare(strict_types=1);

namespace Obelaw\Twist\Console\Tenancy;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Obelaw\Twist\Services\Tenancy\MigrateTenancyService;


final class TenancyMigrateCommand extends Command
{
    protected $signature = 'twist:tenancy:migrate';

    protected $description = '';

    public function handle(): void
    {
        $tenants = Tenant::get();

        foreach ($tenants as $tenant) {
            $this->info("Migrating tenant: {$tenant->id}");
            MigrateTenancyService::make()->migrateAddons($tenant);
        }
    }
}
