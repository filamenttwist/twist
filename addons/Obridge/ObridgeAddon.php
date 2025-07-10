<?php

namespace Obelaw\Obridge;

use Filament\Panel;
use Obelaw\Twist\Base\BaseAddon;
use Obelaw\Twist\Concerns\InteractsWithMigration;
use Obelaw\Twist\Contracts\HasMigration;

class ObridgeAddon extends BaseAddon implements HasMigration
{
    use InteractsWithMigration;

    protected $pathMigrations = __DIR__ . '/database/migrations';

    public function boot(Panel $panel): void
    {
        // Register the service provider
        app()->register(ObridgeServiceProvider::class);
    }

    public function register(Panel $panel): void
    {
        $panel
            ->discoverResources(
                in: __DIR__ . DIRECTORY_SEPARATOR . 'Filament' . DIRECTORY_SEPARATOR . 'Resources',
                for: 'Obelaw\\Obridge\\Filament\\Resources'
            );
    }
}
