<?php namespace Perk\LaravelModules\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ModuleCreateCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        //
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('name', InputArgument::REQUIRED, 'The name of the module to be created.'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array(
                'path',
                null,
                InputOption::VALUE_OPTIONAL,
                'Path to create the module, overriding the config value.',
                null
            ),
            array(
                'namespace',
                null,
                InputOption::VALUE_OPTIONAL,
                'Namespace for the '
            )
        );
    }
}
