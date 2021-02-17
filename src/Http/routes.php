<?php

if (config('laraberg.use_package_routes')) {
    Route::group(['prefix' => config('laraberg.prefix'), 'middleware' => config('laraberg.middlewares')], function () {
        Route::apiResource('blocks', 'Admin\Gutenberg\Http\Controllers\BlockController');
        Route::get('oembed', 'Admin\Gutenberg\Http\Controllers\OEmbedController');
    });
};