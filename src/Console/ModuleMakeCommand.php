<?php namespace PerkDotCom\Modules\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use PerkDotCom\Modules\Generator;

class ModuleMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module
                            {name : The name of the module}
                            {--path= : Path to create the module. Default: app/Modules}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates and scaffolds a new module.';

    /**
     * List of folders and sub folders to create.
     *
     * @var array
     */
    protected $folders = [
        'config',
        'resources' => [
            'lang' => [
                'en'
            ],
            'views',
        ],
        'src',
        'tests'
    ];

    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * @var Generator
     */
    protected $generator;

    /**
     * @param Application $laravel
     * @param Filesystem  $files
     * @param Generator   $generator
     */
    public function __construct(Application $laravel, Filesystem $files, Generator $generator)
    {
        parent::__construct();
        $this->files     = $files;
        $this->generator = $generator;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $moduleName = $this->argument('name');
        $modulePath = ($path = $this->option('path')) ? $path . DIRECTORY_SEPARATOR . $moduleName :
            app_path('Modules' . DIRECTORY_SEPARATOR . $moduleName);

        if ($this->files->exists($modulePath)) {
            $this->error($moduleName . ' already exists!');

            return false;
        }

        $this->createStructure($modulePath, $this->folders);
        $this->createServiceProvider($moduleName, $modulePath);
        $this->info("Created module '$moduleName' in '$modulePath");
    }

    /**
     * Creates the modules structure.
     *
     * @param string $baseModulePath
     * @param array  $folders
     */
    protected function createStructure($baseModulePath, array $folders)
    {
        foreach ($folders as $key => $value) {
            $basePath = $baseModulePath;
            if (is_array($value)) {
                $basePath = $basePath . DIRECTORY_SEPARATOR . $key;
                $this->createStructure($basePath, $value);
                continue;
            }

            $path = $basePath . DIRECTORY_SEPARATOR . $value;
            if (!$this->files->isDirectory(dirname($path))) {
                $this->files->makeDirectory(dirname($path), 0777, true, true);
                $this->files->put($path . DIRECTORY_SEPARATOR . '.gitkeep', '');
            }
        }
    }

    /**
     * Creates the service provider for the module.
     *
     * @param string $moduleName
     * @param string $modulePath
     */
    protected function createServiceProvider($moduleName, $modulePath)
    {
        $stub          = __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'provider.stub';
        $rootNamespace = $this->laravel->getNamespace();
        $replacements  = [
            'namespace'  => "$rootNamespace\\" . ucfirst($moduleName),
            'class_name' => ucfirst($moduleName),
            'name'       => $moduleName
        ];

        $this->generator->make($replacements, $stub, dirname($modulePath));
    }
}
