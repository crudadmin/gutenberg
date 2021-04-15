<?php

namespace Admin\Gutenberg\Contracts\Blocks;

use Admin\Gutenberg\Contracts\Blocks\EmbedBlock;
use Admin\Gutenberg\Contracts\Blocks\SocialBlock;

class BlocksBuilder
{
    private $html;

    static $blockMutators = [
        EmbedBlock::class,
        SocialBlock::class,
    ];

    public function __construct($html)
    {
        $this->html = $html;
    }

    public static function addBlockMutator($class)
    {
        self::$blockMutators = $class;
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
            $rendered[] = (new \WP_Block($blockData))->render();
        }

        return $rendered;
    }

    /**
     * Renders any blocks in the HTML (recursively)
     */
    public function render()
    {
        return '<div class="'.$this->getWrapperClass().'">'.implode('', $this->renderBlocks()).'</div>';
    }
}