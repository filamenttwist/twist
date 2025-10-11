<?php

namespace Obelaw\Twist\View;

use Closure;

class TwistView
{
    protected static array $renderHooks = [];

    public static function registerRenderHook(string $name, Closure $hook): void
    {
        if (empty(trim($name))) {
            throw new \InvalidArgumentException('Hook name cannot be empty');
        }

        if (!isset(self::$renderHooks[$name])) {
            self::$renderHooks[$name] = [];
        }

        self::$renderHooks[$name][] = $hook;
    }

    public static function renderHook(string $name): ?array
    {
        if (isset(self::$renderHooks[$name])) {
            return self::$renderHooks[$name];
        }

        return null;
    }

    public static function applyHooks($name, $hooks)
    {
        return array_merge($hooks, array_map(function ($action) {
            return $action();
        }, TwistView::renderHook($name) ?? []));
    }
}
