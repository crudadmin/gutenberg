<?php

namespace Admin\Gutenberg\Providers;

use Illuminate\Support\ServiceProvider;
use Admin\Gutenberg\Contracts\Blocks\EmbedHelper;

class BlocksServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->addOembedBlock();
        $this->addSocialBlock();
    }

    private function addOembedBlock()
    {
        add_filter('render_block', function($block_content, $block) {
            //Filter only embed blocks
            if ( strpos($block['blockName'], 'core-embed/') === false ) {
                return $block_content;
            }

            $url = $block['attrs']['url'];

            $embed = EmbedHelper::create($url);

            //Replace video
            $block_content = str_replace(
                $url,
                $embed['html'] ?? null,
                $block_content
            );

            return $block_content;
        }, 1, 2);
    }

    private function addSocialBlock()
    {
        add_filter('render_block_core/social-link', function($block_content, $block) {
            return gutenberg_render_block_core_social_link($block['attrs']);
        }, 1, 2);
    }
}