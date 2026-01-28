<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

trait CreatesApplication
{
    // Run once for ALL test classes
    public static $migrated = false;

    /**
     * Creates the application.
     */
    public function createApplication(): Application
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        // Run a fresh migration for the test database
        // This ensures all tables are migrated once before running tests
        if (!self::$migrated) {
            \Artisan::call('migrate:fresh', [
                '--env' => 'testing',
                '--force' => true
            ]);

            echo "  ✓  Test database migrated successfully\n";

            self::$migrated = true;
        }

        return $app;
    }
}
