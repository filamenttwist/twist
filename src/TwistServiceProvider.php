<?php

namespace Obelaw\Twist;

use Illuminate\Support\ServiceProvider;
use Obelaw\Twist\Addons\AddonsPool;
use Obelaw\Twist\Classes\TwistClass;
use Obelaw\Twist\Console\MakeCommand;
use Obelaw\Twist\Console\MigrateCommand;
use Obelaw\Twist\Console\SetupAddonCommand;
use Obelaw\Twist\Console\SetupClearCommand;
use Obelaw\Twist\Console\SetupCommand;
use Obelaw\Twist\Console\SetupDisableCommand;
use Obelaw\Twist\Console\SetupEnableCommand;
use Obelaw\Twist\Support\TenancyManager;
use Obelaw\Twist\Tenancy\Drivers\DriverFactory;

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
        AddonsPool::setPoolPath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'addons', AddonsPool::LEVELONE);

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
        ]);
    }
}
