<?php namespace PerkDotCom\Modules;

use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            'PerkDotCom\Modules\Console\ModuleMakeCommand'
        ]);
    }
}
