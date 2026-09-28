<?php

namespace Admin\Gutenberg\Providers;

use Admin\Core\Helpers\Store\RequestShared;
use Admin\Gutenberg\Contracts\Blocks\EmbedHelper;
use Admin\Providers\AdminPackageServiceProvider;

/**
 * Render filters of the WordPress blocks (embeds, social links), registered by GutenbergServiceProvider.
 */
class BlocksServiceProvider extends AdminPackageServiceProvider
{
    /**
     * Static state reset before every operation of long running processes.
     */
    protected $flush = [
        self::class,
    ];

    /**
     * The filters live in the global $wp_filter of the whole process, another application booted in
     * the same process (tests) must not add them again.
     *
     * @var bool
     */
    #[RequestShared]
    private static $filtersAdded = false;

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        if (self::$filtersAdded === false) {
            self::$filtersAdded = true;

            $this->addOembedBlock();
            $this->addSocialBlock();
        }
    }

    /**
     * Forget the filters which were being applied when the previous request failed in a block.
     *
     * @return void
     */
    public static function flushState()
    {
        $GLOBALS['wp_current_filter'] = [];
    }

    private function addOembedBlock()
    {
        add_filter('render_block', function ($block_content, $block) {
            // Filter only embed blocks
            if (
                strpos($block['blockName'], 'core-embed/') !== false
                || strpos($block['blockName'], 'core/embed') !== false
            ) {
                if (! ($url = $block['attrs']['url'] ?? null)) {
                    return $block_content;
                }

                try {
                    $embed = EmbedHelper::create($url);
                } catch (\Throwable $e) {
                    report($e);

                    return $block_content;
                }

                // Replace video
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
        add_filter('render_block_core/social-link', function ($block_content, $block) {
            return gutenberg_render_block_core_social_link($block['attrs']);
        }, -1, 2);
    }
}
