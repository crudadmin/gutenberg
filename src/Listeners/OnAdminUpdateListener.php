<?php

namespace Admin\Gutenberg\Listeners;
use Admin;
use Admin\Gutenberg\Providers\GutenbergServiceProvider;
use Artisan;

class OnAdminUpdateListener
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if ( config('admin.gutenberg', false) === true ) {
            Artisan::call('vendor:publish', [
                '--provider' => GutenbergServiceProvider::class
            ]);

            Admin::addGitignoreFiles([
                public_path('vendor/gutenberg')
            ]);

            $event->getCommand()->line('<comment>+ Gutenberg vendor directories published successfully.</comment>');
        }
    }
}
