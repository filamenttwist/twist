<?php

namespace Twist\Base;

use Filament\Contracts\Plugin;
use Filament\Panel;

abstract class BaseAddon implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return class_basename($this);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
