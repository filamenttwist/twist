<?php

namespace Obelaw\Twist\Contracts;

interface HasMigration
{
    public function pathMigrations(): string;
}
