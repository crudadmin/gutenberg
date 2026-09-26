<?php

namespace Admin\Gutenberg\Providers;

use Admin\Gutenberg\Contracts\Blocks\EmbedHelper;
use Admin\Providers\AdminPackageServiceProvider;

/**
 * Render filters of the WordPress blocks (embeds, social links), registered by GutenbergServiceProvider.
 */
class BlocksServiceProvider extends AdminPackageServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        $this->addOembedBlock();
        $this->addSocialBlock();
    }

    private function addOembedBlock()
    {
        add_filter('render_block', function($block_content, $block) {
            //Filter only embed blocks
            if (
                strpos($block['blockName'], 'core-embed/') !== false
                || strpos($block['blockName'], 'core/embed') !== false
            ) {
                if ( ! ($url = $block['attrs']['url'] ?? null) ) {
                    return $block_content;
                }

                try {
                    $embed = EmbedHelper::create($url);
                } catch (\Throwable $e) {
                    report($e);

                    return $block_content;
                }

                //Replace video
                $block_content = str_replace(
                    $url,
                    $embed['html'] ?? null,
                    $block_content
                );

                return $block_content;
            }

            return $block_content;
        }, -1, 2);
    }

    private function addSocialBlock()
    {
        add_filter('render_block_core/social-link', function($block_content, $block) {
            return gutenberg_render_block_core_social_link($block['attrs']);
        }, -1, 2);
    }
}