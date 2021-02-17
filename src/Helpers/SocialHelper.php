<?php

namespace Admin\Gutenberg\Helpers;

use Admin\Gutenberg\Models\Block;

class SocialHelper
{
    /**
     * Renders any blocks in the HTML (recursively)
     * @param String $html
     */
    public static function render($html)
    {
        // Replace reusable block ID with reusable block HTML
        $regex = '/<!-- wp:social-link (.*?) \/-->/';
        $result = preg_replace_callback($regex, function ($matches) {
            return self::renderBlock(json_decode($matches[1], true));
        }, $html);

        return $result;
    }

    private static function renderBlock($attributes)
    {
        require_once __DIR__.'/blocks/social-link.php';

        return gutenberg_render_block_core_social_link($attributes);
    }
}