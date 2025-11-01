<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use Orchestra\Testbench\TestCase as BaseTestCase;
use PhpDevKits\Ortto\OrttoServiceProvider;

abstract class TestCase extends BaseTestCase
{
    /**
     * Get package providers.
     *
     * @param  Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            OrttoServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  Application  $app
     */
    protected function defineEnvironment($app): void
    {
        $envPath = __DIR__.'/..';
        if (file_exists($envPath.'/.env')) {
            $app->useEnvironmentPath($envPath);
            $app->bootstrapWith([LoadEnvironmentVariables::class]);
        }
    }

    /**
     * Get application configuration.
     *
     * @param  Application  $app
     */
    protected function getEnvironmentSetUp($app): void
    {
        // Set default test configuration values
        $app['config']->set('ortto.api_key', env('ORTTO_API_KEY', 'test-api-key'));
        $app['config']->set('ortto.url', env('ORTTO_API_URL', 'https://api.eu.ap3api.com/v1'));
    }
}
