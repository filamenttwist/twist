<?php

namespace Obelaw\Twist\Addons;

class AddonRegistrar
{
    private static $paths = [];

    public static function register(string $name, string $path): void
    {
        static::$paths[$name] = $path;
    }

    public static function getPaths(): array
    {
        return static::$paths;
    }
}
