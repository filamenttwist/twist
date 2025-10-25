<?php

namespace Twist\Base;

abstract class BaseAction
{
    public static function make(mixed ...$arguments): mixed
    {
        $static = app(static::class);
        return $static->handle(...$arguments);
    }
}
