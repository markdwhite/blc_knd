<?php

namespace Somsip\BlcKnd\Testing;

use Artisan;

trait SpecificDatabaseMigrations
{
    /**
     * Runs migrations for individual tests
     *
     * @params array $migrations
     * @return void
     */
    public function migrate(array $migrations = [])
    {
        $path = database_path('migrations');
        $migrator = app()->make('migrator');
        $migrator->getRepository()->createRepository();
        $files = $migrator->getMigrationFiles($path);

        if (!empty($migrations)) {
            $files = collect($files)->filter(
                function ($value, $key) use ($migrations) {
                    if (in_array($key, $migrations)) {
                        return [$key => $value];
                    }
                }
            )->all();
        }

        $migrator->requireFiles($files);
        $migrator->runPending($files);
    }

    /**
     * Runs some or all seeds
     *
     * @params string $seeds
     * @return void
     */
    public function seed($seeds = '')
    {
        $command = "db:seed";

        if (empty($seeds)) {
            Artisan::call($command);
        } else {
            Artisan::call($command, ['--class' => $seeds]);
        }
    }
}
