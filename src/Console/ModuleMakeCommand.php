<?php namespace PerkDotCom\Modules\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use PerkDotCom\Modules\Generator;
use PerkDotCom\Modules\Module;

class ModuleMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module
                            {name : The name of the module}';

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
        'src'       => [
            'Console' => [
                'Commands'
            ],
            'Events',
            'Exceptions',
            'Http'    => [
                'Controllers',
                'Middleware',
                'Requests'
            ],
            'Jobs',
            'Listeners'
        ],
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
        $this->laravel   = $laravel;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $module = new Module($this->laravel);
        $module->parse($this->argument('name'));

        if ($this->files->exists($module->path)) {
            $this->error($module->name . ' already exists!');

            return false;
        }

        $this->createStructure($module->path, $this->folders);
        $this->createServiceProvider($module);
        $this->createFiles($module->name, $module->path);
        $this->updateModulesManifest($module->name);
        $this->info("Created module '$module->name' in '$module->path");
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
            $this->files->makeDirectory($path, 0777, true, true);
            $this->files->put($path . DIRECTORY_SEPARATOR . '.gitkeep', "\n");
        }
    }

    /**
     * Creates the service provider for the module.
     *
     * @param Module $module
     */
    protected function createServiceProvider(Module $module)
    {
        $stub          = $this->getStub('provider.stub');
        $rootNamespace = $this->laravel->getNamespace();
        $replacements  = [
            'namespace'  => $rootNamespace . ucfirst($module->name),
            'class_name' => ucfirst($module->name),
            'name'       => $module->name,
            'routes'     => $this->buildRoutesPath(),
            'views'      => $this->buildViewsPath(),
            'lang'       => $this->buildLangPath()
        ];

        $providerFileName = $module->path . DIRECTORY_SEPARATOR . $replacements['class_name'] . 'ServiceProvider.php';

        $this->generator->make($replacements, $stub, $providerFileName);
    }


    protected function createFiles($moduleName, $modulePath)
    {
        $this->files->put($modulePath . $this->buildRoutesPath(), '<?php' . PHP_EOL);

        $stub         = $this->getStub('phpunit.stub');
        $replacements = [
            'name'      => ucfirst($moduleName),
            'bootstrap' => $this->buildBootstrapPath($modulePath)
        ];

        $this->generator->make($replacements, $stub, $modulePath . DIRECTORY_SEPARATOR . 'phpunit.xml');
    }


    protected function updateModulesManifest($moduleName)
    {
        $modulesManifest = storage_path('app' . DIRECTORY_SEPARATOR . 'modules.json');
        $manifest        = [];

        if ($this->files->exists($modulesManifest)) {
            $manifest = json_decode($this->files->get($modulesManifest), true);
        }

        $rootNamespace = $this->laravel->getNamespace();
        $provider      = $rootNamespace . ucfirst($moduleName) . '\\' . ucfirst($moduleName) . 'ServiceProvider';

        $manifest['providers'][] = $provider;

        $this->files->put(
            $modulesManifest, json_encode($manifest, JSON_PRETTY_PRINT)
        );
    }

    /**
     * @return string
     */
    protected function buildRoutesPath()
    {
        return DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'routes.php';
    }

    /**
     * @return string
     */
    protected function buildViewsPath()
    {
        return DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views';
    }

    /**
     * @return string
     */
    protected function buildLangPath()
    {
        return DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'lang';
    }

    /**
     * @param $stub
     *
     * @return string
     */
    protected function getStub($stub)
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . $stub;
    }

    /**
     * @param string $modulePath
     *
     * @return string
     */
    protected function buildBootstrapPath($modulePath)
    {
        $bootstrapPath = 'bootstrap' . DIRECTORY_SEPARATOR . 'autoload.php';

        for ($i = 0; $i < 3; $i++) {
            $bootstrapPath = '..' . DIRECTORY_SEPARATOR . $bootstrapPath;
        }

        return $bootstrapPath;
    }
}
