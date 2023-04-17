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

        $enabledMimeTypes = config('admin.gutenberg.media_mime_types', [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
            'image/svg+xml',
            'video/mp4',
            'video/x-ms-wmv',
            'video/x-flv',
            'video/quicktime',
            'video/x-msvideo',
        ]);

        if ( !in_array($file->getMimeType(), $enabledMimeTypes) ){
            return [
                'error' => _('Only image and video types are available for upload')
            ];
        }

        $uploadDirectory = 'editor/images/shared';

        $filename = str_random(4).'-'.$file->getClientOriginalName();

        $filepath = $file->storeAs($uploadDirectory, $filename, 'crudadmin.uploads');

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