<?php

namespace VanOns\Laraberg;

use Admin\Core\Eloquent\AdminModel;
use Illuminate\Support\ServiceProvider;
use VanOns\Laraberg\Eloquent\Modules\GutenbergModule;
use Illuminate\Support\Facades\Blade;

class LarabergServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/config/laraberg.php' => config_path('laraberg.php')], 'config');
        require __DIR__ . '/Http/routes.php';
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->publishes([__DIR__ . '/../public' => public_path('vendor/laraberg')], 'public');

        Blade::directive('gutenberg', function ($model) {
            return '<link rel="stylesheet" type="text/css" href="<?php echo asset("vendor/laraberg/css/laraberg.css") ?>">';
        });
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

