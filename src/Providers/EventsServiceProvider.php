<?php

namespace Admin\Gutenberg\Providers;

use Admin\Gutenberg\Listeners\OnAdminUpdateListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventsServiceProvider extends ServiceProvider
{
    protected $listen = [
        \Admin\Resources\Events\OnAdminUpdate::class => [
            OnAdminUpdateListener::class,
        ],
    ];
}
