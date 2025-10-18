<?php

namespace Twist\Concerns;

trait InteractsWithMigration
{
    public function pathMigrations(): string
    {
        return $this->pathMigrations;
    }
}
