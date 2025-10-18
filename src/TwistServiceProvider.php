<?php

namespace Twist;

use Illuminate\Support\ServiceProvider;
use Twist\Classes\TwistClass;
use Twist\Console\MakeCommand;
use Twist\Console\MigrateCommand;
use Twist\Console\SetupAddonCommand;
use Twist\Console\SetupClearCommand;
use Twist\Console\SetupCommand;
use Twist\Console\SetupDisableCommand;
use Twist\Console\SetupEnableCommand;
use Twist\Console\Tenancy\TenancyMigrateCommand;
use Twist\Support\TenancyManager;
use Twist\Tenancy\Drivers\DriverFactory;

class TwistServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/twist.php',
            'twist'
        );

        $this->mergeConfigFrom(__DIR__ . '/../config/tenancy.php', 'obelaw.tenancy');

        $this->app->singleton('obelaw.twist.twist-class', TwistClass::class);

        // Driver factory
        $this->app->singleton(DriverFactory::class, function ($app) {
            $config = $app['config']->get('obelaw.tenancy');
            return new DriverFactory($config['drivers'] ?? [], $config['default_driver'] ?? null);
        });

        $this->app->singleton(TenancyManager::class, function ($app) {
            return new TenancyManager($app->make(DriverFactory::class));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources', 'obelaw-twist');

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

            $this->publishes([
                __DIR__ . '/../config/twist.php' => config_path('twist.php'),
            ], 'twist');
        }

        $this->commands([
            SetupCommand::class,
            SetupAddonCommand::class,
            SetupEnableCommand::class,
            SetupDisableCommand::class,
            SetupClearCommand::class,
            MigrateCommand::class,
            // make
            MakeCommand::class,

            //
            TenancyMigrateCommand::class
        ]);
    }
}
