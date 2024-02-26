<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;

trait RefreshDatabaseWithSeeds
{
    use RefreshDatabase;

    /**
     * Refresh a conventional test database and seeds data.
     *
     * @return void
     */
    protected function refreshTestDatabase()
    {
        if (! RefreshDatabaseState::$migrated) {
            $this->artisan('migrate:fresh', [
                '--path' => '/database/migrations/crow_empty',
                '--drop-views' => $this->shouldDropViews(),
                '--drop-types' => $this->shouldDropTypes(),
            ]);

            $this->artisan('migrate');

            $this->artisan('db:seed');

            $this->app[Kernel::class]->setArtisan(null);

            RefreshDatabaseState::$migrated = true;
        }

        $this->beginDatabaseTransaction();
    }
}
