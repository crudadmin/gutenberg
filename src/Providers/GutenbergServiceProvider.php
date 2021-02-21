<?php

namespace Admin\Gutenberg\Providers;

use Admin;
use Admin\Core\Eloquent\AdminModel;
use Admin\Gutenberg\Eloquent\Modules\GutenbergModule;
use Admin\Gutenberg\Fields\Mutations\AddGutenbergRawColumn;
use Admin\Providers\AdminHelperServiceProvider;
use Fields;
use Illuminate\Support\Facades\Blade;

class GutenbergServiceProvider extends AdminHelperServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeAdminConfigs(
            require __DIR__.'/../config/admin.php'
        );

        AdminModel::addGlobalModule(GutenbergModule::class);

        $this->registerProviders([
            RouteServiceProvider::class,
            BlocksServiceProvider::class,
            EventsServiceProvider::class,
        ]);
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //Register publishes
        // $this->publishes([realpath(__DIR__ . '/../config/laraberg.php') => config_path('laraberg.php')], 'config');
        $this->publishes([realpath(__DIR__ . '/../../public') => public_path('vendor/gutenberg')], 'public');

        //Register blade directive
        Blade::directive('gutenberg', function ($model) {
            return '<link rel="stylesheet" type="text/css" href="<?php echo asset("vendor/gutenberg/css/gutenberg.css") ?>">';
        });
    }
}