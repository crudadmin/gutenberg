<?php

/**
 * Global wrapper for selected blocks from array
 */
add_filter('render_block', function($blockContent, $block, $options = []) {
    if ( in_array($block['blockName'], ['core/column']) ){
        return $blockContent;
    }

    if ( !trim(strip_tags($blockContent)) ){
        return '';
    }

    $blockClass = config('admin.gutenberg_box_class');
    $blockClass = apply_filters('render_block_class', $blockClass, $blockContent, $options);

    return '<div class="'.$blockClass.'" data-block="'.$block['blockName'].'">'.$blockContent.'</div>';
}, 1, 3);