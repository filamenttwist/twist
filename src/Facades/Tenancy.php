<?php

namespace Obelaw\Twist\Facades;

use Illuminate\Support\Facades\Facade;
use Obelaw\Twist\Support\TenancyManager;

class Tenancy extends Facade
{
    protected static function getFacadeAccessor()
    {
        return TenancyManager::class;
    }
}
