<?php

namespace Admin\Gutenberg\Contracts\Blocks;


class BlocksBuilder
{
    private $html;

    public function __construct($html)
    {
        $this->html = $html;
    }

    private function getWrapperClass()
    {
        return config('admin.gutenberg_wrapper_class');
    }

    public function renderBlocks()
    {
        $blocks = (new \WP_Block_Parser)->parse($this->html);

        $rendered = [];

        foreach ($blocks as $blockData) {
            $rendered[] = (new \WP_Block($blockData))->render([ 'parent' => true ]);
        }

        return $rendered;
    }

    /**
     * Renders any blocks in the HTML (recursively)
     */
    public function render()
    {
        if ( !($content = implode('', $this->renderBlocks())) ) {
            return;
        }

        return '<div class="'.$this->getWrapperClass().'">'.$content.'</div>';
    }
}