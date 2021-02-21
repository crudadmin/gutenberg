<?php

namespace Admin\Gutenberg\Http\Controllers;

use Illuminate\Http\Request;
use Admin\Gutenberg\Contracts\Blocks\EmbedHelper;

class OEmbedController extends ApplicationController
{
    public function __invoke(Request $request)
    {
        $data = EmbedHelper::create($request->url);

        if ($data['html'] == null) {
            return $this->notFound();
        }

        return $this->ok($data);
    }
}