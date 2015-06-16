<?php namespace PerkDotCom\Modules;

use Illuminate\Contracts\Foundation\Application;
use RuntimeException;

class Module
{
    /**
     * @var Application
     */
    protected $laravel;

    /**
     * @var string
     */
    protected $basePath;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $path;

    /**
     * @var string
     */
    public $namespace;

    public function __construct(Application $laravel)
    {
        $this->laravel  = $laravel;
        $this->basePath = 'app' . DIRECTORY_SEPARATOR . 'Modules';
    }

    /**
     * Parses the input
     *
     * @param string $name
     * @param string $path
     *
     * @return self
     */
    public function parse($name, $path)
    {
        $this->name = $name;

        $path       = implode(DIRECTORY_SEPARATOR, explode('/', str_replace('\\', '/', $path)));
        $path       = (empty($path)) ? $this->basePath : $path;
        $this->path = $path . DIRECTORY_SEPARATOR . $this->name;

        $this->setNamespace();

        return $this;
    }

    /**
     * @throws \RuntimeException
     */
    private function setNamespace()
    {
        $composer = json_decode(file_get_contents($this->laravel->basePath() . '/composer.json'), true);

        foreach ((array) data_get($composer, 'autoload.psr-4') as $namespace => $path) {
            foreach ((array) $path as $pathChoice) {
                if (realpath($this->laravel->basePath() . DIRECTORY_SEPARATOR . 'app') == realpath($this->laravel->basePath() . '/' . $pathChoice)) {
                    return $this->namespace = $namespace . $this->name;
                }
            }
        }

        throw new RuntimeException('Unable to detect application namespace.');
    }
}
