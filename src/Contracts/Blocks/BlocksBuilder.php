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

    /**
     * Renders any blocks in the HTML (recursively)
     */
    public function render()
    {
        $blocks = (new \WP_Block_Parser)->parse($this->html);

        $content = [];

        foreach ($blocks as $blockData) {
            $content[] = (new \WP_Block($blockData))->render();
        }

        return '<div class="'.$this->getWrapperClass().'">'.implode('', $content).'</div>';
    }
}