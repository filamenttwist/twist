<?php

namespace Obelaw\Twist;

use Illuminate\Support\ServiceProvider;
use Obelaw\Twist\Classes\TwistClass;

class TwistServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('obelaw.twist.twist-class', TwistClass::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
