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
        AdminModel::addGlobalModule(GutenbergModule::class);

        $this->app->singleton(Laraberg::class, function () {
            return new Laraberg();
        });
        $this->app->alias(Laraberg::class, 'laraberg');
    }
}