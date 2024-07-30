<?php

namespace Obelaw\Twist;

use Illuminate\Support\ServiceProvider;
use Obelaw\Twist\Classes\TwistClass;
use Obelaw\Twist\Console\MigrateCommand;

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
        $this->commands([
            MigrateCommand::class,
        ]);
    }
}
