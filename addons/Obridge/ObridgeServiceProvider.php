<?php

namespace Obelaw\Obridge;

use Illuminate\Support\ServiceProvider;

class ObridgeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/obridge.php',
            'obridge'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/config/obridge.php' => config_path('obridge.php'),
            ], 'obridge-config');
        }

        // Configure logging channel
        $this->configureLogging();
    }

    /**
     * Configure the Obridge logging channel.
     */
    protected function configureLogging(): void
    {
        $this->app['config']->set('logging.channels.obridge', [
            'driver' => 'daily',
            'path' => storage_path('logs/obridge.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 14,
            'replace_placeholders' => true,
        ]);
    }
}
