<?php namespace spec\PerkDotCom\Modules;

use Illuminate\Contracts\Foundation\Application;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ModuleSpec extends ObjectBehavior
{
    function let(Application $laravel)
    {
        $laravel->basePath()->willReturn(__DIR__ . DIRECTORY_SEPARATOR . 'stubs');
        $this->beConstructedWith($laravel);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('PerkDotCom\Modules\Module');
    }

    function it_parses_input_and_returns_self()
    {
        $this->parse('Testing', 'Foo/Modules')->shouldBeAnInstanceOf('PerkDotCom\Modules\Module');
    }

    function it_parses_name_from_input()
    {
        $module = $this->parse('Testing', 'Foo/Modules');

        $module->name->shouldBe('Testing');
    }

    function it_parses_path_from_input()
    {
        $module = $this->parse('Testing', 'Foo/Modules');

        $module->path->shouldBe('Foo/Modules/Testing');
    }

    function it_uses_base_path_if_no_path_specified()
    {
        $module = $this->parse('Testing', null);

        $module->path->shouldBe('app/Modules/Testing');
    }

    function it_parses_namespace_from_input()
    {
        $module = $this->parse('Testing', 'Foo/Modules');

        $module->namespace->shouldBe('App\Testing');
    }
}
