<?php

Route::group(['middleware' => 'admin'], function () {
    Route::apiResource('admin/gutenberg/blocks', 'BlockController');

    Route::get('admin/gutenberg/oembed', 'OEmbedController');
    Route::post('admin/gutenberg/image-upload', 'ImageUploadController@upload');
});