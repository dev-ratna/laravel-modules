<?php namespace PerkDotCom\Modules\Tests;

use Mockery;
use PerkDotCom\Modules\Module;
use PHPUnit_Framework_TestCase;

class ModuleTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Mockery\MockInterface
     */
    protected $laravel;

    public function setUp()
    {
        $this->laravel = Mockery::mock('Illuminate\Contracts\Foundation\Application');
        $this->laravel->shouldReceive('basePath')->andReturn(__DIR__ . DIRECTORY_SEPARATOR . 'stubs');
    }

    public function testClassExists()
    {
        $this->assertTrue(class_exists('PerkDotCom\Modules\Module'));
    }

    public function testParseWithNameOnly()
    {
        $module = new Module($this->laravel);
        $module->parse('Module', null);

        $this->assertEquals('Module', $module->name);
        $this->assertEquals('app' . DIRECTORY_SEPARATOR . 'Modules' . DIRECTORY_SEPARATOR . 'Module', $module->path);
        $this->assertEquals('App\Module', $module->namespace);
    }

    public function testParseWithNameAndPathForwardSlash()
    {
        $module = new Module($this->laravel);
        $module->parse('Module', 'Foo/Modules');

        $this->assertEquals('Module', $module->name);
        $this->assertEquals('Foo' . DIRECTORY_SEPARATOR . 'Modules' . DIRECTORY_SEPARATOR . 'Module', $module->path);
        $this->assertEquals('App\Module', $module->namespace);
    }

    public function testParseWithNameAndPathBackSlash()
    {
        $module = new Module($this->laravel);
        $module->parse('Module', 'Foo\Modules');

        $this->assertEquals('Module', $module->name);
        $this->assertEquals('Foo' . DIRECTORY_SEPARATOR . 'Modules' . DIRECTORY_SEPARATOR . 'Module', $module->path);
        $this->assertEquals('App\Module', $module->namespace);
    }
}
