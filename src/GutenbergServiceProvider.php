<?php

namespace Admin\Gutenberg;

use Admin\Core\Eloquent\AdminModel;
use Fields;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Admin\Gutenberg\Eloquent\Modules\GutenbergModule;
use Admin\Gutenberg\Fields\Mutations\AddGutenbergRawColumn;

class GutenbergServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //Register publishes
        $this->publishes([__DIR__ . '/config/laraberg.php' => config_path('laraberg.php')], 'config');
        $this->publishes([__DIR__ . '/../public' => public_path('vendor/laraberg')], 'public');

        //Register routes
        require __DIR__ . '/Http/routes.php';

        //Register blade directive
        Blade::directive('gutenberg', function ($model) {
            return '<link rel="stylesheet" type="text/css" href="<?php echo asset("vendor/laraberg/css/laraberg.css") ?>">';
        });

        //Register admin field mutation
        Fields::addMutation([
            AddGutenbergRawColumn::class,
        ]);
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeAdminConfigs();

        AdminModel::addGlobalModule(GutenbergModule::class);

        $this->app->singleton(Laraberg::class, function () {
            return new Laraberg();
        });

        $this->app->alias(Laraberg::class, 'laraberg');
    }

    /*
     * Merge crudadmin config with esolutions config
     */
    private function mergeAdminConfigs($key = 'admin')
    {
        //Additional CrudAdmin Config
        $crudAdminConfig = require __DIR__.'/config/admin.php';

        $config = $this->app['config']->get($key, []);

        $this->app['config']->set($key, array_merge($crudAdminConfig, $config));

        //Merge selected properties with one/two dimensional array
        foreach (['models', 'custom_rules', 'global_rules', 'gettext_source_paths', 'gettext_admin_source_paths'] as $property) {
            if (! array_key_exists($property, $crudAdminConfig) || ! array_key_exists($property, $config)) {
                continue;
            }

            $attributes = array_merge($config[$property], $crudAdminConfig[$property]);

            $this->app['config']->set($key.'.'.$property, $attributes);
        }
    }

}