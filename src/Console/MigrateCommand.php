<?php

declare(strict_types=1);

namespace Obelaw\Twist\Console;

use Illuminate\Console\Command;
use Obelaw\Twist\Facades\Twist;

final class MigrateCommand extends Command
{
    protected $signature = 'twist:migrate {--r|rollback : rollback}';

    protected $description = 'Modules setup';

    public function handle(): void
    {
        $migratePaths = [];
        foreach (Twist::getAddons() as $module) {
            if (method_exists($module, 'pathMigrations')) {
                array_push($migratePaths, $module->pathMigrations());
            }
        }

        if ($this->option('rollback')) {
            $this->call('migrate:rollback', [
                '--path' => $migratePaths,
            ]);

            return;
        }

        $this->call('migrate', [
            '--path' => $migratePaths,
        ]);
    }
}
