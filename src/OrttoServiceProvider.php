<?php

namespace PhpDevKits\Ortto;

use Illuminate\Support\ServiceProvider;

class OrttoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfig();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishConfig();
    }

    private function mergeConfig(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/ortto.php',
            'ortto'
        );
    }

    private function publishConfig(): void
    {

        $this->publishes([
            __DIR__.'/../config/ortto.php' => config_path('ortto.php'),
        ], 'config');
    }
}
