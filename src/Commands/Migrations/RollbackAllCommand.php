<?php

namespace IchieBenjamin\CodeGenerator\Commands\Migrations;

use IchieBenjamin\CodeGenerator\Commands\Bases\MigrationCommandBase;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Database\Migrations\Migrator;
use Symfony\Component\Console\Input\InputOption;

class RollbackAllCommand extends MigrationCommandBase
{
    use ConfirmableTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'migrate:rollback-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback the last database migration from all folders';

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

        $this->migrator->setConnection($this->option('database'));

        $this->migrator->rollback(
            $this->getMigrationPaths(), [
                'pretend' => $this->option('pretend'),
                'step' => (int) $this->option('step'),
            ]
        );

        // Once the migrator has run we will grab the note output and send it out to
        // the console screen, since the migrator itself functions without having
        // any instances of the OutputInterface contract passed into the class.
        foreach ($this->getMigratorNotes() as $note) {
            $this->output->writeln($note);
        }
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
            ['path', null, InputOption::VALUE_OPTIONAL, 'The path of migrations files to be executed.'],
            ['pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run.'],
            ['step', null, InputOption::VALUE_OPTIONAL, 'The number of migrations to be reverted.'],
        ];
    }
}
