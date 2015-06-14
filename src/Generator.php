<?php namespace PerkDotCom\Modules;

use Illuminate\Contracts\Filesystem\Filesystem;
use Mustache_Engine;

class Generator
{
    /**
     * @var Filesystem
     */
    protected $file;

    /**
     * @var Mustache_Engine
     */
    protected $mustache;

    public function __construct(Filesystem $filesystem, Mustache_Engine $mustache)
    {
        $this->file     = $filesystem;
        $this->mustache = $mustache;
    }

    /**
     * Generates the modules service provider
     *
     * @param $input
     * @param $template
     * @param $destination
     */
    public function make($input, $template, $destination)
    {
        $template = $this->file->get($template);

        $stub = $this->mustache->render($template, $input);

        $this->file->put($destination, $stub);
    }
}
