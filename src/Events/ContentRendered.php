<?php

namespace Admin\Gutenberg\Events;

use Illuminate\Queue\SerializesModels;

use Admin\Gutenberg\Models\Content;

class ContentRendered
{
    use SerializesModels;

    public $content;

    /**
     * Create a new event instance
     *
     * @param Admin\Gutenberg\Models\Content $content
     * @return void
     */
    public function __construct(Content $content)
    {
        $this->content = $content;
    }
}