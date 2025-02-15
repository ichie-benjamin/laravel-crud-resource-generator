<?php

namespace IchieBenjamin\CodeGenerator\Commands\Migrations;

use IchieBenjamin\CodeGenerator\Commands\Bases\MigrationCommandBase;
use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Input\InputOption;

class RefreshAllCommand extends MigrationCommandBase
{
    use ConfirmableTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'migrate:refresh-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset and re-run all migrations from all folders';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->confirmToProceed()) {
            return;
        }

        // Next we'll gather some of the options so that we can have the right options
        // to pass to the commands. This includes options such as which database to
        // use and the path to use for the migration. Then we'll run the command.
        $database = $this->input->getOption('database');

        $force = $this->input->getOption('force');

        // If the "step" option is specified it means we only want to rollback a small
        // number of migrations before migrating again. For example, the user might
        // only rollback and remigrate the latest four migrations instead of all.
        $step = $this->input->getOption('step') ?: 0;

        if ($step > 0) {
            $this->runRollback($database, $step, $force);
        } else {
            $this->runReset($database, $force);
        }

        // The refresh command is essentially just a brief aggregate of a few other of
        // the migration commands and just provides a convenient wrapper to execute
        // them in succession. We'll also see if we need to re-seed the database.
        $this->call('migrate-all', [
            '--database' => $database,
            '--force' => $force,
        ]);

        if ($this->needsSeeding()) {
            $this->runSeeder($database);
        }
    }

    /**
     * Run the rollback command.
     *
     * @param  string  $database
     * @param  bool  $step
     * @param  bool  $force
     * @return void
     */
    protected function runRollback($database, $step, $force)
    {
        $this->call('migrate:rollback-all', [
            '--database' => $database,
            '--step' => $step,
            '--force' => $force,
        ]);
    }

    /**
     * Run the reset command.
     *
     * @param  string  $database
     * @param  bool  $force
     * @return void
     */
    protected function runReset($database, $force)
    {
        $this->call('migrate:reset-all', [
            '--database' => $database,
            '--force' => $force,
        ]);
    }

    /**
     * Determine if the developer has requested database seeding.
     *
     * @return bool
     */
    protected function needsSeeding()
    {
        return $this->option('seed') || $this->option('seeder');
    }

    /**
     * Run the database seeder command.
     *
     * @param  string  $database
     * @return void
     */
    protected function runSeeder($database)
    {
        $this->call('db:seed', [
            '--database' => $database,
            '--class' => $this->option('seeder') ?: 'DatabaseSeeder',
            '--force' => $this->option('force'),
        ]);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.'],
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.'],
            ['seed', null, InputOption::VALUE_NONE, 'Indicates if the seed task should be re-run.'],
            ['seeder', null, InputOption::VALUE_OPTIONAL, 'The class name of the root seeder.'],
            ['step', null, InputOption::VALUE_OPTIONAL, 'The number of migrations to be reverted & re-run.'],
        ];
    }
}
