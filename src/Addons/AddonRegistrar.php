<?php

namespace Twist\Addons;

use Twist\Facades\Twist;

class AddonRegistrar
{
    private static $paths = [];

    public static function register(string $name, string $path, string|array|null $panels = null): void
    {
        static::$paths[$name] = [
            'path' => $path,
            'panels' => is_array($panels) ? $panels : [$panels ?? Twist::defaultPanel()],
        ];
    }

    public static function getPaths(): array
    {
        return static::$paths;
    }
}
