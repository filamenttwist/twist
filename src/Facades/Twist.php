<?php

namespace Twist\Facades;

use Illuminate\Support\Facades\Facade;

class Twist extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'obelaw.twist.twist-class';
    }
}
