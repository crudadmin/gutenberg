<?php

namespace Admin\Gutenberg\Http\Controllers;

use Illuminate\Http\Request;
use ImageCompressor;
use Storage;

class ImageUploadController extends ApplicationController
{
    public function upload()
    {
        $file = request()->file;

        if ( !in_array($file->getMimeType(), ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/svg+xml']) ){
            return [
                'error' => _('Only image types are available for upload')
            ];
        }

        $uploadDirectory = 'editor/images/shared';

        $filename = str_random(4).'-'.$file->getClientOriginalName();

        $filepath = $file->storeAs($uploadDirectory, $filename, 'crudadmin');

        ImageCompressor::tryShellCompression(
            $path = Storage::disk('crudadmin')->path($filepath)
        );

        return [
            'caption' => [],
            'title' => [],
            'description' => [],
            'source_url' => asset('uploads/'.$uploadDirectory.'/'.$filename)
        ];
    }
}