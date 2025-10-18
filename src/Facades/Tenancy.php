<?php

namespace Twist\Facades;

use Illuminate\Support\Facades\Facade;
use Twist\Support\TenancyManager;

class Tenancy extends Facade
{
    protected static function getFacadeAccessor()
    {
        return TenancyManager::class;
    }
}
