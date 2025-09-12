<?php

namespace Obelaw\Twist\Tenancy\Concerns;

use Filament\Panel;

trait HasDBTenancy
{
    public static function registerTenancyModelGlobalScope(Panel $panel): void
    {
        return;
    }

    public static function observeTenancyModelCreation(Panel $panel): void
    {
        if (! static::isScopedToTenant()) {
            return;
        }

        $model = static::getModel();

        if (! class_exists($model)) {
            return;
        }
    }
}
