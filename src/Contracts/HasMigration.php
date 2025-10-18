<?php

namespace Twist\Contracts;

interface HasMigration
{
    public function pathMigrations(): string;
}
