<?php

namespace Admin\Gutenberg\Providers;

use Admin\Core\Eloquent\AdminModel;
use Admin\Gutenberg\Eloquent\Modules\GutenbergModule;
use Admin\Providers\AdminPackageServiceProvider;
use Illuminate\Support\Facades\Blade;

/**
 * The main provider of the package. What the package brings is declared in the properties,
 * AdminPackageServiceProvider registers it and keeps it working with php artisan optimize.
 */
class GutenbergServiceProvider extends AdminPackageServiceProvider
{
    /**
     * Merged into config('admin'): gutenberg classes, global rules and the scripts of the editor.
     */
    protected $adminConfig = __DIR__.'/../config/admin.php';

    /**
     * Route files, loaded only when the routes are not cached.
     */
    protected $routes = [
        __DIR__.'/../Http/routes.php' => [
            'namespace' => 'Admin\Gutenberg\Http\Controllers',
            'middleware' => 'admin.panel',
        ],
    ];

    /**
     * Scripts and styles of the editor, published by php artisan admin:update. The administration
     * waits for every script of appearance.scripts, a missing one stops it.
     */
    protected $assets = [
        __DIR__.'/../../public' => 'vendor/gutenberg',
    ];

    protected $assetsTag = 'admin_gutenberg.assets';

    protected $providers = [
        BlocksServiceProvider::class,
    ];

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        AdminModel::addGlobalModule(GutenbergModule::class);
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //Register blade directive
        Blade::directive('gutenberg', function ($model) {
            return '<link rel="stylesheet" type="text/css" href="<?php echo asset("vendor/gutenberg/css/gutenberg.css") ?>">';
        });
    }
}
