<?php

declare(strict_types=1);

namespace Obelaw\Twist\Console;

use Illuminate\Console\Command;
use Obelaw\Twist\Facades\Twist;

final class MigrateCommand extends Command
{
    protected $signature = 'twist:migrate';

    protected $description = 'Modules setup';

    public function handle(): void
    {
        foreach (Twist::getModules() as $module) {
            if (method_exists($module, 'pathMigrations')) {
                dump($module->pathMigrations());
            }
        }
    }
}
