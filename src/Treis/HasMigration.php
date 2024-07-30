<?php

namespace Obelaw\Twist\Treis;

trait HasMigration
{
    public function pathMigrations()
    {
        $class = new \ReflectionClass(static::class);
        return dirname($class->getFileName()) . DIRECTORY_SEPARATOR . 'generate' . DIRECTORY_SEPARATOR . 'migrations';
    }
}
