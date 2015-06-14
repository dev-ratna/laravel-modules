<?php namespace PerkDotCom\Modules\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;

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
     * @var Filesystem
     */
    private $files;

    /**
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
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

        $this->createModuleDirectories($modulePath, $this->folders);
        $this->info("Created module '$moduleName' in '$modulePath");
    }

    /**
     * @param string $baseModulePath
     * @param array  $folders
     */
    protected function createModuleDirectories($baseModulePath, array $folders)
    {
        $this->files->makeDirectory($baseModulePath);
        foreach ($folders as $key => $value) {
            $basePath = $baseModulePath;
            if (is_array($value)) {
                $basePath = $basePath . DIRECTORY_SEPARATOR . $key;
                $this->createModuleDirectories($basePath, $value);
                continue;
            }
            $this->files->makeDirectory($basePath . DIRECTORY_SEPARATOR . $value);
        }
    }
}
