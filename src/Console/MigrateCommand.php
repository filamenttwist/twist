<?php

declare(strict_types=1);

namespace Obelaw\Twist\Console;

use Illuminate\Console\Command;
use Obelaw\Twist\Contracts\HasMigration;
use Obelaw\Twist\Facades\Twist;

final class MigrateCommand extends Command
{
    protected $signature = 'twist:migrate {--r|rollback : rollback}';

    protected $description = 'Modules setup';

    public function handle(): void
    {
        $migratePaths = [];
        foreach (Twist::getAddons() as $module) {
            if ($module instanceof HasMigration) {
                array_push($migratePaths, $module->pathMigrations());
            }
        }

        $migrator = $this->laravel->make('migrator');

        if ($this->option('rollback')) {
            $migrator->rollback($migratePaths);

            $this->info('Migrations rollback completed successfully.');

            return;
        }

        $migrator->run($migratePaths);

        $this->info('Migrations completed successfully.');
    }
}
