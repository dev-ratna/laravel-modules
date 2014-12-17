<?php namespace Perk\LaravelModules;

use Illuminate\Support\ServiceProvider;

abstract class AbstractServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        if ($module = head(func_get_args())) {
            $this->package($module);

            $modulePath = $this->app[ 'config' ]->get('laravel-modules::path') . DIRECTORY_SEPARATOR . $module;
            $includes   = $this->app[ 'config' ]->get('laravel-modules::includes');

            foreach ($includes as $include) {
                $include = $modulePath . DIRECTORY_SEPARATOR . $include;

                if ($this->app[ 'files' ]->exists($include)) {
                    require $include;
                }
            }
        }
    }
}
