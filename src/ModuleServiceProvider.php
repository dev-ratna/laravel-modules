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

        $this->registerModuleProviders();
    }

    protected function registerModuleProviders()
    {
        $files = $this->app['files'];

        $manifestPath = storage_path('app' . DIRECTORY_SEPARATOR . 'modules.json');
        if ($files->exists($manifestPath)) {
            $manifest  = json_decode($files->get($manifestPath), true);
            $providers = $manifest['providers'];

            foreach ($providers as $provider) {
                $this->app->register($provider);
            }
        }
    }
}
