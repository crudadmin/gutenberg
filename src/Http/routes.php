<?php

// Loaded by GutenbergServiceProvider with the admin.panel middleware and the controllers namespace
Route::get('admin/gutenberg/oembed', 'OEmbedController')->name('gutenberg.oembed');
Route::post('admin/gutenberg/image-upload', 'ImageUploadController@upload')->name('gutenberg.image-upload');
